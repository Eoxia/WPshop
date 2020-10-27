<?php
/**
 * La classe gérant les filtres des réglages.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.3.0
 * @version   2.3.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Settings Action Class.
 */
class Settings_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'admin_body_class', array( $this, 'wps_add_dolibarr_class_to_body' ) );
	}

	/**
	 * Ajoute la classe wps-dolibarr-active dans le body si Dolibarr est connecté à WPshop.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @param  array $classes Les classes du body.
	 *
	 * @return array $classes Les classes du body.
	 */
	public function wps_add_dolibarr_class_to_body( $classes ) {

		if ( Settings::g()->dolibarr_is_active() ) {
			$classes .= ' wps-dolibarr-active ';
		}

		return $classes;
	}

}

new Settings_Filter();
