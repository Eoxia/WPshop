<?php
/**
 * La vue affichant un produit dans la liste des produits.
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
 * @var Product $product     Les données d'un produit.
 * @var string  $doli_url    L'url de Dolibarr.
 * @var string  $sync_status True si on affiche le statut de la synchronisation.
 */
?>

<div class="table-row" data-id="<?php echo esc_attr( $product->data['id'] ); ?>">
	<div class="table-cell table-50"><input type="checkbox" /></div>
	<div class="table-cell table-100 table-padding-0"><?php echo get_the_post_thumbnail( $product->data['id'], array( 80, 80 ) ); ?></div>
	<div class="table-cell table-full">
		<div class="reference-title">
			<a href="<?php echo esc_attr( admin_url( 'post.php?post=' . $product->data['id'] . '&action=edit' ) ); ?>"><?php echo esc_html( $product->data['title'] ); ?></a>
		</div>
		<ul class="reference-actions">
			<li><a href="<?php echo esc_attr( admin_url( 'post.php?post=' . $product->data['id'] . '&action=edit' ) ); ?>"><?php esc_html_e( 'Edit', 'wpshop' ); ?></a></li>
			<?php if ( ! empty( $product->data['external_id'] ) ) : ?>
				<li><a href="<?php echo esc_attr( $doli_url ); ?>/product/card.php?id=<?php echo $product->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a></li>
			<?php endif; ?>
			<li><a href="<?php echo esc_attr( get_post_permalink( $product->data['id'] ) ); ?>"><?php esc_html_e( 'Preview', 'wpshop' ); ?></a></li>
			<li class="delete"><a href="<?php echo esc_attr( admin_url( 'post.php?post=' . $product->data['id'] . '&action=delete&_wpnonce='. wp_create_nonce('delete-post_' . $product->data['id'] ) ) ); ?>"><?php esc_html_e( 'Delete', 'wpshop' ); ?></a></li>
		</ul>
	</div>
	<div class="table-cell table-100"><?php $related_categories = Doli_Category::g()->get_related_categories($product) ;
		foreach	($related_categories as $related_category) :
			echo $related_category . ', ';
		endforeach; ?></div>
	<div class="table-cell table-100"><?php echo esc_html( number_format( $product->data['price'], 2, ',', '' ) ); ?>€</div>
	<div class="table-cell table-100"><?php echo esc_html( number_format( $product->data['tva_tx'], 2, ',', '' ) ); ?>%</div>
	<div class="table-cell table-100"><strong><?php echo esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ); ?>€</strong></div>
	<div class="table-cell table-100"><?php echo esc_html( ucfirst( get_post_status( $product->data['id'] ) ) ); ?></div>
	<div class="table-cell"><strong><?php echo $product->data['manage_stock'] ? $product->data['stock'] : __( 'No handle stock', 'wpshop' ); ?></strong></div>
	<?php do_action( 'wps_listing_table_end', $product, $sync_status ); ?>

</div>
