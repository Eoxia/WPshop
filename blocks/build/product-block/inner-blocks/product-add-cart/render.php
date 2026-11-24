<?php
namespace wpshop;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$product    = Product::g()->get( array( 'id' => $product_id ), true );
$cartUrl = Pages::g()->get_cart_link();

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-product-id="<?php echo esc_html($product->data['id']); ?>" data-cart-url="<?php echo esc_html($cartUrl); ?>">
</div>