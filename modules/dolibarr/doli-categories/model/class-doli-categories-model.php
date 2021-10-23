<?php
/**
 * La classe définisant le modèle d'une facture.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\Term_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Category Model Class.
 */
class Doli_Category_Model extends Term_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param Doli_Category $object     Les données de l'objet.
	 * @param string       $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['sync_sha_256'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_sync_sha_256',
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'La cohérence de la synchronisation avec un ERP',
		);

		$this->schema['datec'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'datec',
			'context'     => array( 'GET' ),
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Date de création de la fature. Relation avec dolibarr',
		);

		$this->schema['date_category'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'date_category',
			'context'     => array( 'GET' ),
			'since'       => '2.1.0',
			'version'     => '2.1.0',
			'description' => 'Date de la fature. Relation avec dolibarr',
		);

		parent::__construct( $object, $req_method );
	}
}
