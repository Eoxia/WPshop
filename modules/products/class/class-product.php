<?php
/**
 * La classe gérant les fonctions principales des produits.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\Post_Class;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Product Class.
 */
class Product extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Product_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-product';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'product';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'product';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = 'wps-product-cat';

	/**
	 * Le nom du post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Products';

	/**
	 * La limite par page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var integer
	 */
	public $limit = 10;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	public $option_per_page = 'product_per_page';

	/**
	 * Récupère la liste des produits et appel la vue "list" du module "Product".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $mode Le mode d'affichage de la vue. Soit en mode "view" ou en mode "edit".
	 */
	public function display( $mode = 'view' ) {
		$per_page = get_user_meta( get_current_user_id(), $this->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = $this->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$product_ids = Product::g()->search( $s, array(
			'offset'         => ( $current_page - 1 ) * $per_page,
			'posts_per_page' => $per_page,
			'post_status'    => 'any',
		) );

		$products = array();

		if ( ! empty( $product_ids ) ) {
			$products = $this->get( array(
				'post__in' => $product_ids,
			) );

			if ( ! empty( $products ) ) {
				foreach ( $products as &$product ) {
					$product->data['parent_post'] = null;

					if ( ! empty( $product->data['fk_product_parent'] ) ) {
						$parent_post = get_post( Doli_Products::g()->get_wp_id_by_doli_id( $product->data['fk_product_parent'] ) );

						$product->data['parent_post'] = $parent_post;
					}
				}

				unset ( $product );
			}
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$view = 'view' === $mode ? '' : '-edit';

		View_Util::exec( 'wpshop', 'products', 'list', array(
			'products' => $products,
			'doli_url' => $dolibarr_option['dolibarr_url'],
			'view'     => $view,
		) );
	}

	/**
	 * Appel la vue "item" du produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Product $product     Les données d'un produit.
	 * @param string  $sync_status Le statut de la synchronisation.
	 * @param string  $doli_url    L'url de Dolibarr.
	 */
	public function display_item( $product, $sync_status, $doli_url = '' ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		View_Util::exec( 'wpshop', 'products', 'item', array(
			'product'     => $product,
			'sync_status' => $sync_status,
			'doli_url'    => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Ajoute des metabox pour configurer le produit.
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 */
	public function callback_register_meta_box() {
		if ( Settings::g()->dolibarr_is_active() ) {
			add_meta_box(
				'wp_product_title',
				__( 'Product title', 'wpshop' ),
				array( $this, 'callback_add_meta_box_title' ),
				'wps-product',
				'normal',
				'high'
			);
		}

		add_meta_box(
			'wps_product_configuration',
			__( 'Dolibarr Product configuration', 'wpshop'),
			array( $this, 'callback_add_meta_box' ),
			'wps-product'
		);

		add_meta_box(
			'wps_product_configuration_wordpress',
			__( 'WordPress Product configuration', 'wpshop'),
			array( $this, 'callback_add_meta_box_configuration' ),
			'wps-product'
		);

		add_meta_box(
			'wps_product_gallery',
			__( 'Product Gallery', 'wpshop'),
			array( $this, 'callback_add_meta_box_gallery' ),
			'wps-product',
			'side'
		);

		add_meta_box(
			'wps-product-catdiv',
			__( 'Product category', 'wpshop'),
			array( $this, 'callback_add_meta_box_category' ),
      	'wps-product',
		'side'
		);

		add_meta_box(
			'wps_product_document',
			__( 'Product Document', 'wpshop'),
			array( $this, 'callback_add_meta_box_document' ),
			'wps-product',
			'side'
		);
	}
	public function callback_add_meta_box_category( $post ) {

		echo do_shortcode('[wps_categories]');

		$defaults = array( 'taxonomy' => 'wps-product-cat' );
		if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
			$args = array();
		} else {
			$args = $box['args'];
		}
		$parsed_args = wp_parse_args( $args, $defaults );
		$tax_name    = esc_attr( $parsed_args['taxonomy'] );
		$taxonomy    = get_taxonomy( $parsed_args['taxonomy'] );
		$categories  = Doli_Category::g()->get();

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		View_Util::exec( 'wpshop', 'products', 'metabox/categories', array(
			'parsed_args' => $parsed_args,
			'tax_name'    => $tax_name,
			'taxonomy'    => $taxonomy,
			'post'        => $post,
			'categories'  => $categories
		) );
	}

	/**
	 * La metabox affichant le titre et l'état de synchro du produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param WP_Post $post Le produit.
	 */
	public function callback_add_meta_box_title( $post ) {
		$product = $this->get( array( 'id' => $post->ID ), true );
		if ( empty( $product ) ) {
			$product = $this->get( array( 'schema' => true ), true );
		}
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		View_Util::exec( 'wpshop', 'products', 'metabox/title', array(
			'id'               => ! empty( $product->data['id'] ) ? $product->data['id'] : $post->ID,
			'product'          => $product,
			'sync_status'      => false,
			'doli_url'         => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * La vue de la metabox pour configurer le produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param WP_Post $post Le produit.
	 */
	public function callback_add_meta_box( $post ) {
		$product = $this->get( array( 'id' => $post->ID ), true );

		if ( empty( $product ) ) {
			$product = $this->get( array( 'schema' => true ), true );
		}

		if ( ! empty( $product->data['fk_product_parent'] ) ) {
			$parent_post = get_post( Doli_Products::g()->get_wp_id_by_doli_id( $product->data['fk_product_parent'] ) );

			$product->data['parent_post'] = $parent_post;
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		View_Util::exec( 'wpshop', 'products', 'metabox/main', array(
			'id'               => ! empty( $product->data['id'] ) ? $product->data['id'] : $post->ID,
			'product'          => $product,
		) );
	}

	/**
	 * La vue de la metabox pour configurer le produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param WP_Post $post Le produit.
	 */
	public function callback_add_meta_box_configuration( $post ) {
		$product = $this->get( array( 'id' => $post->ID ), true );

		if ( empty( $product ) ) {
			$product = $this->get( array( 'schema' => true ), true );
		}

		if ( ! empty( $product->data['fk_product_parent'] ) ) {
			$parent_post = get_post( Doli_Products::g()->get_wp_id_by_doli_id( $product->data['fk_product_parent'] ) );

			$product->data['parent_post'] = $parent_post;
		}

		$similar_products = array();

		if ( ! empty( $product->data['similar_products_id'] ) ) {
			$similar_products = Product::g()->get( array( 'post__in' => $product->data['similar_products_id'] ) );
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		View_Util::exec( 'wpshop', 'products', 'metabox/configuration', array(
			'id'               => ! empty( $product->data['id'] ) ? $product->data['id'] : $post->ID,
			'product'          => $product,
			'doli_url'         => $dolibarr_option['dolibarr_url'],
			'similar_products' => $similar_products,
			'sync_status'      => false,
		) );
	}

	/**
	 * La vue de la metabox pour configurer la galerie d'image du produit.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param WP_Post $post Le produit.
	 */
	public function callback_add_meta_box_gallery( $post ) {
		$product = $this->get( array( 'id' => $post->ID ), true );

		if ( empty( $product ) ) {
			$product = $this->get( array( 'schema' => true ), true );
		}

		if ( ! empty( $product->data['fk_product_parent'] ) ) {
			$parent_post = get_post( Doli_Products::g()->get_wp_id_by_doli_id( $product->data['fk_product_parent'] ) );

			$product->data['parent_post'] = $parent_post;
		}

		// Get Dolibarr documents.
		$doli_documents = Request_Util::get( 'documents?modulepart=product&id=' . $product->data['external_id'] );
		if ( empty( $doli_documents ) ) {
			$doli_documents = array();
		}
		$wp_documents = Doli_Documents::g()->convert_to_wp_documents_format( $doli_documents );

		$mine_type = 'image';
		$wp_upload_dir = wp_upload_dir();

		// create the sha256 for documents.
		$sha256   = get_post_meta( $post->ID, 'sha256_documents', true );

		$data_sha = Doli_Documents::g()->build_sha_documents( $post->ID, $doli_documents );


		if ( $sha256 != $data_sha ) {

			$attachments = Doli_Documents::g()->get_attachments( $product, $mine_type );

			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					wp_delete_attachment( $attachment['ID'] );
				}
			}

			Doli_Documents::g()->create_attachments( $wp_documents, $product , $mine_type );
		}

		$attachments = Doli_Documents::g()->get_attachments( $product, $mine_type );
		$attachments = Doli_Documents::g()->add_metadata_attachements( $attachments );

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_product_document = $dolibarr_option['dolibarr_product_document'];
		$dolibarr_url              = $dolibarr_option['dolibarr_url'];

		$upload_link = esc_url( get_upload_iframe_src( 'image', $product->data['id'] ) );

		View_Util::exec(
			'wpshop',
			'products',
			'metabox/gallery',
			array(
				'id'                        => ! empty( $product->data['id'] ) ? $product->data['id'] : $post->ID,
				'wp_upload_dir'             => $wp_upload_dir,
				'upload_link'               => $upload_link,
				'attachments'               => ! empty( $attachments ) ? $attachments : '',
				'product'                   => $product,
				'dolibarr_url'              => $dolibarr_url,
				'dolibarr_product_document' => $dolibarr_product_document,
			)
		);
	}

	/**
	 * La vue de la metabox pour configurer les documents du produit.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param WP_Post $post Le produit.
	 */
	public function callback_add_meta_box_document( $post ) {
		$product = $this->get( array( 'id' => $post->ID ), true );

		if ( empty( $product ) ) {
			$product = $this->get( array( 'schema' => true ), true );
		}

		if ( ! empty( $product->data['fk_product_parent'] ) ) {
			$parent_post = get_post( Doli_Products::g()->get_wp_id_by_doli_id( $product->data['fk_product_parent'] ) );

			$product->data['parent_post'] = $parent_post;
		}

		// Get Dolibarr documents.
		$doli_documents = Request_Util::get( 'documents?modulepart=product&id=' . $product->data['external_id'] );
		$wp_documents   = Doli_Documents::g()->convert_to_wp_documents_format( $doli_documents );

		$mine_type     = 'application';
		$wp_upload_dir = wp_upload_dir();

		// create the sha256 for documents.
		$sha256   = get_post_meta( $post->ID, 'sha256_documents', true );
		$data_sha = Doli_Documents::g()->build_sha_documents( $post->ID, $doli_documents );

		if ( $sha256 == $data_sha ) {

			$attachments = Doli_Documents::g()->get_attachments( $product, $mine_type );

			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					wp_delete_attachment( $attachment['ID'] );
				}
			}

			Doli_Documents::g()->create_attachments( $wp_documents, $product , $mine_type );
		}

		$attachments = Doli_Documents::g()->get_attachments( $product, $mine_type );
		$attachments = Doli_Documents::g()->add_metadata_attachements( $attachments );

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url              = $dolibarr_option['dolibarr_url'];
		$dolibarr_product_document = $dolibarr_option['dolibarr_product_document'];
		$upload_link               = esc_url( get_upload_iframe_src( 'image', $product->data['id'] ) );

		View_Util::exec(
			'wpshop',
			'products',
			'metabox/document',
			array(
				'id'                        => ! empty( $product->data['id'] ) ? $product->data['id'] : $post->ID,
				'wp_upload_dir'             => $wp_upload_dir,
				'upload_link'               => $upload_link,
				'attachments'               => ! empty( $attachments ) ? $attachments : '',
				'product'                   => $product,
				'dolibarr_url'              => $dolibarr_url,
				'dolibarr_product_document' => $dolibarr_product_document,
			)
		);
	}

	/**
	 * Fonction de recherche.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre d'éléments trouvés.
	 */
	public function search( $s = '', $default_args = array(), $count = false ) {
		$args = array(
			'post_type'      => 'wps-product',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post_status'    => 'any',
		);

		$args = wp_parse_args( $default_args, $args );

		if ( ! empty( $s ) ) {
			$products_id = get_posts( array(
				's'              => $s,
				'fields'         => 'ids',
				'post_type'      => 'wps-product',
				'posts_per_page' => -1,
				'post_status'    => 'any',
			) );

			if ( empty( $products_id ) ) {
				if ( $count ) {
					return 0;
				} else {
					return array();
				}
			} else {
				$args['post__in'] = $products_id;

				if ( $count ) {
					return count( get_posts( $args ) );
				} else {
					return $products_id;
				}
			}
		}

		if ( $count ) {
			return count( get_posts( $args ) );
		} else {
			return get_posts( $args );
		}
	}
}

Product::g();
