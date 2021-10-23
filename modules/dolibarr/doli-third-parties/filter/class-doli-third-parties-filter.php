<?php
/**
 * La classe gérant les filtres des tiers de Dolibarr.
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
 * Doli Third Parties Filter Class.
 */
class Doli_Third_Parties_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_save_and_associate_contact', array( $this, 'add_contact_soc' ), 10, 2 );
	}

	/**
	 * Ajoute l'id du tier de Dolibarr à un utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  User        $contact     Les données d'un utilisateur.
	 * @param  Third_Party $third_party Les données d'un tier.
	 *
	 * @return User                     Les données d'un utilisateur avec l'id du tier.
	 */
	public function add_contact_soc( $contact, $third_party ) {
		$contact['third_party_id'] = $third_party->data['external_id'];

		return $contact;
	}
}

new Doli_Third_Parties_Filter();
