<?php
/**
 * Les fonctions principales des produits.
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
 * Product_Downloadable Class.
 */
class Product_Downloadable extends \eoxia\Post_Class {

	/**
	 * Model name @see ../model/*.model.php.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Product_Downloadable_Model';

	/**
	 * Post type
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-product-download';

	/**
	 * La clé principale du modèle
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'product-downloadable';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'product-downloadable';

	/**
	 * Le nom du post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Product_Downloadables';

	/**
	 * Créer un produit téléchargable depuis les produits dans les commandes.
	 *
	 * @since 2.0.0
	 *
	 * @param  Order_Model $order Les données de la commande.
	 *
	 * @return void
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
