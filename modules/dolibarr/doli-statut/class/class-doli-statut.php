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
					'class' => 'status-orange',
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
					'class' => 'status-grey',
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
