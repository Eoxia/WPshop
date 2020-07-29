<?php
/**
 * La classe gérant les filtre des factures de dolibarr.
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
 * Doli Invoice Filter Class.
 */
class Doli_Invoice_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_wps-invoice_register_post_type_args', array( $this, 'callback_register_post_type_args' ) );
		add_filter( 'eo_model_wps-doli-invoice_after_get', array( $this, 'add_details' ), 10, 2 );

		add_filter( 'wps_doli_status', array( $this, 'wps_doli_status' ), 10, 2 );
	}

	/**
	 * Ajoute les détails d'une facture.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  Doli_Invoice $object Les données d'une facture.
	 * @param  array        $args   Les arguments supplémentaires.
	 *
	 * @return Doli_Invoice         Les nouvelles données d'une facture.
	 */
	public function add_details( $object, $args ) {
		$object->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $object->data['id'] ) );
		$object->data['totalpaye'] = 0;

		if ( ! empty( $object->data['payments'] ) ) {
			foreach ( $object->data['payments'] as $payment ) {
				$object->data['totalpaye'] += $payment->data['amount'];
			}
		}

		$object->data['resteapayer'] = $object->data['total_ttc'] - $object->data['totalpaye'];

		return $object;
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
			'name'               => _x( 'Invoices', 'post type general name', 'wpshop' ),
			'singular_name'      => _x( 'Invoice', 'post type singular name', 'wpshop' ),
			'menu_name'          => _x( 'Invoices', 'admin menu', 'wpshop' ),
			'name_admin_bar'     => _x( 'Invoice', 'add new on admin bar', 'wpshop' ),
			'add_new'            => _x( 'Add New', 'invoice', 'wpshop' ),
			'add_new_item'       => __( 'Add New Invoice', 'wpshop' ),
			'new_item'           => __( 'New Invoice', 'wpshop' ),
			'edit_item'          => __( 'Edit Invoice', 'wpshop' ),
			'view_item'          => __( 'View Invoice', 'wpshop' ),
			'all_items'          => __( 'All Invoices', 'wpshop' ),
			'search_items'       => __( 'Search Invoices', 'wpshop' ),
			'parent_item_colon'  => __( 'Parent Invoices:', 'wpshop' ),
			'not_found'          => __( 'No invoices found.', 'wpshop' ),
			'not_found_in_trash' => __( 'No invoices found in Trash.', 'wpshop' ),
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
	 * Change le statut d'une facture.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string       $status Le statut de la facture.
	 * @param  Doli_Invoice $object Les données d'une facture.
	 *
	 * @return string               Le nouveau statut de la facture.
	 */
	public function wps_doli_status( $status, $object ) {
		if ( $object->data['type'] == Doli_Invoice::g()->get_type() ) {
			if ( $object->data['totalpaye'] != 0 && ! $object->data['paye'] ) {
				return __( 'Started', 'wpshop' );
			}
		}

		return $status;

	}
}

new Doli_Invoice_Filter();
