<?php
/**
 * function wpbasis_defaults_tabs()
 */
function wpbasis_default_tabs( $tabs ) {
	
	/* add the welcome tab to the tabs array */
	$tabs[ 'welcome' ] = array(
		'id'	=> '#wpbasis-welcome',
		'label'	=> 'Welcome',
	);
	
	/* add an updates tab */
	$tabs[ 'updates' ] = array(
		'id'	=> '#wpbasis-updates',
		'label'	=> 'Updates'
	);
	
	return $tabs;
	
}

add_filter( 'wpbasis_dashboard_tabs', 'wpbasis_default_tabs' );

/**
 *
 */
function wpbasis_default_tabs_content( $tabs_contents ) {
	
	/* add the welcome tabs callback function name */
	$tabs_contents[ 'welcome' ] = array(
		'callback'	=> 'wpbasis_dashboard_welcome_tab',
	);
	
	/* add the updates tab callback function name */
	$tabs_contents[ 'updates' ] = array(
		'callback'	=> 'wpbasis_updates_tab_content'
	);
	
	return $tabs_contents;
	
}

add_filter( 'wpbasis_dashboard_tabs_contents', 'wpbasis_default_tabs_content' );

/**
 * function wpbasis_dashboard_welcome_tab_content
 * Content for the welcome tab dashboard home
 * @hooked wpbasis_dashboard_welcome_tab
 */
if( ! function_exists( 'wpbasis_dashboard_welcome_tab' ) ) { // make function pluaggable
	
	function wpbasis_dashboard_welcome_tab() {
	
		?>
		
		<div id="wpbasis-welcome" class="wpbasis-tab-content" style="display:block;">
		
			<?php
	
				/**
				 * @hook wpbasis_before_welcome_tab_content
				 */
				do_action( 'wpbasis_before_welcome_tab_content' );
	
			?>
				
			<h3><?php echo apply_filters( 'wpbasis_thanks_heading', 'Thank you for choosing ' . wpbasis_get_orgnisation_name() . '.' ); ?></h3>
			<p class="wpbasis-thankyou-text">Thank you for choosing <?php echo wpbasis_get_orgnisation_name(); ?> to build your website, to get started with your site, use the links on the left to add and edit content and change your sites settings.</p>
										
			<?php
	
				/**
				 * @hook wpbasis_after_welcome_tab_content
				 */
				do_action( 'wpbasis_after_welcome_tab_content' );
	
			?>
			
		</div><!-- // wpbasis-welcome -->
		
		<?php
		
	}
	
}

add_action( 'wpbasis_dashboard_welcome_tab', 'wpbasis_dashboard_welcome_tab_content', 10 );

/**
 * function wpbasis_updates_tab_content
 * Content for the updates tab dashboard home
 */
if( ! function_exists( 'wpbasis_updates_tab_content' ) ) { // make function pluaggable
	
	function wpbasis_updates_tab_content() {
		
		?>
		
		<div id="wpbasis-updates" class="wpbasis-tab-content">
			
			<?php
	
				/**
				 * @hook wpbasis_before_updates_tab_content
				 */
				do_action( 'wpbasis_before_updates_tab_content' );
				
				/* output information about any core updates required */
				echo wpbasis_get_core_updates_display();
				
				/* output information about any plugin updates required */
				echo wpbasis_get_plugin_updates_display();
	
				/**
				 * @hook wpbasis_after_updates_tab_content
				 */
				do_action( 'wpbasis_after_updates_tab_content' );
	
			?>
			
		</div>
		
		<?php
		
	}

}