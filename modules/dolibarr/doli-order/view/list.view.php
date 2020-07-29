<?php
/**
 * La vue affichant la liste des commandes dans le backend.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array      $orders   Le tableau contanant toutes les données des commandes.
 * @var Doli_Order $order    Les données d'une commande.
 * @var string     $doli_url L'url de Dolibarr.
 */
?>

<div class="wps-list-product wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-full"><?php esc_html_e( 'Order reference', 'wpshop' ); ?></div>
		<div class="table-cell table-200"><?php esc_html_e( 'Billing contact', 'wpshop' ); ?></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Method of payment', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
		<?php do_action( 'wps_listing_table_header_end', 'order' ); ?>
	</div>

	<?php if ( ! empty( $orders ) ) :
		foreach ( $orders as $order ) :
			View_Util::exec( 'wpshop', 'doli-order', 'item', array(
				'order'    => $order,
				'doli_url' => $doli_url,
			) );
		endforeach;
	endif; ?>
</div>
