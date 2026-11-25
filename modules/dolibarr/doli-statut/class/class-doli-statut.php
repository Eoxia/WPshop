<?php
/**
 * La classe gérant les fonctions principales des status de dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.3
 */

namespace wpshop;

use eoxia\Singleton_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Statut Class.
 */
class Doli_Statut extends Singleton_Util {

	/**
	 * Les statuts des entitées de Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $status = array();

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.3.3
	 */
	protected function construct() {
		$this->status = array(
			'wps-proposal'     => array(
				'draft'         => array(
					'text'  => 'Draft',
					'class' => 'status-grey',
				),
				'publish'       => array(
					'text'  => 'Validated',
					'class' => 'status-orange',
				),
				'wps-accepted'  => array(
					'text'  => 'Signed',
					'class' => 'status-green',
				),
				'wps-refused'   => array(
					'text'  => 'Not signed',
					'class' => 'status-grey',
				),
				'wps-billed'    => array(
					'text'  => 'Billed',
					'class' => 'status-grey',
				),
				'wps-delivered' => array(
					'text'  => 'Delivered',
					'class' => 'status-green',
				),
			),
			'wps-order'        => array(
				'draft'         => array(
					'text'  => 'Draft',
					'class' => 'status-grey',
				),
				'publish'       => array(
					'text'  => 'Validated',
					'class' => 'status-green',
				),
				'wps-canceled'  => array(
					'text'  => 'Canceled',
					'class' => 'status-red',
				),
				'wps-billed'    => array(
					'text'  => 'Processed',
					'class' => 'status-grey',
				),
				'wps-shipmentprocess'    => array(
					'text'  => 'In progress',
					'class' => 'status-green',
				),
				'wps-delivered' => array(
					'text'  => 'Delivered',
					'class' => 'status-green',
				),
			),
			'wps-doli-invoice' => array(
				'draft'         => array(
					'text'  => 'Draft',
					'class' => 'status-grey',
				),
				'publish'       => array(
					'text'  => 'Unpaid',
					'class' => 'status-orange',
				),
				'wps-billed'    => array(
					'text'  => 'Paid',
					'class' => 'status-green',
				),
				'wps-abandoned' => array(
					'text'  => 'Abandoned',
					'class' => 'status-red',
				),
				'wps-canceled' => array(
					'text'  => 'Canceled',
					'class' => 'status-red',
				)
			),
		);
	}

	/**
	 * Affiche un status lisible selon le status de l'objet
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param mixed $object Les données d'un objet.
	 */
	public function display_status( $object ) {
		$status = null;

		if ( $this->status[ $object->data['type'] ][ $object->data['status'] ] ) {
			$status = $this->status[ $object->data['type'] ][ $object->data['status'] ];
		}

		if ( $status ) {
			View_Util::exec( 'wpshop', 'doli-statut', 'item', array(
				'object' => $object,
				'text'   => __( $status['text'] ),
				'class'  => $status['class'],
			) );
		}
	}

	/*
	 * Affiche un status lisible selon le status de l'objet
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param mixed $object Les données d'un objet.
	 */
	public function get_status($object) {
		$status = null;

		if ( $this->status[ $object->data['type'] ][ $object->data['status'] ] ) {
			$status = $this->status[ $object->data['type'] ][ $object->data['status'] ];
		}

		if ( $status ) {
			// Labels are defined before the translations are loaded: translate them here.
			$status['text'] = __( $status['text'], 'wpshop' );

			return [
				'text'  => $status['text'],
				'class' => $status,
			];
		}
		return '';
	}

	/**
	 * Status of an order as the customer understands it (my account block).
	 *
	 * Dolibarr "Validated" only means the order is recorded: what matters to the customer
	 * is whether it is paid. An order is paid when Dolibarr classified it as billed (online
	 * payment creates the invoice and does it, or the shop does it by hand).
	 * The statuses stored on the WordPress orders (admin) are not changed.
	 *
	 * @since 2.7.0
	 *
	 * @param Doli_Order $order Order converted by Doli_Order::doli_to_wp().
	 *
	 * @return array {
	 *     @type string $key  canceled, draft, awaiting-payment, paid, shipping or delivered.
	 *     @type string $text Translated label.
	 *     @type string $tone grey, orange, green, blue or red.
	 *     @type bool   $paid Whether the order is paid.
	 * }
	 */
	public function get_customer_order_status( $order ) {
		$paid = ! empty( $order->data['billed'] );

		switch ( $order->data['status'] ) {
			case 'wps-canceled':
				$status = array( 'canceled', __( 'Canceled', 'wpshop' ), 'red' );
				break;
			case 'draft':
				$status = array( 'draft', __( 'Being recorded', 'wpshop' ), 'grey' );
				break;
			case 'wps-shipmentprocess':
				$status = array( 'shipping', __( 'Being delivered', 'wpshop' ), 'blue' );
				break;
			case 'wps-delivered':
			case 'wps-billed':
				$status = array( 'delivered', __( 'Delivered', 'wpshop' ), 'green' );
				break;
			default:
				$status = $paid
					? array( 'paid', __( 'Paid, being prepared', 'wpshop' ), 'green' )
					: array( 'awaiting-payment', __( 'Awaiting payment', 'wpshop' ), 'orange' );
		}

		$status = array(
			'key'  => $status[0],
			'text' => $status[1],
			'tone' => $status[2],
			'paid' => $paid,
		);

		/**
		 * Filters the status of an order displayed to the customer.
		 *
		 * @param array      $status Status (key, text, tone, paid).
		 * @param Doli_Order $order  Order.
		 */
		return apply_filters( 'wps_customer_order_status', $status, $order );
	}

	/**
	 * Whether the customer can still pay the order online.
	 *
	 * @since 2.7.0
	 *
	 * @param Doli_Order $order Order converted by Doli_Order::doli_to_wp().
	 *
	 * @return bool
	 */
	public function order_can_be_paid( $order ) {
		return empty( $order->data['billed'] ) && ! in_array( $order->data['status'], array( 'draft', 'wps-canceled' ), true ) && (float) $order->data['total_ttc'] > 0;
	}

	/**
	 * Ajoute une classe dans la balise du statut.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  mixed $object Les données d'un objet.
	 *
	 * @return string        La classe à ajouter.
	 */
	public function display_status_class( $object ) {
		$status = null;

		if ( $this->status[ $object->data['type'] ][ $object->data['status'] ] ) {
			$status = $this->status[ $object->data['type'] ][ $object->data['status'] ];
		}

		if ( $status ) {
			return $status['class'];
		}
	}
}

Doli_Statut::g();
