<?php
/**
 * Function wpbasis_profile_field()
 * Adds additional field on the users profile page which makes
 * the user a wpbasis super user
 */
function wpbasis_profile_field( $user ) {

	/* bail out early if user is not an admin */
	if ( ! current_user_can( 'manage_options' ) )
		return false;
	
	/* check the current users email domain name validates against wpbasis domain */
	if( wpbasis_validate_email_domain( $user->ID ) == false ) {
		return;
	}

	?>

	<table class="form-table">

		<tr>
			<th scope="row">WP Basis Super User?</th>

			<td>
				
				<fieldset>
				
					<legend class="screen-reader-text">
						<span>WP Basis Super User?</span>
					</legend>
					
					<label for="wpbasis_user">
						<input name="wpbasis_user" type="checkbox" id="wpbasis_user" value="1"<?php checked( get_user_meta( $user->ID, 'wpbasis_user', true ) ) ?> />
						Choose whether this user is a WP Basis super user.
					</label>
				
				</fieldset>
				
			</td>
		</tr>
	
	</table>
	
	<?php

}

add_action( 'personal_options', 'wpbasis_profile_field' );

/**
 * Function wpbasis_save_pixel_profile_field()
 * Saves the information from the additional profile fields
 */
function wpbasis_save_pixel_profile_field( $user_id ) {
	
	/* check the current user is a super admin */
	if ( ! current_user_can( 'manage_options', $user_id ) )
		return false;

	/* get the current user information */
	$wpbasis_current_user = wp_get_current_user();

	/* get the current users email address */
	$wpbasis_current_user_email = $wpbasis_current_user->user_email;

	/* split email at the @ sign */
	$wpbasis_email_parts = explode( '@', $wpbasis_current_user_email );
	
	/* get the domain part of the email */
	$wpbasis_user_email_domain = $wpbasis_email_parts[1];
	
	/* get the email domain option */
	$wpbasis_email_domain = wpbasis_get_wpbasis_domain_name();
	
	/* check we have an email domain added */
	if( ! empty( $wpbasis_email_domain ) ) {
		
		/* if we have a wpbasis user posted */
		if( isset( $_POST[ 'wpbasis_user' ] ) ) {
			
			/* get the email domain is a wpbasis one */
			if( in_array( $wpbasis_user_email_domain, $wpbasis_email_domain ) ) {
	
				/* update the user meta with the additional fields on the profile page */
				update_usermeta( $user_id, 'wpbasis_user', '1' );

			}
			
		/* the email domain does not match the users email domain */
		} else {
			
			/* remove the wpbasis super user meta */
			update_usermeta( $user_id, 'wpbasis_user', '0' );
			
		}
		
	/* no email domain added - update anyway*/	
	} else {
		
		/* update the user meta with the additional fields on the profile page */
		update_usermeta( $user_id, 'wpbasis_user', 1 );
		
	}

}

add_action( 'personal_options_update', 'wpbasis_save_pixel_profile_field' );
add_action( 'edit_user_profile_update', 'wpbasis_save_pixel_profile_field' );

/**
 * Function wpbasis_register_settings()
 * Register the settings for this plugin. Just a username and a
 * password for authenticating.
 */
function wpbasis_register_settings() {
	
	/* create an array of the default settings, making it filterable */
	$wpbasis_registered_plugin_settings = apply_filters(
		'wpbasis_register_plugin_settings',
		array(
			'wpbasis_domain_name',
			'wpbasis_organisation_name',
		)
	);

	/* loop through each setting to register */
	foreach( $wpbasis_registered_plugin_settings as $wpbasis_registered_plugin_setting ) {

		/* register the setting */
		register_setting( 'wpbasis_plugin_settings', $wpbasis_registered_plugin_setting );

	}

}

add_action( 'admin_init', 'wpbasis_register_settings' );

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

/**
 * Function wpbasis_admin_footer_text()
 * Change the display text in the wordpress dashboard footer
 */
function wpbasis_admin_footer_text () {

	/* output this text, running through a filter first */
	echo apply_filters( 'wpbasis_admin_footer_text', wpbasis_get_admin_footer_text() );

}

add_filter( 'admin_footer_text', 'wpbasis_admin_footer_text' );


/**
 * Function wpbasis_change_login_landing()
 * Changing the page users are redirected to after logging in.
 */
function wpbasis_change_login_landing( $redirect_to, $request_redirect_to, $user ) {

	/* return the url of our new dashboard page */
	return apply_filters( 'wpbasis_login_redirect', admin_url( 'admin.php?page=wpbasis_dashboard' ) );

}

add_filter( 'login_redirect', 'wpbasis_change_login_landing', 20, 3 );

/**
 * Function wpbasis_login_logo()
 * Adds a login logo from the theme folder if present, otherwise
 * falls back to the default
 */
