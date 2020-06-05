<?php
/**
 * Les fonctions principales des outils
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
 * Tools Class.
 */
class Tools extends \eoxia\Singleton_Util {
	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {}

	/**
	 * Appel la vue principal
	 *
	 * @since 2.0.
	 *
	 * @param  string $section La section actuelle.
	 */
	public function display_general( $section ) {
		\eoxia\View_Util::exec( 'wpshop', 'tools', 'general' );
	}
}

Tools::g();
