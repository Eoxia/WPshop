<?php
/**
 * Les filtres du tableau de bord.
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
 * Dashboard Filter class.
 */
class Dashboard_Filter {

	/**
	 * Init filter
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		// add_filter( 'wps_dashboard_metaboxes', array( $this, 'add_sync_metabox' ) );.
	}

	/**
	 * Metabox pour synchroniser tous les éléments de Dolibarr.
	 *
	 * @since 2.0.0
	 *
	 * @param array $metaboxes La définition des metaboxes.
	 *
	 * @return array           La définition des metaboxes avec la définition
	 * de la metaboxe de sync.
	 */
	public function add_sync_metabox( $metaboxes ) {
		if ( Settings::g()->dolibarr_is_active() ) {
			$array = array(
				'wps-dashboard-sync' => array(
					'callback' => array( $this, 'metabox_sync' ),
				),
			);

			$metaboxes = $array + $metaboxes;
		}

		return $metaboxes;
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_sync() {
		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-sync' );
	}

}

new Dashboard_Filter();
