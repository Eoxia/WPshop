<?php

/**
 * Cart Checkout Button Block Template
 *
 * @package wpshop
 */

 defined( 'ABSPATH' ) || exit;

require_once PLUGIN_WPSHOP_PATH . 'lib/request.lib.php';

$link_checkout = request_get( 'wp-shop/v1/cart/checkout/link' );

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <a href="<?php echo esc_url( $link_checkout['link'] ); ?>" class="wpeo-button alignright wps-process-checkout-button">
        <?php echo apply_filters( 'wps_cart_to_checkout_link_title', __( 'Proceed to checkout', 'wpshop' ) ); ?>
    </a>
</div>