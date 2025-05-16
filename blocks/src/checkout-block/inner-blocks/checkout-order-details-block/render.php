<?php

use wpshop\Cart_Session;

$cart = Cart_Session::g()->cart_contents;

$total_price_no_shipping = 0;
$total_price_ttc = 0;

if ( ! empty( $cart ) ) {
    foreach ( $cart as $key => $product ) {
        if ( ! empty( $product['price_ttc'] ) ) {
            $total_price_no_shipping += $product['price'] * $product['qty'];
            $total_price_ttc += $product['price_ttc'] * $product['qty'];
        }
    }
}
$tva_amount = $total_price_ttc - $total_price_no_shipping;
?>

<div class="wps-checkout-subtitle wps-checkout-subtitle-step-2"><?php echo esc_html( 'Your order', 'wpshop' ) ?></div>

<div <?php echo wp_kses_data( get_block_wrapper_attributes( [ 'class' => 'wps-cart-resume' ]) ); ?>>

    <?php if ( ! empty( $cart) ) : ?>
        <div class="wps-checkout-review-order wps-list-product">
            <?php foreach ( $cart as $key => $product ) : ?>
                <div itemscope itemtype="https://schema.org/Product" class="wps-product">
                    <figure class="wps-product-thumbnail">
                        <?php if ( ! empty( $product['thumbnail_id'] ) ) :
                            echo wp_get_attachment_image( $product['thumbnail_id'], 'thumbnail', '', array(
                                'class'    => 'attachment-wps-product-thumbnail',
                                'itemprop' => 'image',
                            ) );
                        else :
                            echo '<img src="' . PLUGIN_WPSHOP_URL . '/core/asset/image/default-product-thumbnail-min.jpg" class="attachment-wps-product-thumbnail" itemprop="image" /> ';
                        endif; ?>
                    </figure>
                    <div class="wps-product-content">
                        <div itemprop="name" class="wps-product-title"><?php echo esc_html( $product['title'] ); ?></div>
                        <div class="wps-product-footer">
                            <div class="wps-product-quantity"><?php echo esc_html( $product['qty'] ); ?></div>
                            <?php if ( ! empty( $product['price_ttc'] ) ) : ?>
                                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
                                    <span itemprop="price" content="<?php echo esc_html( number_format( $product['price_ttc'] * $product['qty'], 2, '.', '' ) ); ?>"><?php echo esc_html( number_format( $product['price_ttc'] * $product['qty'], 2, '.', '' ) ); ?></span>
                                    <span itemprop="priceCurrency" content="EUR"><?php echo esc_html( '€', 'wpshop' ); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <li class="wps-resume-line">
        <span class="wps-line-content"><?php echo esc_html( 'Subtotal', 'wpshop' ); ?></span>
        <span class="wps-line-value"><?php echo esc_html( number_format( $total_price_no_shipping, 2, '.', '' ) ); ?>€</span>
    </li>
    <li class="wps-resume-line">
        <span class="wps-line-content"><?php echo esc_html( 'Taxes', 'wpshop' ); ?></span>
        <span class="wps-line-value"><?php echo esc_html( number_format( $tva_amount, 2, '.', '' ) ); ?>€</span>
    </li>

    <li class="wps-resume-line featured">
        <span class="wps-line-content"><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></span>
        <span class="wps-line-value"><?php echo esc_html( number_format( $total_price_ttc, 2, '.', '' ) ); ?>€</span>
    </li>
</div>