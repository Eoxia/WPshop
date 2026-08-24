<?php
/**
 * La classe gérant les fonctions utilitaires des requêtes.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
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
				$body_str = wp_remote_retrieve_body($request);
				$is_assoc = ( strpos( $end_point, 'documents?modulepart=product' ) !== false );
				$decoded  = json_decode( $body_str, $is_assoc );
				
				if ( json_last_error() !== JSON_ERROR_NONE ) {
					// Si l'ERP renvoie de l'HTML (ex: page de connexion) au lieu du JSON
					set_transient( 'wps_request_error', __( 'The ERP returned an invalid format (non-JSON). Make sure the URL points exactly to Dolibarr.', 'wpshop' ), 60 );
					return false;
				}
				return $decoded;
			} else {
				$body = json_decode( wp_remote_retrieve_body($request), true );
				$error_msg = $body['error']['message'] ?? 'HTTP ' . $request['response']['code'];
				if ( 401 === $request['response']['code'] || 403 === $request['response']['code'] ) {
					$error_msg = __( 'Invalid API Key or unauthorized access.', 'wpshop' );
				} elseif ( 404 === $request['response']['code'] ) {
					$error_msg = __( 'REST API route not found. Make sure the DoliWPshop module is activated in Dolibarr.', 'wpshop' );
				}
				set_transient( 'wps_request_error', $error_msg, 60 );
			}
		} else {
			set_transient( 'wps_request_error', $request->get_error_message(), 60 );
		}

		return false;
	}

	/**
	 * Test the ERP connection and return detailed checklist.
	 * 
	 * @param string $url The Dolibarr URL
	 * @param string $key The API key
	 * @return array
	 */
	public static function test_erp_connection( $url, $key ) {
		if ( substr( trim( $url ), -1 ) === '/' ) {
			$url = substr( trim( $url ), 0, -1 );
		}
		
		$api_url = $url . '/api/index.php/doliwpshop/checkPermissions';
		$request = wp_remote_get( $api_url, array(
			'headers' => array(
				'Content-type' => 'application/json',
				'DOLAPIKEY'    => $key,
			),
		) );

		$checklist = array(
			'url' => '✅',
			'api' => '✅',
			'key' => '✅',
			'msg' => ''
		);

		$statut = false;
		$user_info = null;

		if ( is_wp_error( $request ) ) {
			$checklist['url'] = '❌';
			$checklist['api'] = '⏳';
			$checklist['key'] = '⏳';
			$checklist['msg'] = $request->get_error_message();
		} else {
			$code = wp_remote_retrieve_response_code( $request );
			$body = wp_remote_retrieve_body( $request );
			$json = json_decode( $body );

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				$checklist['url'] = '✅';
				$checklist['api'] = '❌';
				$checklist['key'] = '⏳';
				$checklist['msg'] = __( 'The ERP returned an invalid format (non-JSON). Make sure the URL points exactly to Dolibarr.', 'wpshop' );
			} elseif ( 404 === $code ) {
				$checklist['url'] = '✅';
				$checklist['api'] = '❌';
				$checklist['key'] = '⏳';
				$checklist['msg'] = __( 'REST API route not found. Make sure the DoliWPshop module is activated in Dolibarr.', 'wpshop' );
			} elseif ( 401 === $code || 403 === $code ) {
				$checklist['url'] = '✅';
				$checklist['api'] = '✅';
				$checklist['key'] = '❌';
				$checklist['msg'] = __( 'Invalid API Key or unauthorized access.', 'wpshop' );
			} elseif ( 200 === $code && ! empty($json->success) && 200 === $json->success->code ) {
				$statut = true;
				if ( ! empty( $json->success->user ) ) {
					$user_info = $json->success->user;
				}
			} else {
				$checklist['url'] = '✅';
				$checklist['api'] = '❌';
				$checklist['key'] = '⏳';
				$error_msg = 'HTTP ' . $code;
				if ( ! empty( $json->error->message ) ) {
					$error_msg .= ' : ' . $json->error->message;
				}
				$checklist['msg'] = $error_msg;
			}
		}

		$detailed_error = '';
		if ( ! $statut ) {
			$detailed_error  = "- URL Dolibarr : " . $checklist['url'] . "\n";
			$detailed_error .= "- Module DoliWPShop (REST) : " . $checklist['api'] . "\n";
			$detailed_error .= "- Clé API : " . $checklist['key'] . "\n\n";
			$detailed_error .= $checklist['msg'];
		}

		return array(
			'statut' => $statut,
			'detailed_error' => $detailed_error,
			'user' => $user_info
		);
	}
}

new Request_Util();
