<?php
/**
 * La classe gérant les fonctions principales des produits téléchargeables.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Class;

defined( 'ABSPATH' ) || exit;

/**
 * Product_Downloadable Class.
 */
class Product_Downloadable extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Product_Downloadable_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-product-download';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'product-downloadable';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'product-downloadable';

	/**
	 * Le nom du post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Product_Downloadables';

	/**
	 * Créer un produit téléchargable depuis les produits dans la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order Les données de la commande.
	 */
	public function create_from_order( $order ) {
		if ( ! empty( $order->data['lines'] ) ) {
			foreach ( $order->data['lines'] as $line ) {
				$product = Product::g()->get( array(
					'meta_key'   => '_external_id',
					'meta_value' => (int) $line['fk_product'],
				), true );

				if ( ! empty( $product->data['associated_document_id']['downloadable_product_id'] ) ) {
					foreach ( $product->data['associated_document_id']['downloadable_product_id'] as $id ) {
						if ( $product->data['product_downloadable'] ) {
							$product_downloadable = Product_Downloadable::g()->get( array(
								'post_parent' => $order->data['id'],
								'author_id'   => $order->data['author_id'],
								'meta_key'    => '_product_id',
								'meta_value'  => $id,
							), true );

							if ( empty( $product_downloadable ) ) {
								$downlodable_product_guid = get_the_guid( $id );
								Product_Downloadable::g()->create( array(
									'title'      => basename( $downlodable_product_guid ),
									'parent_id'  => $order->data['id'],
									'author_id'  => $order->data['author_id'],
									'product_id' => $id,
								) );
							}
						}
					}
				}
			}
		}
	}
}

Product_Downloadable::g();
