<?php
/**
 * La classe gérant les filtres du tunnel de vente.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout Filter Class.
 */
class Checkout_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'query_vars', array( $this, 'add_order_or_quotation_id' ) );

		add_filter( 'wps_cart_to_checkout_link_title', array( $this, 'wps_cart_to_checkout_link_title' ), 10, 1 );
	}

	/**
	 * Add parameters to the route /{type}/{id}.
	 *
	 * @param  array $vars Les paramètres de base.
	 *
	 * @return array       Les paramètres de base avec {type} et {id}.
	 */
	public function add_order_or_quotation_id( $vars ) {
		$vars[] = 'id';
		$vars[] = 'type';
		$vars[] = 'object_type';

		return $vars;
	}

	/**
	 * Ajoute le bouton sur le panier.
	 *
	 * @param  string $title Le titre du bouton.
	 *
	 * @return array         Le titre du bouton.
	 */
	public function wps_cart_to_checkout_link_title( $title ) {
		if ( Settings::g()->dolibarr_is_active() ) {
			return $title;
		}

		return __( 'Add to wish list', 'wpshop' );
	}

}

new Checkout_Filter();
