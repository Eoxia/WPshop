<?php
/**
 * La classe définisant le modèle d'un produit.
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
 * Product Model Class.
 */
class Doli_Category_Model extends Term_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Product $object     Les données de l'objet.
	 * @param string  $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'ID provenant de dolibarr',
			'default'     => 0,
		);

		$this->schema['fk_product_parent'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_fk_product_parent',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'ID parent provenant de dolibarr',
		);

		$this->schema['label'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_ref',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La référence du produit (varchar(128)). Ne peut être NULL. Clé unique. Aucune valeur par défaut. Relation avec dolibarr.',
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
