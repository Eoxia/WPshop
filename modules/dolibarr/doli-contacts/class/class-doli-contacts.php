<?php
/**
 * La classe gérant les fonctions principales des contacts/adresses de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.3.0
 * @version   2.3.0
 */

namespace wpshop;

use eoxia\Post_Class;
use eoxia\View_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Contacts Class.
 */
class Doli_Contacts extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Contacts_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	protected $type = 'wps-contacts';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	protected $meta_key = 'doli-contacts';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	protected $base = 'doli-contacts';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	/**
	 * Le nom du post type.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Doli Contacts';

	/**
	 * La limite par page.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var integer
	 */
	public $limit = 10;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @var string
	 */
	public $option_per_page = 'contacts_doli_per_page';

	/**
	 * Convertit un tableau Contacts Object provenant de Dolibarr vers un format Contacts Object WPshop afin de normisé pour l'affichage.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @param  stdClass $doli_contacts Le tableau contenant toutes les données des factures provenant de Dolibarr.
	 *
	 * @return array                   Le tableau contenant toutes les données des factures convertis depuis le format de Dolibarr.
	 */
	public function convert_to_wp_contact_format( $doli_contacts ) {
		$wp_contacts = array();

		if ( ! empty( $doli_contacts ) ) {
			foreach ( $doli_contacts as $doli_contact ) {
				$wp_contact    = $this->get( array( 'schema' => true ), true );
				$wp_contacts[] = $this->doli_to_wp( $doli_contact, $wp_contact, true );
			}
		}

		return $wp_contacts;
	}

	/**
	 * Synchronise depuis Dolibarr vers WP.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @param  stdClass      $doli_contacts Les données d'un contact/adresse Dolibarr.
	 * @param  Doli_Contacts $wp_contact    Les données d'un contact/adresse WordPress.
	 * @param  boolean       $only_convert  Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Doli_Contacts                Les données d'un contact/adresse WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_contacts, $wp_contact, $only_convert = false ) {

		$wp_contact->data['external_id']    = (int) $doli_contacts->id;
		$wp_contact->data['civility']       = $doli_contacts->civility;
		$wp_contact->data['address']        = $doli_contacts->address;
		$wp_contact->data['zip']            = $doli_contacts->zip;
		$wp_contact->data['town']           = $doli_contacts->town;
		$wp_contact->data['state']          = $doli_contacts->state;
		$wp_contact->data['third_party_id'] = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $doli_contacts->socid );
		$wp_contact->data['email']          = $doli_contacts->email;
		$wp_contact->data['phone_pro']      = $doli_contacts->phone_pro;
		$wp_contact->data['country']        = $doli_contacts->country;
		$wp_contact->data['lastname']       = $doli_contacts->lastname;
		$wp_contact->data['firstname']      = $doli_contacts->firstname;

		if ( ! $only_convert ) {
			$wp_contact = Doli_Contacts::g()->update( $wp_contact->data );
		}

		return $wp_contact;
	}
}

Doli_Contacts::g();
