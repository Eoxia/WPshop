<?php

namespace wpshop;

$product = Product::g()->get( array( 'id' => get_the_ID() ), true );

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <div class="wps-product-title">
        <h1 class="wps-product-title-name"><?php echo esc_html( $product->data['title'] ); ?></h1>
    </div>
</div>