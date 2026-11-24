<?php
/**
 * Checkout Acceptance Block Template.
 *
 * Container of the general conditions of sale checkbox, filled by view.js. Rendered
 * server side so the container exists whatever the source of the block (page content,
 * WPshop page template or shortcode).
 *
 * @package wpshop
 */

defined( 'ABSPATH' ) || exit;

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
</div>
