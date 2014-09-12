<?php
/***************************************************************
* Function wpbasis_add_submenu_page()
* add a submenu item labelled 'Description' to each public post
* type (filterable)
***************************************************************/
function wpbasis_add_submenu_page() {

	/* get a list of the post types */
	$post_types = wpbasis_get_post_types();

	/* loop through each post type */
	foreach( $post_types as $post_type => $post_type_obj) {

		/* add the menu item */
		$parent_slug = 'edit.php?post_type='.$post_type;
		$page_title  = $post_type_obj->labels->name . ' description';
		$menu_title  = 'Description';
		$capability  = $post_type_obj->cap->edit_posts;
		$menu_slug	 = $post_type_obj->name . '-description';
		$function    = 'wpbasis_manage_description';

		add_submenu_page(
			$parent_slug,
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function
		);

	}
}

add_action( 'admin_menu', 'wpbasis_add_submenu_page' );

/***************************************************************
* Function wpbasis_manage_description()
* Adds the content of the admin page to edit the description
***************************************************************/
function wpbasis_manage_description() {

	/* if we don't have a post type go no further */
	if ( empty( $_GET['post_type'] ) )
		return;

	/* get the post type object - we have one! */
	$post_type = get_post_type_object( $_GET[ 'post_type' ] ); 

	/* if a current description is already added get it */
	$current_description = stripslashes( get_option( $post_type->name . '-description' ) );
	
	/* get any current image url stored */
	$current_image_url = stripslashes( get_option( $post_type->name . '-image_url' ) ); 

	?>
	<h2><?php echo esc_html( $post_type->labels->name ); ?> Description</h2>

	<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] ) { ?>

		<div id="message" class="updated">
			<p>Description Updated.</p>
		</div>

	<?php } ?>

	<form method="POST">
		<div style="width: 95%; margin-top: 50px;">
			<?php
				/* use a tinymce editor */
				wp_editor(
					$current_description,
					'description',
					$settings = array(
						'textarea_rows' => 10,
					)
				);
			?>
		</div>
		
		<div style="width: 95%; margin-top: 20px;">
		
			<label for="post_type_image">Image URL</label>
			<input style="width: 80%;" type="text" name="post_type_image" value="<?php echo esc_attr( $current_image_url ); ?>" />
			<p class="description">Enter the URL of an image to use for this post types archive page.</p>
		
		</div>

		<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type->name ); ?>" />
		
		<p class="submit">
			<input class="button-primary" type="submit" name="wpbasis_update_description" value="Update Description"/>
		</p>

	</form>


<?php }

/***************************************************************
* Function wpbasis_update_description()
* Updates/saves the added description
***************************************************************/
function wpbasis_update_description() {

	if( isset( $_POST[ 'wpbasis_update_description' ] ) ) {

		/* get the posted values for post type and description */
		$post_type = $_POST[ 'post_type' ];
		$description = $_POST[ 'description' ];
		$image_url = $_POST[ 'post_type_image' ];

		/* update the option using the new description entered */
		update_option( $post_type . '-description', $description );
		
		/* update the image url */
		update_option( $post_type . '-image_url', $image_url );

		/* redirect the user to description admin page with added query vars */
		wp_redirect(
			add_query_arg(
				array(
					'post_type' => $post_type,
					'page' => $post_type . '-description',
					'updated' => 'true',
					'post_type' => $post_type
				),
				$wp_get_referer
			)
		);
		exit;

	}

}

add_action( 'init', 'wpbasis_update_description' );

/***************************************************************
* Function wpbasis_description()
* front end function to display the description in the template
***************************************************************/
function wpbasis_post_type_description() {

	/* get the current post type for this archive page */
	$post_type = get_query_var( 'post_type' );

	/* get the saved description from the options table */
	$post_type_description = stripslashes( get_option( $post_type . '-description' ) );

	/* outout the description, running it through the content for wpautop */
	echo apply_filters( 'the_content', $post_type_description );

}

/***************************************************************
* Function wpbasis_description()
* helper function to get all the post types (filterable)
***************************************************************/
function wpbasis_get_post_types() {

	$post_types = get_post_types(
		array(
			'public' => true,
			'show_ui' => true,
		),
		'objects'
	);

	/* allow post types to be filterable */
	$post_types = apply_filters( 'wpbasis_enabled_post_types', $post_types );

	return $post_types;

}

/***************************************************************
* Function wpbasis_description()
* helper function to get an array of post types
***************************************************************/
function wpbasis_get_enabled_post_type_array() {

	/* create an empty array to add to */
	$post_types_array = array();

	/* get our post types list */
	$post_types = wpbasis_get_post_types();

	/* loop through each returned post type */
	foreach( $post_types as $post_type => $post_type_obj ) {

		/* add the post type to our post type array */
		$post_types_array[] = $post_type;

	}

	/* return the array of post types */
	return $post_types_array;

}

/***************************************************************
* Function wpbasis_description()
* remove post types from having a description
***************************************************************/
function wpbasis_remove_pages_post_type( $post_types ) {

    //unset( $post_types[ 'page' ] );
    return $post_types;
    
}

add_filter( 'wpbasis_enabled_post_types', 'wpbasis_remove_pages_post_type' );