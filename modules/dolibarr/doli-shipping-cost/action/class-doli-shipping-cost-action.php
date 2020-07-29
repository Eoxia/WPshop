<?php
/**
 * La classe gérant les actions des frais de port.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Shipping Cost Action Class.
 */
class Doli_Shipping_Cost_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wps_after_calculate_totals', array( $this, 'add_shipping_cost' ), 10, 0 );
	}

	/**
	 * Ajoute le frais de port.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function add_shipping_cost() {
		$shipping_cost_option = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );

		if ( 0 === $shipping_cost_option['shipping_product_id'] ) {
			return;
		}

		if ( (float) Cart_Session::g()->total_price_no_shipping < (float) $shipping_cost_option['from_price_ht'] &&
			! Cart_Session::g()->has_product( $shipping_cost_option['shipping_product_id'] ) && count( Cart_Session::g()->cart_contents ) > 0 ) {
			$product = Product::g()->get( array( 'id' => $shipping_cost_option['shipping_product_id'] ), true );
			Cart_Session::g()->update( 'shipping_cost', $product->data['price_ttc'] );
			Cart::g()->add_to_cart( $product );
		}

		if ( (float) Cart_Session::g()->total_price_no_shipping >= (float) $shipping_cost_option['from_price_ht'] ||
			count( Cart_Session::g()->cart_contents ) === 1 && Cart_Session::g()->has_product( $shipping_cost_option['shipping_product_id'] ) ) {
			Cart_Session::g()->update( 'shipping_cost', 0 );
			Cart_Session::g()->remove_product( $shipping_cost_option['shipping_product_id'] );
		}
	}
}

new Doli_Shipping_Cost_Action();
