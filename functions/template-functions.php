<?php
/**
 * Function wpbasis_is_wpbasis_user()
 * Checks to see if the current user is a wpbasis super user
 */
function wpbasis_is_wpbasis_user( $user_id = '' ) {
	
	/* if no user is passed, default to the current users id */
	if( empty( $user_id ) )
		$user_id = get_current_user_id();
		
	/* if the current user is a pixel team member */
	if( get_user_meta( $user_id, 'wpbasis_user', true ) == '1' ) {
	
		return true;
	
	} else {
		
		return false;
		
	}
	
}

/**
 * Function wpbasis_featured_img_url()
 * Function to output the featured image url.
 */
function wpbasis_featured_img_url( $size, $post_id = '' ) {
	
	global $post;
	
	/* if we have no post id - use current post in loop */
	if( empty( $post_id ) )
		$post_id = $post->ID;

	/* get the id of the featured image */
	$wpbasis_image_id = get_post_thumbnail_id( $post_id );

	/* get the image src date for this featuredimage id */
	$wpbasis_image_url = wp_get_attachment_image_src( $wpbasis_image_id, $size );

	/* get the first part of the array which is the url */
	$wpbasis_image_url = $wpbasis_image_url[0];

	/* output the url */
	return $wpbasis_image_url;

}

/**
 * Function wpbasis_featured_img_caption()
 * Function to output the featured image caption. Pass to it before
 * and after tags, such as '<p>' and '</p>'
 */
function wpbasis_featured_img_caption( $wpbasis_before, $wpbasis_after ) {

	/* load the global post variable */
	global $post;

	/* get the id of the featured image */
	$wpbasis_thumbnail_id = get_post_thumbnail_id( $post->ID );

	/* get any attachment posts with the above attachment id i.e. get the post data for the featured image */
	$wpbasis_thumbnail_image = get_posts( array( 'p' => $wpbasis_thumbnail_id, 'post_type' => 'attachment', 'post_status' => 'any' ) );

	/* if we have a post returend */
	if( $wpbasis_thumbnail_image && isset( $wpbasis_thumbnail_image[0] ) ) {

		/* return the caption in a paragraph tag */
		return $wpbasis_before . $wpbasis_thumbnail_image[0]->post_excerpt . $wpbasis_after;

	} // end if we have a post

}

/**
 * Function wpbasis_featured_img_title()
 * Function to output the featured image title. Pass to it before
 * and after tags, such as '<h2>' and '</h2>'
 */
function wpbasis_featured_img_title( $wpbasis_before, $wpbasis_after ) {

	/* load the global post variable */
	global $post;

	/* get the id of the featured image */
	$wpbasis_thumbnail_id = get_post_thumbnail_id( $post->ID );

	/* get any attachment posts with the above attachment id i.e. get the post data for the featured image */
	$wpbasis_thumbnail_image = get_posts( array( 'p' => $wpbasis_thumbnail_id, 'post_type' => 'attachment', 'post_status' => 'any' ) );

	/* if we have a post returend */
	if( $wpbasis_thumbnail_image && isset( $wpbasis_thumbnail_image[0] ) ) {

		/* return the caption in a span tag */
		return $wpbasis_before . $wpbasis_thumbnail_image[0]->post_title . $wpbasis_after;

	} // end if we have a post

}

/**
 * Function wpbasis_get_blog_permalink()
 * Find and returns the permalink of the page used for blog posts.
 */
function wpbasis_get_blog_permalink() {

	/* get the post used for pages */
	$wpbasis_blog_page_id = get_option( 'page_for_posts' );

	/* check the page id does not equal zero */
	if( $wpbasis_blog_page_id != 0 ) {

		/* build permalink of the blog page */
		$wpbasis_blog_permalink = get_permalink( $wpbasis_blog_page_id );

		return $wpbasis_blog_permalink;

	/* no blog page set */	
	} else {

		return false;

	}

}

/**
 * Function wpbasis_get_wpbasis_users()
 * Returns an array of user IDs for all core users.
 */
