<?php
/***************************************************************
* Function wpbasis_dashboard_content()
* Pulls in the new dashboard page content from plugin file
***************************************************************/
function wpbasis_dashboard() {

	/* check for a dashboard content file in the theme folder */
	if( file_exists( STYLESHEETPATH . '/wpbasis/dashboard.php' ) ) {

		/* load the dashboard content file from the theme folder */
		get_template_part( 'wpbasis/dashboard', 'content' );

	} else {
	
		/* load plugin dashboard content file */
		require_once WPBASIS_LOCATION . '/inc/dashboard-content.php';

	}

}

/***************************************************************
* Function wpbasis_settings_content()
* Outputs the contents of the settings screen for the plugin
***************************************************************/
function wpbasis_plugin_settings_content() {

	?>
	
	<div class="wrap">
		
		<h2>WP Basis Settings</h2>
				
		<form method="post" action="options.php">
		
			<?php settings_fields( 'wpbasis_plugin_settings' ); ?>
			
			<table class="form-table">
			
				<tbody>
				
					<?php

						/* create empty filterable array for plugins to add own settings */
						$wpbasis_site_option_settings = apply_filters(
							'wpbasis_site_option_settings',
							array(
								'wpbasis_domain_name' => array(
									'setting_name' => 'wpbasis_domain_name',
									'setting_label' => 'Domain Name',
									'setting_description' => 'Enter a domain name to be used for WP Basis Super Users only.',
									'setting_type' => 'text',
									'setting_class' => 'domain-name',
								),
								'wpbasis_organisation_name' => array(
									'setting_name' => 'wpbasis_organisation_name',
									'setting_label' => 'Orgnisation Name',
									'setting_description' => 'Enter the name of the orgnisation.',
									'setting_type' => 'text',
									'setting_class' => 'organisation-name',
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

						/* do before settings page action */
						do_action( 'wpbasis_after_site_options' );

					?>
				
				</tbody>
			
			</table>
			
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
			</p>
			
	<?php

}

/***************************************************************
* Function wpbasis_site_options_content()
* Creates the output markup for the added site options page
***************************************************************/
function wpbasis_site_options_content() {

	?>
	
	<div class="wrap">
		
		<h2>Site Options</h2>
		
		<?php

			/* output filterable intro text */
			echo apply_filters( 'wpbasis_site_option_intro', '<p>Below you can set some basic options for your site. Some of these options are used to display content on the front end, for example your telephone number may appear depending on your design.</p>' );

			/* do before settings page action */
			do_action( 'wpbasis_after_site_options_form' );

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
								'wpbasis_content_email' => array(
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
									'media_buttons' => false,
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

						/* do before settings page action */
						do_action( 'wpbasis_after_site_options' );

					?>
				</tbody>
				
			</table>
			
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
			</p>
			
		</form>
		
		<?php

		/* do after settings page action */
		do_action( 'wpbasis_after_site_options_form' );

		?>
		
	</div><!- // wrap -->
	
	<?php

}