<?php
/**
 * La classe gérant les fonctions principales de WPshop.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * WPshop Class.
 */
class WPshop extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * La méthode appelée à l'activation du plugin.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function default_install() {
		$db_version = get_option( 'wpshop2_database_version', false );

		if ( ! $db_version ) {
			Pages::g()->create_default_page();

			update_option( 'wpshop2_database_version', '1.0.0' );
		}
	}
}

WPshop::g();
