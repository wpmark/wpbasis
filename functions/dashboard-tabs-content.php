<?php
/***************************************************************
* function wpbasis_dashboard_welcome_tab
* Outputs the content for the dashboard welcome tab
***************************************************************/
function wpbasis_dashboard_welcome_tab() {

	?>
	
	<div id="wpbasis-welcome" class="wpbasis-tab-content" style="display:block;">
	
		<?php

			/***************************************************************
			* @hook wpbasis_before_welcome_tab_content
			***************************************************************/
			do_action( 'wpbasis_before_welcome_tab_content' );

		?>
			
		<div class="col col-half wpbasis-welcome-text">
			<h3><?php echo apply_filters( 'wpbasis_thanks_heading', 'Thank you for choosing ' . get_option( 'wpbasis_organisation_name' ) . '.' ); ?></h3>
			<p class="wpbasis-thankyou-text">Thank you for choosing <?php echo get_option( 'wpbasis_organisation_name' ); ?> to build your website; we appreciate your business and have enjoyed working with you.</p>
			
			<h4 class="wpbasis-important-info">Important Information</h4>
						
		</div>
		
		<div class="col col-half col-last wpbasis-welcome-members">
		
			<h3>Help & Support</h3>
		
		</div>
		
		<div class="clearfix"></div>
		
		<?php

			/***************************************************************
			* @hook wpbasis_after_welcome_tab_content
			***************************************************************/
			do_action( 'wpbasis_after_welcome_tab_content' );

		?>
		
	</div><!-- // wpbasis-team-welcome -->
	
	<?php

}