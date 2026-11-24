<?php
/**
 * Product Image Block Template.
 *
 * Inside a Query Loop (catalogue), the image links to the product.
 *
 * @package wpshop
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$product    = Product::g()->get( array( 'id' => $product_id ), true );
$in_loop    = isset( $block->context['queryId'] );

$thumbnail = wp_get_attachment_image_src( $product->data['thumbnail_id'], 'wps-product-thumbnail' );

if ( empty( $thumbnail ) ) {
	$thumbnail = home_url( 'wp-content/plugins/wpshop/core/asset/image/default-product-thumbnail.jpg' );
} else {
	$thumbnail = $thumbnail[0];
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<div class="wps-product-image">
		<div class="wps-product-image-container">
			<?php if ( $in_loop ) : ?>
				<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
			<?php endif; ?>
			<img src="<?php echo esc_url( $thumbnail ); ?>" class="attachment-wps-product-thumbnail" itemprop="image" alt="<?php echo esc_attr( get_the_title( $product_id ) ); ?>">
			<?php if ( $in_loop ) : ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
