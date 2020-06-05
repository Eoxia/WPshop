<?php
/**
 * La page de validation du tunnel de vente
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

<div class="content">
	<?php
	// translators: Thanks, your order has been received.
	printf( __( 'Thanks, your %s has been received', 'wpshop' ), $title );
	?>

	<div class="wpeo-gridlayout grid-2">
		<ul>
			<li>
				<?php
				// translators: order number: 011111.
				printf( __( '%s :', 'wpshop' ), ucfirst( $title ) );
				?>
				<strong><?php echo esc_html( $object->data['title'] ); ?></strong>
			</li>
			<li><?php esc_html_e( 'Date', 'wpshop' ); ?> : <strong><?php echo esc_html( $object->data['date']['rendered']['date'] ); ?></strong></li>
			<li><?php esc_html_e( 'Total', 'wpshop' ); ?> : <strong><?php echo esc_html( number_format( $object->data['total_ttc'], 2 ) ); ?>€</strong></li>

			<?php
			if ( $atts['type'] == 'order' ) :
				?>
				<li><?php esc_html_e( 'Method of payment', 'wpshop' ); ?> :
					<strong><?php echo esc_html( Payment::g()->get_payment_title( $object->data['payment_method'] ) ); ?></strong>
				</li>
				<?php
			endif;
			?>
		</ul>

		<div id="order_review" class="wps-checkout-review-order">
			<?php do_action( 'wps_checkout_order_review', $total_price_no_shipping, $tva_amount, $total_price_ttc, $shipping_cost ); ?>
		</div>
	</div>

	<a href="<?php echo Pages::g()->get_account_link() . $atts['type']; ?>s/" class="wpeo-button button-main">
		<span><?php echo $button_text; ?></span>
	</a>
</div>
