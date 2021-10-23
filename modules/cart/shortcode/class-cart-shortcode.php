<?php
/**
 * La classe gérant ldes shortcodes du panier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Cart Shortcode Class.
 */
class Cart_Shortcode extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Initialise les shortcodes.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_init() {
		add_shortcode( 'wps_cart', array( $this, 'callback_cart' ) );
		add_shortcode( 'wps_add_cart', array( $this, 'callback_add_to_cart' ) );
	}

	/**
	 * Affichage de la vue du shortcode.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_cart() {
		$cart_contents = Cart_Session::g()->cart_contents;

		$shipping_cost_option  = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );
		$shipping_cost_product = Product::g()->get( array( 'id' => $shipping_cost_option['shipping_product_id'] ), true );
		if ( ! empty( $cart_contents ) ) {
			$total_price_no_shipping = Cart_Session::g()->total_price_no_shipping;
			$tva_amount              = Cart_Session::g()->tva_amount;
			$total_price_ttc         = Cart_Session::g()->total_price_ttc;
			$shipping_cost           = Cart_Session::g()->shipping_cost;

			include( Template_Util::get_template_part( 'cart', 'cart' ) );
		} else {
			include( Template_Util::get_template_part( 'cart', 'empty-cart' ) );
		}
	}

	/**
	 * Permet d'afficher le bouton "Ajouter au panier".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $atts Les attributs du shortcode. Voir ci dessous shortcode_atts.
	 *
	 * @return view        La vue du bouton ""Ajouter au panier".
	 */
	public function callback_add_to_cart( $atts ) {
		if ( ! is_admin() ) {
			$a = shortcode_atts( array(
				'id'   => 0,
				'text' => __( 'Add to cart', 'wpshop' ),
			), $atts );

			$product = Product::g()->get( array( 'id' => $a['id'] ), true );
			ob_start();
			include( Template_Util::get_template_part( 'cart', 'add-to-cart' ) );
			return ob_get_clean();
		}
	}
}

Cart_Shortcode::g();
