<?php
/**
 * Add To Cart Button Block Template (one click, for product catalogues).
 *
 * @package wpshop
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();

if ( ! $product_id || Product::g()->get_type() !== get_post_type( $product_id ) || ! Cart::g()->can_add_product() ) {
	return;
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<button type="button" class="wpeo-button wps-button-add-to-cart"
		data-product-id="<?php echo esc_attr( $product_id ); ?>"
		data-product-title="<?php echo esc_attr( get_the_title( $product_id ) ); ?>"
		data-cart-url="<?php echo esc_url( Pages::g()->get_cart_link() ); ?>">
		<i class="wps-button icon fas fa-shopping-cart"></i>
		<span class="wps-button-text"><?php esc_html_e( 'Add to cart', 'wpshop' ); ?></span>
	</button>
</div>
