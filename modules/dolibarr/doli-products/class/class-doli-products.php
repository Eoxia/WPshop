<?php
/**
 * La classe gérant les fonctions principales des produits de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Product Class.
 */
class Doli_Products extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Synchronise de Dolibarr vers WP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  stdClass $doli_product Les données d'un produit Dolibarr.
	 * @param  Product  $wp_product   Les données d'un produit WordPress.
	 * @param  boolean  $save         Enregistres les données sinon renvoies l'objet remplit sans l'enregistrer en base de donnée.
	 * @param  array    $notices      Gestion des erreurs et informations de l'évolution de la méthode.
	 *
	 * @return Product                Les données d'un produit Wordpress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_product, $wp_product, $save = true, &$notices = array(
		'errors'   => array(),
		'messages' => array(),
	) ) {

			$wp_product->data['external_id']       = (int) $doli_product->id;
			$wp_product->data['fk_product_parent'] = isset( $doli_product->fk_product_parent ) ? (int) $doli_product->fk_product_parent : 0;
			$wp_product->data['ref']               = $doli_product->ref;
			$wp_product->data['title']             = $doli_product->label;
			$wp_product->data['content']           = $doli_product->description;
			$wp_product->data['price']             = $doli_product->price;
			$wp_product->data['price_ttc']         = $doli_product->price_ttc;
			$wp_product->data['tva_amount']        = $doli_product->price_ttc - $doli_product->price;
			$wp_product->data['tva_tx']            = $doli_product->tva_tx;
			$wp_product->data['stock']             = (int) $doli_product->stock_reel;
			$wp_product->data['barcode']           = $doli_product->barcode;
			$wp_product->data['fk_product_type']   = (int) $doli_product->type; // Product 0 or Service 1.
			$wp_product->data['status']            = $doli_product->array_options->options__wps_status;

			$wp_product = Product::g()->update( $wp_product->data );

			if ( $save ) {
				$data_sha = array();

				$data_sha['doli_id']     = $doli_product->id;
				$data_sha['wp_id']       = $wp_product->data['id'];
				$data_sha['label']       = $wp_product->data['title'];
				$data_sha['description'] = $wp_product->data['content'];
				$data_sha['price']       = $doli_product->price;
				$data_sha['price_ttc']   = $doli_product->price_ttc;
				$data_sha['tva_tx']      = $doli_product->tva_tx;
				$data_sha['stock']       = $doli_product->stock_reel;
				$data_sha['status']      = $wp_product->data['status'];

				$wp_product->data['sync_sha_256'] = hash( 'sha256', implode( ',', $data_sha ) );
				//@todo save_post utilisé ?

				remove_all_actions( 'save_post' );
				update_post_meta( $wp_product->data['id'], '_sync_sha_256', $wp_product->data['sync_sha_256'] );
				update_post_meta( $wp_product->data['id'], '_external_id', (int) $doli_product->id );

				// translators: Erase data for the product <strong>dolibarr</strong> data.
				$notices['messages'][] = sprintf( __( 'Erase data for the product <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop' ), $wp_product->data['title'] );
			}

			return $wp_product;
	}

	/**
	 * Récupère l'id d'un produit WordPress selon l'id d'un produit de Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  integer $doli_id L'id d'un produit de Dolibarr.
	 *
	 * @return integer          L'id d'un produit de WordPress.
	 */
	public function get_wp_id_by_doli_id( $doli_id ) {
		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => $doli_id,
		), true );

		return $product->data['id'];
	}
}

Doli_Products::g();
