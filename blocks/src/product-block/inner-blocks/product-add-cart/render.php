<?php
namespace wpshop;

$product = Product::g()->get( array( 'id' => get_the_ID() ), true );

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-product-id="<?php echo esc_html($product->data['id']); ?>">
</div>