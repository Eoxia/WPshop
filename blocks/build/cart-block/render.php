<?php

/**
 * Cart Block Template
 *
 * @package wpshop
 */

use wpshop\Cart;
use wpshop\Cart_Session;

// Products deleted from Dolibarr since they were added: removed, and named in a message
// (also the ones removed on another page, e.g. by the checkout).
Cart::g()->remove_unavailable_products();
$unavailable_message = Cart::g()->take_unavailable_message();

$cart = Cart_Session::g()->cart_contents;

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <?php if ( $unavailable_message ) : ?>
        <p class="wps-product-unavailable wps-cart-notice" role="alert"><?php echo esc_html( $unavailable_message ); ?></p>
    <?php endif; ?>
    <?php
        if (!empty($cart)) {
            echo render_block($block->parsed_block['innerBlocks'][0]);
        } else {
            echo render_block($block->parsed_block['innerBlocks'][1]);
        }
    ?>
</div>
