<?php
/**
 * La vue affichant la page d'un produit.
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
 * @var Product  $product Les données d'un produit.
 * @var \WP_Post $post    Les données d'un post.
 */
?>

<div class="wps-product-content">
	<div class="wps-product-price"><?php echo ! empty( $product->data['price_ht'] ) ? esc_html( number_format( $product->data['price_ht'], 2, ',', '' ) ) . ' € HT' : ''; ?></div>
	<div class="wps-product-price"><?php echo ! empty( $product->data['price_ttc'] ) ? esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ) . ' € TTC' : ''; ?></div>
	<div class="wps-product-description"><?php echo apply_filters( 'wps_product_single', $post->post_content, $product ); ?></div>

	<div class="wps-product-quantity">
		<input type="hidden" class="base-price" value="<?php echo ! empty( $product->data['price_ttc'] ) ? esc_html( number_format( $product->data['price_ttc'], 2, '.', '' ) ) : ''; ?>" />
		<span class="wps-quantity-minus fas fa-minus-circle"></span>
		<span class="qty">1</span>
		<span class="wps-quantity-plus fas fa-plus-circle"></span>
	</div>

	<div class="wps-product-action">
	<?php if ( Cart::g()->can_add_product() ) : ?>
		<div class="wps-product-buy wpeo-button action-attribute <?php echo apply_filters( 'wps_product_add_to_cart_class', '', $product ); ?>"
			<?php echo apply_filters( 'wps_product_add_to_cart_attr', '', $product ); ?>
			data-action="add_to_cart"
			data-qty="1"
			data-nonce="<?php echo wp_create_nonce( 'add_to_cart' ); ?>"
			data-id="<?php echo esc_attr( the_ID() ); ?>"><?php esc_html_e( 'Add to cart', 'wpshop' ); ?></div>
	<?php endif; ?>
	</div>
</div>

<?php if ( ! empty( $product->data['similar_products_id'] ) ) : ?>
	<h2><?php esc_html_e( 'Similar products', 'wpshop' ); ?></h2>

	<?php
	do_shortcode( '[wps_product ids=' . implode( $product->data['similar_products_id'], ',' ) . ']');
endif; ?>
