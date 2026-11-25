<?php
/**
 * Shortcodes displaying the WPshop blocks, for classic themes and page builders.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2026 Eoxia <technique@eoxia.com>.
 * @since     2.7.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Display_Shortcode class.
 */
class Display_Shortcode extends Singleton_Util {

	/**
	 * Constructor.
	 */
	protected function construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Registers the shortcodes.
	 *
	 * [wps_account] was injected on the my account page but no longer existed since the
	 * account views were replaced by the my account block.
	 */
	public function register() {
		add_shortcode( 'wps_account', array( $this, 'callback_account' ) );
		add_shortcode( 'wps_connection', array( $this, 'callback_connection' ) );
		add_shortcode( 'wps_mini_cart', array( $this, 'callback_mini_cart' ) );
		add_shortcode( 'wps_account_link', array( $this, 'callback_account_link' ) );
	}

	/**
	 * [wps_account_link show_label="1"]: user icon linking to the account page (account link block).
	 *
	 * @param array|string $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function callback_account_link( $atts ) {
		if ( is_admin() ) {
			return '';
		}

		$atts = shortcode_atts( array( 'show_label' => '0' ), $atts, 'wps_account_link' );

		return do_blocks( '<!-- wp:wpshop/account-link ' . wp_json_encode( array( 'showLabel' => rest_sanitize_boolean( $atts['show_label'] ) ) ) . ' /-->' );
	}

	/**
	 * [wps_mini_cart show_total="1" open_on_add="1" side="right"]: header cart icon and side panel
	 * (mini cart block), for classic theme headers, widgets and menus.
	 *
	 * @param array|string $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function callback_mini_cart( $atts ) {
		if ( is_admin() ) {
			return '';
		}

		$atts = shortcode_atts( array(
			'show_total'  => '0',
			'open_on_add' => '1',
			'side'        => 'right',
		), $atts, 'wps_mini_cart' );

		$attributes = array(
			'showTotal' => rest_sanitize_boolean( $atts['show_total'] ),
			'openOnAdd' => rest_sanitize_boolean( $atts['open_on_add'] ),
			'side'      => 'left' === $atts['side'] ? 'left' : 'right',
		);

		return do_blocks( '<!-- wp:wpshop/mini-cart ' . wp_json_encode( $attributes ) . ' /-->' );
	}

	/**
	 * [wps_account]: customer account (my account block).
	 *
	 * @return string
	 */
	public function callback_account() {
		return is_admin() ? '' : Display_Mode::g()->render_page_blocks( 'my_account_id' );
	}

	/**
	 * [wps_connection]: login and registration forms (connection block).
	 *
	 * @return string
	 */
	public function callback_connection() {
		return is_admin() ? '' : Display_Mode::g()->render_page_blocks( 'connection_id' );
	}
}

Display_Shortcode::g();
