<?php
/**
 * La classe définisant le modèle d'un document.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.4.0
 * @version   2.4.0
 */

namespace wpshop;

use eoxia\Attachment_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Documents Model Class.
 */
class Doli_Documents_Model extends Attachment_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param Doli_Order $object     Les données de l'objet.
	 * @param string     $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {

		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'L\'ID du customer (dolibarr). Relation avec dolibarr.',
		);

		$this->schema['name'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'name',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Le nom du document.',
		);

		$this->schema['level1name'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'level1name',
			'since'       => '2.4.0',
			'version'     => '2.4.0',
			'description' => 'Le répertoire du document.',
		);

		$this->schema['path'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'path',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Le chemin d\'accès au document.',
		);

		$this->schema['fullpath'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'guid',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Le chemin d\'acces total au document.',
		);

		$this->schema['date'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'date',
			'context'     => array( 'GET' ),
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Date de création du document. Relation avec dolibarr',
		);

		$this->schema['size'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'size',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'La taille du document en Ko.',
		);

		$this->schema['dolibarr_type'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'type',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Le type de document.',
		);

		$this->schema['linked_objects_ids'] = array(
			'type'        => 'array',
			'meta_type'   => 'single',
			'field'       => '_linked_objects_ids',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'L\'id des objets liés.',
			'default'     => null,
		);

		parent::__construct( $object, $req_method );
	}
}
