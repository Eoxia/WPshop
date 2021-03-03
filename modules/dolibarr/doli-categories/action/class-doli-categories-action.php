<?php
/**
 * La classe gérant les actions des catégories de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\View_Util;
use stdClass;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Category Action Class.
 */
class Doli_Category_Action {

	/**
	 * Définition des metaboxes sur la page.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var array
	 */
	public $metaboxes = null;

	/**
	 * Le constructeur.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 60 );

		add_action( 'wps_checkout_create_category', array( $this, 'create_category' ), 10, 1 );
		add_action( 'admin_post_wps_download_category', array( $this, 'download_category' ) );

	}

	/**
	 * Initialise la page "Catégories".
	 *
	* @since     2.1.0
	* @version   2.1.0
	 */
	public function callback_admin_menu() {
		if ( Settings::g()->dolibarr_is_active() ) {
//			$hook = add_submenu_page( 'wpshop', __( 'Categories WPshop', 'wpshop' ), __( 'Categories WPshop', 'wpshop' ), 'manage_options', 'wps-product-cat', array( $this, 'callback_add_menu_page' ) );
//
//			if ( ! isset( $_GET['id'] ) ) {
//				add_action( 'load-' . $hook, array( $this, 'callback_add_screen_option' ) );
//			}

			if ( user_can( get_current_user_id(), 'manage_options' ) ) {
				CMH::register_menu( 'wpshop', __( 'Categories WPshop', 'wpshop' ), __( 'Categories WPshop', 'wpshop' ), 'manage_options', 'wps-product-cat', array( $this, 'callback_add_menu_page' ), 'fas fa-tag', 7 );
			}
		}
	}

	/**
	 * Affichage de la vue du menu.
	 *
	 * @since     2.1.0
 	 * @version   2.1.0
	 */
	public function callback_add_menu_page() {
		global $wpdb;
		if ( isset( $_GET['id'] ) ) {
			// Single page.
			$id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;

			$doli_category = Request_Util::get( 'categories/' . $id );
			$wp_category   = Doli_Category::g()->get( array( 'schema' => true ), true );
			$wp_category   = Doli_Category::g()->doli_to_wp( $doli_category, $wp_category);

			$wp_category->data['datec'] = \eoxia\Date_Util::g()->fill_date( $wp_category->data['datec'] );

			$third_party = Third_Party::g()->get( array( 'id' => $wp_category->data['parent_id'] ), true );

			View_Util::exec( 'wpshop', 'doli-categories', 'single', array(
				'third_party'    => $third_party,
				'category'       => $wp_category,
			) );
		} else {
			// Listing page.
			// @todo: Doublon avec Class Doli Category display() ?
			$per_page = get_user_meta( get_current_user_id(), Doli_Category::g()->option_per_page, true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
			$dolibarr_url    = $dolibarr_option['dolibarr_url'];

			$dolibarr_create_category    = 'categories/card.php?action=create&type=product&backtopage=%2Fdolibarr%2Fhtdocs%2Fcategories%2Findex.php%3Ftype%3Dproduct';

			if ( empty( $per_page ) || 1 > $per_page ) {
				$per_page = Doli_Category::g()->limit;
			}

			$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

			$count        = Doli_Category::g()->search( $s, array(), true );
			$number_page  = ceil( $count / $per_page );
			$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

			$base_url = admin_url( 'admin.php?page=wps-product-cat' );

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

			//$wp_categories = Doli_Category::g()->get();

//			if ( ! empty($wp_categories)) {
//				foreach( $wp_categories as $wp_category) {
//					if (empty($wp_category->data['external_id'] )) {
//						wp_delete_term($wp_category->data['id'],'wps-product-cat');
//					}
//				}
//			}

			View_Util::exec( 'wpshop', 'doli-categories', 'main', array(
				'number_page'  => $number_page,
				'current_page' => $current_page,
				'count'        => $count,
				'begin_url'    => $begin_url,
				'end_url'      => $end_url,
				'prev_url'     => $prev_url,
				'next_url'     => $next_url,
				's'            => $s,
				//'wp_categories'=> $wp_categories,

				'dolibarr_create_category' => $dolibarr_create_category,
				'dolibarr_url'    => $dolibarr_url,
			) );
		}
	}

	/**
	 * Ajoute le menu "Options de l'écran".
	 *
	 * @since     2.1.0
	 * @version   2.1.0
	 */
	public function callback_add_screen_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => _x( 'Categories', 'Category per page', 'wpshop' ),
				'default' => Doli_Category::g()->limit,
				'option'  => Doli_Category::g()->option_per_page,
			)
		);
	}
}

new Doli_Category_Action();
