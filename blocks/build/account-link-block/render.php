<?php
/**
 * Account Link Block Template: user icon linking to the customer account page.
 *
 * Same markup for guests and customers (cache-safe): guests are sent to the connection
 * page by the my account page itself.
 *
 * @package wpshop
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

$account_url = Pages::g()->get_account_link();

if ( empty( $account_url ) ) {
	return;
}

$show_label = ! empty( $attributes['showLabel'] );

?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<a class="wps-account-link__link" href="<?php echo esc_url( $account_url ); ?>"<?php echo $show_label ? '' : ' aria-label="' . esc_attr__( 'My account', 'wpshop' ) . '"'; ?>>
		<svg class="wps-account-link__icon" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
			<path fill="currentColor" d="M12 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm0 10c4.42 0 8 2.24 8 5v3H4v-3c0-2.76 3.58-5 8-5Zm0 2c-3.47 0-6 1.69-6 3v1h12v-1c0-1.31-2.53-3-6-3Z"/>
		</svg>
		<?php if ( $show_label ) : ?>
			<span class="wps-account-link__label"><?php esc_html_e( 'My account', 'wpshop' ); ?></span>
		<?php endif; ?>
	</a>
</div>
