<?php
/*
Plugin Name: WP Basis
Plugin URI: https://github.com/wpmark/wpbasis
Description: WP Basis provides the basis of a WordPress site by giving you access to the types of functions you end up writing for all sites. It also gives modifications to the WordPress dashboard which make it easier to work with for your clients.
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
Version: 0.1
*/

/* define variable for path to this plugin file. */
define( WPBASIS_LOCATION, dirname( __FILE__ ) );

/* make the plugin updatable without being in the WordPress plugin repo */
require_once( 'wp-updates-plugin.php' );
new WPUpdatesPluginUpdater_485( 'http://wp-updates.com/api/2/plugin', plugin_basename( __FILE__ ) );

/***************************************************************
* include the necessary functions file for the plugin
***************************************************************/
require_once dirname( __FILE__ ) . '/functions/template-tags.php';
require_once dirname( __FILE__ ) . '/functions/admin-menus.php';
require_once dirname( __FILE__ ) . '/functions/admin-menus-content.php';
require_once dirname( __FILE__ ) . '/functions/admin.php';
require_once dirname( __FILE__ ) . '/functions/dashboard-tabs-content.php';
require_once dirname( __FILE__ ) . '/functions/post-type-descriptions.php';
require_once dirname( __FILE__ ) . '/functions/counters.php';
require_once dirname( __FILE__ ) . '/functions/admin-bar.php';

/* check whether the metabox class already exists */
if( ! class_exists( 'CMB_Meta_Box' ) ) {

	/* load metaboxes if not already loaded */
	require_once dirname( __FILE__ ) . '/metaboxes/custom-meta-boxes.php';

}

/***************************************************************
* Function wpbasis_enqueue_scripts()
* Adds plugins scripts and styles
***************************************************************/
function wpbasis_enqueue_scripts() {

	/* load the global variable to see which admin page we are on */
	global $pagenow;

	/* check whether the current admin page is wpbasis dashboard page */
	if( $pagenow == 'admin.php' ) {

		wp_enqueue_script( 'wpbasis_tabs', plugins_url( 'js/wpbasis-tabs.js', __FILE__ ), 'jquery' );

	}
	
	/* register the stylesheet */
    wp_register_style( 'wpbasis_admin_css', plugins_url( 'css/admin-style.css', __FILE__ ) );
    
    /* enqueue the stylsheet */
    wp_enqueue_style( 'wpbasis_admin_css' );

}

add_action( 'admin_enqueue_scripts', 'wpbasis_enqueue_scripts' );