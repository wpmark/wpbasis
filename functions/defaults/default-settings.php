<?php
/**
 * Function wpbasis_register_settings()
 * Register the settings for this plugin.
 */
function wpbasis_register_plugin_settings() {
	
	/* create an array of the default settings, making it filterable */
	$wpbasis_registered_plugin_settings = apply_filters(
		'wpbasis_register_plugin_settings',
		array(
			'wpbasis_domain_name',
			'wpbasis_organisation_name',
		)
	);

	/* loop through each setting to register */
	foreach( $wpbasis_registered_plugin_settings as $wpbasis_registered_plugin_setting ) {

		/* register the setting */
		register_setting( 'wpbasis_plugin_settings', $wpbasis_registered_plugin_setting );

	}

}

add_action( 'admin_init', 'wpbasis_register_plugin_settings' );

/**
 *
 */
function wpbasis_default_plugin_settings( $settings ) {
	
	/* add the domain names setting */
	$settings[ 'wpbasis_domain_name' ] = array(
		'setting_name' => 'wpbasis_domain_name',
		'setting_label' => 'Domain Name',
		'setting_description' => 'Enter a domain name to be used. This domain name is used for links in the WordPress admin e.g. the footer credit link and also to check against when assigned a WP Basis user. If the users email domain does not match here, they cannot be a WP Basis user.',
		'setting_type' => 'text',
		'setting_class' => 'domain-name',
	);
	
	/* add the orgnisation name setting */
	$settings[ '' ] = array(
		'setting_name' => 'wpbasis_organisation_name',
		'setting_label' => 'Orgnisation Name',
		'setting_description' => 'Enter the name of the organisation. This name is displayed in the WordPress admin as the site developer.',
		'setting_type' => 'text',
		'setting_class' => 'organisation-name',
	);
	
	return $settings;
	
}

add_filter( 'wpbasis_plugin_option_settings', 'wpbasis_default_plugin_settings' );