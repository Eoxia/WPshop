<?php
/**
 * Classe définisant le modèle d'un contact.
 *
 * @todo: Translate in English.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019-2020 Eoxia <dev@eoxia.com>.
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
 * Class Contact model.
 */
class User_Model extends \eoxia\User_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Contact $object     Les données de l'objet.
	 * @param string  $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.0.0',
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['phone'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_phone',
			'default'     => null,
			'since'       => '2.0.0',
			'description' => 'Le téléphone du contact (varchar(30)). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['phone_mobile'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_phone_mobile',
			'default'     => null,
			'since'       => '2.0.0',
			'description' => 'Le téléphone mobile du contact (varchar(30)). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['third_party_id'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_third_party_id',
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
