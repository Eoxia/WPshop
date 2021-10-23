<?php
/**
 * La classe gérant les actions des tiers de Dolibarr.
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
 * Doli Third Parties Action Class.
 */
class Doli_Third_Parties_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wps_checkout_create_third_party', array( $this, 'checkout_create_third_party' ) );
		add_action( 'checkout_create_proposal', array( $this, 'checkout_sync_third' ), 10, 2 );

		// add_action( 'wps_payment_complete', array( $this, 'update_address' ), 10, 1 );
	}

	/**
	 * Lors du tennel de vente, créer un tier vers dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $wp_third_party Les données d'un tier.
	 */
	public function checkout_create_third_party( $wp_third_party ) {

		if ( Settings::g()->dolibarr_is_active() ) {
			Doli_Third_Parties::g()->wp_to_doli( $wp_third_party, null );

			// translators: Checkout create third party to dolibarr {json_data}.
			\eoxia\LOG_Util::log( sprintf( 'Checkout create third party to dolibarr %s', json_encode( $wp_third_party->data ) ), 'wpshop2' );
		}
	}

	/**
	 * Lors du tennel de vente, synchronise le tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $wp_third_party Les données d'un tier.
	 * @param User        $wp_contact     Les données d'un utilisateur.
	 */
	public function checkout_sync_third( $wp_third_party, $wp_contact ) {
		if ( Settings::g()->dolibarr_is_active() ) {
			Doli_Sync::g()->sync( $wp_third_party->data['id'], $wp_third_party->data['external_id'], $wp_third_party->data['type'] );
		}
	}

	/**
	 * Met à jour l'adresse d'un tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $data Les données d'un tier.
	 */
	public function update_address( $data ) {
		$wp_order = Doli_Order::g()->get( array( 'id' => (int) $data['custom'] ), true );

		if ( 'paypal' === $wp_order->data['payment_method'] ) {
			$third_party = Third_Party::g()->get( array( 'id' => $wp_order->data['parent_id'] ), true );

			$third_party->data['contact'] = $data['address_street'];
			$third_party->data['town']    = $data['address_city'];
			$third_party->data['zip']     = $data['address_zip'];
			$third_party->data['country'] = $data['address_country'];
			$third_party->data['phone']   = $data['phone'];

			$third_party = Third_Party::g()->update( $third_party->data );

			$doli_third_party = Request_Util::get( 'thirdparties/' . $third_party->data['external_id'] );
			Doli_Third_Parties::g()->wp_to_doli( $third_party, $doli_third_party );

			// translators: Checkout update third party adress {json_data}.
			\eoxia\LOG_Util::log( sprintf( 'Checkout update third party contact %s', json_encode( $third_party->data ) ), 'wpshop2' );

		}
	}
}

new Doli_Third_Parties_Action();
