<?php
/*-------------------------------------------------------------------------
| Does the cleanup once uninstalled
|--------------------------------------------------------------------------
|
| - Loose checking and delete plugin options if set.
| - Ignores options if multisite
|
-------------------------------------------------------------------------*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_name = 'wpweather_settings';
 
if ( !is_multisite() ) {
    delete_option( $option_name );
} 
 
/*Multisite uninstall - stanby hear..*/
// delete_site_option($option_name);