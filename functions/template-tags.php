<?php
/***************************************************************
* Function wpbasis_is_wpbasis_user()
* Checks to see if the current user is a wpbasis super user
***************************************************************/
function wpbasis_is_wpbasis_user() {
	
	/* if the current user is a pixel team member */
	if( get_user_meta( get_current_user_id(), 'wpbasis_user', true ) == '1' ) {
	
		return true;
	
	} else {
		
		return false;
		
	}
	
}

/***************************************************************
* Function wpbasis_var_dump()
* Creates prettier version of the var_dump() php function.
***************************************************************/
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

/***************************************************************
* Function wpbasis_content_nav()
* Function to displaying multiple posts per page navigation.
* Display as a numbered list, needs styling.
***************************************************************/
function wpbasis_content_nav() {

	/* initiate global variable for database and wp_query */
	global $wpdb, $wp_query;

	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;

	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}

	$pages_to_show = apply_filters('pxjn_filter_pages_to_show', 8);
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;

	if($start_page <= 0) {
		$start_page = 1;
	}

	$end_page = $paged + $half_page_end;

	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}

	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}

	if($start_page <= 0) {
		$start_page = 1;
	}

	if ($max_page > 1) {
		echo $before.'<div class="pagenav clearfix">';
		if ($start_page >= 2 && $pages_to_show < $max_page) {
			$first_page_text = "&laquo;";
			echo '<a href="'.get_pagenum_link().'" title="'.$first_page_text.'" class="number">'.$first_page_text.'</a>';
		}
		//previous_posts_link('&lt;');
		for($i = $start_page; $i  <= $end_page; $i++) {
			if($i == $paged) {
				echo ' <span class="number current">'.$i.'</span> ';
			} else {
				echo ' <a href="'.get_pagenum_link($i).'" class="number">'.$i.'</a> ';
			}
		}
		//next_posts_link('&gt;');
		if ($end_page < $max_page) {
			$last_page_text = "&raquo;";
			echo '<a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'" class="number">'.$last_page_text.'</a>';
		}
		echo '</div>'.$after;
	}

}

/***************************************************************
* Function wpbasis_featured_img_url()
* Function to output the featured image url.
***************************************************************/
function wpbasis_featured_img_url( $wpbasis_featured_img_size ) {

	/* get the id of the featured image */
	$wpbasis_image_id = get_post_thumbnail_id();

	/* get the image src date for this featuredimage id */
	$wpbasis_image_url = wp_get_attachment_image_src( $wpbasis_image_id, $wpbasis_featured_img_size );

	/* get the first part of the array which is the url */
	$wpbasis_image_url = $wpbasis_image_url[0];

	/* output the url */
	return $wpbasis_image_url;

}

/***************************************************************
* Function wpbasis_featured_img_caption()
* Function to output the featured image caption. Pass to it before
* and after tags, such as '<p>' and '</p>'
***************************************************************/
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

/***************************************************************
* Function wpbasis_featured_img_title()
* Function to output the featured image title. Pass to it before
* and after tags, such as '<h2>' and '</h2>'
***************************************************************/
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

/***************************************************************
* Function wpbasis_get_blog_permalink()
* Find and returns the permalink of the page used for blog posts.
***************************************************************/
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

/***************************************************************
* Function wpbasis_get_wpbasis_users()
* Returns an array of user IDs for all core users.
***************************************************************/
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

/***************************************************************
* Function wpbasis_get_current_url()
* Returns the current url.
***************************************************************/
function wpbasis_get_current_url() {

	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	return $current_url;

}