<?php
/***************************************************************
* Function pxlcore_admin_bar_edit()
* Amends the admin bar
***************************************************************/
function pxlcore_admin_bar_edit() {

	/* if the current user is a wpbasis super user */
	if( wpbasis_is_wpbasis_user() )
		return;		

	global $wp_admin_bar;

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
			'id' => 'site-admin-link',
			'title' => $link_name,
			'href' => $site_link
		)
	);

	/* remove unwanted menu items */
	$wp_admin_bar->remove_menu( 'my-sites' );
	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'site-name' );
	$wp_admin_bar->remove_menu( 'wpseo-menu' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_menu( 'new-content' );

}
 
add_action( 'wp_before_admin_bar_render', 'pxlcore_admin_bar_edit', 100 );