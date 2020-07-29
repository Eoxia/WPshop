<?php
/**
 * La vue affichant la méthode de paiement Stripe dans la page "Réglages".
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array $stripe_options Le tableau contenant toutes les données de la méthode de paiement Stripe.
 */
?>

<div class="form-element">
	<span class="form-label"><?php esc_html_e( 'Publish key', 'wpshop' ); ?></span>
	<label class="form-field-container">
		<input type="text" class="form-field" name="publish_key" value="<?php echo esc_attr( $stripe_options['publish_key'] ); ?>" />
	</label>
</div>

<div class="form-element">
	<span class="form-label"><?php esc_html_e( 'Secret key', 'wpshop' ); ?></span>
	<label class="form-field-container">
		<input type="text" class="form-field" name="secret_key" value="<?php echo esc_attr( $stripe_options['secret_key'] ); ?>" />
	</label>
</div>

<div class="form-element form-align-horizontal">
	<div class="form-field-inline">
		<input type="checkbox" id="use-stripe-sandbox" class="form-field" <?php echo $stripe_options['use_stripe_sandbox'] ? 'checked' : ''; ?> name="use_stripe_sandbox" />
		<label for="use-stripe-sandbox"><?php esc_html_e( 'Stripe Sandbox', 'wpshop' ); ?></label>
	</div>
</div>
