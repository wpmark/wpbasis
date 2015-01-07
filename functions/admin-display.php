<?php
/***************************************************************
* function wpbasis_dashboard_welcome_tab_content
* Content for the welcome tab dashboard home
* @hooked wpbasis_dashboard_welcome_tab
***************************************************************/
if( ! function_exists( 'wpbasis_dashboard_welcome_tab' ) ) { // make function pluaggable
	
	function wpbasis_dashboard_welcome_tab() {
	
		?>
		
		<div id="wpbasis-welcome" class="wpbasis-tab-content" style="display:block;">
		
			<?php
	
				/***************************************************************
				* @hook wpbasis_before_welcome_tab_content
				***************************************************************/
				do_action( 'wpbasis_before_welcome_tab_content' );
	
			?>
				
			<h3><?php echo apply_filters( 'wpbasis_thanks_heading', 'Thank you for choosing ' . wpbasis_get_orgnisation_name() . '.' ); ?></h3>
			<p class="wpbasis-thankyou-text">Thank you for choosing <?php echo wpbasis_get_orgnisation_name(); ?> to build your website; we appreciate your business and have enjoyed working with you.</p>
			
			<p class="get-started">To get started with your site, use the links on the left to add and edit content and change your sites settings.</p>
										
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

/***************************************************************
* Function wpbasis_dashboard_about_text()
* Outputs the about text on the wp basis dashboard above the
* dashboard tabs.
***************************************************************/
function wpbasis_dashboard_about_text() {
	
	global $wp_version; 
	?>
	
	<div class="about-text">
		<?php echo apply_filters( 'wpbasis_welcome_text', 'Welcome to your website, designed & developed by ' . wpbasis_get_orgnisation_name() . '.' ); ?>
	</div>
	
	<div class="wpbasis-badge">
		<img src="<?php echo esc_url( apply_filters( 'wpbasis_version_logo', plugins_url( 'assets/images/logo.svg', dirname( __FILE__ ) ) ) ); ?>" alt="Logo" />
		<?php printf( __( 'Version %s' ), $wp_version ); ?>
	</div>
	
	<?php
	
}

add_action( 'wpbasis_dashboard_about_text', 'wpbasis_dashboard_about_text', 10 );