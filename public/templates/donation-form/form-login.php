<?php
/**
 * The template used to display the login form.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$user 			= wp_get_current_user();

if ( 0 !== $user->ID ) {
	return;
}
?>
<p class="login-prompt">
	<a href="#" data-charitable-toggle="charitable-donation-login-form"><?php _e( 'Donated before? Log in before continuing.', 'charitable' ) ?></a>
</p>
<div id="charitable-donation-login-form" class="charitable-login-form charitable-form">
	<p><?php _e( 'If you have donated before, please enter your details below to login. If this is your first time, proceed to the donation form.', 'charitable' ) ?></p>
	<?php wp_login_form() ?>
</div>