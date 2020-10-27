<?php
/**
 * La classe définisant le modèle d'un contact/adresse.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Contacts Model Class.
 */
class Doli_Contacts_Model extends Post_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 *
	 * @param Doli_Contacts $object     Les données de l'objet.
	 * @param string        $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['civility'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_civility',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'La civilité d\'un contact/adresse (varchar(255)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);


		$this->schema['address'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_address',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'L\'adresse d\'un contact/adresse (varchar(255)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);

		$this->schema['zip'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_zip',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'Le code postal d\'un contact/adresse (varchar(25)). Peut être NULL. Valeur par défault NULL.',
			'default'     => '',
		);

		$this->schema['town'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_town',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'La ville d\'un contact/adresse (varchar(50)). Peut être NULL. Valeur par défault NULL.',
			'default'     => '',
		);

		$this->schema['state'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_state',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'Le département d\'un contact/adresse (varchar(50)). Peut être NULL. Valeur par défault NULL.',
			'default'     => '',
		);

		$this->schema['third_party_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_third_party_id',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'L\'id du tier.',
			'default'     => '',
		);

		$this->schema['email'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'email',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'L\'email du tier. (varchar(30)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);

		$this->schema['phone_pro'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_phone_pro',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'Le téléphone portable d\'un contact/adresse (varchar(30)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);

		$this->schema['country'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_country',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'Le pays d\'un tier (varchar(50)). Ne peut être NULL. Aucune valeur par défault.',
			'default'     => '',
		);

		$this->schema['lastname'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_lastname',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'Le nom de famille d\'un contact/adresse (varchar(255)). Ne peut être NULL. Aucune valeur par défault.',
			'default'     => '',
		);

		$this->schema['firstname'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_firstname',
			'since'       => '2.3.0',
			'version'     => '2.3.0',
			'description' => 'Le prénom d\'un contact/adresse (varchar(255)). Ne peut être NULL. Aucune valeur par défault.',
			'default'     => '',
		);

		parent::__construct( $object, $req_method );
	}
}
