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
