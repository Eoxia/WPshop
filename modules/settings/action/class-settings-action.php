<?php
/**
 * La classe gérant les actions des réglages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.4.0
 */

namespace wpshop;

use eoxia\View_Util;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Settings Action Class.
 */
class Settings_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 80 );
		add_action( 'admin_notices', array( $this, 'notice_activate_erp' ) );

		add_action( 'admin_post_wps_load_settings_tab', array( $this, 'callback_load_tab' ) );

		add_action( 'admin_post_wps_update_general_settings', array( $this, 'callback_update_general_settings' ) );
		add_action( 'admin_post_wps_update_pages_settings', array( $this, 'callback_update_pages_settings' ) );
		add_action( 'admin_post_wps_update_erp_settings', array( $this, 'callback_update_erp_settings' ) );

		add_action( 'wp_ajax_wps_hide_notice_erp', array( $this, 'dismiss_notice_erp' ) );

		add_action( 'init', array( $this, 'callback_add_product_thumbnail_size' ) );
	}

	/**
	 * Initialise la page "Réglages".
	 *
	 * @since   2.0.0
	 * @version 2.4.0
	 */
	public function callback_admin_menu() {
		//add_submenu_page( 'wpshop', __( 'Settings', 'wpshop' ), __( 'Settings', 'wpshop' ), 'manage_options', 'wps-settings', array( $this, 'callback_add_menu_page' ) );
		if ( user_can( get_current_user_id(), 'manage_options' ) ) {
			CMH::register_menu( 'wpshop', __( 'Settings', 'wpshop' ), __( 'Settings', 'wpshop' ), 'manage_options', 'wps-settings', array( $this, 'callback_add_menu_page' ), 'fas fa-cog', 9 );
		}
	}

	/**
	 * Call notice activate erp view if dolibarr url and secret is empty.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function notice_activate_erp() {
		$user = wp_get_current_user();

		if ( in_array( 'administrator', (array) $user->roles ) ) {
			$dolibarr_option = get_option('wps_dolibarr', Settings::g()->default_settings);

			if ( ! empty( $dolibarr_option['error'] ) && $dolibarr_option['notice'] && $dolibarr_option['notice']['error_erp'] ) {
				View_Util::exec( 'wpshop', 'settings', 'notice-error-erp', array('error' => $dolibarr_option['error']  ) );
			} elseif ( ( empty($dolibarr_option['dolibarr_url'] ) || empty( $dolibarr_option['dolibarr_secret'] ) ) && ( $dolibarr_option['notice'] && $dolibarr_option['notice']['activate_erp'] ) ) {
				View_Util::exec('wpshop', 'settings', 'notice-activate-erp' );
			}
		}
	}

	/**
	 * Appel la vue "main" du module "Réglages".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_menu_page() {
		$tab     = ! empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
		$section = ! empty( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : '';

		$transient = get_transient( 'updated_wpshop_option_' . get_current_user_id() );
		delete_transient( 'updated_wpshop_option_' . get_current_user_id() );

		View_Util::exec( 'wpshop', 'settings', 'main', array(
			'tab'       => $tab,
			'section'   => $section,
			'transient' => $transient,
		) );
	}

	/**
	 * Redirige vers le bon onglet dans la page réglages.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_load_tab() {
		check_admin_referer( 'callback_load_tab' );

		$tab     = ! empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
		$section = ! empty( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : '';

		$url = 'admin.php?page=wps-settings&tab= ' . $tab;

		if ( ! empty( $section ) ) {
			$url .= '&section=' . $section;
		}

		wp_redirect( admin_url( $url ) );
	}

	/**
	 * Met à jour les options général.
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 */
	public function callback_update_general_settings() {
		check_admin_referer( 'callback_update_general_settings' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$tab                      = ! empty( $_POST['tab'] ) ? sanitize_text_field( $_POST['tab'] ) : 'general';
		$shop_email               = ! empty( $_POST['shop_email'] ) ? sanitize_text_field( $_POST['shop_email'] ) : '';
		$thumbnail_size           = ! empty( $_POST['thumbnail_size'] ) ? (array) $_POST['thumbnail_size'] : array();
		$thumbnail_size['width']  = ! empty( $thumbnail_size['width'] ) ? (int) $thumbnail_size['width'] : 0;
		$thumbnail_size['height'] = ! empty( $thumbnail_size['height'] ) ? (int) $thumbnail_size['height'] : 0;
		$split_product            = isset( $_POST['split_product'] ) && 'on' == $_POST['split_product'] ? true : false;
		$price_min                = ! empty( $_POST['price_min'] ) ? (int) $_POST['price_min'] : 0;

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_option['shop_email']               = $shop_email;
		$dolibarr_option['thumbnail_size']['width']  = $thumbnail_size['width'];
		$dolibarr_option['thumbnail_size']['height'] = $thumbnail_size['height'];
		$dolibarr_option['split_product']            = $split_product;
		$dolibarr_option['price_min']                = $price_min;

		update_option( 'wps_dolibarr', $dolibarr_option );

		$response = Request_Util::get( 'status' );
		if ( false === $response ) {
			$dolibarr_option['error'] = __( 'WPshop cannot connect to dolibarr. Please check your settings', 'wpshop' );
		} else {
			$dolibarr_option['error'] = '';
		}

		update_option( 'wps_dolibarr', $dolibarr_option );

		set_transient( 'updated_wpshop_option_' . get_current_user_id(), __( 'Your settings have been saved.', 'wpshop' ), 30 );

		wp_redirect( admin_url( 'admin.php?page=wps-settings&tab= ' . $tab ) );
	}

	/**
	 * Met à jour les options "pages".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_update_pages_settings() {
		check_admin_referer( 'callback_update_pages_settings' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$tab                                 = ! empty( $_POST['tab'] ) ? sanitize_text_field( $_POST['tab'] ) : 'general';
		$wps_page_shop_id                    = ! empty( $_POST['wps_page_shop_id'] ) ? (int) $_POST['wps_page_shop_id'] : 0;
		$wps_page_cart_id                    = ! empty( $_POST['wps_page_cart_id'] ) ? (int) $_POST['wps_page_cart_id'] : 0;
		$wps_page_checkout_id                = ! empty( $_POST['wps_page_checkout_id'] ) ? (int) $_POST['wps_page_checkout_id'] : 0;
		$wps_page_my_account_id              = ! empty( $_POST['wps_page_my_account_id'] ) ? (int) $_POST['wps_page_my_account_id'] : 0;
		$wps_page_general_conditions_of_sale = ! empty( $_POST['wps_page_general_conditions_of_sale'] ) ? (int) $_POST['wps_page_general_conditions_of_sale'] : 0;

		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );

		$page_ids_options = Pages::g()->default_options;

		$page_ids_options['shop_id']                    = $wps_page_shop_id;
		$page_ids_options['cart_id']                    = $wps_page_cart_id;
		$page_ids_options['checkout_id']                = $wps_page_checkout_id;
		$page_ids_options['my_account_id']              = $wps_page_my_account_id;
		$page_ids_options['general_conditions_of_sale'] = $wps_page_general_conditions_of_sale;

		update_option( 'wps_page_ids', $page_ids_options );

		set_transient( 'updated_wpshop_option_' . get_current_user_id(), __( 'Your settings have been saved.', 'wpshop' ), 30 );

		wp_redirect( admin_url( 'admin.php?page=wps-settings&tab= ' . $tab ) );
	}

	/**
	 * Met à jour les options erp.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_update_erp_settings() {
		check_admin_referer( 'callback_update_erp_settings' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$tab                 = ! empty( $_POST['tab'] ) ? sanitize_text_field( $_POST['tab'] ) : 'general';
		$dolibarr_url        = ! empty( $_POST['dolibarr_url'] ) ? sanitize_text_field( $_POST['dolibarr_url'] ) : '';
		$dolibarr_secret     = ! empty( $_POST['dolibarr_secret'] ) ? sanitize_text_field( $_POST['dolibarr_secret'] ) : '';

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_option['dolibarr_url']        = $dolibarr_url;
		$dolibarr_option['dolibarr_secret']     = $dolibarr_secret;

		update_option( 'wps_dolibarr', $dolibarr_option );

		$response = Request_Util::get( 'status' );
		if ( false === $response ) {
			$dolibarr_option['error'] = __( 'WPshop cannot connect to dolibarr. Please check your settings', 'wpshop' );
		} else {
			$dolibarr_option['error'] = '';
		}

		update_option( 'wps_dolibarr', $dolibarr_option );

		set_transient( 'updated_wpshop_option_' . get_current_user_id(), __( 'Your settings have been saved.', 'wpshop' ), 30 );

		wp_redirect( admin_url( 'admin.php?page=wps-settings&tab= ' . $tab ) );
	}

	/**
	 * Ajoute la taille des images des produits.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_product_thumbnail_size() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		if ( ! empty( $dolibarr_option['thumbnail_size']['width'] ) && ! empty( $dolibarr_option['thumbnail_size']['height'] ) ) {
			add_image_size( 'wps-product-thumbnail', $dolibarr_option['thumbnail_size']['width'], $dolibarr_option['thumbnail_size']['height'], true );
		}
	}

	/**
	 * Chache la notice concernant l'ERP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function dismiss_notice_erp() {
		check_ajax_referer( 'wps_hide_notice_erp' );

		$type = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		if ( empty( $type ) ) {
			wp_send_json_error();
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_option['notice'][ $type ] = false;

		update_option( 'wps_dolibarr', $dolibarr_option );

		wp_send_json_success( array(
			'namespace'        => 'wpshop',
			'module'           => 'settings',
			'callback_success' => 'dismiss',
		) );
	}
}

new Settings_Action();
