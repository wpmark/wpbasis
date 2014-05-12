<?php global $wp_version; ?>
<div class="wrap about-wrap wpbasis-dashboard-wrap">

	<h1><?php bloginfo( 'name' ); ?><br />Dashboard</h1>
	
	<div class="about-text">
		<?php echo apply_filters( 'wpbasis_welcome_text', 'Welcome to your website, designed & developed by ' . get_option( 'wpbasis_organisation_name' ) . '.' ); ?>
	</div>
	
	<div class="wpbasis-badge">
		<a href="http://<?php echo esc_url( get_option( 'wpbasis_domain_name' ) ); ?>">
			<img src="<?php echo esc_url( apply_filters( 'wpbasis_version_logo', plugins_url( 'images/logo.svg', dirname( __FILE__ ) ) ) ); ?>" alt="Logo" />
		</a>
		<?php printf( __( 'Version %s' ), $wp_version ); ?>
	</div>
	
	<div class="wpbasis-tabs-wrapper">
	
		<ul class="wpbasis-tabs">
			
			<?php

				/***************************************************************
				* set an array of tab titles and ids
				* the id set here should match the id given to the content wrapper
				* which has the class wpbasis-tab-content included in the callback
				* function
				***************************************************************/
				$wpbasis_dashboard_tabs = apply_filters(
					'wpbasis_dashboard_tabs',
					array(
						'welcome' => array(
							'id' => '#wpbasis-welcome',
							'label' => 'Welcome',
						),
					)
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
				array(
					'welcome' => array(
						'callback' => 'wpbasis_dashboard_welcome_tab',
					),
				)
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