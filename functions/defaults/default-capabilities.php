<?php
/**
 * function wpbasis_default_capabilities()
 *
 * removes some capabilities for none wpbasis users
 * @param	array	$caps is the current caps in the filter
 */
function wpbasis_default_capabilities( $caps ) {
	
	/* prevent user from updating wordpress */
	$caps[ 'update_core' ] = array(
		'name'		=> 'update_core',
		'action' 	=> false,
	);
	
	/* prevent user from updating, activating and installing plugins */
	$caps[ 'install_plugins' ] = array(
		'name'		=> 'install_plugins',
		'action'	=> false,
	);
	
	$caps[ 'activate_plugins' ] = array(
		'name'		=> 'activate_plugins',
		'action'	=> false,
	);
	
	$caps[ 'update_plugins' ] = array(
		'name'		=> 'update_plugins',
		'action'	=> false,
	);
	
	/* prevent user from installing, activating or switching themes */
	$caps[ 'update_themes' ] = array(
		'name'		=> 'update_themes',
		'action'	=> false,
	);
	
	$caps[ 'install_themes' ] = array(
		'name'		=> 'install_themes',
		'action'	=> false,
	);
	
	$caps[ 'switch_themes' ] = array(
		'name'		=> 'switch_themes',
		'action'	=> false,
	);
	
	return $caps;
	
}

add_filter( 'wpbasis_user_capabilities', 'wpbasis_default_capabilities', 10, 1 );

/**
 * function wpmark_modify_gforms_capabilities()
 *
 * modifies the capabilities assigned to admin users
 * wpbasis super admin users get all gfrom caps whereas
 * no super users get view entires only
 */
function wpmark_modify_gforms_capabilities( $caps ) {
   	
   	/* only run if gravity forms is active */
   	if( ! class_exists( 'GFForms' ) ) {
	   	return $caps;
   	}
   	
    /* if this is a wpbasis super user */
    if( wpbasis_is_wpbasis_user() ) {
	    
	    /* return unfiltered caps - do nothing basically */
        return $caps;
        
    /* not a wpbasis user */
    } else {
	    
	    /* stop gravity forms adding the capabilities to this user */
	    remove_filter( 'user_has_cap', array( 'RGForms', 'user_has_cap' ), 10, 3 );
	    
	    /* remove unwanted caps */
        $caps['gform_full_access'] = false;

        $caps['gravityforms_create_form'] = false;
        $caps['gravityforms_delete_forms'] = false;
        $caps['gravityforms_edit_forms'] = false;
        $caps['gravityforms_edit_settings'] = false;
        $caps['gravityforms_uninstall'] = false;
        $caps['gravityforms_view_settings'] = false;
        $caps['gravityforms_addon_browser'] = false;
        $caps['gravityforms_view_updates'] = false;
        $caps['gravityforms_view_entries'] = true;
        $caps['gravityforms_edit_entries'] = true;
        $caps['gravityforms_view_entry_notes'] = true;
        $caps['gravityforms_edit_entry_notes'] = true;
        $caps['gravityforms_delete_entries'] = true;
        
    }
	
	/* return our filtered caps */
    return $caps;
    
}

add_filter( 'user_has_cap', 'wpmark_modify_gforms_capabilities', 5, 3 );