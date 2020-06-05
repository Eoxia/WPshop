<?php
/**
 * Gestion des actions des contact  avec dolibarr.
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
 * Doli Contact Action Class.
 */
class Doli_User_Action {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		// add_action( 'wps_checkout_create_contact', array( $this, 'checkout_create_contact' ) );

		add_action( 'wps_deleted_contact', array( $this, 'delete_contact' ), 10, 2 );
	}

	/**
	 * Création d'un contact lors du tunnel de vente
	 *
	 * @since 2.0.0
	 *
	 * @param  Contact_Model $wp_contact Les données du contact.
	 */
	public function checkout_create_contact( $wp_contact ) {
		if ( Settings::g()->dolibarr_is_active() ) {
			// translators: Checkout create contact from wp to dolibarr {json_data}.
			\eoxia\LOG_Util::log( sprintf( 'Checkout create contact from wp to dolibarr %s', json_encode( $wp_contact->data ) ), 'wpshop2' );

			$external_id = Doli_User::g()->wp_to_doli( $wp_contact );

			$third_party = Third_Party::g()->get( array( 'id' => $wp_contact->data['third_party_id'] ), true );

			// @todo: Only for get SHA256 between WP/Doli Third Party and WP/DOLI Contact.
			Doli_Sync::g()->sync( $third_party->data['id'], $third_party->data['external_id'], $third_party->data['type'] );
			Doli_Sync::g()->sync( $wp_contact->data['id'], $wp_contact->data['external_id'], 'wps-user' );
		}
	}

	/**
	 * Supprimes un contact
	 *
	 * @since 2.0.0
	 *
	 * @param  Third_Party_Model $third_party Les données du tier.
	 * @param  Contact_Model     $contact     Les données du contact.
	 */
	public function delete_contact( $third_party, $contact ) {
		$data = array(
			'socid' => -1,
		);

		Request_Util::put( 'contact/' . $contact->data['external_id'], $data );
	}
}

new Doli_User_Action();
