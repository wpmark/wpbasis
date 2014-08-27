<?php
/*
Plugin Name: WP Basis
Plugin URI: https://github.com/wpmark/wpbasis
Description: WP Basis provides the basis of a WordPress site by giving you access to the types of functions you end up writing for all sites. It also gives modifications to the WordPress dashboard which make it easier to work with for your clients.
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
Version: 0.4
*/

/* define variable for path to this plugin file. */
define( WPBASIS_LOCATION, dirname( __FILE__ ) );

/***************************************************************
* include the necessary functions file for the plugin
***************************************************************/
require_once dirname( __FILE__ ) . '/functions/template-tags.php';
require_once dirname( __FILE__ ) . '/functions/admin-menus.php';
require_once dirname( __FILE__ ) . '/functions/admin-menus-content.php';
require_once dirname( __FILE__ ) . '/functions/admin.php';
require_once dirname( __FILE__ ) . '/functions/post-type-descriptions.php';
require_once dirname( __FILE__ ) . '/functions/counters.php';
require_once dirname( __FILE__ ) . '/functions/admin-bar.php';
require_once dirname( __FILE__ ) . '/functions/admin-display.php';

/* load metaboxes if not already loaded */
if( ! class_exists( 'CMB_Meta_Box' ) )
	require_once dirname( __FILE__ ) . '/metaboxes/custom-meta-boxes.php';

/***************************************************************
* Function wpbasis_on_activation()
* On plugin activation makes current user a wpbasis user and
* sets an option to redirect the user to another page.
***************************************************************/
function wpbasis_on_activation() {
	
	/* get the current users, user ID */
	$wpbasis_user_id = get_current_user_id();
	
	/* make the user a wpbasis super user */
	update_usermeta( $wpbasis_user_id, 'wpbasis_user', 1 );
	
	/* set option to initialise the redirect */
	add_option( 'wpbasis_activation_redirect', true );
	
}

register_activation_hook( __FILE__, 'wpbasis_on_activation' );

/***************************************************************
* Function wp_basis_activation_redirect()
* Redirects user to the settings page for wp basis on plugin
* activation.
***************************************************************/
function wp_basis_activation_redirect() {
	
	/* check whether we should redirect the user or not based on the option set on activation */
	if( true == get_option( 'wpbasis_activation_redirect' ) ) {
		
		/* delete the redirect option */
		delete_option( 'wpbasis_activation_redirect' );
		
		/* redirect the user to the wp basis settings page */
		wp_redirect( admin_url( 'admin.php?page=wpbasis_settings' ) );
		exit;
		
	}
	
}

add_action( 'admin_init', 'wp_basis_activation_redirect' );

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