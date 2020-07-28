<?php
/**
 * La vue affichant les méthodes de paiement.
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
 * @var Payment $payment_methods Tableau contenant toutes les données des méthodes de paiements.
 * @var string  $key             Le type de paiement.
 * @var string  $payment_method  Le type de paiement choisi.
 * @var string  $checked         L'attribut HTML "checked".
 */
?>

<div id="payment" class="wps-checkout-payment">
	<div class="wps-checkout-subtitle"><?php esc_html_e( 'Payment method', 'wpshop' ); ?></div>
	<ul class="wps-payment-list wpeo-gridlayout grid-2 grid-gap-0">
		<?php if ( ! empty( $payment_methods ) ) :
			foreach ( $payment_methods as $key => $payment_method ) :
				if ( $payment_method['active'] ) :
					$checked = '';
					if ( 'cheque' === $key ) :
						$checked = 'checked';
					endif; ?>
					<li class="wps-payment <?php echo esc_attr( $checked ); ?>">
						<label for="radio-<?php echo esc_attr( $key ); ?>" class="wps-payment-container">
							<div class="wps-payment-title">
								<input type="radio" id="radio-<?php echo esc_attr( $key ); ?>" class="form-field" name="type_payment" <?php echo esc_attr( $checked ); ?> value="<?php echo $key; ?>">
								<span class="wps-payement-icon"><?php echo $payment_method['logo']; ?></span>
								<span class="wps-payement-label"><?php echo esc_html( $payment_method['title'] ); ?></span>
							</div>
							<?php if ( ! empty( $payment_method['description'] ) ) : ?>
								<div class="wps-payment-description"><?php echo apply_filters( 'wps_payment_method_' . $key . '_description', nl2br( $payment_method['description'] ) ); ?></div>
							<?php endif; ?>
						</label>
					</li>
				<?php endif;
			endforeach;
		endif; ?>
	</ul>
</div>
