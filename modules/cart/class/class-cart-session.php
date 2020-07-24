<?php
/**
 * Les fonctions principales de la session du panier.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Cart Session Class.
 */
class Cart_Session extends Singleton_Util {

	/**
	 * Les données externe.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $external_data = array();

	/**
	 * Un tableau de produit.
	 *
	 * @var array
	 */
	public $cart_contents = array();

	/**
	 * Le prix total HT.
	 *
	 * @since 2.0.0
	 *
	 * @var float
	 */
	public $total_price;

	/**
	 * Le prix total HT sans frais de port.
	 *
	 * @since 2.0.0
	 *
	 * @var float
	 */
	public $total_price_no_shipping;

	/**
	 * Le prix total TTC.
	 *
	 * @since 2.0.0
	 *
	 * @var float
	 */
	public $total_price_ttc;

	/**
	 * Le total TVA.
	 *
	 * @since 2.0.0
	 *
	 * @var float
	 */
	public $tva_amount;

	/**
	 * L'ID du devis.
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $proposal_id;

	/**
	 * L'ID de la commande.
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $order_id;

	/**
	 * Quantité de produit dans la commande.
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $qty;

	/**
	 * Prix du frais de livaison;
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $shipping_cost;

	/**
	 * Le constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		$this->cart_contents           = isset( $_SESSION['wps_cart'] ) ? $_SESSION['wps_cart'] : array();
		$this->total_price             = isset( $_SESSION['wps_total_price'] ) ? $_SESSION['wps_total_price'] : null;
		$this->total_price_no_shipping = isset( $_SESSION['wps_total_price_no_shipping'] ) ? $_SESSION['wps_total_price_no_shipping'] : null;
		$this->total_price_ttc         = isset( $_SESSION['wps_total_price_ttc'] ) ? $_SESSION['wps_total_price_ttc'] : null;
		$this->tva_amount              = isset( $_SESSION['wps_tva_amount'] ) ? $_SESSION['wps_tva_amount'] : null;
		$this->proposal_id             = isset( $_SESSION['wps_proposal_id'] ) ? $_SESSION['wps_proposal_id'] : null;
		$this->order_id                = isset( $_SESSION['wps_order_id'] ) ? $_SESSION['wps_order_id'] : null;
		$this->qty                     = isset( $_SESSION['wps_qty'] ) ? $_SESSION['wps_qty'] : null;
		$this->shipping_cost           = isset( $_SESSION['wps_shipping_cost'] ) ? $_SESSION['wps_shipping_cost'] : null;
		$this->external_data           = isset( $_SESSION['wps_external_data'] ) ? $_SESSION['wps_external_data'] : array();
	}

	/**
	 * Ajoute une donnée externe.
	 *
	 * @since 2.0.0
	 *
	 * @param string $property Le nom.
	 * @param mixed  $value    La valeur.
	 */
	public function add_external_data( $property, $value ) {
		$this->external_data[ $property ] = $value;
		$this->update_session();
	}

	/**
	 * Met à jour la SESSION.
	 *
	 * @since 2.0.0
	 */
	public function update_session() {
		$shipping_cost_option = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );

		$this->qty = 0;

		if ( ! empty( $this->cart_contents ) ) {
			foreach ( $this->cart_contents as $key => $product ) {
				if ( isset ( $product['qty'] ) ) {
					if ( empty( $shipping_cost_option['shipping_product_id'] ) ||
						( ! empty( $shipping_cost_option['shipping_product_id'] ) && $shipping_cost_option['shipping_product_id'] !== $product['id'] ) ) {
						$product['qty'] = (int) $product['qty'];
						$this->qty     += $product['qty'];
					}
				}
			}
		}

		$_SESSION['wps_cart']                    = $this->cart_contents;
		$_SESSION['wps_total_price']             = $this->total_price;
		$_SESSION['wps_total_price_no_shipping'] = $this->total_price_no_shipping;
		$_SESSION['wps_total_price_ttc']         = $this->total_price_ttc;
		$_SESSION['wps_tva_amount']              = $this->tva_amount;
		$_SESSION['wps_proposal_id']             = $this->proposal_id;
		$_SESSION['wps_order_id']                = $this->order_id;
		$_SESSION['wps_qty']                     = $this->qty;
		$_SESSION['wps_shipping_cost']           = $this->shipping_cost;
		$_SESSION['wps_external_data']           = $this->external_data;

		if ( empty( $this->cart_contents ) ) {
			$this->destroy();
		}
	}

	/**
	 * Supprime toutes les données de la SESSION.
	 *
	 * @since 2.0.0
	 */
	public function destroy() {
		unset( $_SESSION['wps_cart'] );
		unset( $_SESSION['wps_total_price'] );
		unset( $_SESSION['wps_total_price_no_shipping'] );
		unset( $_SESSION['wps_total_price_ttc'] );
		unset( $_SESSION['wps_tva_amount'] );
		unset( $_SESSION['wps_proposal_id'] );
		unset( $_SESSION['wps_order_id'] );
		unset( $_SESSION['wps_qty'] );
		unset( $_SESSION['wps_shipping_cost'] );
		unset( $_SESSION['wps_external_data'] );

		$this->cart_contents = array();
	}

	/**
	 * Ajoute un produit dans le panier.
	 *
	 * @since 2.0.0
	 *
	 * @param Product $data Les données du produit.
	 */
	public function add_product( $data ) {
		$this->cart_contents[] = $data;
		$this->update_session();

	}

	/**
	 * Met à jour un produit dans le panier.
	 *
	 * @since 2.0.0
	 *
	 * @param  integer $index L'index du produit dans le tableau cart_contents.
	 * @param  Product $data  Les données du produit.
	 */
	public function update_product( $index, $data ) {
		$this->cart_contents[ $index ] = $data;
		$this->update_session();
	}

	/**
	 * Met à jour une propriété et met à jour la SESSION.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $property Le nom de la propriété.
	 * @param  mixed  $value    La valeur de la propriété.
	 */
	public function update( $property, $value ) {
		$this->$property = $value;
		$this->update_session();
	}

	/**
	 * Recherche le produit dans le panier correspondant à l'ID.
	 *
	 * @since 2.0.0
	 *
	 * @param  integer $id L'id du produit.
	 *
	 * @return boolean     True si trouvé. Sinon false.
	 */
	public function has_product( $id ) {
		if ( ! empty( $this->cart_contents ) ) {
			foreach ( $this->cart_contents as $cart_content ) {
				if ( $cart_content['id'] === $id ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Supprime un produit du panier selon son $id.
	 *
	 * @since 2.0.0
	 *
	 * @param  integer $id L'ID du produit.
	 */
	public function remove_product( $id ) {
		if ( ! empty( $this->cart_contents ) ) {
			foreach ( $this->cart_contents as $key => $cart_content ) {
				if ( $cart_content['id'] === $id ) {
					array_splice( $this->cart_contents, $key, 1 );
					break;
				}
			}
		}

		$this->update_session();
	}

	/**
	 * Supprime un produit depuis $key qui est son index dans le tableau cart_contents.
	 *
	 * @since 2.0.0
	 *
	 * @param integer $key L'index dans le tableau cart_contents.
	 */
	public function remove_product_by_key( $key ) {
		array_splice( $this->cart_contents, $key, 1 );

		$this->update_session();
	}
}

Cart_Session::g();
