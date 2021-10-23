<?php
/**
 * La vue affichant une méthode de paiement dans les réglages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Payment $payment_data Les données d'une méthode de paiement.
 * @var string  $section      La méthode de paiement.
 */
?>

<h3><a href="<?php echo admin_url( 'admin.php?page=wps-settings&tab=payment_method' ); ?>"><?php esc_html_e( 'Payment method', 'wpshop' ); ?></a> -> <?php echo $payment_data['title']; ?></h3>

<?php do_action( 'wps_setting_payment_method_' . $section . '_before_form' ); ?>


<form class="wpeo-form payment-method" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
	<?php do_action( 'wps_setting_payment_method_' . $section . '_prepend_form' ); ?>

	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_method_payment' ); ?>" />
	<input type="hidden" name="type" value="<?php echo esc_attr( $section ); ?>" />
	<?php wp_nonce_field( 'wps_update_method_payment' ); ?>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Title', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="title" value="<?php echo esc_attr( $payment_data['title'] ); ?>" />
		</label>
	</div>

	<div class="form-element bloc-activate">
		<span class="form-label"><?php esc_html_e( 'Activate', 'wpshop' ); ?></span>
		<input type="hidden" name="activate" class="activate" value="<?php echo (int) 1 === (int) $payment_data['active'] ? 'true' : 'false'; ?>" />
		<i style="font-size: 2em;" class="button-toggle fas fa-toggle-<?php echo $payment_data['active'] ? 'on' : 'off'; ?>" data-bloc="bloc-activate" data-input="activate"></i>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Description', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<textarea name="description" class="form-field" rows="3" cols="20"><?php echo $payment_data['description']; ?></textarea>
		</label>
	</div>

	<?php do_action( 'wps_setting_payment_method_' . $section . '_after_form' ); ?>

	<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
</form>
