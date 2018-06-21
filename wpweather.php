<?php 
/*
Plugin Name: WPWeather
Plugin URI:   https://pwoghub.pw/plugins/wpweather/
Description: Current weather and forecast.
Version:      1.0.0
Author:       Huberson D.
Author URI:   https:pwoghub.pw/huberson
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpweather
Domain Path:  /languages
*/


defined( 'ABSPATH' ) || exit;

if( !defined('WPWEATHER_VERSION') ) { define('WPWEATHER_VERSION', '0.0.1' ); }
if( !defined('WPWEATHER_PLUGIN_FILE') ) { define('WPWEATHER_PLUGIN_FILE',  __FILE__  ); }
if( !defined('WPWEATHER_DIR_PATH') ) { define('WPWEATHER_DIR_PATH', dirname( __FILE__ ).'/' ); }
if( !defined('WPWEATHER_DIR_LIB') ) { define('WPWEATHER_DIR_LIB', dirname( __FILE__ ).'/lib/' ); }
if( !defined('WPWEATHER_DIR_SRC') ) { define('WPWEATHER_DIR_SRC', dirname( __FILE__ ).'/src/' ); }
if( !defined('WPWEATHER_ASSETS_URL') ) { define('WPWEATHER_ASSETS_URL', plugin_dir_url( __FILE__ ).'src/assets/' ); }
if( !defined('WPWEATHER_DIR_URL') ) { define('WPWEATHER_DIR_URL', plugin_dir_url( __FILE__ ) ); }


require WPWEATHER_DIR_PATH.'bootstrap.php';


WPWeather::init();
WPWeather_Settings::init();
