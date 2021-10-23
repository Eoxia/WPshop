<?php
/**
 * La classe gérant les fonctions principales des tiers de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Third Parties Class.
 */
class Doli_Third_Parties extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Synchronisation de Dolibarr vers WordPress.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  stdClass    $doli_third_party Les données d'un tier Dolibarr.
	 * @param  Third_Party $wp_third_party   Les données d'un tier WordPress.
	 * @param  boolean     $save             Enregistres les données sinon renvoies l'objet remplit sans l'enregistrer en base de donnée.
	 * @param  array       $notices          Gestion des erreurs et informations de l'évolution de la méthode.
	 *
	 * @return Third_Party                   Les données d'un tier WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_third_party, $wp_third_party, $save = true, &$notices = array(
		'errors'   => array(),
		'messages' => array(),
	) ) {
		$wp_third_party->data['external_id'] = (int) $doli_third_party->id;
		$wp_third_party->data['title']       = $doli_third_party->name;
		$wp_third_party->data['address']     = $doli_third_party->address;
		$wp_third_party->data['town']        = $doli_third_party->town;
		$wp_third_party->data['zip']         = $doli_third_party->zip;
		$wp_third_party->data['state']       = $doli_third_party->state;
		$wp_third_party->data['country']     = $doli_third_party->country;
		$wp_third_party->data['phone']       = $doli_third_party->phone;
		$wp_third_party->data['email']       = $doli_third_party->email;
		$wp_third_party->data['status']      = 'publish';

		if ( $save ) {
			$wp_third_party = Third_Party::g()->update($wp_third_party->data);

			// translators: Erase data for the third party <strong>Eoxia</strong> with the <strong>dolibarr</strong> data.
			$notices['messages'][] = sprintf(__('Erase data for the third party <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop'), $wp_third_party->data['title']);

			$data_sha = array();

			$data_sha['doli_id'] = (int)$doli_third_party->id;
			$data_sha['wp_id'] = (int)$wp_third_party->data['id'];
			$data_sha['title'] = $wp_third_party->data['title'];
			$data_sha['town'] = $wp_third_party->data['town'];
			$data_sha['zip'] = $wp_third_party->data['zip'];
			$data_sha['state'] = $wp_third_party->data['state'];
			$data_sha['country'] = $wp_third_party->data['country'];
			$data_sha['address'] = $wp_third_party->data['address'];
			$data_sha['phone'] = $wp_third_party->data['phone'];
			$data_sha['email'] = $wp_third_party->data['email'];

			update_post_meta($wp_third_party->data['id'], '_sync_sha_256', hash('sha256', implode(',', $data_sha)));

			$user = User::g()->get(array('search' => $wp_third_party->data['email'], 'search_columns' => array('user_email')), true);

			if ( empty( $user ) ) {
				$user = Doli_User::g()->create_user( $wp_third_party, $doli_third_party );
			} else {
				$user = Doli_User::g()->update_user( $user, $wp_third_party, $doli_third_party );
			}

			if (!in_array( $user->data['id'], $wp_third_party->data['contact_ids'] ) ) {
				$wp_third_party->data['contact_ids'][] = $user->data['id'];
				$wp_third_party = Third_Party::g()->update($wp_third_party->data);
			}

		}

		// Get before return for get the new contact_ids array.
		return $wp_third_party;
	}

	/**
	 * Synchronisation de WordPress vers Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  Third_Party $wp_third_party   Les données d'un tier WordPress.
	 * @param  stdClass    $doli_third_party Les données d'un tier Dolibarr.
	 * @param  boolean     $save             Enregistres les données sinon renvoies l'objet remplit sans l'enregistrer en base de donnée.
	 * @param  array       $notices          Gestion des erreurs et informations de l'évolution de la méthode.
	 *
	 * @return Third_Party                   Les données d'un tier Dolibarr avec ceux de WordPress.
	 */
	public function wp_to_doli( $wp_third_party, $doli_third_party, $save = true, &$notices = array(
		'errors'   => array(),
		'messages' => array(),
	) ) {
		$contact = User::g()->get( array( 'id' => $wp_third_party->data['contact_ids'][0] ), true );

		$data = array(
			'name'          => $wp_third_party->data['title'],
			'country'       => $wp_third_party->data['country'],
			'country_id'    => $wp_third_party->data['country_id'],
			'address'       => $wp_third_party->data['address'],
			'zip'           => $wp_third_party->data['zip'],
			'state'         => $wp_third_party->data['state'],
			'phone'         => $wp_third_party->data['phone'],
			'town'          => $wp_third_party->data['town'],
			'email'         => $wp_third_party->data['email'],
			'client'        => 1,
			'code_client'   => 'auto',
			'array_options' => array(
				'options_firstname' => $contact->data['firstname'],
				'options__wps_id'   => $wp_third_party->data['id'],
			),
		);

		if ( ! empty( $wp_third_party->data['external_id'] ) ) {
			$doli_third_party = Request_Util::put( 'thirdparties/' . $wp_third_party->data['external_id'], $data );

			// translators: Erase data for the third party <strong>Eoxia</strong> with the <strong>WordPress</strong> data.
			$notices['messages'][] = sprintf( __( 'Erase data for the third party <strong>%s</strong> with the <strong>WordPress</strong> data', 'wpshop' ), $doli_third_party->name );
		} else {
			$doli_third_party_id                 = Request_Util::post( 'thirdparties', $data );
			$wp_third_party->data['external_id'] = $doli_third_party_id;

			$doli_third_party = Request_Util::get( 'thirdparties/' . $doli_third_party_id );
		}

		$wp_third_party = Third_Party::g()->update( $wp_third_party->data );
		return $doli_third_party;
	}

	/**
	 * Récupère l'id de WordPress depuis l'id de Dolibarr
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  integer $doli_id L'id d'un tier de Dolibarr.
	 *
	 * @return integer          L'id d'un tier de WordPress.
	 */
	public function get_wp_id_by_doli_id( $doli_id ) {
		// @todo: Que se passe-t-il si une commande ou une entité peu importe possède la même ID.
		$third_party = Third_Party::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $doli_id,
		), true ); // WPCS: slow query ok.

		if ( ! empty( $third_party ) ) {
			return $third_party->data['id'];
		}else {
			return;
		}
	}
}

Doli_Third_Parties::g();
