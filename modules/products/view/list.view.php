<?php
/**
 * Affichage du listing de produit dans le backend.
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

<div class="wps-list-product wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-50"><input type="checkbox" /></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Thumbnail', 'wpshop' ); ?></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Title', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price HT(€)', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Tax Rate', 'wpshop' ); ?>%</div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Stock', 'wpshop' ); ?></div>
		<?php do_action( 'wps_listing_table_header_end', 'products' ); ?>
	</div>

	<?php
	if ( ! empty( $products ) ) :
		foreach ( $products as $product ) :
			\eoxia\View_Util::exec( 'wpshop', 'products', 'item' . $view, array(
				'product'     => $product,
				'sync_status' => false,
				'doli_url'    => $doli_url,
			) );
		endforeach;
	endif;
	?>
</div>
