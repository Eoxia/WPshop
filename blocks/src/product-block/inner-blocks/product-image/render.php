<?php

namespace wpshop;

$product = Product::g()->get( array( 'id' => get_the_ID() ), true );

$thumbnail = wp_get_attachment_image_src( $product->data['thumbnail_id'], 'wps-product-thumbnail' );

if (empty( $thumbnail )) {
    $thumbnail = home_url( 'wp-content/plugins/wpshop/core/asset/image/default-product-thumbnail.jpg' );
} else {
    $thumbnail = $thumbnail[0];
}

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
    <div class="wps-product-image">
        <div class="wps-product-image-container">
            <img src="<?php echo esc_html($thumbnail) ?>" class="attachment-wps-product-thumbnail" itemprop="image">
        </div>
    </div>
</div>