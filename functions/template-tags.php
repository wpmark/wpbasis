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
 * Function wpbasis_var_dump()
 * Creates prettier version of the var_dump() php function.
 */
function wpbasis_var_dump( $data, $label = '' ) {

	/* check whether we have been provided with a label */
	if( ! empty( $label ) ) {

		/* output our label as a heading */
		echo '<h2>' . $label . '</h2>';

	}

	/* output the normal var_dump wrapped in <pre> for formatting */
	echo '<pre>'; var_dump( $data ); echo '</pre><hr>';

	return;

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
 * checks a user email domains and matches it witht the wpbasis domain
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