function wpbasis_login_logo() {
	
	/* set the login logo path - filterable */
	$logo_path = apply_filters( 'wpbasis_login_logo_path', '/images/login-logo.png' );

	/* check whether a login logo exists in the child theme */
	if( file_exists( STYLESHEETPATH . $logo_path ) ) {

		$wpbasis_login_logo_sizes = apply_filters( 'wpbasis_login_logo_sizes',
            array(
                'width' => '300',
                'height' => '100'
            )
        );

		echo '
			<style>
			.login h1 a {
				background-image: url(' . get_stylesheet_directory_uri() . $logo_path . ');
				background-size: ' . $wpbasis_login_logo_sizes[ 'width' ] . 'px' . ' ' . $wpbasis_login_logo_sizes[ 'height' ] . 'px;
				height: ' . $wpbasis_login_logo_sizes[ 'height' ] . 'px;
				width: ' . $wpbasis_login_logo_sizes[ 'width' ] . 'px;
			}
			</style>
		';

	} // end if login logo present in theme

}


add_action( 'login_head', 'wpbasis_login_logo' );

/**
 * Function wpbasis_remove_meta_boxes()
 * Removes unwanted metabox from the write post/page screens.
 */
function wpbasis_remove_meta_boxes() {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {

		$wpbasis_remove_metaboxes = apply_filters(
			'wpbasis_remove_metaboxes',
			array(
				'post-postcustom' => array(
					'id' => 'postcustom',
					'page' => 'post',
					'context' => 'normal'
				),
				'post-commentsdiv' => array(
					'id' => 'commentsdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				'post-commentstatusdiv' => array(
					'id' => 'commentstatusdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				'post-slugdiv' => array(
					'id' => 'slugdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				'post-trackbacksdiv' => array(
					'id' => 'trackbacksdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				'post-revisionsdiv' => array(
					'id' => 'revisionsdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				'post-authordiv' => array(
					'id' => 'authordiv',
					'page' => 'post',
					'context' => 'normal'
				),
				'page-postcustom' => array(
					'id' => 'postcustom',
					'page' => 'page',
					'context' => 'normal'
				),
				'page-commentsdiv' => array(
					'id' => 'commentsdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				'page-trackbacksdiv' => array(
					'id' => 'trackbacksdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				'page-revisionsdiv' => array(
					'id' => 'revisionsdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				'page-commentstatusdiv' => array(
					'id' => 'commentstatusdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				'page-authordiv' => array(
					'id' => 'authordiv',
					'page' => 'page',
					'context' => 'normal'
				),
				'page-slugdiv' => array(
					'id' => 'slugdiv',
					'page' => 'page',
					'context' => 'normal'
				),
			)
		);

		/* loop through each meta box item to remove */
		foreach( $wpbasis_remove_metaboxes as $wpbasis_remove_metabox ) {

			/* remove each metabox from the array */
			remove_meta_box( $wpbasis_remove_metabox[ 'id' ], $wpbasis_remove_metabox[ 'page' ] , $wpbasis_remove_metabox[ 'context' ] );

		}

	}

}

add_action( 'do_meta_boxes', 'wpbasis_remove_meta_boxes');

/**
 * Function wpbasis_edit_user_capabilities()
 * Adds widgets and menus to editors.
 */
function wpbasis_edit_user_capabilities( $caps ) {

	/* check if the user has the edit_pages capability */
	if( ! empty( $caps[ 'edit_pages' ] ) ) {

		/* give the user the edit theme options capability */
		$caps[ 'edit_theme_options' ] = true;

	}
	
	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {
		
		/* setup an array of capabilities to change - filterable */
		$wpbasis_capabilities = apply_filters(
			'wpbasis_user_capabilities',
			array(
				'update_core' => array(
					'capability_name' => 'update_core',
					'capability_action' => false,
				),
				'update_plugins' => array(
					'capability_name' => 'update_plugins',
					'capability_action' => false,
				),
				'activate_plugins' => array(
					'capability_name' => 'activate_plugins',
					'capability_action' => false,
				),
				'install_plugins' => array(
					'capability_name' => 'install_plugins',
					'capability_action' => false,
				),
			)
		);
		
		/* loop through each capability */
		foreach( $wpbasis_capabilities as $wpbasis_capability ) {
			
			/* check if the user has the capability */
			if( ! empty( $caps[ $wpbasis_capability[ 'capability_name' ] ] ) ) {
			
				/* action the capability - adding or remove accordingly */
				$caps[ $wpbasis_capability[ 'capability_name' ] ] = $wpbasis_capability[ 'capability_action' ];
			
			}
			
		}
										
	}

	/* return the modified capabilities */
	return $caps;

}

add_filter( 'user_has_cap', 'wpbasis_edit_user_capabilities' );

/**
 * Function wpbasis_remove_update_nag()
 * Removes the defauly wordpress update nag when core needs updating.
 */
function wpbasis_remove_update_nag() {
	
	/* remove the update nag */
	remove_action( 'admin_notices', 'update_nag', 3 );
	
}

