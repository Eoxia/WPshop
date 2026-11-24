<?php
/**
 * Product Price Block Template.
 *
 * Title tag on the single product, paragraph inside a Query Loop (catalogue).
 *
 * @package wpshop
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$product    = Product::g()->get( array( 'id' => $product_id ), true );
$tag        = isset( $block->context['queryId'] ) ? 'p' : 'h2';

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<div class="wps-product-price">
		<<?php echo $tag; ?> class="wps-product-price-ttc">
			<?php echo ! empty( $product->data['price_ttc'] ) ? esc_html( format_price( $product->data['price_ttc'], 'TTC' ) ) : ''; ?>
		</<?php echo $tag; ?>>
	</div>
</div>