function wpbasis_get_wpbasis_users() {

	$wpbasis_wpbasis_users_args = array(
		'meta_key' => 'wpbasis_user',
		'meta_value' =>	'1',
		'role' => '',
	);

	/* create the user query */
	$wpbasis_wpbasis_users = new WP_User_Query( $wpbasis_wpbasis_users_args );

	/* check results of user query are not empty */
	if ( ! empty( $wpbasis_wpbasis_users->results ) ) {

		/* setup an array to store user ids in */
		$wpbasis_user_ids = array();

		/* loop through each user returned */
		foreach ( $wpbasis_wpbasis_users->results as $wpbasis_wpbasis_user ) {

			$wpbasis_user_ids[] = $wpbasis_wpbasis_user->ID;

		}

	}

	/* reset query */
	wp_reset_query();

	/* return array of pixel user ids */
	return $wpbasis_user_ids;

}

/**
 * Function wpbasis_get_current_url()
 * Returns the current url.
 */
function wpbasis_get_current_url() {

	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	return $current_url;

}

/**
 * Function wpbasis_get_orgnisation_name()
 * Returns the organisation name set in wp basis option or Wordpress
 * if a name is not set.
 */
function wpbasis_get_orgnisation_name() {
	
	/* get the organisation name from theme options */
	$wpbasis_org_name = get_option( 'wpbasis_organisation_name' );
	
	/* check a name is added */
	if( ! empty( $wpbasis_org_name ) ) {
		
		/* return the name added */
		return $wpbasis_org_name;
		
	/* no name added */
	} else {
		
		/* return wordpress as the org name */
		return 'WordPress';
		
	}
	
}

/**
 * Function wpbasis_get_wpbasis_domain_name()
 * Returns the organisation domain name set in wp basis option or
 * Wordpress.org if a domain name is not set.
 */
function wpbasis_get_wpbasis_domain_name( $returntype = '' ) {
	
	/* get the organisation name from theme options */
	$wpbasis_domain_names = get_option( 'wpbasis_domain_name' );
	
	/* turn the domain name into an array, using commas as a seperator */
	$wpbasis_domain_names_array = explode( ',', $wpbasis_domain_names );
	
	/* check a name is added */
	if( ! empty( $wpbasis_domain_names_array ) ) {
	
		return $wpbasis_domain_names_array;
		
	/* no name added */
	} else {
		
		/* return wordpress as the org name */
		return 'wordpress.org';
		
	}
	
} 

/**
 * Function wpbasis_get_admin_footer_text()
 * Returns a custom admin footer text if name and domain are added
 * in the wpbasis settings page. Returns normal wordpress admin
 * footer text is either one is missing.
 */
function wpbasis_get_admin_footer_text() {
	
	/* get the orgnisation name from wpbasis settings */
	$wpbasis_organisation_name = get_option( 'wpbasis_organisation_name' );
	
	/* get the orgnisation domain from wpbasis settings */
	$wpbasis_domain_name = wpbasis_get_wpbasis_domain_name();

	/* check we have a domain name and an organisation name */
	if( ! empty( $wpbasis_organisation_name ) && ! empty( $wpbasis_domain_name ) ) {
	
		return 'Site created by <a href="' . esc_url( $wpbasis_domain_name[0] ) . '">' . esc_html( $wpbasis_organisation_name ) . '</a> using <a href="http://wordpress.org">WordPress</a>';
	
	/* no domain or organisation names added */
	} else {
		
		/* return the standard footer */
		return 'Thank you for creating with <a href="http://wordpress.org">WordPress</a>';
		
	}
		
}

/**
 * function wpbasis_get_contact_page_id()
 * gets the page id of the page marked as the contact page
 */
function wpbasis_get_contact_page_id() {
	
	/* query the pages for those marked with contact page meta */
	$pages = get_posts(
		array(
			'post_type'		=> 'page',
			'meta_key'		=> '_wpbasis_contact_page',
			'meta_value'	=> '1',
			'fields'		=> 'ids'
		)
	);
	
	/* shift the first item off the array - there should only be one */
	$contact_page = array_shift( $pages );
	
	/* if we do not have a contact page */
	if( empty( $contact_page ) ) {
		return 0;
	}
	
	return $contact_page;
	
}

/**
 * function wpbasis_validate_email_domain
 *
 * checks a user email domains and matches it with the wpbasis domain
 * if the email domain matches we return true or false if not
 */
