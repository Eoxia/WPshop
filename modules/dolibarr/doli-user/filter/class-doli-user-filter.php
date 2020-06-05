<?php
/**
 * Les filtres des contact de dolibarr.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019-2020 Eoxia <dev@eoxia.com>.
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
 * Contact Filter class.
 */
class Doli_User_Filter {

	/**
	 * Initialise les filtres.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'check_user' ) );
	}

	public function check_user() {
		if ( Settings::g()->dolibarr_is_active() ) {
			$response = Doli_User::g()->check_connected_to_erp();

			if ( ! $response['status'] ) {
				$link = Template_Util::get_template_part( 'dolibarr/doli-user', 'user-alert' );
				include $link;
			}
		}
	}
}

new Doli_User_Filter();
