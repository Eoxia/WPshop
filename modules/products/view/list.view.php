<?php
/**
 * La vue affichant la liste de produit dans le backend.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array   $products Le tableau contenant toutes les données des produits.
 * @var Product $product  Les données d'un produit.
 * @var string  $view     Le type de vue.
 * @var string  $doli_url L'url de Dolibarr.
 */
?>

<div class="wps-list-product wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-50"><input type="checkbox" /></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Thumbnail', 'wpshop' ); ?></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Title', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Categories', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price HT(€)', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Tax Rate', 'wpshop' ); ?>%</div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Stock', 'wpshop' ); ?></div>
		<?php do_action( 'wps_listing_table_header_end', 'products' ); ?>
	</div>

	<?php
	if ( ! empty( $products ) ) :
		foreach ( $products as $product ) :
			View_Util::exec( 'wpshop', 'products', 'item' . $view, array(
				'product'     => $product,
				'sync_status' => false,
				'doli_url'    => $doli_url,
			) );
		endforeach;
	endif;
	?>
</div>
