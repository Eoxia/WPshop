<?php
/**
 * La classe gérant les actions du panier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.5.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Cart Action Class.
 */
class Cart_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( Cart_Shortcode::g(), 'callback_init' ), 5 );

		add_action( 'wps_calculate_totals', array( $this, 'callback_calculate_totals' ) );

		add_action( 'wp_ajax_nopriv_add_to_cart', array( $this, 'callback_add_to_cart' ) );
		add_action( 'wp_ajax_add_to_cart', array( $this, 'callback_add_to_cart' ) );

		add_action( 'wp_ajax_nopriv_wps_update_cart', array( $this, 'callback_update_cart' ) );
		add_action( 'wp_ajax_wps_update_cart', array( $this, 'callback_update_cart' ) );

		add_action( 'wp_ajax_nopriv_delete_product_from_cart', array( $this, 'callback_delete_product_from_cart' ) );
		add_action( 'wp_ajax_delete_product_from_cart', array( $this, 'callback_delete_product_from_cart' ) );

		add_action( 'wp_logout', array( $this, 'clear_cart' ) );
	}

	/**
	 * Calcul le total du panier.
	 *
	 * @Todo bouger dans class-cart.php
	 *
	 * @since   2.0.0
	 * @version 2.5.0
	 */
	public function callback_calculate_totals() {
		$shipping_cost_option = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$price             = 0;
		$price_no_shipping = 0;
		$price_ttc         = 0;
		$tva_amount        = 0;

		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach ( Cart_Session::g()->cart_contents as $key => $line ) {
				if ( $dolibarr_option['price_min'] > ( $line['price_ttc'] * $line['qty'] ) ) {
					$price     += $dolibarr_option['price_min'] - ( $dolibarr_option['price_min'] * $line['tva_tx'] / 100 );
					$price_ttc += $dolibarr_option['price_min'];
				} else {
					$price     += $line['price'] * $line['qty'];
					$price_ttc += $line['price_ttc'] * $line['qty'];
				}

				if ( $shipping_cost_option['shipping_product_id'] !== $line['id'] ) {
					if ( $dolibarr_option['price_min'] > ( $line['price_ttc'] * $line['qty'] ) ) {
						$tva_amount += $dolibarr_option['price_min'] * $line['tva_tx'] / 100;
						$price_no_shipping = $price;
					} else {
						$tva_amount  += $line['tva_amount'] * $line['qty'];
						$price_no_shipping = $price;
					}
				}
			}
		}

		Cart_Session::g()->update( 'tva_amount', $tva_amount );
		Cart_Session::g()->update( 'total_price', $price );
		Cart_Session::g()->update( 'total_price_no_shipping', $price_no_shipping );
		Cart_Session::g()->update( 'total_price_ttc', $price_ttc );
	}

	/**
	 * Action pour ajouter un produit dans le panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_to_cart() {
		check_ajax_referer( 'add_to_cart' );

		$id        = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$qty       = ! empty( $_POST['qty'] ) ? (float) $_POST['qty'] : 1;
		$desc      = ! empty( $_POST['desc'] ) ? sanitize_text_field( $_POST['desc'] ) : '';

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$product = Product::g()->get( array( 'id' => $id ), true );

		$added = Cart::g()->add_to_cart( $product, $qty, $desc );

		ob_start();
		include( Template_Util::get_template_part( 'cart', 'link-cart' ) );
		wp_send_json_success( array(
			'namespace'        => 'wpshopFrontend',
			'module'           => 'cart',
			'callback_success' => 'addedToCart',
			'view'             => ob_get_clean(),
			'qty'              => Cart_Session::g()->qty,
			'added'            => $added,
		) );
	}

	/**
	 * Action pour mettre à jour le panier.
	 *
	 * @todo Validate Data in $_POST['products']
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_update_cart() {
		check_ajax_referer( 'ajax_update_cart' );

		$products = ! empty( $_POST['products'] ) ? (array) $_POST['products'] : array();

		if ( empty( $products ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $products ) ) {
			foreach ( $products as $key => $product ) {
				if ( isset ( $product['qty'] ) ) {
					$product['qty'] = (int) $product['qty'];

					if ( $product['qty'] <= 0 ) {
						Cart::g()->delete_product( $key );
					} else {
						Cart::g()->update_cart( $product );
					}
				}
			}
		}

		ob_start();
		echo do_shortcode( '[wps_cart]' );
		wp_send_json_success( array(
			'namespace'        => 'wpshopFrontend',
			'module'           => 'cart',
			'callback_success' => 'updatedCart',
			'view'             => ob_get_clean(),
			'qty'              => Cart_Session::g()->qty,
		) );
	}

	/**
	 * Action pour supprimer un produit du panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_delete_product_from_cart() {
		check_ajax_referer( 'ajax_delete_product_from_cart' );

		$key = isset( $_POST['key'] ) ? (int) $_POST['key'] : -1;

		if ( -1 !== $key ) {
			Cart::g()->delete_product( $key );
		}

		ob_start();
		echo do_shortcode( '[wps_cart]' );
		wp_send_json_success( array(
			'namespace'        => 'wpshopFrontend',
			'module'           => 'cart',
			'callback_success' => 'deletedProdutFromCart',
			'view'             => ob_get_clean(),
			'qty'              => null === Cart_Session::g()->qty ? 0 : Cart_Session::g()->qty,
		) );
	}

	/**
	 * Action pour vider le panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function clear_cart() {
		Cart_Session::g()->destroy();
	}
}

new Cart_Action();
