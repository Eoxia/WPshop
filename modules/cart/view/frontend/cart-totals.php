<?php
/**
 * Le total du panier.
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
 * @var integer $total_price Prix total du panier.
 *
 */
?>

<div class="wps-cart-totals">
	<?php do_action( 'wps_before_cart_totals' ); ?>

	<h3><?php _e( 'Cart totals', 'wpshop' ); ?></h3>

	<table>
		<tbody>
			<?php do_action( 'wps_cart_totals_table_before' ); ?>

			<?php do_action( 'wps_cart_totals_table_after' ); ?>

			<tr>
				<td><?php _e( 'Total', 'wpshop' ); ?></td>
				<td><?php esc_html( number_format( $total_price, 2, ',', '' ) ); ?>€</td>
			</tr>

		</tbody>
	</table>

	<?php do_action( 'wps_after_cart_totals' ); ?>
</div>
