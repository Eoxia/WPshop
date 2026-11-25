<?php
/**
 * La classe gérant les fonctions principales du panier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Cart Class.
 */
class Cart extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {

		add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );

	}

	/**
	 * Enregistre l'API REST.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function register_rest_api() {
		if ( ! Settings::g()->dolibarr_is_active() ) {
			return;
		}

		register_rest_route(
			'wp-shop/v1',
			'/cart/',
			[
				'methods'  => 'GET',
				'callback' => [$this, 'get_cart' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'wp-shop/v1',
			'/cart/checkout/link',
			[
				'methods'  => 'GET',
				'callback' => [$this, 'get_chekout_link' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'wp-shop/v1',
			'/cart/(?P<id>\d+)',
			[
				'methods'  => 'DELETE',
				'callback' => [$this, 'delete_cart_item' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'wp-shop/v1',
			'/cart/(?P<id>\d+)/increment',
			[
				'methods'  => 'POST',
				'callback' => [$this, 'increment_cart_item' ],
				'permission_callback' => '__return_true',
			]
		);
		register_rest_route(
			'wp-shop/v1',
			'/cart/(?P<id>\d+)/decrement',
			[
				'methods'  => 'POST',
				'callback' => [$this, 'decrement_cart_item' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Récupère le contenu du panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return array Le contenu du panier.
	 */
	/**
	 * Session key of the products removed from the cart because they are no longer
	 * available, until the cart page shows them.
	 *
	 * @since 2.7.0
	 */
	const UNAVAILABLE_SESSION_KEY = 'wps_unavailable_products';

	/**
	 * Removes from the cart the products that can no longer be bought (deleted from Dolibarr),
	 * and keeps their titles for the cart page message.
	 *
	 * @since 2.7.0
	 *
	 * @return string[] Titles of the products removed now.
	 */
	public function remove_unavailable_products() {
		$lines = (array) Cart_Session::g()->cart_contents;

		if ( empty( $lines ) || ! Settings::g()->dolibarr_is_active() ) {
			return array();
		}

		// One Dolibarr request for all the lines whose state is not cached.
		$doli_ids = array();
		foreach ( $lines as $line ) {
			if ( ! empty( $line['id'] ) && false === get_transient( 'wps_doli_product_state_' . (int) $line['id'] ) ) {
				$doli_ids[] = (int) get_post_meta( (int) $line['id'], '_external_id', true );
			}
		}
		Doli_Products::g()->prefetch_existence( $doli_ids );

		$titles = array();
		foreach ( $lines as $line ) {
			if ( ! empty( $line['id'] ) && ! Doli_Products::g()->is_purchasable( $line['id'] ) ) {
				$titles[] = ! empty( $line['title'] ) ? $line['title'] : get_the_title( $line['id'] );
				Cart_Session::g()->remove_product( $line['id'] );
			}
		}

		if ( ! empty( $titles ) ) {
			// Totals of the legacy views and of the checkout.
			do_action( 'wps_calculate_totals' );
		}

		if ( ! empty( $titles ) && isset( $_SESSION ) ) {
			$pending = isset( $_SESSION[ self::UNAVAILABLE_SESSION_KEY ] ) ? (array) $_SESSION[ self::UNAVAILABLE_SESSION_KEY ] : array();

			$_SESSION[ self::UNAVAILABLE_SESSION_KEY ] = array_values( array_unique( array_merge( $pending, $titles ) ) );
		}

		return $titles;
	}

	/**
	 * Message about the products removed from the cart since the last display, then forgets them.
	 *
	 * @since 2.7.0
	 *
	 * @return string Translated message, empty when nothing was removed.
	 */
	public function take_unavailable_message() {
		if ( empty( $_SESSION[ self::UNAVAILABLE_SESSION_KEY ] ) ) {
			return '';
		}

		$titles = (array) $_SESSION[ self::UNAVAILABLE_SESSION_KEY ];
		unset( $_SESSION[ self::UNAVAILABLE_SESSION_KEY ] );

		return sprintf(
			/* translators: %s: product titles, comma separated. */
			_n(
				'This product is no longer available and has been removed from your cart: %s. Please check your cart before ordering.',
				'These products are no longer available and have been removed from your cart: %s. Please check your cart before ordering.',
				count( $titles ),
				'wpshop'
			),
			implode( ', ', $titles )
		);
	}

	public function get_cart() {
		// Products deleted from Dolibarr since they were added: removed (the cart page tells which).
		$this->remove_unavailable_products();

		$cart            = [];
		$products        = Cart_Session::g()->cart_contents;
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		if ( empty( $products ) ) {
			return [];
		}
		$cart['products'] = [];
		foreach ( $products as $product ) {
			$cart['products'][] = array(
				'id'            => $product['id'],
				'title'         => $product['title'],
				'qty'           => $product['qty'],
				'price'         => $product['price'],
				'price_ttc'     => $product['price_ttc'],
				'thumbnail_url' => $product['thumbnail_url'],
				'url'           => get_permalink( $product['id'] ),
			);
		}

		$total     = 0;
		$total_ttc = 0;
		foreach ( $products as $product ) {
			$total     += $product['price'] * $product['qty'];
			$total_ttc += $product['price_ttc'] * $product['qty'];	
		}

		$cart['total']     = $total;
		$cart['taxes']     = $total_ttc - $total;
		$cart['total_ttc'] = $total_ttc;

		return rest_ensure_response( $cart );
	}

	public function delete_cart_item( $data ) {
		$id = $data['id'];
		Cart_Session::g()->remove_product( $id );

		return rest_ensure_response( $this->get_cart() );
	}

	public function increment_cart_item( $data ) {
		$id = (int) $data['id'];
		$qty = (int) $data['qty'];

		// Not a product, or deleted from Dolibarr: it could not be ordered.
		if ( Product::g()->get_type() !== get_post_type( $id ) || ! Doli_Products::g()->is_purchasable( $id ) ) {
			return new \WP_Error( 'wps_product_unavailable', __( 'This product is no longer available.', 'wpshop' ), array( 'status' => 409 ) );
		}

		Cart_Session::g()->increment_product( $id, $qty );

		return rest_ensure_response( $this->get_cart() );
	}

	public function decrement_cart_item( $data ) {
		$id = (int) $data['id'];
		Cart_Session::g()->decrement_product( $id );

		return rest_ensure_response( $this->get_cart() );
	}

	public function get_chekout_link() {
		$checkout_url = Pages::g()->get_checkout_link();
		if ( empty( $checkout_url ) ) {
			return rest_ensure_response( [ 'link' => null ] );
		}

		return rest_ensure_response( ['link' => $checkout_url] );
	}

	/**
	 * Récupère tous les produits.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return array La liste des produits.
	 */
	public function get_all_products() {
		// Fetch products from the database or from Dolibarr
		$products = array();
		
		// If there's a Product class in the plugin
		if ( class_exists( '\wpshop\Product' ) ) {
			$products = Product::g()->get_all();
		}
		
		return rest_ensure_response( $products );
	}

	/**
	 * Vérifie si un produit peut être ajouté.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return boolean True si tout s'est bien passé.
	 */
	public function can_add_product() {
		if ( ! Settings::g()->dolibarr_is_active() ) {
			return false;
		}

		return true;
	}

	/**
	 * Ajoute un produit dans le panier.
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 *
	 * @param  Product  $product Les données du produit.
	 * @param  integer  $qty     La quantité à ajouter.
	 *
	 * @return boolean           True si tout s'est bien passé.
	 */
	public function add_to_cart( $product, $qty = 1, $desc = '' ) {
		if ( ! $this->can_add_product() ) {
			return;
		}

		$data = array_merge(
			array( 'qty' => $qty ),
			$product->data
		);

		$index = -1;

		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach ( Cart_Session::g()->cart_contents as $key => $line ) {
				$data['content'] = $desc;
				if ( $line['id'] === $product->data['id'] ) {
					$data['qty'] = $line['qty'] + $qty;
					$index       = $key;
					break;
				}
			}
		}

		$can_add = apply_filters( 'wps_add_to_cart_product', true, $product );

		if ( $can_add ) {
			if ( $index === -1 ) {
				$data['content'] = $desc;
				Cart_Session::g()->add_product( $data );
			} else {
				Cart_Session::g()->update_product( $index, $data );
			}
		}

		do_action( 'wps_add_to_cart' );
		do_action( 'wps_before_calculate_totals' );
		do_action( 'wps_calculate_totals' );
		do_action( 'wps_after_calculate_totals' );

		return $can_add;
	}

	/**
	 * Met à jour le contenu du panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Product $product Les données du produit.
	 */
	public function update_cart( $product ) {
		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach ( Cart_Session::g()->cart_contents as $key => $line ) {
				if ( (int) $line['id'] === (int) $product['id'] ) {
					$line['qty'] = $product['qty'];
					Cart_Session::g()->update_product( $key, $line );
				}
			}
		}

		do_action( 'wps_update_cart' );
		do_action( 'wps_before_calculate_totals' );
		do_action( 'wps_calculate_totals' );
		do_action( 'wps_after_calculate_totals' );
	}

	/**
	 * Supprime un produit du panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param integer $key La clé du produit dans le tableau.
	 */
	public function delete_product( $key ) {
		Cart_Session::g()->remove_product_by_key( $key );

		do_action( 'wps_delete_to_cart', $key );
		do_action( 'wps_before_calculate_totals' );
		do_action( 'wps_calculate_totals' );
		do_action( 'wps_after_calculate_totals' );
	}

	/**
	 * Affiche le résumé du panier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param integer $total_price_no_shipping Prix total sans frais de livraison.
	 * @param integer $tva_amount              Montant de la TVA.
	 * @param integer $total_price_ttc         Prix total TTC.
	 * @param integer $shipping_cost           Frais de livraison.
	 */
	public function display_cart_resume( $total_price_no_shipping, $tva_amount, $total_price_ttc ) {
		include( Template_Util::get_template_part( 'cart', 'cart-resume' ) );
	}

	/**
	 * Vérifie le stock au moment du passage de la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return array is_valid à false si le produit n'est plus disponible.
	 */
	public function check_stock() {
		$stock_statut = array(
			'is_valid' => true,
			'errors'   => array(),
		);

		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach ( Cart_Session::g()->cart_contents as $product ) {
				if ( ! $product['manage_stock'] ) {
					continue;
				}

				if ( $product['stock'] < $product['qty'] ) {
					$stock_statut['is_valid'] = false;
					// translators: Product A is sold out.
					$stock_statut['errors'][] = sprintf( __( '%s is sold out.', 'wpshop' ), $product['title'] );
				}
			}
		}

		return $stock_statut;
	}

	/**
	 * Pour chaque produit, décremente son stock selon la quantité de celui-ci dans la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function decrease_stock() {
		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach ( Cart_Session::g()->cart_contents as $product ) {
				if ( ! $product['manage_stock'] ) {
					continue;
				}

				$product['stock'] -= $product['qty'];

				Product::g()->update( $product );
			}
		}
	}
}

Cart::g();
