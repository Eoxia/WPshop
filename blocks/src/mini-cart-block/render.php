<?php
/**
 * Mini Cart Block Template.
 *
 * Only the toggle button is printed: the item count and the panel content are loaded
 * by view.js from the cart REST API, so pages stay safe to cache.
 *
 * @package wpshop
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( ! Cart::g()->can_add_product() ) {
	return;
}

$page_ids = get_option( 'wps_page_ids', Pages::g()->default_options );
$shop_url = ! empty( $page_ids['shop_id'] ) ? get_permalink( $page_ids['shop_id'] ) : get_post_type_archive_link( 'wps-product' );

$config = array(
	'showTotal'   => ! empty( $attributes['showTotal'] ),
	'openOnAdd'   => ! isset( $attributes['openOnAdd'] ) || ! empty( $attributes['openOnAdd'] ),
	'side'        => isset( $attributes['side'] ) && 'left' === $attributes['side'] ? 'left' : 'right',
	'cartUrl'     => Pages::g()->get_cart_link(),
	'checkoutUrl' => Pages::g()->get_checkout_link(),
	'shopUrl'     => $shop_url ? $shop_url : home_url( '/' ),
);

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?> data-wps-mini-cart="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
	<button type="button" class="wps-mini-cart__toggle" aria-haspopup="dialog" aria-label="<?php esc_attr_e( 'Cart', 'wpshop' ); ?>">
		<svg class="wps-mini-cart__icon" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
			<path fill="currentColor" d="M7 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM1 2h3.3l.9 2H21a1 1 0 0 1 .96 1.27l-2.4 8.4A2 2 0 0 1 17.64 15H8.1a2 2 0 0 1-1.94-1.52L3.6 4H1V2Zm5.1 4 2 7h9.54l2-7H6.1Z"/>
		</svg>
		<span class="wps-mini-cart__count" hidden>0</span>
		<?php if ( $config['showTotal'] ) : ?>
			<span class="wps-mini-cart__total"></span>
		<?php endif; ?>
	</button>
</div>
