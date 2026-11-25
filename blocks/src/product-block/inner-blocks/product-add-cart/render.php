<?php
namespace wpshop;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$product    = Product::g()->get( array( 'id' => $product_id ), true );
$cartUrl = Pages::g()->get_cart_link();

// Deleted from Dolibarr (last check): the product page stays, without the add to cart form.
if ( Doli_Products::g()->is_missing( $product_id ) ) : ?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-unavailable="1">
	<p class="wps-product-unavailable"><?php esc_html_e( 'This product is no longer available.', 'wpshop' ); ?></p>
</div>

<?php return; endif; ?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-product-id="<?php echo esc_html($product->data['id']); ?>" data-cart-url="<?php echo esc_html($cartUrl); ?>">
</div>
