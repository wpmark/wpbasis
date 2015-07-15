<?php
/**
 * Function wpbasis_admin_remove_elements()
 * Amends the admin bar
 */
function wpbasis_admin_remove_elements() {

	/* if the current user is a wpbasis super user */
	if( wpbasis_is_wpbasis_user() )
		return;		

	global $wp_admin_bar;
	
	/* create a filterable array of admin bar elements to remove */
	$admin_bar_remove = apply_filters(
		'wpbasis_admin_bar_elements',
		array(
			'my-sites',
			'wp-logo',
			'site-name',
			'comments',
			'new-content',
			'abus_switch_to_user', // remove admin bar user switching element from the plugin
			'wpseo-menu', // wordpress seo plugins menu item
			'upgrades', // should not show anyway as cap removed
		),
		get_current_user_id()
	);
	
	/* check we have elements to remove */
	if( ! empty( $admin_bar_remove ) ) {
		
		/* loop through each element to remove */
		foreach( $admin_bar_remove as $element ) {
			
			/* remove unwanted menu item */
			$wp_admin_bar->remove_menu( $element );
			
		}
		
	}

}
 
add_action( 'wp_before_admin_bar_render', 'wpbasis_admin_remove_elements', 10 );

/**
 * function wpbasis_admin_bar_site_toggle()
 * adds an admin bar toggle for front/back end
 */
function wpbasis_admin_bar_site_toggle( &$wp_admin_bar ) {
	
	/* if the current user is a wpbasis super user */
	if( wpbasis_is_wpbasis_user() )
		return;
	
	/* only do this if in the admin */
	if( is_admin() ) {

		/* set link to home url */
		$site_link = home_url();
		$link_name = 'View Site';

	/* if not in the admin */	
	} else {

		/* set link to admin url */
		$site_link = admin_url( 'admin.php?page=wpbasis_dashboard' );
		$link_name = 'Site Admin';

	} // end check if admin

	/* add a view site or admin link menu to the admin bar */
	$wp_admin_bar->add_menu(
		array(
			'id' => 'site-toggle',
			'title' => apply_filters( 'wpbasis_admin_bar_site_admin_link_name', $link_name, get_current_user_id() ),
			'href' => apply_filters( 'wpbasis_admin_bar_site_admin_link_url', $site_link, get_current_user_id() )
		)
	);

}

add_action( 'admin_bar_menu', 'wpbasis_admin_bar_site_toggle', 9);

/**
 * Function wpbasis_howdy()
 * Change Howdy? in the admin bar
 */
function wpbasis_howdy() {

	global $wp_admin_bar;

	/* get the current logged in users gravatar */
	$wpbasis_avatar = get_avatar( get_current_user_id(), 16 );

    /* there is a howdy node, lets alter it */
    $wp_admin_bar->add_node(
    	array(
	        'id' => 'my-account',
	        'title' => sprintf( 'Logged in as, %s', wp_get_current_user()->display_name ) . $wpbasis_avatar,
	    )
	);

}

add_filter( 'admin_bar_menu', 'wpbasis_howdy', 10, 2 );