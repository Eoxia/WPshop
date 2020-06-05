<?php
/**
 * Le formulaire pour créer son adresse de livraison
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wps-checkout-step-3">
	<div id="order_review_heading" class="wps-checkout-subtitle"><?php esc_html_e( 'Your order', 'wpshop' ); ?></div>
	<a href="<?php echo esc_url( Pages::g()->get_cart_link() ); ?>" class="wps-checkout-edit-order"><i class="fas fa-pencil-alt"></i> <?php esc_html_e( 'Edit order', 'wpshop' ); ?></a>

	<?php do_action( 'wps_checkout_before_order_review' ); ?>

	<div id="order_review" class="wps-checkout-review-order">
		<?php do_action( 'wps_checkout_order_review', $total_price_no_shipping, $tva_amount, $total_price_ttc, $shipping_cost ); ?>
	</div>

	<?php do_action( 'wps_checkout_after_order_review' ); ?>
</div>
