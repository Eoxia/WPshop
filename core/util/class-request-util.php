<?php
/**
 * La classe gérant les fonctions utilitaires des requêtes.
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
 * Request Util Class.
 */
class Request_Util extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Requête POST.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $end_point L'url a appeler.
	 * @param  array  $data      Les données du formulaire.
	 * @param  string $method    Le type de la méthode.
	 *
	 * @return mixed             Retournes les données de la requête ou false.
	 */
	public static function post( $end_point, $data = array(), $method = 'POST' ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url = $dolibarr_option['dolibarr_url'];

		if ( substr( trim( $dolibarr_url ), strlen( $dolibarr_url ) - 1, 1 ) === '/' ) {
			$dolibarr_url = substr( trim( $dolibarr_url ), 0, strlen( $dolibarr_url ) - 1 );
		}

		$api_url = $dolibarr_url . '/api/index.php/' . $end_point;

		$request = wp_remote_post( $api_url, array(
			'method'    => $method,
			'blocking'  => true,
			'headers'   => array(
				'Content-type' => 'application/json',
				'DOLAPIKEY'    => $dolibarr_option['dolibarr_secret'],
			),
			//@todo: Grave selon moi.
			'sslverify' => false,
			'body'      => json_encode( $data ),
		) );

		if ( ! is_wp_error( $request ) ) {
			return json_decode( $request['body'] );
		}

		return false;
	}

	/**
	 * Requête PUT.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $end_point L'url a appeler.
	 * @param  array  $data      Les données du formulaire.
	 *
	 * @return array|boolean     Retournes les données de la requête ou false.
	 */
	public static function put( $end_point, $data ) {
		return Request_Util::post( $end_point, $data, 'PUT' );
	}

	/**
	 * Requête GET.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $end_point L'url a appeler.
	 *
	 * @return array|boolean    Retournes les données de la requête ou false.
	 */
	public static function get( $end_point ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url = $dolibarr_option['dolibarr_url'];

		if ( substr( trim( $dolibarr_url ), strlen( $dolibarr_url ) - 1, 1 ) === '/' ) {
			$dolibarr_url = substr( trim( $dolibarr_url ), 0, strlen( $dolibarr_url ) - 1 );
		}

		$api_url = $dolibarr_url . '/api/index.php/' . $end_point;

		$request = wp_remote_get( $api_url, array(
			'headers' => array(
				'Content-type' => 'application/json',
				'DOLAPIKEY'    => $dolibarr_option['dolibarr_secret'],
			),
		) );

		if ( ! is_wp_error( $request ) ) {
			if ( 200 === $request['response']['code'] ) {
				return json_decode( $request['body'] );
			}
		}

		return false;
	}
}

new Request_Util();