add_action( 'admin_init', 'wpbasis_remove_update_nag' );

/**
 * Function wpbasis_update_nag()
 * Adds an update nag when there is a new core version available.
 * unique nag provided for no wpbasis users.
 */
function wpbasis_update_nag() {
	
	/* if this is a multisite and the user does not have update core capabilities - bail */
	if ( is_multisite() && !current_user_can( 'update_core' ) )
		return false;

	global $pagenow;
	
	/* if we are on the update page in the admin - bail */
	if ( 'update-core.php' == $pagenow )
		return;
	
	/* get the first update version */
	$cur = get_preferred_from_update_core();
	
	/* if there is not upgrade version of the response is not to upgrade - bail */
	if ( ! isset( $cur->response ) || $cur->response != 'upgrade' )
		return false;
	
	/* check the current user has update core capabilities */
	if ( current_user_can('update_core') ) {
		
		/* build a message to display */
		$msg = sprintf( __('<a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> is available! <a href="%2$s">Please update now</a>.'), $cur->current, network_admin_url( 'update-core.php' ) );
	
	/* user does not have update core capabilities */
	} else {
		
		/* get the wpbasis domain - as added in settings */
		$wpbasis_domain = wpbasis_get_wpbasis_domain_name();
		
		/* if the wpbasis domain is an array */
		if( is_array( $wpbasis_domain ) ) {
			
			/* get the first element on the array */
			$wpbasis_domain = $wpbasis_domain[0];
			
		}
		
		/* build full get in touch url */
		$wpbasis_update_contact_url = $wpbasis_domain . '/contact/';
		
		/* build message to display */
		$msg = sprintf( __('<a href="http://codex.wordpress.org/Version_%1$s">WordPress %1$s</a> is available! Please <a href="' . esc_url( apply_filters( 'wpbasis_update_contact_url', $wpbasis_update_contact_url ) ) . '">get in touch</a> for an update.'), $cur->current );
	}
	
	echo "<div class='update-nag'>$msg</div>";
	
}

add_action( 'admin_notices', 'wpbasis_update_nag', 3 );

/**
 * function wpbasis_contact_page_checkbox()
 * outputs the checkbox to mark a page as the contact page
 */
function wpbasis_contact_page_checkbox() {
	
	/* only do this for pages */
	if( 'page' != get_post_type() ) {
		return;
	}
	
	/* add a nonce field so we can check for it later */
	wp_nonce_field( 'wpbasis_contact_page_checkbox', 'wpbasis_contact_page_checkbox_nonce' );
	
	/* get the current contact page */
	$current_contact_page = wpbasis_get_contact_page_id();
	
	?>
	
	<div class="misc-pub-section contact-page">
		<input type="hidden" name="wpbasis_current_contact_page" value="<?php echo esc_attr( $current_contact_page ); ?>" />
		<input type="checkbox" name="wpbasis_contact_page" value="1" <?php checked( 1, get_post_meta( $_GET[ 'post' ], '_wpbasis_contact_page', true ) ); ?> />
		<label for="wpbasis_contact_page">Mark as Contact Page</label>
	</div>
	
	<?php
	
}

add_action( 'post_submitbox_misc_actions', 'wpbasis_contact_page_checkbox' );

/**
 * function wpbasis_contact_page_checkbox_save()
 * saves the checkbox marking a page as the contact page
 */
function wpbasis_contact_page_checkbox_save( $post_id ) {
	
	/* only do this for pages */
	if( 'page' != get_post_type( $post_id ) ) {
		return;
	}
	
	/* check if our nonce is se */
	if ( ! isset( $_POST[ 'wpbasis_contact_page_checkbox_nonce' ] ) ) {
		return;
	}
	
	/* verify that the nonce is valid */
	if ( ! wp_verify_nonce( $_POST[ 'wpbasis_contact_page_checkbox_nonce' ], 'wpbasis_contact_page_checkbox' ) ) {
		return;
	}
	
	/* if this is an autosave, our form has not been submitted, so we don't want to do anything */
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	/* check the current user can edit this page */
	if ( ! current_user_can( 'edit_page', $_GET[ 'post' ] ) ) {
		return;
	}
	
	/* check if a page is already set as the contact page */
	if( $_POST[ 'wpbasis_current_contact_page' ] != 0 && isset( $_POST[ 'wpbasis_contact_page' ] ) ) {
		
		/* remove the post meta from the currently assigned page */
		delete_post_meta( $_POST[ 'wpbasis_current_contact_page' ], '_wpbasis_contact_page' );
		
	}
	
	/* if the box has beeb ticked */
	if( isset( $_POST[ 'wpbasis_contact_page' ] ) ) {
		
		/* update post meta with the contact page value */
		update_post_meta( $post_id, '_wpbasis_contact_page', $_POST[ 'wpbasis_contact_page' ] );
		
	}
	
}

add_action( 'save_post', 'wpbasis_contact_page_checkbox_save' );