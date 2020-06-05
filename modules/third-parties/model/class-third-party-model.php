<?php
/**
 * Classe définisant le modèle d'un tier.
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
 * Class Third Party model.
 */
class Third_Party_Model extends \eoxia\Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
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
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['address'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_address',
			'default'     => '',
			'since'       => '2.0.0',
			'description' => 'L\'adresse d\'un tier (varchar(255)). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['zip'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_zip',
			'default'     => '',
			'since'       => '2.0.0',
			'description' => 'Le code postal d\'un tier (varchar(25)). Peut être NULL. Valeur par défault NULL.',
		);

		$this->schema['town'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_town',
			'default'     => '',
			'since'       => '2.0.0',
			'description' => 'La ville d\'un tier (varchar(50)). Peut être NULL. Valeur par défault NULL.',
		);

		$this->schema['state'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_state',
			'default'     => '',
			'since'       => '2.0.0',
			'description' => 'Le département d\'un tier (varchar(50)). Peut être NULL. Valeur par défault NULL.',
		);

		$this->schema['country'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_country',
			'default'     => '',
			'since'       => '2.0.0',
			'description' => 'Le pays d\'un tier (varchar(50)). Ne peut être NULL. Aucune valeur par défault.',
		);

		$this->schema['country_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_country_id',
			'since'       => '2.0.0',
			'description' => 'Le pays d\'un tier (varchar(50)). Ne peut être NULL. Aucune valeur par défault.',
		);

		$this->schema['phone'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => '_phone',
			'default'   => '',
		);

		$this->schema['contact_ids'] = array(
			'type'        => 'array',
			'array_type'  => 'integer',
			'meta_type'   => 'single',
			'field'       => '_contact_ids',
			'since'       => '2.0.0',
			'description' => 'Association des contact. Attends un tableau d\'ids des contact. Aucune valeur par défaut.',
		);

		$this->schema['siret'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'idprof2',
			'since'       => '2.0.0',
			'default'     => null,
			'description' => 'Numéro SIRET de l\'entreprise (varchar(128)). Par défault NULL.',
		);

		$this->schema['email'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'email',
			'since'     => '2.0.0',
			'default'   => '',
		);

		$this->schema['sync_sha_256'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_sync_sha_256',
			'since'       => '2.0.0',
			'description' => 'La cohérence de la synchronisation avec un ERP',
		);

		parent::__construct( $object, $req_method );
	}
}
