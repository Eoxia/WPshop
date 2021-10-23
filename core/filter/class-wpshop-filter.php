<?php
/**
 * La classe gérant les filtres principales de WPshop.
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
 * WPshop Filter.
 */
class WPshop_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'custom_menu_order', array( $this, 'order_menu' ), 10, 1 );
		add_filter( 'menu_order', array( $this, 'order_menu' ), 10, 1 );

		add_filter( 'parent_file', array( $this, 'highlight_menu' ) );

		add_filter( 'set-screen-option', array( $this, 'save_per_page' ), 10, 3 );

		add_filter( 'plugin_action_links_wpshop/wpshop.php', array( $this, 'add_link_plugin_page' ) );

		add_filter( 'http_request_timeout', array( $this, 'extend_timeout_request' ) );
	}

	/**
	 * Réorganise le menu du backadmin de WordPress.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $order_menu Le menu.
	 *
	 * @return array             Le menu réorgéanisé.
	 */
	public function order_menu( $order_menu ) {
		if ( ! $order_menu || empty( $order_menu ) ) {
			return true;
		}

		$key         = array_search( 'edit-comments.php', $order_menu, true );
		$key_product = array_search( 'wps-product', $order_menu, true );

		if ( false !== $key ) {
			array_splice( $order_menu, $key, 0, array( 'wps-product' ) );
			array_splice( $order_menu, $key_product + 1, 1 );
		}

		$key_wpshop = array_search( 'wpshop', $order_menu, true );

		if ( false !== $key ) {
			array_splice( $order_menu, $key, 0, array( 'wpshop' ) );
			array_splice( $order_menu, $key_wpshop + 1, 1 );
		}

		return $order_menu;
	}


	/**
	 * Permet d'ajouter l'active sur le menu.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $parent_file Le fichier parent.
	 * @return string              Le nouveau parent.
	 */
	public function highlight_menu( $parent_file ) {
		global $submenu_file, $current_screen, $pagenow;

		if ( 'wps-product' === $current_screen->post_type ) {
			if ( 'post.php' === $pagenow ) {
				$submenu_file = 'wps-product';
			}

			if ( 'edit-tags.php' === $pagenow ) {
				$submenu_file = 'edit-tags.php?taxonomy=wps-product-cat&post_type=' . $current_screen->post_type;
			}

			$parent_file = 'wps-product';
		}

		return $parent_file;
	}

	/**
	 * Permet de sauvegarder l'option d'écran "Par page".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $status Le statut.
	 * @param  string $option Le nom de l'option.
	 * @param  string $value  La valeur.
	 *
	 * @return string         La valeur.
	 */
	public function save_per_page( $status, $option, $value ) {
		if ( in_array( $option, array(
			Product::g()->option_per_page,
			Proposals::g()->option_per_page,
			Third_Party::g()->option_per_page,
			Doli_Order::g()->option_per_page,
		), true ) ) {
			return $value;
		}

		return $status;
	}

	/**
	 * Ajoute les liens sur la page du menu WordPress.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $links Le tableau contenant les liens des pages du menu WordPress.
	 *
	 * @return array        Le tableau contenant les liens des pages du menu WordPress + de nouveaux liens.
	 */
	public function add_link_plugin_page( $links ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=wps-settings' ) . '">' .  __( 'Settings', 'wpshop' ) . '</a>';
		//$links[] = '<a href="' . admin_url( 'admin.php?page=wps-tools' ) . '">' .  __( 'Tools', 'wpshop' ) . '</a>';

		return $links;
	}

	/**
	 * Augmente le temps d'éxécution d'une requête.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  integer $timeout Le temps de la requête.
	 *
	 * @return integer          Le temps de la requête plus long.
	 */
	public function extend_timeout_request( $timeout ) {
		// $timeout = 50;
		return $timeout;
	}
}

new WPshop_Filter();
