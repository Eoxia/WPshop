<?php
/**
 * La vue affichant le formulaire de login.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string $transient               Un message.
 * @var string $transient_lost_password Un message.
 * @var string $account_page            Le slug de la page mon compte.
 */
?>

<?php do_action( 'wps_before_customer_login_form' ); ?>

<?php if ( ! empty( $transient ) ) : ?>
	<div class="notice notice-error ">
		<p><?php echo $transient; ?></p>
	</div>
<?php endif; ?>

<?php if ( ! empty( $transient_lost_password ) ) : ?>
	<div class="notice notice-success">
		<p><?php echo $transient_lost_password; ?></p>
	</div>
<?php endif; ?>

<form class="wpeo-form" action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<?php wp_nonce_field( 'handle_login' ); ?>
	<input type="hidden" name="action" value="wps_login" />
	<input type="hidden" name="page" value="<?php echo $account_page ? Pages::g()->get_slug_my_account_page() : Pages::g()->get_slug_checkout_page(); ?>" />
	<?php do_action( 'wps_login_form_start' ); ?>

	<div class="wpeo-gridlayout grid-2 grid-margin-1">
		<div class="form-element">
			<label class="form-field-container">
				<input type="text" class="form-field" placeholder="<?php esc_html_e( 'Username or email address', 'wpshop' ); ?>" name="username" />
			</label>
		</div>

		<div class="form-element">
			<label class="form-field-container">
				<input type="password" class="form-field" placeholder="<?php esc_html_e( 'Password', 'wpshop' ); ?>" name="password" />
			</label>
		</div>
	</div>

	<?php do_action( 'wps_login_form' ); ?>

	<input class="wpeo-button button-main" type="submit" value="<?php esc_attr_e( 'Log in', 'wphsop' ); ?>" />
	<a href="<?php echo site_url( 'wp-login.php?action=lostpassword' ); ?>" title="Lost Password"><?php esc_html_e( 'Lost Password', 'wpshop' ); ?></a>

	<?php do_action( 'wps_login_form_end' ); ?>
	<?php wp_register() ?>
</form>

<?php do_action( 'wps_after_customer_login_form' ); ?>
