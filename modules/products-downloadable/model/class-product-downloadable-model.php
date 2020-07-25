<?php
/**
 * Classe définisant le modèle d'un produit téléchargeable WPshop.
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
 * Product_Downloadable Model Class.
 */
class Product_Downloadable_Model extends Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Product_Downloadable $object     Les données de l'objet.
	 * @param string               $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['download_count'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_download_count',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le nombre de fois où le produit a été téléchargé.',
			'default'     => 0,
		);

		$this->schema['product_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_product_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'id du produit.',
		);

		parent::__construct( $object, $req_method );
	}
}
