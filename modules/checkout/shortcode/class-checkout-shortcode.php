<?php
/**
 * La classe gérant les shortcodes du tunnel de vente.
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
 * Checkout Shortcode Class.
 */
class Checkout_Shortcode extends Singleton_Util {

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
		add_shortcode( 'wps_checkout', array( $this, 'callback_checkout' ) );
	}

	/**
	 * Affichage du tunnel de vente.
	 *
	 * @since 2.0.
	 *
	 * @param array $param Les paramètres du shortcode.
	 */
	public function callback_checkout( $param ) {
		if ( ! is_admin() ) {
			global $wp;

			$current_user = wp_get_current_user();

			$third_party = Third_Party::g()->get( array( 'schema' => true ), true );
			$contact     = User::g()->get( array( 'schema' => true ), true );

			if ( 0 !== $current_user->ID ) {
				$contact = User::g()->get( array(
					'search' => $current_user->user_email,
					'number' => 1,
				), true );

				$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );
			}

			$total_price_no_shipping = Cart_Session::g()->total_price_no_shipping;
			$tva_amount              = Cart_Session::g()->tva_amount;
			$total_price_ttc         = Cart_Session::g()->total_price_ttc;
			$shipping_cost           = Cart_Session::g()->shipping_cost;
			$direct_pay              = false;

			if ( array_key_exists( 'type', $wp->query_vars ) && in_array( 'received', $wp->query_vars ) ) {
				$this->display_valid_checkout( array(
					'type' => $wp->query_vars['object_type'],
					'id'   => $wp->query_vars['id'],
				) );
			} else if ( ! array_key_exists( 'id', $wp->query_vars ) ) {
				include( Template_Util::get_template_part( 'checkout', 'form-checkout' ) );
			} else {
				$direct_pay = true;
				include( Template_Util::get_template_part( 'checkout', 'form-payment' ) );
			}
		}
	}

	/**
	 * Affichage la validation de la commande.
	 *
	 * @param array $atts Les attributs du shortcode.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_valid_checkout( $atts ) {
		if ( ! is_admin() && is_user_logged_in() ) {
			$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
			$object      = null;
			$text        = '';
			$button_text = '';

			if ( 'proposal' === $atts['type'] ) {
				$object       = Proposals::g()->get( array( 'id'  => $atts['id'] ), true );
				$title        = __( 'quotation', 'wpshop' );
				$button_text  = __( 'See my quotations', 'wpshop' );
				$atts['type'] = 'quotation';
			} elseif ( 'doli-proposal' === $atts['type'] ) {
				$doli_proposal = Request_Util::get( 'proposals/' . $atts['id'] );
				$object        = Proposals::g()->get( array( 'schema' => true ), true );
				$object        = Doli_Proposals::g()->doli_to_wp( $doli_proposal, $object );
				$title         = __( 'quotation', 'wpshop' );
				$button_text   = __( 'See my quotations', 'wpshop' );
				$atts['type']  = 'quotation';
			} elseif ( 'order' === $atts['type'] ) {
				$doli_order  = Request_Util::g()->get( 'orders/' . $atts['id'] );
				$object      = Doli_Order::g()->get( array( 'schema' => true ), true );
				$object      = Doli_Order::g()->doli_to_wp( $doli_order, $object, true );

				$title       = __( 'order', 'wpshop' );
				$button_text = __( 'See my orders', 'wpshop' );
			}

			// @todo: Check this security.
			if ( ! is_object( $object ) || $object->data['parent_id'] != $contact->data['third_party_id'] ) {
				wp_die( __( 'You can not see this page. Go back to <a href="' . home_url() . '">home!</a>', 'wpshop' ) );
			}

			if ( null !== $object ) {
				$total_price_no_shipping = $object->data['total_ht'];
				$tva_amount              = $object->data['total_tva'];
				$total_price_ttc         = $object->data['total_ttc'];
				$shipping_cost           = $object->data['shipping_cost'];

				include( Template_Util::get_template_part( 'checkout', 'valid-checkout' ) );
			}
		}
	}

	/**
	 * Affichage de la validation du devis.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_valid_proposal() {
		if ( ! is_admin() ) {
			$proposal_id = ! empty( $_GET['proposal_id'] ) ? (int) $_GET['proposal_id'] : 0;
			$proposal    = Proposals::g()->get( array( 'id' => $proposal_id ), true );

			if ( ! empty( $proposal_id ) ) {
				include( Template_Util::get_template_part( 'checkout', 'valid-proposal' ) );
			}
		}
	}
}

Checkout_Shortcode::g();
