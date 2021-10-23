<?php
/**
 * La vue affichant le formulaire pour avoir un aperçu de sa commande.
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
 * @var integer $total_price_no_shipping Prix total sans frais de livraison.
 * @var integer $tva_amount              Montant de la TVA.
 * @var integer $total_price_ttc         Prix total TTC.
 * @var integer $shipping_cost           Frais de livraison.
 */
?>

<div class="wps-checkout-step-3">
	<div id="order_review_heading" class="wps-checkout-subtitle"><?php esc_html_e( 'Your order', 'wpshop' ); ?></div>
	<a href="<?php echo esc_url( Pages::g()->get_cart_link() ); ?>" class="wps-checkout-edit-order"><i class="fas fa-pencil-alt"></i> <?php esc_html_e( 'Edit order', 'wpshop' ); ?></a>

	<?php do_action( 'wps_checkout_before_order_review' ); ?>

	<div id="order_review" class="wps-checkout-review-order">
		<?php do_action( 'wps_checkout_order_review', $total_price_no_shipping, $tva_amount, $total_price_ttc, $shipping_cost ); ?>
	</div>

	<?php do_action( 'wps_checkout_after_order_review' ); ?>
</div>
