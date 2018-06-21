<?php


if(!class_exists('WPWeather_Settings')) {

/**
 * Plugin settings module.
 *
 * BlaBlahBlah...
 */
class WPWeather_Settings
{
	private const PREFIX = 'wpweather';
	private static $instance = null;

	/**
	 * [__construct description]
	 */
	private  function __construct()
	{
		/**
		 * register our settings_init to the admin_init action hook
		 */
		add_action( 'admin_init', array( $this, 'settings_init' ) );	

		add_action('admin_menu', array($this, 'add_settings_submenu') );

		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );

	}

		 
	/**
	 * [init description]
	 */
	public static function init()
	{
		if( self::$instance !== null ) return;
		self::$instance = new self();

		return self::$instance;
	}

	/**
	 * [add_settings_submenu description]
	 */
	public function add_settings_submenu()
	{
		add_options_page(
		    __('Weather Settings', 'wpweather'),
		    __('WPWeather Settings', 'wpweather'),
		    'manage_options',
		    'wpweather-settings',
	    	array( $this, 'settings_submenu_page_html' )
		);
	}

	/**
	 * [settings_submenu_page_html description]
	 * @return [type] [description]
	 */
	public function settings_submenu_page_html()
	{
		if( !current_user_can( 'manage_options' ) ) {
	        return;
		}
	    
	    ?>
	    <div class="wrap">
	        <h1><?= esc_html( get_admin_page_title() ); ?></h1>
	        <form action="options.php" method="post">
	            <?php
	            settings_fields( 'wpweather' );

	            do_settings_sections( 'wpweather-settings' );

	            submit_button( 'Save Settings' );
	            ?>
	        </form>
	    </div>
	    <?php
	}


	/**
	 * [settings_init description]
	 * @return [type] [description]
	 */
	public function settings_init()
	{
		register_setting( 
			'wpweather', 
			'wpweather_settings'

		);

		add_settings_section(
			self::PREFIX.'_default_location_section',
			__( 'Default weather location', 'wpweather' ),
			array( $this, 'default_location_section_html' ),
			'wpweather-settings'
		);

		add_settings_field(
			self::PREFIX.'_country_field', // as of WP 4.6 this value is used only internally. use $args' label_for to populate the id inside the callback
			__( 'Select Country', 'wpweather' ),
			array( $this, 'country_field_html' ),
			'wpweather-settings',
			self::PREFIX.'_default_location_section',
			[
				'label_for' => 'country_code',
				'class' => self::PREFIX.'_country',
			]
		);

		add_settings_field(
			self::PREFIX.'_city_field',
			__( 'Set city', 'wpweather' ),
			array( $this, 'city_field_html' ),
			'wpweather-settings',
			self::PREFIX.'_default_location_section',
			[
				'label_for' => self::PREFIX.'_city',
				'class' => self::PREFIX.'_city',
			]
		);
	}
	 

	/**
	 * Displays preferences section.
	 * 
	 * section callbacks can accept an $args parameter, which is an array. 
	 * $args have the following keys defined: title, id, callback. 
	 * the values are defined at the add_settings_section() function.
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function default_location_section_html( $args )
	{
		?>
	 	<!-- <p id="<?php echo esc_attr( $args['id'] ); ?>">
	 		<?php esc_html_e( 'Default weather location', 'wpweather' ); ?>
 		</p> -->
		<?php
	}
	
	/**
	 * [country_field_html description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function country_field_html( $args )
	{
		$options = get_option( 'wpweather_settings' );
		$country = !empty( $options ) ? $options[$args['label_for']] : '';

		?>
			<input type="text" name="country" id="country" />

			<input type="hidden" name="wpweather_settings[<?php echo esc_attr($args['label_for']); ?>]" 
				id="<?php echo esc_attr($args['label_for']) ?>" 
				value="<?php echo esc_attr( sprintf( __( '%s', 'wpweather' ), $country ) ) ?>" />

		<?php
	}


	/**
	 * Displays city field.
	 * 
	 * field callbacks can accept an $args parameter, which is an array.
	 * $args is defined at the add_settings_field() function.
	 * wordpress has magic interaction with the following keys: label_for, class.
	 * the "label_for" key value is used for the "for" attribute of the <label>.
	 * the "class" key value is used for the "class" attribute of the <tr> containing the field.
	 * you can add custom key value pairs to be used inside your callbacks.
	 *
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function city_field_html( $args )
	{
		$options = get_option( 'wpweather_settings' );
		$city = !empty( $options ) ? $options[$args['label_for']] : '';

		?>

			<input type="text" name="wpweather_settings[<?php echo esc_attr($args['label_for']); ?>]" 
				id="<?php echo esc_attr($args['label_for']) ?>" 
				placeholder="Tokyo, JP" 
				value="<?php echo esc_attr( sprintf( __( '%s', 'wpweather' ), $city ) ) ?>" />

			<div id="cities" ></div>
			
		<?php
	}
	
	
	/**
	 * [enqueue_scripts description]
	 * @param  [type] $current_admin_page [description]
	 * @return [type]                     [description]
	 */
	public function enqueue_scripts($current_admin_page)
	{

		$options = get_option( 'wpweather_settings' );
		$country = !empty( $options ) ? $options['country_code'] : '';
		$city = !empty( $options ) ? $options['wpweather_city'] : '';

		/*Loads only if wpweather settings page*/
		if( 'settings_page_wpweather-settings' != $current_admin_page ) return;

		wp_enqueue_style( 
			'settings_country_css', 
			WPWEATHER_ASSETS_URL.'country-select.css',
			array(),
			filemtime(WPWEATHER_DIR_SRC.'assets/country-select.css')
		);

		wp_enqueue_style( 
			'settings_admin_css', 
			WPWEATHER_ASSETS_URL.'settings-admin.css',
			array(),
			filemtime(WPWEATHER_DIR_SRC.'assets/settings-admin.css')
		);

		wp_enqueue_script( 
			'settings_country_script',
			WPWEATHER_ASSETS_URL.'country-select.js',
			array(
				'jquery'
			),
			filemtime(WPWEATHER_DIR_SRC.'assets/country-select.js')
		);

		wp_enqueue_script( 
			'settings_ajax_script',
			WPWEATHER_ASSETS_URL.'ajax-search.js',
			array(
				'jquery',
				'jquery-ui-autocomplete'
			),
			filemtime(WPWEATHER_DIR_SRC.'assets/ajax-search.js')
		);

		wp_localize_script( 'settings_ajax_script',
			'wpweather_ajax',
			array(
				'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
				'client_ip' => $_SERVER['REMOTE_ADDR'],
				'country' 	=> $country,
				'city' 	=> $city,
				'plugin_src_dir' => WPWEATHER_DIR_SRC,
				'plugin_assests_url' => WPWEATHER_ASSETS_URL,
				'plugin_dir_url' => WPWEATHER_DIR_URL,
			)
		);

	}

}

}
