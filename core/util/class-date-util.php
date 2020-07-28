<?php
/**
 * La classe gérant les fonctions utilitaires des dates de EOFramework.
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
 * Date Util Class.
 */
class Date_util extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Fixe Dolibarr Date and Display it.
	 * This method should be not exist.
	 *
	 * @todo: Expliquer pourquoi ce FIX.
	 * class-doli-order L126
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $mysql_date La date au format mysql.
	 * @param string $output     Le type de date.
	 *
	 * @return string            La date au format lecture humaine
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
