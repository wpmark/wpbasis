<?php
/***************************************************************
* Function wpbasis_profile_field()
* Adds additional field on the users profile page which makes
* the user a wpbasis super user
***************************************************************/
function wpbasis_profile_field( $user ) {

	/* bail out early if user is not an admin */
	if ( ! current_user_can( 'manage_options' ) )
		return false;

	?>

	<table class="form-table">

		<tr>
			<th scope="row">WP Basis Super User?</th>

			<td>
				
				<fieldset>
				
					<legend class="screen-reader-text">
						<span>WP Basis Super User?</span>
					</legend>
					
					<label>
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

/***************************************************************
* Function wpbasis_save_pixel_profile_field()
* Saves the information from the additional profile fields
***************************************************************/
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
		
		/* get the email domain is a pixel one */
		if( in_array( $wpbasis_user_email_domain, $wpbasis_email_domain ) ) {

			/* update the user meta with the additional fields on the profile page */
			update_usermeta( $user_id, 'wpbasis_user', $_POST[ 'wpbasis_user' ] );
		
		/* the email domain does not match the users email domain */
		} else {
			
			/* remove the wpbasis super user meta */
			update_usermeta( $user_id, 'wpbasis_user', '0' );
			
		}
		
	/* no email domain added - update anyway*/	
	} else {
		
		/* update the user meta with the additional fields on the profile page */
		update_usermeta( $user_id, 'wpbasis_user', $_POST[ 'wpbasis_user' ] );
		
	}

}

add_action( 'personal_options_update', 'wpbasis_save_pixel_profile_field' );
add_action( 'edit_user_profile_update', 'wpbasis_save_pixel_profile_field' );

/***************************************************************
* Function wpbasis_register_settings()
* Register the settings for this plugin. Just a username and a
* password for authenticating.
***************************************************************/
function wpbasis_register_settings() {

	/* create an array of the default settings, making it filterable */
	$wpbasis_registered_site_option_settings = apply_filters(
		'wpbasis_register_site_option_settings',
		array(
			'wpbasis_twitter_url',
			'wpbasis_facebook_url',
			'wpbasis_linkedin_url',
			'wpbasis_contact_email',
			'wpbasis_tel_no',
			'wpbasis_footer_text',
		)
	);

	/* loop through each setting to register */
	foreach( $wpbasis_registered_site_option_settings as $wpbasis_registered_site_option_setting ) {

		/* register the setting */
		register_setting( 'wpbasis_site_options', $wpbasis_registered_site_option_setting );

	}
	
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

/***************************************************************
* Function wpbasis_howdy()
* Change Howdy? in the admin bar
***************************************************************/
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

/***************************************************************
* Function wpbasis_admin_footer_text()
* Change the display text in the wordpress dashboard footer
***************************************************************/
function wpbasis_admin_footer_text () {

	/* output this text, running through a filter first */
	echo apply_filters( 'wpbasis_admin_footer_text', wpbasis_get_admin_footer_text() );

}

add_filter( 'admin_footer_text', 'wpbasis_admin_footer_text' );


/***************************************************************
* Function wpbasis_change_login_landing()
* Changing the page users are redirected to after logging in.
***************************************************************/
function wpbasis_change_login_landing( $redirect_to, $request_redirect_to, $user ) {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user( $user->ID ) ) {

		/* return the url of our new dashboard page */
		return apply_filters( 'wpbasis_login_redirect', admin_url( 'admin.php?page=wpbasis_dashboard' ) );

	/* if the current user is a pixel member */
	} else {

		/* return the normal admin url */
		return apply_filters( 'wpbasis_super_user_login_redirect', admin_url() );

	} // end if type of user

}

add_filter( 'login_redirect', 'wpbasis_change_login_landing', 100, 3 );

/***************************************************************
* Function wpbasis_login_logo()
* Adds a login logo from the theme folder if present, otherwise
* falls back to the default
***************************************************************/
function wpbasis_login_logo() {

	/* check whether a login logo exists in the child theme */
	if( file_exists( STYLESHEETPATH . '/images/login-logo.png' ) ) {

		$wpbasis_login_logo_sizes = apply_filters( 'wpbasis_login_logo_sizes',
            array(
                'width' => '300',
                'height' => '100'
            )
        );

		echo '
			<style>
			.login h1 a {
				background-image: url('.get_stylesheet_directory_uri() . '/images/login-logo.png);
				background-size: ' . $wpbasis_login_logo_sizes[ 'width' ] . 'px' . ' ' . $wpbasis_login_logo_sizes[ 'height' ] . 'px;
				height: ' . $wpbasis_login_logo_sizes[ 'height' ] . 'px;
				width: ' . $wpbasis_login_logo_sizes[ 'width' ] . 'px;
			}
			</style>
		';

	} // end if login logo present in theme

}


add_action( 'login_head', 'wpbasis_login_logo' );

/***************************************************************
* Function pxjn_alter_admin_bar()
* Changes the admin bar for non pixel users.
***************************************************************/
function wpbasis_alter_admin_bar() {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {

		/* load the admin bar global variable */
		global $wp_admin_bar;

		/* remove the updates admin bar item */
		$wp_admin_bar->remove_menu( 'updates' );

	}

}
 
add_action( 'wp_before_admin_bar_render', 'wpbasis_alter_admin_bar', 0 );

/***************************************************************
* Function wpbasis_remove_meta_boxes()
* Removes unwanted metabox from the write post/page screens.
***************************************************************/
function wpbasis_remove_meta_boxes() {

	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {

		$wpbasis_remove_metaboxes = apply_filters( 'wpbasis_remove_metaboxes',
			array(
				array(
					'id' => 'postcustom',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'commentsdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'commentstatusdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'slugdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'trackbacksdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'revisionsdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'tagsdiv-post_tag',
					'page' => 'post',
					'context' => 'side'
				),
				array(
					'id' => 'authordiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'postcustom',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'commentsdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'trackbacksdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'revisionsdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'commentstatusdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'authordiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
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

/***************************************************************
* Function wpbasis_edit_user_capabilities()
* Adds widgets and menus to editors.
***************************************************************/
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
				array(
					'capability_name' => 'update_core',
					'capability_action' => false,
				),
				array(
					'capability_name' => 'update_plugins',
					'capability_action' => false,
				),
				array(
					'capability_name' => 'activate_plugins',
					'capability_action' => false,
				),
				array(
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