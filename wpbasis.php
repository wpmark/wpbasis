<?php
/*
Plugin Name: WP Basis
Plugin URI: https://github.com/wpmark/wpbasis
Description: WP Basis provides the basis of a WordPress site by giving you access to the types of functions you end up writing for all sites. It also gives modifications to the WordPress dashboard which make it easier to work with for your clients.
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
Version: 1.6.2

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

/* exist if directly accessed */
if( ! defined( 'ABSPATH' ) ) exit;

/* define variable for path to this plugin file. */
define( 'WPBASIS_LOCATION', dirname( __FILE__ ) );
define( 'WPBASIS_LOCATION_URL', plugins_url( '', __FILE__ ) );

/**
 * include the necessary functions file for the plugin
 */
require_once dirname( __FILE__ ) . '/functions/template-functions.php';
require_once dirname( __FILE__ ) . '/functions/counters.php';
require_once dirname( __FILE__ ) . '/deprecated/deprecated.php';

/**
 * include the admin functions 
 */
require_once dirname( __FILE__ ) . '/functions/admin/admin-profile.php';
require_once dirname( __FILE__ ) . '/functions/admin/admin-menus.php';
require_once dirname( __FILE__ ) . '/functions/admin/admin-menus-content.php';
require_once dirname( __FILE__ ) . '/functions/admin/admin.php';
require_once dirname( __FILE__ ) . '/functions/admin/admin-bar.php';
require_once dirname( __FILE__ ) . '/functions/admin/admin-display.php';

/**
 * include the plugin defaults
 */
require_once dirname( __FILE__ ) . '/functions/defaults/default-tabs.php';
require_once dirname( __FILE__ ) . '/functions/defaults/default-settings.php';
require_once dirname( __FILE__ ) . '/functions/defaults/default-capabilities.php';

/**
 * deal with legacy code here
 * load post type descriptions - filterable
 * add the site options - filterable
 */
if( apply_filters( 'wpbasis_use_post_type_descriptions', false ) == true ) {
	require_once dirname( __FILE__ ) . '/deprecated/post-type-descriptions.php';
}

if( apply_filters( 'wpbasis_show_site_options', false ) == true ) {
	require_once dirname( __FILE__ ) . '/deprecated/site-options.php';
}

/**
 * Function wpbasis_on_activation()
 * On plugin activation makes current user a wpbasis user and
 * sets an option to redirect the user to another page.
 */
function wpbasis_on_activation() {
	
	/* get the current users, user ID */
	$wpbasis_user_id = get_current_user_id();
	
	/* make the user a wpbasis super user */
	update_usermeta( $wpbasis_user_id, 'wpbasis_user', 1 );
	
	/* set option to initialise the redirect */
	add_option( 'wpbasis_activation_redirect', true );
	
}

register_activation_hook( __FILE__, 'wpbasis_on_activation' );

/**
 * Function wp_basis_activation_redirect()
 * Redirects user to the settings page for wp basis on plugin
 * activation.
 */
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

/**
 * Function wpbasis_enqueue_scripts()
 * Adds plugins scripts and styles
 */
function wpbasis_enqueue_scripts() {

	/* load the global variable to see which admin page we are on */
	global $pagenow;

	/* check whether the current admin page is wpbasis dashboard page */
	if( $pagenow == 'admin.php' ) {
	
		/* site js scripts */
		wp_enqueue_script( 'wpbasis_js', plugins_url( 'assets/js/wpbasis-min.js', __FILE__ ), 'jquery' );
		
		/* register the stylesheet */
		wp_enqueue_style( 'wpbasis_admin_css', plugins_url( 'assets/css/wpbasis.css', __FILE__ ) );

	}

}

add_action( 'admin_enqueue_scripts', 'wpbasis_enqueue_scripts' );