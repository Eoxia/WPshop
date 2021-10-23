<?php
/**
 * La classe gérant les fonctions principales des outils.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Tools Class.
 */
class Tools extends Singleton_Util {
	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Appel la vue principal.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section actuelle.
	 */
	public function display_general( $section ) {
		View_Util::exec( 'wpshop', 'tools', 'general' );
	}
}

Tools::g();
