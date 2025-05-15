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
			$wp_product->data['stock']             = (int) $doli_product->stock_reel ?? 0;
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
				$data_sha['stock']       = $doli_product->stock_reel ?? 0;
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

	public function update_post_image( $post_id, $doli_id ) {
		$files = Request_Util::get('documents?modulepart=product&id=' . $doli_id);
		
		if (empty($files)) {
			return;
		}

		$file = $files[0];

		$level1 = $file->level1name;
		$filename = $file->filename;

		// Check if an attachment with this filename already exists for this post
		$existing_attachment = get_posts(array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'post_parent'    => $post_id,
			'title'          => sanitize_file_name($filename),
		));

		// If attachment exists and is set as featured image, don't recreate
		if (!empty($existing_attachment)) {
			$existing_id = $existing_attachment[0]->ID;
			$current_thumbnail_id = get_post_thumbnail_id($post_id);
			
			// If this attachment is already the featured image, exit
			if ($current_thumbnail_id == $existing_id) {
				return;
			}
			
			// If attachment exists but isn't set as featured image, just set it
			set_post_thumbnail($post_id, $existing_id);
			return;
		}

		$filepath = rawurlencode($level1 . '/' . $filename);

		$image = Request_Util::get('documents/download?modulepart=product' . '&original_file=' . $filepath);

		// Prépare le nom du fichier
		$filename = basename(parse_url($filename, PHP_URL_PATH));

		// Détermine le chemin de destination personnalisé
		$upload_dir = wp_upload_dir();
		$custom_subdir = '/product/' . $post_id;
		$custom_dir = $upload_dir['basedir'] . $custom_subdir;

		// Crée le dossier s'il n'existe pas
		if (!file_exists($custom_dir)) {
			wp_mkdir_p($custom_dir);
		}

		// Écrit le fichier localement
		$file_path = $custom_dir . '/' . $filename;
		file_put_contents($file_path, base64_decode($image->content));
		// URL de l'image
		$file_url = $upload_dir['baseurl'] . $custom_subdir . '/' . $filename;

		// Vérifie le type de fichier
		$filetype = wp_check_filetype($filename, null);

		// Crée la pièce jointe
		$attachment = array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_file_name($filename),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $file_url
		);

		// Insère l'attachement dans la base
		$attach_id = wp_insert_attachment($attachment, $file_path, $post_id);

		// Génère les métadonnées
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
		wp_update_attachment_metadata($attach_id, $attach_data);

		// Définit comme image à la une du produit
		set_post_thumbnail($post_id, $attach_id);
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
