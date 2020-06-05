<?php
/**
 * Fixes des dates de EOFramework.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
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
 * Date Util Class.
 */
class Date_util extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 0.2.0
	 */
	protected function construct() {}

	/**
	 * Fixe Dolibarr Date and Display it.
	 * This method should be not exist.
	 *
	 * @todo: Expliquer pourquoi ce FIX.
	 * class-doli-order L126
	 *
	 * @since  2.0.0
	 *
	 * @param string $module   Le nom du module.
	 * @param string $template Le nom du template.
	 *
	 * @return string          Le chemin du template
	 */
	public static function readable_date( $mysql_date, $output = 'date_time' ) {
		if ( empty( $mysql_date ) ) {
			return '-';
		}

		$mysql_date = \eoxia\Date_Util::g()->fill_date( $mysql_date );

		if ( isset( $mysql_date[ $output ] ) ) {
			return $mysql_date[ $output ];
		}

		return '-';
	}
}
