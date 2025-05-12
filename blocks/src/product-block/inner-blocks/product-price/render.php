<?php

namespace wpshop;

$product = Product::g()->get( array( 'id' => get_the_ID() ), true );

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <div class="wps-product-price">
        <h2 class="wps-product-price-ttc">
            <?php echo ! empty( $product->data['price_ttc'] ) ? esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ) . ' € TTC' : ''; ?>
        </h2>
    </div>
</div>