<?php
/**
 * Function wpbasis_profile_field()
 * Adds additional field on the users profile page which makes
 * the user a wpbasis super user
 */
function wpbasis_profile_field( $user ) {

	/* bail out early if user is not an admin */
	if ( ! current_user_can( 'manage_options' ) )
		return false;
	
	/* check the current users email domain name validates against wpbasis domain */
	if( wpbasis_validate_email_domain( $user->ID ) == false ) {
		return;
	}

	?>

	<table class="form-table">

		<tr>
			<th scope="row">WP Basis Super User?</th>

			<td>
				
				<fieldset>
				
					<legend class="screen-reader-text">
						<span>WP Basis Super User?</span>
					</legend>
					
					<label for="wpbasis_user">
						<input name="wpbasis_user" type="checkbox" id="wpbasis_user" value="1"<?php checked( get_user_meta( $user->ID, 'wpbasis_user', true ) ) ?> />
						Choose whether this user is a WP Basis super user.
					</label>
				
				</fieldset>
				
			</td>
		</tr>
	
	</table>
	
	<?php

}

add_action( 'personal_options', 'wpbasis_profile_field' );

/**
 * Function wpbasis_save_pixel_profile_field()
 * Saves the information from the additional profile fields
 */
function wpbasis_save_pixel_profile_field( $user_id ) {
	
	/* check the current user is a super admin */
	if ( ! current_user_can( 'manage_options', $user_id ) )
		return false;

	/* get the current user information */
	$wpbasis_current_user = wp_get_current_user();

	/* get the current users email address */
	$wpbasis_current_user_email = $wpbasis_current_user->user_email;

	/* split email at the @ sign */
	$wpbasis_email_parts = explode( '@', $wpbasis_current_user_email );
	
	/* get the domain part of the email */
	$wpbasis_user_email_domain = $wpbasis_email_parts[1];
	
	/* get the email domain option */
	$wpbasis_email_domain = wpbasis_get_wpbasis_domain_name();
	
	/* check we have an email domain added */
	if( ! empty( $wpbasis_email_domain ) ) {
		
		/* if we have a wpbasis user posted */
		if( isset( $_POST[ 'wpbasis_user' ] ) ) {
			
			/* get the email domain is a wpbasis one */
			if( in_array( $wpbasis_user_email_domain, $wpbasis_email_domain ) ) {
	
				/* update the user meta with the additional fields on the profile page */
				update_usermeta( $user_id, 'wpbasis_user', '1' );

			}
			
		/* the email domain does not match the users email domain */
		} else {
			
			/* remove the wpbasis super user meta */
			update_usermeta( $user_id, 'wpbasis_user', '0' );
			
		}
		
	/* no email domain added - update anyway*/	
	} else {
		
		/* update the user meta with the additional fields on the profile page */
		update_usermeta( $user_id, 'wpbasis_user', 1 );
		
	}

}

add_action( 'personal_options_update', 'wpbasis_save_pixel_profile_field' );
add_action( 'edit_user_profile_update', 'wpbasis_save_pixel_profile_field' );