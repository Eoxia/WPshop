<?php
/**
 * La vue affichant la liste des produits dans la boutique.
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
 * @var array   $products Le tableau contenant toutes les données des produits.
 * @var Product $product  Les données d'un produit.
 */
?>

<?php if ( ! empty( $products ) ) : ?>
	<div class="wpeo-gridlayout grid-4">
	<?php foreach ( $products as $product ) : ?>
		<div class="wps-product">
			<a href="<?php echo get_permalink( $product->data['id'] ); ?>">
				<figure class="wps-product-thumbnail">
					<?php echo wp_get_attachment_image( $product->data['thumbnail_id'] ); ?>
				</figure>
				<div class="wps-product-content">
					<div class="wps-product-title"><?php echo $product->data['title']; ?></div>
					<div class="wpeo-gridlayout grid-2">
						<div class="wps-product-price"><?php echo ! empty( $product->data['price_ttc'] ) ? esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ) . ' €' : ''; ?></div>
						<div class="wps-product-buy wpeo-button button-square-40 action-attribute"
							data-action="add_to_cart"
							data-nonce="<?php echo wp_create_nonce( 'add_to_cart' ); ?>"
							data-id="<?php echo esc_attr( $product->data['id'] ); ?>"><i class="button-icon fas fa-cart-arrow-down"></i></div>
					</div>
				</div>
			</a>
		</div>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
