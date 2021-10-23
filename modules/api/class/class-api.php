<?php
/**
 * La classe gérant les fonctions principales de l'API.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * API Class.
 */
class API extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Génère un token sur 20 caractères.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le token généré.
	 */
	public function generate_token() {
		$length            = 20;
		$characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_][-&';
		$characters_length = strlen( $characters );

		$token = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$token .= $characters[ rand( 0, $characters_length - 1) ];
		}

		return $token;
	}

	/**
	 * Récupère un utilisateur selon le token.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $token   Le token.
	 *
	 * @return User_Model|null Les données de l'utilisateur ou NULL si aucun utilisateur correspond au token.
	 */
	public function get_user_by_token( $token ) {
		$users = get_users( array(
			'meta_key'   => '_wpshop_api_key',
			'meta_value' => $token,
			'number'     => 1,
		) );

		if ( empty( $users ) ) {
			return null;
		}

		if ( ! empty( $users ) && 1 === count( $users ) ) {
			$token_base = get_user_meta( $users[0]->ID, '_wpshop_api_key', true );

			if ( $token_base !== $token ) {
				return false;
			}

			return $users[0];
		}

		return null;
	}
}

new API();
