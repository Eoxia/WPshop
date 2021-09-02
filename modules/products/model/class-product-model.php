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
class Product_Model extends Post_Model {

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

		$this->schema['ref'] = array(
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

		$this->schema['price'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_price',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix HT du produit (double(24,8)). Peut être NULL. Valeur par défaut 0.00000000',
			'default'     => 0.00000000,
		);

		$this->schema['price_ttc'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_price_ttc',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix TTC du produit (double(24,8)). Peut être NULL. Valeur par défaut 0.00000000',
			'default'     => 0.00000000,
		);

		$this->schema['tva_amount'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_tva_amount',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le montant de la TVA du produit (double(24,8)). Peut être NULL. Valeur par défaut 0.00000000',
			'default'     => 0.00000000,
		);

		$this->schema['tva_tx'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_tva_tx',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le taux de TVA du produit (double(6,3)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['barcode'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_barcode',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le code-barres du produit (varchar(180)). Peut être NULL. Valeur par défaut NULL.',
			'default'     => null,
		);

		// A verifier.
		$this->schema['fk_product_type'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_fk_product_type',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le type du produit (int(11)). Peut être NULL. Valeur par défaut 0. Valeur attendu: 0 (Produit) ou 1 (Service).',
			'default'     => 0,
		);

		$this->schema['product_downloadable'] = array(
			'type'        => 'boolean',
			'meta_type'   => 'single',
			'field'       => '_product_downloadable',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le type du produit (int(11)). Peut être NULL. Valeur par défaut 0. Valeur attendu: 0 (Produit) ou 1 (Service).',
			'default'     => false,
		);

		$this->schema['volume'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_volume',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le volume en m³ (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => null,
		);

		$this->schema['length'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_length',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La longueur en m (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => null,
		);

		$this->schema['width'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_width',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La largeur en m (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => null,
		);

		$this->schema['height'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_height',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La hauteur en m (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => null,
		);

		$this->schema['weight'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_weight',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le poids en Kg (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => null,
		);

		$this->schema['manage_stock'] = array(
			'type'        => 'boolean',
			'meta_type'   => 'single',
			'field'       => '_manage_stock',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Gestion du stock (boolean). Valeur par défaut false.',
			'default'     => false,
		);

		$this->schema['stock'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_stock',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le stock du produit (int). Valeur par défaut 0.',
			'default'     => 0,
		);

		$this->schema['web'] = array(
			'type'        => 'string', // @todo: Dolibarr type is horrible.
			'meta_type'   => 'single',
			'field'       => '_web',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Option web on dolibarr.',
			'default'     => 0,
		);

		$this->schema['similar_products_id'] = array(
			'type'        => 'array',
			'meta_type'   => 'integer',
			'field'       => '_similar_products_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Les id des produits similaires',
			'default'     => null,
		);

		$this->schema['associated_document_id'] = array(
			'type'        => 'array',
			'meta_type'   => 'multiple',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Les id des documents associés.',
			'child'       => array(),
		);

		$this->schema['associated_document_id']['child']['downloadable_product_id'] = array(
			'type'        => 'array',
			'meta_type'   => 'multiple',
			'array_type'  => 'integer',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'id d\'un produit téléchargeable.',
		);

		parent::__construct( $object, $req_method );
	}
}
