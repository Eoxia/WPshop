<?php
/**
 * La vue affichant le Lost password.
 *
 * @todo a supprimer
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>


<h2><?php _e('Lost password ?', 'wpshop' ); ?></h2>

<p><?php _e( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'wpshop' ); ?></p>

<form class="wpeo-form" action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<?php wp_nonce_field( 'wps_lost_password' ); ?>
	<input type="hidden" name="action" value="wps_lost_password" />
	<input type="hidden" name="page" value="<?php echo Pages::g()->get_slug_my_account_page(); ?>" />
	<?php do_action( 'wps_lost_password_form_start' ); ?>

	<div class="wpeo-gridlayout grid-2 grid-margin-1">
		<div class="form-element">
			<label class="form-field-container">
				<input type="text" class="form-field" placeholder="<?php esc_html_e( 'Username or email address', 'wpshop' ); ?>" name="user_login" />
			</label>
		</div>
	</div>

	<?php do_action( 'wps_lost_password_form' ); ?>

	<input class="wpeo-button button-main" type="submit" value="<?php esc_attr_e( 'Reset password', 'wphsop' ); ?>" />

	<?php do_action( 'wps_lost_password_form_end' ); ?>
</form>

<?php do_action( 'wps_after_customer_lost_password_form' ); ?>

