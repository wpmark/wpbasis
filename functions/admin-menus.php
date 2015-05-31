<?php
/***************************************************************
* Function wpbasis_add_dashboard_home()
* Adds a new page to use for the home page in admin
***************************************************************/
function wpbasis_add_dashboard_home() {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {
		
		/* add a new menu item linking to our new dashboard page */
		add_menu_page(
			'Dashboard',
			'Dashboard',
			'edit_posts',
			'wpbasis_dashboard',
			'wpbasis_dashboard',
			'dashicons-dashboard',
			1
		);

	} else {
		
		/* add the dashboard page with no menu items for wpbasis super users  */
		add_submenu_page(
			NULL,
			'Dashboard',
			'Dashboard',
			'edit_posts',
			'wpbasis_dashboard',
			'wpbasis_dashboard'
		);
		
	}

}

add_action( 'admin_menu', 'wpbasis_add_dashboard_home' );

/***************************************************************
* Function wpbasis_add_plugin_settings()
* Adds a new page to use for the home page in admin
***************************************************************/
function wpbasis_add_plugin_settings() {

	/* if the current user is a wpbasis super user */
	if( wpbasis_is_wpbasis_user() ) {

		/* a plugin settings as sub page of settings */
		add_submenu_page(
			'options-general.php',
			'WP Basis',
			'WP Basis',
			'manage_options',
			'wpbasis_settings',
			'wpbasis_plugin_settings_content'
		);

	}

}

add_action( 'admin_menu', 'wpbasis_add_plugin_settings' );

/***************************************************************
* Function wpbasis_remove_admin_menus()
* Removes admin menus for no wp basis super users
***************************************************************/
function wpbasis_remove_admin_menus() {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {

		$wpbasis_remove_menu_items = apply_filters(
			'wpbasis_remove_admin_menus',
			array(
				'index.php',
				'seperator1',
				'tools.php',
				'options-general.php',
				'edit-comments.php',
				'plugins.php',
			)
		);

		/* loop through each of the items from our array */
		foreach( $wpbasis_remove_menu_items as $wpbasis_remove_menu_item ) {

			/* reomve the menu item */
			remove_menu_page( $wpbasis_remove_menu_item );

		}

	}

}

add_action( 'admin_menu', 'wpbasis_remove_admin_menus', 999 );

/***************************************************************
* Function wpbasis_remove_admin_sub_menus()
* Removes sub admin menus for no pixel junction team members
***************************************************************/
function wpbasis_remove_admin_sub_menus() {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {

		$wpbasis_remove_sub_menu_items = apply_filters(
			'wpbasis_remove_admin_sub_menus',
			array(
				'themes' => array(
					'parent' => 'themes.php',
					'child' => 'themes.php'
				),
				/* remove theme editor - shouldn't be needed when theme mods are turned off in config */
				'theme_editor' => array(
					'parent' => 'themes.php',
					'child' => 'theme-editor.php'
				),
			)
		);

		/* loop through each of the items in our array to remove */
		foreach( $wpbasis_remove_sub_menu_items as $wpbasis_remove_sub_menu_item ) {

			/* remove the sub menu item */
			remove_submenu_page( $wpbasis_remove_sub_menu_item[ 'parent'], $wpbasis_remove_sub_menu_item[ 'child' ] );	

		} // end foreach item
		
		/**
		 * hacky but removes the customize menu which we never want
		 * using the conventional remove_subpage_menu seems not to work
		 */
		global $submenu;
        unset( $submenu[ 'themes.php' ][ 6 ]);

	} // end if wpbasis super user

}

add_action( 'admin_menu', 'wpbasis_remove_admin_sub_menus', 999 );