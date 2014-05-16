<?php
/***************************************************************
* function wpbasis_dashboard_welcome_tab_content
* Content for the welcome tab dashboard home
* @hooked wpbasis_dashboard_welcome_tab
***************************************************************/
if( ! function_exists( 'wpbasis_dashboard_welcome_tab_content' ) ) { // make function pluaggable
	
	function wpbasis_dashboard_welcome_tab_content() {
	
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
	
}

add_action( 'wpbasis_dashboard_welcome_tab', 'wpbasis_dashboard_welcome_tab_content', 10 );

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

/***************************************************************
* Function wpbasis_site_options_content()
* Creates the output markup for the added site options page
***************************************************************/
function wpbasis_settings_page_intro() {
	
	?>
	
	<div class="welcome-panel wpbasis_settings_intro">
		
		<h3 class="wpbasis-welcome-heading">Welcome to WP Basis</h3>
		
		<p>To get you started you need to update some settings below. With WP Basis there are two types of user. The first is a normal WordPress user and the second is a WP Basis user. The plugins functionality is activated for normal WordPress users. Therefore any users you want to have a normal WordPress experience without any of the admin customisations, should be made a WP Basis user. This can be done on the users profile pages.</p>
		
		<a class="button button-primary button-hero" href="<?php echo admin_url( 'users.php' ); ?>">View Users</a>
		
		<p>or, <a href="<?php echo admin_url( 'users.php' ); ?>">edit your profile here</a></p>
		
	</div>
	
	<?php
	
}

add_action( 'wpbasis_before_settings_options', 'wpbasis_settings_page_intro', 10 );