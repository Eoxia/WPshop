<?php
/**
 * La classe gérant les fonctions principales des utilisateurs de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\Singleton_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli User Class.
 */
class Doli_User extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Synchronise les contact de dolibarr vers WP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  stdClass $doli_contact Les données d'un utilisateur Dolibarr.
	 * @param  User     $wp_contact   Les données d'un utilisateur WordPress.
	 * @param  boolean  $save         True pour enregister le contact en base de donnée. Sinon false pour seulement récupérer un objet remplis.
	 *
	 * @return User|boolean           Les données d'un utilisateur WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_contact, $wp_contact, $save = true ) {
		$wp_third_party = null;

		if ( ! empty( $doli_contact->socid ) ) {
			$wp_third_party = Third_Party::g()->get( array(
				'meta_key'   => '_external_id',
				'meta_value' => (int) $doli_contact->socid,
			), true );
		}

		$wp_contact->data['external_id']  = (int) $doli_contact->id;
		$wp_contact->data['login']        = sanitize_title( $doli_contact->email );
		$wp_contact->data['firstname']    = $doli_contact->firstname;
		$wp_contact->data['lastname']     = $doli_contact->lastname;
		$wp_contact->data['displayname']  = $doli_contact->lastname;
		$wp_contact->data['phone']        = $doli_contact->phone_pro;
		$wp_contact->data['phone_mobile'] = $doli_contact->phone_mobile;
		$wp_contact->data['email']        = $doli_contact->email;

		if ( ! empty( $wp_third_party ) ) {
			$wp_contact->data['third_party_id'] = $wp_third_party->data['id'];
		}

		if ( $save ) {
			// @todo: Vérifier cette condition.
			if ( 0 === $wp_contact->data['id'] && false !== email_exists( $wp_contact->data['email'] ) ) {
				LOG_Util::log( sprintf( 'Contact: doli_to_wp can\'t create %s email already exist', json_encode( $wp_contact->data ) ), 'wpshop2' );
				return false;
			}

			if ( empty( $wp_contact->data['id'] ) ) {
				$wp_contact->data['password'] = wp_generate_password();
			}

			$contact_saved = Contact::g()->update( $wp_contact->data );

			if ( is_wp_error( $contact_saved ) ) {
				// translators: Contact: doli_to_wp error when update or create contact {json_data}.
				LOG_Util::log( sprintf( 'Contact: doli_to_wp error when update or create contact: %s', json_encode( $contact_saved ) ), 'wpshop2' );
				return false;
			}

			$data_sha = array();

			$data_sha['doli_id']      = (int) $doli_contact->id;
			$data_sha['wp_id']        = (int) $wp_contact->data['id'];
			$data_sha['lastname']     = $wp_contact->data['lastname'];
			$data_sha['phone']        = $wp_contact->data['phone'];
			$data_sha['phone_mobile'] = $wp_contact->data['phone_mobile'];
			$data_sha['email']        = $wp_contact->data['email'];

			update_user_meta( $wp_contact->data['id'], '_sync_sha_256', hash( 'sha256', implode( ',', $data_sha ) ) );

			if ( ! empty( $wp_third_party ) ) {
				$wp_third_party->data['contact_ids'][] = $contact_saved->data['id'];
				Third_Party::g()->update( $wp_third_party->data );
			}
		}

		return $wp_contact;
	}

	/**
	 * Synchronise WP vers Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  User $wp_contact Les données d'un utilisateur.
	 *
	 * @return User             Les données d'un utilisateur.
	 */
	public function wp_to_doli( $wp_contact ) {
		/*$third_party = Third_Party::g()->get( array(
			'id' => $wp_contact->data['third_party_id'],
		), true );

		$data = array(
			'lastname'  => $wp_contact->data['lastname'],
			'firstname' => $wp_contact->data['firstname'],
			'email'     => $wp_contact->data['email'],
			'phone_pro' => $wp_contact->data['phone'],
			'socid'     => $third_party->data['external_id'],
		);

		if ( ! empty( $wp_contact->data['external_id'] ) ) {
			//$contact = Request_Util::put( 'contact/' . $wp_contact->data['external_id'], $data );
		} else {
			//$contact_id                      = Request_Util::post( 'contact', $data );
			$wp_contact->data['external_id'] = (int) $contact_id;

			User::g()->update( $wp_contact->data );

		}*/

		return $wp_contact->data['external_id'];
	}

	/**
	 * Récupère l'id de WordPress depuis l'id de Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  integer $doli_id L'id d'un utilisateur de dolibarr.
	 *
	 * @return integer          L'id d'un utilisateur de WordPress.
	 */
	public function get_wp_id_by_doli_id( $doli_id ) {
		$users = get_users( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $doli_id,
		) );

		$user = isset ( $users[0] ) ? $users[0] : null;

		if ( ! $user ) {
			return null;
		}

		return $user->ID;
	}

	/**
	 * Vérifie si l'utilisateur est connecté à un ERP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return array          Les données de connexion d'un utilisateur à l'ERP.
	 */
	public function check_connected_to_erp() {
		if ( ! is_user_logged_in() ) {
			return array(
				'status' => true,
				'status_code' => '0x0',
				'status_message' => 'User not log',
			);
		}

		$contact = User::g()->get( array( 'id' => get_current_user_id() ), true );

		if ( ! $contact ) {
			return array(
				'status' => false,
				'status_code' => '0x5',
				'status_message' => 'User not found',
			);
		}

		if ( empty( $contact->data['third_party_id'] ) ) {
			return array(
				'status' => false,
				'status_code' => '0x8',
				'status_message' => 'User not connected to third party',
			);
		}

		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		if ( ! $third_party ) {
			return array(
				'status' => false,
				'status_code' => '0x9',
				'status_message' => 'Third Party not found',
			);
		}

		if ( empty( $third_party->data['external_id'] ) ) {
			return array(
				'status' => false,
				'status_code' => '0x10',
				'status_message' => 'Third Party not external id',
			);
		}

		// @todo: If contact_ids is empty, alert.

		if ( empty( $third_party->data['sync_sha_256'] ) ) {
			return array(
				'status' => false,
				'status_code' => '0x11',
				'status_message' => 'Third Party SHA256 cannot be empty',
			);
		}


		return array(
			'status' => true,
			'status_code' => '0x0',
		);
	}

	/**
	 * Créer un utilisateur à partir d'un tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party   Les données d'un tier Wordpress.
	 * @param stdClass $doli_third_party Les données d'un tier Dolibarr.
	 *
	 * @return User | boolean            L'utilisateur créé.
	 */
	public function create_user( $third_party, $doli_third_party ) {
		$contact = array();

		$contact['login']          = sanitize_title( $third_party->data['email'] );
		$contact['firstname']      = $doli_third_party->array_options->options_firstname;
		$contact['lastname']       = $third_party->data['title'];
		$contact['email']          = $third_party->data['email'];
		$contact['third_party_id'] = $third_party->data['id'];
		$contact['password']       = wp_generate_password();

//
//			if ( 0 === $wp_contact->data['id'] && false !== email_exists( $wp_contact->data['email'] ) ) {
//				\eoxia\LOG_Util::log( sprintf( 'Contact: doli_to_wp can\'t create %s email already exist', json_encode( $wp_contact->data ) ), 'wpshop2' );
//				return false;
//			}


		$contact_saved = User::g()->update( $contact );

		if ( is_wp_error( $contact_saved ) ) {
			// translators: Contact: doli_to_wp error when update or create contact {json_data}.
			LOG_Util::log( sprintf( 'Contact: doli_to_wp error when update or create contact: %s', json_encode( $contact_saved ) ), 'wpshop2' );
			return false;
		}

		return $contact_saved;
	}

	/**
	 * Met à jour l'utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param User $user               Les données d'un utilisateur.
	 * @param Third_Party $third_party Les données d'un tier.
	 *
	 * @return User                    Les données d'un utilisateur mise à jour.
	 */
	public function update_user( $user, $third_party ) {
		return $user;
	}
}

Doli_User::g();
