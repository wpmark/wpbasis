<?php
/***************************************************************
* Function wpbasis_add_site_options()
* Adds a new menu item for the plugin settings.
***************************************************************/
function wpbasis_add_site_options() {
	
	/* if the current user is not a wpbasis super user */
	if( ! wpbasis_is_wpbasis_user() ) {

		/* add a new menu item linking to our new dashboard page */
		add_menu_page(
			'Site Options',
			'Site Options',
			'edit_pages',
			'wpbasis_site_options',
			'wpbasis_site_options_content',
			'dashicons-admin-generic',
			99
		);

	/* current user is a pixel team member */
	} else {

		/* a site options as sub page of settings */
		add_submenu_page(
			'options-general.php',
			'Site Options',
			'Site Options',
			'edit_pages',
			'wpbasis_site_options',
			'wpbasis_site_options_content'
		);

	}

}

add_action( 'admin_menu', 'wpbasis_add_site_options' );


/***************************************************************
* Function wpbasis_site_options_content()
* Creates the output markup for the added site options page
***************************************************************/
function wpbasis_site_options_content() {

	?>
	
	<div class="wrap">
		
		<h2>Site Options</h2>
		
		<?php

			/***************************************************************
			* @hooked wpbasis_before_site_options_form
			***************************************************************/
			do_action( 'wpbasis_before_site_options_form' );

		?>
		
		<form method="post" action="options.php">
		
			<?php settings_fields( 'wpbasis_site_options' ); ?>
			
			<table class="form-table">
			
				<tbody>
					
					<?php

						/* create empty filterable array for plugins to add own settings */
						$wpbasis_site_option_settings = apply_filters(
							'wpbasis_site_option_settings',
							array(
								'wpbasis_twitter_url' => array(
									'setting_name' => 'wpbasis_twitter_url',
									'setting_label' => 'Twitter URL',
									'setting_description' => 'Enter the URL for your Twitter page.',
									'setting_type' => 'text',
									'setting_class' => 'twitter',
								),
								'wpbasis_facebook_url' => array(
									'setting_name' => 'wpbasis_facebook_url',
									'setting_label' => 'Facebook URL',
									'setting_description' => 'Enter the URL for your Facebook page.',
									'setting_type' => 'text',
									'setting_class' => 'facebook',
								),
								'wpbasis_linkedin_url' => array(
									'setting_name' => 'wpbasis_linkedin_url',
									'setting_label' => 'LinkedIn URL',
									'setting_description' => 'Enter the URL for your LinkedIn page.',
									'setting_type' => 'text',
									'setting_class' => 'linkedin',
								),
								'wpbasis_contact_email' => array(
									'setting_name' => 'wpbasis_contact_email',
									'setting_label' => 'Contact Email',
									'setting_description' => 'Enter a contact email here, this may be used on the site for people to get in touch with you.',
									'setting_type' => 'text',
									'setting_class' => 'email',
								),
								'wpbasis_tel_no' => array(
									'setting_name' => 'wpbasis_tel_no',
									'setting_label' => 'Telephone Number',
									'setting_description' => 'Please enter your contact telephone number here, which may be displayed on the site depending on your design.',
									'setting_type' => 'text',
									'setting_class' => 'telno',
								),
								'wpbasis_footer_text' => array(
									'setting_name' => 'wpbasis_footer_text',
									'setting_label' => 'Footer Text',
									'setting_description' => 'Enter text here to display in the footer of your site. You could include a Copyright notice for example.',
									'setting_type' => 'wysiwyg',
									'setting_class' => 'footer_text',
									'media_buttons' => apply_filters( 'wpbasis_footer_text_media_buttons', false ),
									'textarea_rows' => 5,
								),
							)
						);

						/* check we have settings items to output */
						if( ! empty( $wpbasis_site_option_settings ) ) {

							/* loop through each feature control output */
							foreach( $wpbasis_site_option_settings as $wpbasis_site_option_setting ) {

								?>
						
								<tr valign="top" class="wpbasis-setting wpbasis-setting-<?php echo esc_attr( $wpbasis_site_option_setting[ 'setting_class' ] ); ?>">
									<th scope="row">
										<label for="<?php echo esc_attr( $wpbasis_site_option_setting[ 'setting_name' ] ); ?>"><?php echo $wpbasis_site_option_setting[ 'setting_label' ]; ?></label>
									</th>
									<td>
										
										<?php

											/* setup a swith statement to output based on setting type */
											switch( $wpbasis_site_option_setting[ 'setting_type' ] ) {

												/* if the type is set to select input */
											    case 'select':

											    	?>
											    	<select name="<?php echo $wpbasis_site_option_setting[ 'setting_name' ]; ?>" id="<?php echo $wpbasis_site_option_setting[ 'setting_name' ]; ?>">
											    	
											    	<?php

											    	/* get the setting options */
											    	$wpbasis_setting_options = $wpbasis_site_option_setting[ 'setting_options' ];

											        /* loop through each option */
											        foreach( $wpbasis_setting_options as $wpbasis_setting_option ) {

												        ?>
												        <option value="<?php echo esc_attr( $wpbasis_setting_option[ 'value' ] ); ?>" <?php selected( get_option( $wpbasis_site_option_setting[ 'setting_name' ] ), $wpbasis_setting_option[ 'value' ] ); ?>><?php echo $wpbasis_setting_option[ 'name' ]; ?></option>
														<?php

											        }

											        ?>
											    	</select>
											        <?php

											        /* break out of the switch statement */
											        break;

											    /* if the type is set to a textarea input */  
											    case 'textarea':

											    	?>
											        <textarea name="<?php echo $wpbasis_site_option_setting[ 'setting_name' ]; ?>" rows="4" cols="50" id="<?php echo $wpbasis_site_option_setting[ 'setting_name' ]; ?>" class="regular-text"><?php echo get_option( $wpbasis_site_option_setting[ 'setting_name' ] ) ?></textarea>
											        <?php

											        /* break out of the switch statement */
											        break;

											    case 'wysiwyg':

											    	/* set some settings args for the editor */
											    	$wpbasis_editor_settings = array(
											    		'textarea_rows' => $wpbasis_site_option_setting[ 'textarea_rows' ],
											    		'media_buttons' => $wpbasis_site_option_setting[ 'media_buttons' ],
											    	);

											    	/* get current content for the wysiwyg */
											    	$wpbasis_wysiwyg_content = get_option( $wpbasis_site_option_setting[ 'setting_name' ] );

											    	/* display the wysiwyg editor */
											    	wp_editor( $wpbasis_wysiwyg_content, $wpbasis_site_option_setting[ 'setting_name' ], $wpbasis_editor_settings );

											    	break;

											    /* any other type of input - treat as text input */ 
											    default:
													?>
													<input type="text" name="<?php echo $wpbasis_site_option_setting[ 'setting_name' ]; ?>" id="<?php echo $wpbasis_site_option_setting[ 'setting_name' ]; ?>" class="regular-text" value="<?php echo get_option( $wpbasis_site_option_setting[ 'setting_name' ] ) ?>" />
													<?php

											}

										?>
										
										<p class="description"><?php echo $wpbasis_site_option_setting[ 'setting_description' ]; ?></p>
									</td>
								</tr>
								
								<?php

							}

						}

					?>
				</tbody>
				
			</table>
			
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
			</p>
			
		</form>
		
		<?php

		/***************************************************************
		* @hooked wpbasis_after_site_options_form
		***************************************************************/
		do_action( 'wpbasis_after_site_options_form' );

		?>
		
	</div><!- // wrap -->
	
	<?php

}

/***************************************************************
* Function wpbasis_site_options_intro()
* Output intro text on the site options page
* @hooked wpbasis_before_site_options_form
***************************************************************/
function wpbasis_site_options_intro() {
	
	/* output filterable intro text */
	$wpbasis_site_options_intro = '<p>Below you can set some basic options for your site. Some of these options are used to display content on the front end, for example your telephone number may appear depending on your design.</p>';
	
	echo apply_filters( 'wpbasis_site_option_intro', $wpbasis_site_options_intro );
	
}

add_action( 'wpbasis_before_site_options_form', 'wpbasis_site_options_intro', 10 );