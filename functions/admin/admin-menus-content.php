<?php
/**
 * Function wpbasis_dashboard_content()
 * Pulls in the new dashboard page content
 */
function wpbasis_dashboard() {

	/* check for a dashboard content file in the theme folder */
	if( file_exists( STYLESHEETPATH . '/wpbasis/dashboard.php' ) ) {

		/* load the dashboard content file from the theme folder */
		get_template_part( 'wpbasis/dashboard', 'content' );
		
	/* lets output the content of the dashboard */
	} else {
	
		?>
		<div class="wrap about-wrap wpbasis-dashboard-wrap">
			
			<h1><?php bloginfo( 'name' ); ?></h1>
	
			<?php
				
				/**
				 * @hooked wpbasis_dashboard_about_text
				 */
				do_action( 'wpbasis_dashboard_about_text' );
			
			?>
			
			<div class="wpbasis-tabs-wrapper">
			
				<ul class="wpbasis-tabs">
				
					<?php
		
						/**
						 * set an array of tab titles and ids
						 * the id set here should match the id given to the content wrapper
						 * which has the class wpbasis-tab-content included in the callback
						 * function
						 */
						$wpbasis_dashboard_tabs = apply_filters(
							'wpbasis_dashboard_tabs',
							array()
						);
		
						/* check we have items to show */
						if( ! empty( $wpbasis_dashboard_tabs ) ) {
		
							/* loop through each item */
							foreach( $wpbasis_dashboard_tabs as $wpbasis_dashboard_tab ) {
		
								?>
								<li><a href="<?php echo esc_attr( $wpbasis_dashboard_tab[ 'id' ] ); ?>"><?php echo esc_html( $wpbasis_dashboard_tab[ 'label' ] ); ?></a></li>
								<?php
		
							}
		
						}
		
					?>
					
				</ul>
			
				<?php
	
					/* set an array of tab content blocks */
					$wpbasis_dashboard_tabs_contents = apply_filters(
						'wpbasis_dashboard_tabs_contents',
						array()
					);
		
					/* check we have items to show */
					if( ! empty( $wpbasis_dashboard_tabs_contents ) ) {
		
						/* loop through each item */
						foreach( $wpbasis_dashboard_tabs_contents as $wpbasis_dashboard_tabs_content ) {
		
							/* run the callback function for showing the content */
							$wpbasis_dashboard_tabs_content[ 'callback' ]();
		
						}
		
					}
		
				?>
		
			</div><!-- // wpbasis-tabs-wrapper -->
		</div>
		<?php	

	}

}

/**
 * Function wpbasis_settings_content()
 * Outputs the contents of the settings screen for the plugin
 */
function wpbasis_plugin_settings_content() {

	?>
	
	<div class="wrap">
		
		<h2>WP Basis Settings</h2>
		
		<?php
		
			/**
			 * @hooked wpbasis_before_settings_options
			 */
			do_action( 'wpbasis_before_settings_options' );
		
		?>
				
		<form method="post" action="options.php">
		
			<?php settings_fields( 'wpbasis_plugin_settings' ); ?>
			
			<table class="form-table">
			
				<tbody>
				
					<?php

						/* create empty filterable array for plugins to add own settings */
						$wpbasis_site_option_settings = apply_filters(
							'wpbasis_plugin_option_settings',
							array()
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
			
	<?php
	
	/**
	 * @hooked wpbasis_after_settings_options
	 */
	do_action( 'wpbasis_after_settings_options' );

}