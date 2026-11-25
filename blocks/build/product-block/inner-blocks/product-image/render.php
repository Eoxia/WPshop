<?php
/**
 * Product Image Block Template.
 *
 * The image links to the product ("isLink" attribute, enabled by default), except on the
 * page of the product itself.
 *
 * @package wpshop
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

$product_id = ! empty( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$product    = Product::g()->get( array( 'id' => $product_id ), true );
$is_current = is_singular() && (int) get_queried_object_id() === (int) $product_id;
$with_link  = ( ! isset( $attributes['isLink'] ) || $attributes['isLink'] ) && ! $is_current;

// Thumbnail size in a loop (catalogue), larger image on the single product.
$image_size = isset( $block->context['queryId'] ) ? 'wps-product-thumbnail' : 'large';
$thumbnail  = wp_get_attachment_image_src( $product->data['thumbnail_id'], $image_size );

if ( empty( $thumbnail ) ) {
	$thumbnail = home_url( 'wp-content/plugins/wpshop/core/asset/image/default-product-thumbnail.jpg' );
} else {
	$thumbnail = $thumbnail[0];
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<div class="wps-product-image">
		<div class="wps-product-image-container">
			<?php if ( $with_link ) : ?>
				<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
			<?php endif; ?>
			<img src="<?php echo esc_url( $thumbnail ); ?>" class="attachment-wps-product-thumbnail" itemprop="image" alt="<?php echo esc_attr( get_the_title( $product_id ) ); ?>">
			<?php if ( $with_link ) : ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
