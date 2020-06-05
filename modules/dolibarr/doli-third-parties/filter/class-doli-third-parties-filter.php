<?php
/**
 * Gestion des filtres des tiers.
 * En relation avec Dolibarr.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Filters
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Third Parties Filter Class.
 */
class Doli_Third_Parties_Filter {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_save_and_associate_contact', array( $this, 'add_contact_soc' ), 10, 2 );
	}

	/**
	 * Ajoutes l'ID du tier de dolibarr dans le contact
	 *
	 * @since 2.0.0
	 *
	 * @param Contact_Model     $contact     Les données du contact.
	 * @param Third_Party_Model $third_party Les données du tier.
	 *
	 * @return Contact_Model                 Les données du contact avec l'ID
	 * du tier.
	 */
	public function add_contact_soc( $contact, $third_party ) {
		$contact['third_party_id'] = $third_party->data['external_id'];

		return $contact;
	}
}

new Doli_Third_Parties_Filter();
