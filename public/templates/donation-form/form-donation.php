<?php
/**
 * The template used to display the default form.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$form 			= charitable_get_current_donation_form();
$user_fields 	= $form->get_user_fields();
$user 			= wp_get_current_user();

if ( ! $form ) {
	return;
}

/**
 * If the user is not logged in, show a login form at the top of the page. 
 */
if ( 0 === $user->ID ) :

	/**
	 * Add a login form to the top of the page.
	 *
	 * @hook 	charitable_login_form
	 */
	do_action( 'charitable_login_form', $form ); 

endif 
?>
<form method="post" id="charitable-donation-form" class="charitable-form">
	<?php 
	/**
	 * Add the donation form amount field
	 *
	 * @hook 	charitable_donation_form_amount
	 */
	do_action( 'charitable_donation_form_amount', $form ); 

	/**
	 * Add the user fields. 
	 *
	 * @hook 	charitable_donation_form_user_fields
	 */
	do_action( 'charitable_donation_form_user_fields', $form );

		/**
		 * User stuff. 
		 *
		 * If the user isn't logged in, display a login form. 
		 * If they are, display the information we have in store without showing the fields unless they opt to change them.
		 * If they are not logged in and they have no account, they will see the full list of user fields.
		 */
	?>	

	<div class="charitable-form-field charitable-submit-field">
		<input class="button button-primary" type="submit" name="donate" value="<?php esc_attr_e( 'Donate', 'charitable' ) ?>" />
	</div>
</form>