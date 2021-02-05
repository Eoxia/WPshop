<?php
/**
 * La classe gérant les fonctions principales des status de dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
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
					'text'  => __( 'Draft', 'wpshop' ),
					'class' => 'status-grey',
				),
				'publish'       => array(
					'text'  => __( 'Validated', 'wpshop' ),
					'class' => 'status-orange',
				),
				'wps-accepted'  => array(
					'text'  => __( 'Signed', 'wpshop' ),
					'class' => 'status-green',
				),
				'wps-refused'   => array(
					'text'  => __( 'Not signed', 'wpshop' ),
					'class' => 'status-grey',
				),
				'wps-billed'    => array(
					'text'  => __( 'Billed', 'wpshop' ),
					'class' => 'status-grey',
				),
				'wps-delivered' => array(
					'text'  => __( 'Delivered', 'wpshop' ),
					'class' => 'status-green',
				),
			),
			'wps-order'        => array(
				'draft'         => array(
					'text'  => __( 'Draft', 'wpshop' ),
					'class' => 'status-grey',
				),
				'publish'       => array(
					'text'  => __( 'Validated', 'wpshop' ),
					'class' => 'status-orange',
				),
				'wps-canceled'  => array(
					'text'  => __( 'Canceled', 'wpshop' ),
					'class' => 'status-red',
				),
				'wps-billed'    => array(
					'text'  => __( 'Processed', 'wpshop' ),
					'class' => 'status-grey',
				),
				'wps-shipmentprocess'    => array(
					'text'  => __( 'In progress', 'wpshop' ),
					'class' => 'status-green',
				),
				'wps-delivered' => array(
					'text'  => __( 'Delivered', 'wpshop' ),
					'class' => 'status-green',
				),
			),
			'wps-doli-invoice' => array(
				'draft'         => array(
					'text'  => __( 'Draft', 'wpshop' ),
					'class' => 'status-grey',
				),
				'publish'       => array(
					'text'  => __( 'Unpaid', 'wpshop' ),
					'class' => 'status-orange',
				),
				'wps-billed'    => array(
					'text'  => __( 'Paid', 'wpshop' ),
					'class' => 'status-grey',
				),
				'wps-abandoned' => array(
					'text'  => __( 'Abandoned', 'wpshop' ),
					'class' => 'status-red',
				),
				'wps-canceled' => array(
					'text'  => __( 'Canceled', 'wpshop' ),
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
				'text'   => $status['text'],
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
