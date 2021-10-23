<?php
/**
 * La classe gérant les filtres du panier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Cart Filter Class.
 */
class Cart_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'wp_nav_menu_objects', array( $this, 'nav_menu_add_search' ), 10, 2 );

		add_filter( 'wps_add_to_cart_product', array( $this, 'check_stock' ), 10, 2 );
	}

	/**
	 * Ajoute le nombre de produit dans le panier dans le menu "Panier".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $items Les items du menu.
	 * @param  array $args  Arguments supplémentaires.
	 *
	 * @return array       Les items du menu avec le bouton "Panier" modifié.
	 */
	public function nav_menu_add_search( $items, $args ) {
		if ( ! empty( $items ) ) {
			foreach ( $items as &$item ) {
				if ( Pages::g()->get_cart_link() === $item->url ) {
					$item->original_title = $item->title;

					$item->classes[] = 'cart-button';
					$qty             = Cart_Session::g()->qty;

					if ( ! empty( $qty ) ) {
						$item->title .= ' <span class="qty">(<span class="qty-value">' . $qty . '</span>)</span>';
					} else {
						$item->title .= ' <span class="qty"></span>';
					}

					$item->title = apply_filters( 'wps_cart_menu_item_qty', $item->title, $item, $qty );
				}
			}
		}

		return $items;
	}

	/**
	 * Vérifie si le produit est disponible en stock.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  boolean $can_add Filter params.
	 * @param  Product $product Les données du produit.
	 *
	 * @return boolean          True si oui, sinon false.
	 */
	public function check_stock( $can_add, $product ) {
		if ( $product->data['manage_stock'] && 0 >= $product->data['stock'] ) {
			$can_add = false;
		}

		return $can_add;
	}
}

new Cart_Filter();
