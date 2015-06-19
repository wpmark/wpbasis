<?php
/**
 * Function wpbasis_content_nav()
 * Function to displaying multiple posts per page navigation.
 * Display as a numbered list, needs styling.
 */
function wpbasis_content_nav() {
	
	/**
	 * indicate a depreciated function to the user
	 * outputs with WP_DEBUG turned on
	 */
	_doing_it_wrong(
		'wpbasis_content_nav',
		'This is a depreciated function - consider using the_post_navigation instead',
		'WP Basis 1.4.3'
	);

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
		echo '<div class="pagenav clearfix">';
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
		echo '</div>';
	}

}

function wpbasis_var_dump() {

	/**
	 * indicate a depreciated function to the user
	 * outputs with WP_DEBUG turned on
	 */
	_doing_it_wrong(
		'wpbasis_var_dump',
		'This is a depreciated function.',
		'WP Basis 1.5.1'
	);

}