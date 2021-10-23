<?php
/**
 * La classe gérant les actions des produits téléchargeables.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Product_Downloadable Action Class.
 */
class Product_Downloadable_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'create_wpshop_upload_dir' ) );
		add_action( 'admin_post_wps_download_product', array( $this, 'download_product' ) );
	}

	/**
	 * Créer un répertoire sécurisé pour les produits téléchargables.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function create_wpshop_upload_dir() {
		$dir = wp_upload_dir();

		$path = $dir['basedir'] . '/wpshop_uploads';

		if ( wp_mkdir_p( $path ) && ! file_exists( $path . '/.htaccess' ) ) {
			$f = fopen( $path . '/.htaccess', 'a+' );
			fwrite( $f, "Options -Indexes\r\ndeny from all" );
			fclose( $f );
		}
	}

	/**
	 * Télécharge le produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function download_product() {
		check_admin_referer( 'download_product' );

		$product_id = ! empty( $_GET['product_id'] ) ? (int) $_GET['product_id'] : 0;

		if ( ! $product_id ) {
			exit;
		}

		$contact              = Contact::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party          = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );
		$product_downloadable = Product_Downloadable::g()->get( array( 'id' => $product_id ), true );

		if ( ( $product_downloadable->data['author_id'] !== $contact->data['id'] ) && ! current_user_can( 'administrator' ) ) {
			exit;
		}

		$wp_upload_dir = wp_upload_dir();
		$path          = str_replace( '\\', '/', $wp_upload_dir['path'] );
		$basedir       = str_replace( '\\', '/', $wp_upload_dir['basedir'] );
		$baseurl       = str_replace( '\\', '/', $wp_upload_dir['baseurl'] );

		$guid      = get_the_guid( $product_downloadable->data['product_id'] );
		$file_path = str_replace( $baseurl, $basedir, $guid );

		header( 'Content-Type: application/octect-stream' );
		header( 'Content-Transfer-Encoding: Binary' );
		header( 'Content-Disposition: attachment; filename="' . basename( $guid ) . '"' );
		readfile( $file_path );
		exit;
	}
}

new Product_Downloadable_Action();
