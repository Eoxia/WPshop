<?php
/**
 * La classe gérant les actions des utilisateur de Dolibarr.
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
 * Doli User Action Class.
 */
class Doli_User_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		// add_action( 'wps_checkout_create_contact', array( $this, 'checkout_create_contact' ) );

		add_action( 'wps_deleted_contact', array( $this, 'delete_contact' ), 10, 2 );
	}

	/**
	 * Création d'un utilisateur lors du tunnel de vente.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param User $wp_contact Les données d'un utilisateur.
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
	 * Supprime un utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données d'un tier.
	 * @param User        $contact     Les données d'un utilisateur.
	 */
	public function delete_contact( $third_party, $contact ) {
		$data = array(
			'socid' => -1,
		);

		Request_Util::put( 'contact/' . $contact->data['external_id'], $data );
	}
}

new Doli_User_Action();
