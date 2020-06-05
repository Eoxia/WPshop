<?php
/**
 * Classe définisant le modèle d'un produit WPshop.
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
 * Product Model Class.
 */
class Product_Downloadable_Model extends \eoxia\Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Product $object     Les données de l'objet.
	 * @param string  $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['download_count'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_download_count',
			'since'       => '2.0.0',
			'default'     => 0,
			'description' => '',
		);

		$this->schema['product_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_product_id',
			'since'       => '2.0.0',
			'description' => '',
		);

		parent::__construct( $object, $req_method );
	}
}
