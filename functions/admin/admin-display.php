<?php
/**
 * Function wpbasis_site_options_content()
 * Creates the output markup for the added site options page
 */
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

/**
 * Function wpbasis_dashboard_about_text()
 * Outputs the about text on the wp basis dashboard above the
 * dashboard tabs.
 */
function wpbasis_dashboard_about_text() {
	
	global $wp_version; 
	?>
	
	<div class="about-text">
		<?php echo apply_filters( 'wpbasis_welcome_text', 'Welcome to your website, designed & developed by ' . wpbasis_get_orgnisation_name() . '.' ); ?>
	</div>
	
	<div class="wpbasis-badge">
		<img src="<?php echo esc_url( apply_filters( 'wpbasis_version_logo', admin_url( '/images/wordpress-logo.svg' ) ) ); ?>" alt="Logo" />
		<?php printf( __( 'Version %s' ), $wp_version ); ?>
	</div>
	
	<?php
	
}

add_action( 'wpbasis_dashboard_about_text', 'wpbasis_dashboard_about_text', 10 );

/**
 * function wpbasis_before_updates_tab_content()
 * outpus text before the updates tab content
 */
function wpbasis_before_updates_tab_content() {
	
	?>
	
	<p>Your site is built using the WordPress content management system. On top of that is other functionality added in the form of plugins. Periodically these are updated to improve performance and security. Any updates required at shown below.</p>
	
	<?Php
	
}

add_action( 'wpbasis_before_updates_tab_content', 'wpbasis_before_updates_tab_content', 10 );