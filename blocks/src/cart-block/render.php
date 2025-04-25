<?php

/**
 * Cart Block Template
 *
 * @package wpshop
 */

use wpshop\Cart_Session;
$cart = Cart_Session::g()->cart_contents;

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <?php
        if (!empty($cart)) {
            echo render_block($block->parsed_block['innerBlocks'][0]);
        } else {
            echo render_block($block->parsed_block['innerBlocks'][1]);
        }
    ?>
</div>