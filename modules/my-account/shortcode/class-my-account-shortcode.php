<?php
/**
 * Gestion des shortcodes pour la page "Mon compte"
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * My Account Shortcode Class.
 */
class My_Account_Shortcode extends \eoxia\Singleton_Util {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {}

	/**
	 * Initialise le shortcode.
	 *
	 * @since 2.0.0
	 */
	public function init_shortcode() {
		add_shortcode( 'wps_account', array( $this, 'callback_account' ) );
	}

	/**
	 * Appel la vue "my-account" si l'utilisateur est connecté. Sinon
	 * appel la vue form-login.
	 *
	 * @since 2.0.0
	 */
	public function callback_account() {
		if ( ! is_admin() ) {
			if ( ! is_user_logged_in() ) {
				global $wp;
				if ( ! array_key_exists( 'lost-password', $wp->query_vars ) ) {
					My_Account::g()->display_form_login();
				} else {
					My_Account::g()->display_lost_password();
				}
			} else {
				global $wp;

				$tab = Settings::g()->dolibarr_is_active() ? 'orders' : 'details';

				if ( $tab == 'quotations' ) {
					$tab = Settings::g()->use_quotation() ? 'quotations' : 'details';
				}

				if ( array_key_exists( 'orders', $wp->query_vars ) ) {
					$tab = 'orders';
				}

				if ( array_key_exists( 'details', $wp->query_vars ) ) {
					$tab = 'details';
				}

				if ( array_key_exists( 'invoices', $wp->query_vars ) ) {
					$tab = 'invoices';
				}

				if ( array_key_exists( 'download', $wp->query_vars ) ) {
					$tab = 'download';
				}

				if ( array_key_exists( 'quotations', $wp->query_vars ) ) {
					$tab = 'quotations';
				}

				if ( array_key_exists( 'dolibarr-quotations', $wp->query_vars ) ) {
					$tab = 'dolibarr_quotations';
				}

				if ( array_key_exists( 'support', $wp->query_vars ) ) {
					$tab = 'support';
				}

				$tab = apply_filters( 'wps_navigation_shortcode', $tab, $wp->query_vars );

				include( Template_Util::get_template_part( 'my-account', 'my-account' ) );
			}
		}
	}
}

My_Account_Shortcode::g();
