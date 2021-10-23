<?php
/**
 * Les fonctions utilitaires des prix.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Formatte les prix.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param  integer $amount Le prix.
 *
 * @return integer         Le prix formatté.
 */
function price2num( $amount ) {
	$dec      = ',';
	$thousand = ' ';

	if ( is_numeric( $amount ) ) {
		$temps   = sprintf( '%0.10F', $amount - intval( $amount ) );
		$temps   = preg_replace( '/([\.1-9])0+$/', '\\1', $temps );
		$nbofdec = max( 0, strlen( $temps ) - 2 );
		$amount  = number_format( $amount, $nbofdec, $dec, $thousand );
	}

	if ( ',' !== $thousand && '.' !== $thousand ) {
		$amount = str_replace( ',', '.', $amount );
	}

	$amount = str_replace( ' ', '', $amount );
	$amount = str_replace( $thousand, '', $amount );
	$amount = str_replace( $dec, '.', $amount );

	return $amount;
}
