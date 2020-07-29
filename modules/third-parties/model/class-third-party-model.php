<?php
/**
 * La classe définissant le modèle d'un tier.
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
 * Third Party Model Class.
 */
class Third_Party_Model extends Post_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $object     Les données de l'objet.
	 * @param string      $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['address'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_address',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'adresse d\'un tier (varchar(255)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);

		$this->schema['zip'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_zip',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le code postal d\'un tier (varchar(25)). Peut être NULL. Valeur par défault NULL.',
			'default'     => '',
		);

		$this->schema['town'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_town',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La ville d\'un tier (varchar(50)). Peut être NULL. Valeur par défault NULL.',
			'default'     => '',
		);

		$this->schema['state'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_state',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le département d\'un tier (varchar(50)). Peut être NULL. Valeur par défault NULL.',
			'default'     => '',
		);

		$this->schema['country'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_country',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le pays d\'un tier (varchar(50)). Ne peut être NULL. Aucune valeur par défault.',
			'default'     => '',
		);

		$this->schema['country_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_country_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le pays d\'un tier (varchar(50)). Ne peut être NULL. Aucune valeur par défault.',
		);

		$this->schema['phone'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_phone',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le téléphone du contact (varchar(30)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);

		$this->schema['contact_ids'] = array(
			'type'        => 'array',
			'meta_type'   => 'single',
			'field'       => '_contact_ids',
			'array_type'  => 'integer',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Association des contact. Attends un tableau d\'ids des contact. Aucune valeur par défaut.',
		);

		$this->schema['siret'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'idprof2',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Numéro SIRET de l\'entreprise (varchar(128)). Par défault NULL.',
			'default'     => null,
		);

		$this->schema['email'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'email',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'email du tier. (varchar(30)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => '',
		);

		$this->schema['sync_sha_256'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_sync_sha_256',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La cohérence de la synchronisation avec un ERP',
		);

		parent::__construct( $object, $req_method );
	}
}
