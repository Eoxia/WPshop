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
 * Product Class.
 */
class Product extends \eoxia\Post_Class {

	/**
	 * Model name @see ../model/*.model.php.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Product_Model';

	/**
	 * Post type
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-product';

	/**
	 * La clé principale du modèle
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'product';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'product';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = 'wps-product-cat';

	/**
	 * Le nom du post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Products';

	/**
	 * La limite par page.
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $limit = 10;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $option_per_page = 'product_per_page';

	/**
	 * Récupères la liste des produits et appel la vue "list" du module
	 * "Product".
	 *
	 * @param string $mode Le mode d'affichage de la vue. Soit en mode "view"
	 * ou en mode "edit".
	 *
	 * @since 2.0.0
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

		\eoxia\View_Util::exec( 'wpshop', 'products', 'list', array(
			'products' => $products,
			'doli_url' => $dolibarr_option['dolibarr_url'],
			'view'     => $view,
		) );
	}

	public function display_item( $product, $sync_status, $doli_url = '' ) {
		\eoxia\View_Util::exec( 'wpshop', 'products', 'item', array(
			'product'     => $product,
			'sync_status' => $sync_status,
			'doli_url'    => $doli_url,
		) );
	}

	/**
	 * Ajoutes une metabox pour configurer le produit.
	 *
	 * @since 2.0.0
	 */
	public function callback_register_meta_box() {
		add_meta_box(
			'wps_product_configuration',
			__( 'Product configuration', 'wpshop'),
			array( $this, 'callback_add_meta_box' ),
			'wps-product'
		);
	}

	/**
	 * La vue de la metabox pour configurer le produit
	 *
	 * @param WP_Post $post Le produit.
	 *
	 * @since 2.0.0
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

		$similar_products = array();

		if ( ! empty( $product->data['similar_products_id'] ) ) {
			$similar_products = Product::g()->get( array( 'post__in' => $product->data['similar_products_id'] ) );
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		\eoxia\View_Util::exec( 'wpshop', 'products', 'metabox/main', array(
			'id'               => ! empty( $product->data['id'] ) ? $product->data['id'] : $post->ID,
			'product'          => $product,
			'doli_url'         => $dolibarr_option['dolibarr_url'],
			'similar_products' => $similar_products,
			'sync_status'      => false,
		) );
	}

	/**
	 * Fonctions de recherche
	 *
	 * @since 2.0.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon
	 * renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre
	 * d'éléments trouvés.
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
