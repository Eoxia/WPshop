<?php
namespace wpshop;

$product = Product::g()->get( array( 'id' => get_the_ID() ), true );
$cart    = Cart::g()->get_cart();
$qty     = 0;

if ($cart) {
    $cartProduct = array_filter($cart->data['products'], function($item) use ($product) {
        return $item['id'] == $product->data['id'];
    });

    if (!empty($cartProduct)) {
        foreach ($cartProduct as $cartProduct) {
            $qty += $cartProduct['qty'];
        }
    } 
}


?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-qty="<?php echo esc_html($qty); ?>" data-product-id="<?php echo esc_html($product->data['id']); ?>">
</div>