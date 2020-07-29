<?php
/**
 * La classe gérant les filtres des commandes de Dolibarr.
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
 * Doli Order Filter Class.
 */
class Doli_Order_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_wps-order_register_post_type_args', array( $this, 'callback_register_post_type_args' ) );
		add_filter( 'wps_review_order_table_class', array( $this, 'add_review_order_table_class' ), 10, 2 );

		add_filter( 'wps_doli_status', array( $this, 'wps_doli_status' ), 10, 2 );

		add_filter( "eo_model_wps-order_after_put", array( $this, 'update_after_billed' ), 10, 2 );

	}

	/**
	 * Ajoute des données supplémentaires pour le register_post_type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return array Les données supplementaires.
	 */
	public function callback_register_post_type_args() {
		$labels = array(
			'name'               => _x( 'Orders', 'post type general name', 'wpshop' ),
			'singular_name'      => _x( 'Order', 'post type singular name', 'wpshop' ),
			'menu_name'          => _x( 'Orders', 'admin menu', 'wpshop' ),
			'name_admin_bar'     => _x( 'Order', 'add new on admin bar', 'wpshop' ),
			'add_new'            => _x( 'Add New', 'product', 'wpshop' ),
			'add_new_item'       => __( 'Add New Order', 'wpshop' ),
			'new_item'           => __( 'New Order', 'wpshop' ),
			'edit_item'          => __( 'Edit Order', 'wpshop' ),
			'view_item'          => __( 'View Order', 'wpshop' ),
			'all_items'          => __( 'All Orders', 'wpshop' ),
			'search_items'       => __( 'Search Orders', 'wpshop' ),
			'parent_item_colon'  => __( 'Parent Orders:', 'wpshop' ),
			'not_found'          => __( 'No products found.', 'wpshop' ),
			'not_found_in_trash' => __( 'No products found in Trash.', 'wpshop' ),
		);

		$args['labels']              = $labels;
		$args['supports']            = array( 'title' );
		$args['public']              = false;
		$args['has_archive']         = false;
		$args['show_ui']             = false;
		$args['show_in_nav_menus']   = false;
		$args['show_in_menu']        = false;
		$args['query_var']           = false;
		$args['publicly_queryable']  = false;
		$args['exclude_from_search'] = true;

		return $args;
	}

	/**
	 * Ajoute une classe dans le tableau.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $class  Donnée reçue par le filtre.
	 * @param  mixed  $object Donnée de la commande/facture/devis.
	 *
	 * @return string         Classe renvoyée au tableau
	 */
	public function add_review_order_table_class( $class, $object ) {
		switch ( $object->data['type'] ) {
			case Doli_Order::g()->get_type():
				$class = 'gridw-4';
				break;
			case Doli_Invoice::g()->get_type():
				$class = 'gridw-4';
				break;
			case Proposals::g()->get_type():
				$class = 'gridw-3';
				break;
			default:
				break;
		}
		return $class;
	}

	/**
	 * Change le statut d'une commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string       $status Le statut d'une facture.
	 * @param  Doli_Invoice $object Les données d'une facture.
	 *
	 * @return string               Le nouveau statut d'une facture.
	 */
	public function wps_doli_status( $status, $object ) {
		if ( $object->data['type'] == Doli_Order::g()->get_type() ) {
			if ( $object->data['traitment_in_progress'] ) {
				return __( 'Traitment in progress', 'wpshop' );
			}

			if ( $object->data['delivered'] ) {
				return $status . ' ' . __( '(Delivery)', 'wpshop' );
			}
		}

		return $status;
	}

	/**
	 * Télécharge la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  Doli_Order $object Les données d'une commande.
	 * @param  array      $args   Les arguments supplémentaires.
	 *
	 * @return Doli_Order         Les nouvelles données d'une commande.
	 */
	public function update_after_billed( $object, $args ) {
		if ( $object->data['billed'] ) {
			Product_Downloadable::g()->create_from_order( $object );
		}

		return $object;
	}
}

new Doli_Order_Filter();
