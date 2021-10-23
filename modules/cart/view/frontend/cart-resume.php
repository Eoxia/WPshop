<?php
/**
 * La vue affichant le tableau récapitulatif du panier.
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
 * @var integer       $total_price_no_shipping Prix total sans frais de livraison.
 * @var integer       $tva_amount              Montant de la TVA.
 * @var integer       $shipping_cost           Frais de livraison.
 * @var integer       $total_price_ttc         Prix total TTC.
 */
?>

<ul class="wps-cart-resume">
	<?php do_action( 'wps_before_cart_resume_lines' ); ?>

	<li class="wps-resume-line">
		<span class="wps-line-content"><?php echo esc_html( 'Subtotal', 'wpshop' ); ?></span>
		<span class="wps-line-value"><?php echo esc_html( number_format( $total_price_no_shipping, 2, '.', '' ) ); ?>€</span>
	</li>
	<li class="wps-resume-line">
		<span class="wps-line-content"><?php echo esc_html( 'Taxes', 'wpshop' ); ?></span>
		<span class="wps-line-value"><?php echo esc_html( number_format( $tva_amount, 2, '.', '' ) ); ?>€</span>
	</li>
	<?php if ( ! empty( $shipping_cost ) ) : ?>
		<li class="wps-resume-line">
			<span class="wps-line-content"><?php echo esc_html( 'Shipping costs', 'wpshop' ); ?></span>
			<span class="wps-line-value"><?php echo esc_html( number_format( $shipping_cost, 2, '.', '' ) ); ?>€</span>
		</li>
	<?php endif; ?>

	<?php do_action( 'wps_after_cart_resume_lines' ); ?>

	<li class="wps-resume-line featured">
		<span class="wps-line-content"><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></span>
		<span class="wps-line-value"><?php echo esc_html( number_format( $total_price_ttc, 2, '.', '' ) ); ?>€</span>
	</li>

	<?php do_action( 'wps_after_cart_resume_total' ); ?>
</ul>
