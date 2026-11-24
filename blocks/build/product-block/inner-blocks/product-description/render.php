<?php

namespace wpshop;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$product    = Product::g()->get( array( 'id' => $product_id ), true );

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <div class="wps-product-description">
        <?php echo $product->data['content'] ?>
    </div>
</div>