function wpbasis_validate_email_domain( $user_id ) {

	/* get the current user object */
	$current_user = wp_get_current_user();

	/* get the current users email address */
	$current_user_email = $current_user->user_email;

	/* split email at the @ sign */
	$email_parts = explode( '@', $current_user_email );
	
	/* get the domain part of the email */
	$email_domain = $email_parts[1];
	
	/* get the email domain option */
	$wpbasis_domain = wpbasis_get_wpbasis_domain_name();
	
	/* if the domain name id not wordpress.org */
	if( $wpbasis_domain != 'wordpress.org' ) {
		
		/* check the email domain is a wpbasis domain name */
		if( in_array( $email_domain, $wpbasis_domain ) ) {
			return true;
		}
		
	}
	
	return false;

}

/**
 * function wpbasis_get_plugin_updates_display()
 *
 * checks all plugins to see if any updates are required and then
 * lists the plugin that need updating along with their current
 * and new version numbers.
 *
 * @return (string) html output of plugins requiring update
 */
function wpbasis_get_plugin_updates_display() {
	
	/* get the plugin updates */
	$updates = get_plugin_updates();
	
	/* store all output to a variable */
	$output = '<h4><span class="dashicons dashicons-admin-plugins"></span> Plugin Updates</h4>';

	/* check we have any updates returned */
	if( count( $updates ) > 0 ) {
		
		/* start building output */
		$output .= '<div class="wpbasis-error"><p>The following plugins require updates on this site:</p>';
			
		$output .= '<ul class="wpabsis-updates wpbasis-plugin-updates">';
			
		/* loop through each update */
		foreach( $updates as $update ) {
			
			/* get the sites version of this plugin */
			$installed_version = $update->Version;
			
			/* get the new version to be upgraded to */
			$upgrade_version = $update->update->new_version;

			$output .= '<li class="wrdb-update wrdb-update-' . esc_attr( $update->TextDomain ) . '"><strong>' .  $update->Name . '</strong> - Version ' . esc_html( $installed_version ) . ' is installed. <strong>Version ' . esc_html( $upgrade_version ) . ' is available</strong>.</li>';
		
		} // end loop through each plugin
		
		$output .= '</ul></div>';
		
	} else {
		
		$output .= '<div class="wpbasis-message"><p>All plugins are up-to-date.</p></div>';
		
	}
	
	/* if we have output to show */
	if( ! empty( $output ) ) {
	
		return apply_filters( 'wpbasis_plugin_updates_output', $output );
		
	}
		
}

/**
 * function wpbasis_get_core_updates_display
 *
 * checks whether there are any core updates required and
 * lists the updates required including version from and to
 *
 * @return (string) html output of core updates required
 */
function wpbasis_get_core_updates_display() {
	
	/* get the core updates */
	$updates = get_core_updates();
	
	/* disprove this later */
	$updates_to_install = false;
	
	/* get the sites version of core */
	$installed_version = get_bloginfo( 'version' );
	
	/* get current language */
	$lang = get_option( 'WPLANG' );
	
	//echo '<pre>'; var_dump( $updates ); echo '</pre>';
	
	/* start output var */
	$output = '';
	
	/* loop through each update */
	foreach( $updates as $update ) {
		
		
		/* check if current is not the the installed version */
		if( $update->response == 'upgrade' && $update->locale == $lang ) {
		
			/* set output */
			$update_message .= '<div class="wpbasis-error"><p>Your site requires a WordPress core upgrade. <strong>WordPress ' . $update->current . '</strong> is available for upgrade.</p></div>';
			
			/* set install updates to true */
			$updates_to_install = true;
			
		}
		
	} // end loop through updates
	
	/* check if we have updates */
	if( $updates_to_install == false ) {
		
		$output .= '<h4><span class="dashicons dashicons-wordpress"></span> WordPress Core Updates</h4><div class="wpbasis-message"><p>This site is currently running WordPress version ' . esc_html( get_bloginfo( 'version' ) ) .'. Excellent. WordPress is up-to-date and you are running the latest version.</p></div>';
		
	} else {
		
		/* add title to output */
		$output .= '<h4><span class="dashicons dashicons-wordpress"></span> WordPress Core Updates</h4>' . $update_message;
		
	}
		
	/* output the update info */
	return apply_filters( 'wpbasis_core_update_output', $output );
	
}