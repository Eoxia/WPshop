<?php
/**
 * La classe gérant les actions des produits.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Product Action Class.
 */
class Product_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 30 );
		add_action( 'save_post', array( $this, 'callback_save_post' ), 10, 2 );

		add_action( 'template_redirect', array( $this, 'init_product_archive_page' ) );
		add_action( 'template_redirect', array( $this, 'search_page' ) );

		add_action( 'wp_ajax_nopriv_get_product_by_external_id', array( $this, 'get_product_by_external_id' ) );
		add_action( 'wp_ajax_get_product_by_external_id', array( $this, 'get_product_by_external_id' ) );
	}

	/**
	 * Initialise la page "Product".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_admin_menu() {
//		$hook = add_submenu_page( 'wpshop', __( 'Products', 'wpshop' ), __( 'Products', 'wpshop' ), 'manage_options', 'wps-product', array( $this, 'callback_add_menu_page' ) );
//		if ( ! Settings::g()->dolibarr_is_active() ) {
//			add_submenu_page( 'wpshop', __( 'Add', 'wpshop' ), __( 'Add', 'wpshop' ), 'manage_options', 'post-new.php?post_type=wps-product' );
//		}
		if ( user_can( get_current_user_id(), 'manage_options' ) ) {
			CMH::register_menu( 'wpshop', __( 'Products', 'wpshop' ), __( 'Products', 'wpshop' ), 'manage_options', 'wps-product', array( $this, 'callback_add_menu_page' ), 'fas fa-cube', 3 );
		}
		if ( ! Settings::g()->dolibarr_is_active() ) {
//			add_submenu_page( 'wpshop', __( 'Add', 'wpshop' ), __( 'Add', 'wpshop' ), 'manage_options', 'post-new.php?post_type=wps-product' );
			CMH::register_menu( 'wpshop', __( 'Add', 'wpshop' ), __( 'Add', 'wpshop' ), 'manage_options', 'post-new.php?post_type=wps-product' );
		}
	}

	/**
	 * Appel la vue "main" du module "Product".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_menu_page() {
		$per_page = get_user_meta( get_current_user_id(), Product::g()->option_per_page, true );
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_create_product    = $dolibarr_option['dolibarr_create_product'];
		$dolibarr_url               = $dolibarr_option['dolibarr_url'];

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Third_Party::g()->limit;
		}

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$count = Product::g()->search( $s, array(), true );

		$number_page  = ceil( $count / $per_page );
		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$base_url = admin_url( 'admin.php?page=wps-product' );

		$begin_url = $base_url . '&current_page=1';
		$end_url   = $base_url . '&current_page=' . $number_page;

		$prev_url = $base_url . '&current_page=' . ( $current_page - 1 );
		$next_url = $base_url . '&current_page=' . ( $current_page + 1 );

		if ( ! empty( $s ) ) {
			$begin_url .= '&s=' . $s;
			$end_url   .= '&s=' . $s;
			$prev_url  .= '&s=' . $s;
			$next_url  .= '&s=' . $s;
		}

		View_Util::exec( 'wpshop', 'products', 'main', array(
			'number_page'  => $number_page,
			'current_page' => $current_page,
			'count'        => $count,
			'begin_url'    => $begin_url,
			'end_url'      => $end_url,
			'prev_url'     => $prev_url,
			'next_url'     => $next_url,
			's'            => $s,

			'dolibarr_create_product' => $dolibarr_create_product,
			'dolibarr_url'            => $dolibarr_url,
		) );
	}

	/**
	 * Ajoute le menu "Options de l'écran" dans la page produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_screen_option() {
		$args = array(
			'label'   => _x( 'Products', 'Product per page', 'wpshop' ),
			'default' => Product::g()->limit,
			'option'  => Product::g()->option_per_page,
		);

		add_screen_option( 'per_page', $args );
	}

	/**
	 * Enregistre les métadonnées d'un produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  integer $post_id L'ID du produit.
	 * @param  WP_Post $post    Les données du produit.
	 *
	 * @return integer|void
	 */
	public function callback_save_post( $post_id, $post ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( 'wps-product' !== $post->post_type && 'publish' !== $post->post_status ) {
			return $post_id;
		}

		$product = Product::g()->get( array( 'id' => $post_id ), true );

		if ( empty( $product ) || ( ! empty( $product ) && 0 === $product->data['id'] ) ) {
			return $post_id;
		}

		$action = ! empty( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';

		$product_data                         = ! empty( $_POST['product_data'] ) ? (array) $_POST['product_data'] : array();
		$product_data['price']                = isset( $product_data['price'] ) ? (float) round( str_replace( ',', '.', $product_data['price'] ), 2 ) : $product->data['price'];
		$product_data['tva_tx']               = ! empty( $product_data['tva_tx'] ) ? (float) round( str_replace( ',', '.', $product_data['tva_tx'] ), 2 ) : $product->data['tva_tx'];
		$product_data['manage_stock']         = ( ! empty( $product_data['manage_stock'] ) && 'true' == $product_data['manage_stock'] ) ? true : false;
		$product_data['stock']                = ! empty( $product_data['stock'] ) ? (int) $product_data['stock'] : $product->data['stock'];
		$product_data['product_downloadable'] = ( ! empty( $product_data['product_downloadable'] ) && 'true' === $product_data['product_downloadable'] ) ? true : false;
		$product_data['price_ttc']            = price2num( $product_data['price'] * ( 1 + ( $product_data['tva_tx'] / 100 ) ) );
		$product_data['similar_products_id']  = ! empty( $_POST['similar_products_id'] ) ? (array) $_POST['similar_products_id'] : array();
		$product_data['similar_products_id']  = array_map( 'intval', $product_data['similar_products_id'] );

		update_post_meta( $post_id, '_price', $product_data['price'] );
		update_post_meta( $post_id, '_tva_tx', $product_data['tva_tx'] );
		update_post_meta( $post_id, '_price_ttc', $product_data['price_ttc'] );
		update_post_meta( $post_id, '_tva_amount', ( $product_data['price_ttc'] - $product_data['price'] ) );
		update_post_meta( $post_id, '_manage_stock', $product_data['manage_stock'] );
		update_post_meta( $post_id, '_stock', $product_data['stock'] );
		update_post_meta( $post_id, '_product_downloadable', $product_data['product_downloadable'] );

		if ( 'quick_save' !== $action ) {
			update_post_meta( $post_id, '_similar_products_id', ! empty( $product_data['similar_products_id'] ) ? json_encode( $product_data['similar_products_id'] ) : null );
		}
	}

	/**
	 * Création d'une page spéciale pour afficher les archives.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function init_product_archive_page() {
		global $post;
		global $wp_query;

		$queried_object = get_queried_object();
		$shop_page_id   = get_option( 'wps_page_ids', Pages::g()->default_options );
		$shop_page      = get_post( $shop_page_id['shop_id'] );

		// Arrête la fonction si on n'est pas une sur une page catégorie de produit.
		if ( ! is_tax( get_object_taxonomies( 'wps-product-cat' ) ) ) {
			return;
		}

		// Récupère la Description de la catégorie.
		if ( ! empty( $queried_object->description ) ) {
			$archive_description = '<div class="wps-archive-description">' . $queried_object->description . '</div>';
		} else {
			$archive_description = '';
		}
		$archive_description = apply_filters( 'wps_archive_description', $archive_description, $queried_object );

		// Création de notre propre page.
		$dummy_post_properties = array(
			'ID'                    => 0,
			'post_status'           => 'publish',
			'post_author'           => $shop_page->post_author,
			'post_parent'           => 0,
			'post_type'             => 'page',
			'post_date'             => $shop_page->post_date,
			'post_date_gmt'         => $shop_page->post_date_gmt,
			'post_modified'         => $shop_page->post_modified,
			'post_modified_gmt'     => $shop_page->post_modified_gmt,
			'post_content'          => $archive_description . $this->generate_archive_page_content(),
			'post_title'            => $queried_object->name,
			'post_excerpt'          => '',
			'post_content_filtered' => '',
			'post_mime_type'        => '',
			'post_password'         => '',
			'post_name'             => $queried_object->slug,
			'guid'                  => '',
			'menu_order'            => 0,
			'pinged'                => '',
			'to_ping'               => '',
			'ping_status'           => '',
			'comment_status'        => 'closed',
			'comment_count'         => 0,
			'filter'                => 'raw',
		);

		$post = new \WP_Post( (object) $dummy_post_properties );

		$wp_query->post          = $post;
		$wp_query->posts         = array( $post );
		$wp_query->post_count    = 1;
		$wp_query->is_404        = false;
		$wp_query->is_page       = true;
		$wp_query->is_single     = true;
		$wp_query->is_archive    = false;
		$wp_query->is_tax        = true;
		$wp_query->max_num_pages = 0;

		setup_postdata( $post );
		remove_all_filters( 'the_content' );
		remove_all_filters( 'the_excerpt' );
		add_filter( 'template_include', array( $this, 'force_single_template_filter' ) );
	}

	/**
	 * Force l'affichage d'un template single.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $template Chemin du template.
	 *
	 * @return string
	 */
	public function force_single_template_filter( $template ) {
		$possible_templates = array(
			'page',
			'single',
			'singular',
			'index',
		);

		foreach ( $possible_templates as $possible_template ) {
			$path = get_query_template( $possible_template );
			if ( $path ) {
				return $path;
			}
		}

		return $template;
	}

	/**
	 * Génère le HTML de la page archive.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Output HTML content.
	 */
	public function generate_archive_page_content() {
		global $wp_query;
		global $post;

		$shipping_cost_option = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );
		if ( ! empty( $shipping_cost_option['shipping_product_id'] ) ) {
			$wp_query->query_vars['post__not_in'] = array( $shipping_cost_option['shipping_product_id'] );
		}

		$wp_query->query_vars['tax_query'] = array(
			array(
				'taxonomy'         => 'wps-product-cat',
				'field'            => 'slug',
				'terms'            => $wp_query->query_vars['wps-product-cat'],
				'include_children' => 0,
			),
		);

		$wps_query = new \WP_Query( $wp_query->query_vars );

		foreach ( $wps_query->posts as &$product ) {
			$product->price_ttc    = get_post_meta( $product->ID, '_price_ttc', true );
			$product->manage_stock = get_post_meta( $product->ID, '_manage_stock', true );
			$product->stock        = get_post_meta( $product->ID, '_stock', true );
		}

		setup_postdata( $post );

		$term               = get_term_by( 'slug', $wp_query->query_vars['wps-product-cat'], 'wps-product-cat' );
		$product_taxonomies = get_terms( array(
			'taxonomy'   => 'wps-product-cat',
			'parent'     => $term->term_id,
			'hide_empty' => false,
		) );

		ob_start();
		include( Template_Util::get_template_part( 'products', 'wps-product-taxonomy-container' ) );
		include( Template_Util::get_template_part( 'products', 'wps-product-grid-container' ) );
		$view = ob_get_clean();

		return $view;
	}

	/**
	 * Créer une page temporaire lors du recherche avec WPshop.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function search_page() {
		global $wp_query;
		global $post;

		$post_type = ! empty( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';

		if ( ! $wp_query->is_search() && Product::g()->get_type() !== $post_type ) {
			return;
		}

		$search_query = get_search_query();

		$queried_object = get_queried_object();
		$shop_page_id   = get_option( 'wps_page_ids', Pages::g()->default_options );
		$shop_page      = get_post( $shop_page_id['shop_id'] );

		// translators: Résultat de la recherche pour "%s".
		$title = sprintf( __( 'Search result for "%s"', 'wpshop' ), $search_query );

		$wps_query = new \WP_Query( array_merge( $wp_query->query_vars, array( 'post_parent' => 0 ) ) );

		foreach ( $wps_query->posts as &$product ) {
			$product->price_ttc    = get_post_meta( $product->ID, '_price_ttc', true );
			$product->manage_stock = get_post_meta( $product->ID, '_manage_stock', true );
			$product->stock        = get_post_meta( $product->ID, '_stock', true );
		}

		setup_postdata( $post );

		ob_start();
		include( Template_Util::get_template_part( 'products', 'wps-product-grid-container' ) );
		$content = ob_get_clean();

		// Création de notre propre page.
		$dummy_post_properties = array(
			'ID'                    => 0,
			'post_status'           => 'publish',
			'post_author'           => $shop_page->post_author,
			'post_parent'           => 0,
			'post_type'             => 'page',
			'post_date'             => $shop_page->post_date,
			'post_date_gmt'         => $shop_page->post_date_gmt,
			'post_modified'         => $shop_page->post_modified,
			'post_modified_gmt'     => $shop_page->post_modified_gmt,
			'post_content'          => $content,
			'post_title'            => $title,
			'post_excerpt'          => '',
			'post_content_filtered' => '',
			'post_mime_type'        => '',
			'post_password'         => '',
			'post_name'             => sanitize_title( $title ),
			'guid'                  => '',
			'menu_order'            => 0,
			'pinged'                => '',
			'to_ping'               => '',
			'ping_status'           => '',
			'comment_status'        => 'closed',
			'comment_count'         => 0,
			'filter'                => 'raw',
		);

		$post = new \WP_Post( (object) $dummy_post_properties );

		$wp_query->post          = $post;
		$wp_query->posts         = array( $post );
		$wp_query->post_count    = 1;
		$wp_query->is_404        = false;
		$wp_query->is_page       = false;
		$wp_query->is_single     = true;
		$wp_query->is_seach      = true;
		$wp_query->is_archive    = false;
		$wp_query->is_tax        = false;
		$wp_query->max_num_pages = 0;

		setup_postdata( $post );
		remove_all_filters( 'the_content' );
		remove_all_filters( 'the_excerpt' );
		add_filter( 'template_include', array( $this, 'force_single_template_filter' ) );

	}

	/**
	 * Récupère le produit par l'external id.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function get_product_by_external_id() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $id,
		), true );

		wp_send_json_success( array( 'product' => $product, ) );
	}
}

new Product_Action();
