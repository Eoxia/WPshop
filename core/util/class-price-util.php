<?php
/**
 * Prix utils.
 *
 * @package WPshop
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Formattes les prix.
 *
 * @since 2.0.0
 *
 * @param  integer $amount      Le prix.
 * @return integer              Le prix formatté.
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
