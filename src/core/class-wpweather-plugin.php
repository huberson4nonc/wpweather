<?php


if(!class_exists('WPWeather')) {

/**
 * Plugin core module.
 *
 * BlaBlahBlah...
 */
class WPWeather
{
	private static $instance = null;

	/**
	 * [__construct description]
	 */
	private function __construct()
	{
		add_action('wp_dashboard_setup', array($this, 'add_dash_widget') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );

	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public static function init()
	{
		if( self::$instance === null ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Renders activities summary in dashboard welcome panel.
	 * @return [type] [description]
	 *
	 * @todo  Should be private or protected
	 */
	public function render_dash_summary_panel()
	{
		
		?>
		<div class="container">
			<div id="current-weather"></div>
			<div id="forecast"></div>
		</div>

		<?php
	}

	/**
	 * [add_dash_widget description]
	 *
	 * @todo  Should be private or protected
	 */
	public function add_dash_widget()//should not be public
	{
		// Globalize the metaboxes array, holds all the widgets for wp-admin
		global $wp_meta_boxes;

		wp_add_dashboard_widget( 
			'dashboard_wpweather',
			'WPWeather - '.__('Forecast Summary', 'wpweather'),
			array($this, 'render_dash_summary_panel'),
			$control_callback = null,
			$callback_args = null
		);
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


		/*Loads only if Dashboad Home*/
		if( 'index.php' != $current_admin_page ) return;
		wp_enqueue_style( 
			'dash_admin_css', 
			WPWEATHER_ASSETS_URL.'dash-admin.css',
			array(),
			filemtime(WPWEATHER_DIR_SRC.'assets/dash-admin.css')
		);

		wp_enqueue_script( 
			'dash_ajax_script',
			WPWEATHER_ASSETS_URL.'weather.js',
			array('jquery'),
			filemtime(WPWEATHER_DIR_SRC.'assets/weather.js')
		);

		wp_localize_script( 'dash_ajax_script',
			'wpweather_ajax',
			array(
				'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
				'client_ip' => $_SERVER['REMOTE_ADDR'],
				'country' 	=> $country,
				'city' 	=> $city,
				'plugin_src_dir' => WPWEATHER_DIR_SRC,
				'plugin_assests_url' => WPWEATHER_ASSETS_URL,
				'humidity' 	=> __( 'Humidity', 'wpweather' ),
				'wind' 		=> __( 'Wind', 'wpweather' ),
				'sunrise'	=> __( 'Sunrise', 'wpweather' ),
				'sunset' 	=> __( 'Sunset', 'wpweather' ),
				'min_temp' 	=> __( 'Min temp', 'wpweather' ),
				'max_temp' 	=> __( 'Max temp', 'wpweather' ),
			)
		);

	}

}

}