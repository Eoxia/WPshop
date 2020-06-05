<?php
/**
 * Les actions relatives aux commandes.
 *
 * @package   WPshop\Classes
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Order Action Class.
 */
class Order_Action {

	/**
	 * Initialise les actions liées aux commandes.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'callback_admin_init' ), 11 );
	}

	/**
	 * Ajoute les meta box.
	 *
	 * @since 2.0.0
	 */
	public function callback_admin_init() {
		add_meta_box( 'wps-invoice-products', __( 'Products', 'wpshop' ), array( $this, 'callback_products' ), 'wps-invoice', 'normal', 'default' );
		add_meta_box( 'wps-order-products', __( 'Products', 'wpshop' ), array( $this, 'callback_products' ), 'wps-order', 'normal', 'default' );
	}

	/**
	 * Box affichant les produits de la commande.
	 *
	 * @since 2.0.0
	 *
	 * @param  mixed $object Les données pour le tableau de produit de la commande.
	 */
	public function callback_products( $object ) {
		$tva_lines = array();

		if ( ! empty( $object->data['lines'] ) ) {
			foreach ( $object->data['lines'] as $line ) {
				if ( empty( $tva_lines[ $line['tva_tx'] ] ) ) {
					$tva_lines[ $line['tva_tx'] ] = 0;
				}

				$tva_lines[ $line['tva_tx'] ] += $line['total_tva'];
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'order', 'review-order', array(
			'object'    => $object,
			'tva_lines' => $tva_lines,
		) );
	}
}

new Order_Action();
