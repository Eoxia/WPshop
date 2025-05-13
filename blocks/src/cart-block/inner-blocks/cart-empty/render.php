<?php
/**
 * Cart Empty Block Template
 *
 * @package wpshop
 */

 defined( 'ABSPATH' ) || exit;

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <?php echo $content; ?>
</div>