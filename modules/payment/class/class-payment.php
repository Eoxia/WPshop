<?php
/**
 * La classe gérant les fonctions principales pour les paiements.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.5.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Payement Class.
 */
class Payment extends Singleton_Util {

	/**
	 * Le tableau contenant toutes les données des méthodes de paiements par défaut.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $default_options;

	/**
	 * Le tableau contenant tous les statuts des paiements.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $statut;

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.5.0
	 */
	protected function construct() {
		$this->default_options = array(
			'cheque'          => array(
				'active'      => true,
				'logo'        => '<i class="fas fa-money-check"></i>',
				'title'       => __( 'Cheque', 'wpshop' ),
				'description' => __( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'wpshop' ),

			),
			'payment_in_shop' => array(
				'active'      => true,
				'logo'        => '<i class="fas fa-money-bill-alt"></i>',
				'title'       => __( 'Payment in shop', 'wpshop' ),
				'description' => __( 'Pay and pick up directly your products at the shop.', 'wpshop' ),
			),
			'online_payment' => array(
				'active'      => true,
				'logo'        => '<i class="fas fa-credit-card"></i>',
				'title'       => __( 'Online payment', 'wpshop' ),
				'description' => __( 'Pay your product online.', 'wpshop' ),
			),
		);

		$this->default_options = apply_filters( 'wps_payment_methods', $this->default_options );

		$this->status = array(
			'cheque'          => array(
				'publish' => __( 'Waiting for the check', 'wpshop' ),
				'billed'  => __( 'Paid', 'wpshop' ),
			),
			'payment_in_shop' => array(
				'publish' => __( 'Waiting for the payment', 'wpshop' ),
				'billed'  => __( 'Paid', 'wpshop' ),
			),
		);
	}

	/**
	 * Récupère les données d'une méthode de paiement selon le slug.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $slug Le slug de la méthode de paiement.
	 *
	 * @return array        Les données de la méthode de paiement.
	 */
	public function get_payment_option( $slug = '' ) {
		$payment_methods_option = get_option( 'wps_payment_methods', $this->default_options );

		if ( empty( $slug ) || ! isset( $payment_methods_option[ $slug ] ) ) {
			return $payment_methods_option;
		}

		return $payment_methods_option[ $slug ];
	}

	/**
	 * Récupère le titre d'une méthode de paiement selon le slug.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $slug Le slug de la méthode de paiement.
	 *
	 * @return array        Le titre de la méthode de paiement.
	 */
	public function get_payment_title( $slug ) {
		$payment_methods_option = get_option( 'wps_payment_methods', $this->default_options );
		$payment_method         = ! empty( $payment_methods_option[ $slug ] ) ? $payment_methods_option[ $slug ] : null;

		if ( empty( $payment_method ) ) {
			return '-';
		}

		return ! empty( $payment_method['title'] ) ? $payment_method['title'] : '-';
	}

	/**
	 * Affiche un statut lisible selon la méthode de paiement et le statut du paiement.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  mixed $object Les données de l'objet.
	 *
	 * @return string        Le status lisible.
	 */
	public function make_readable_statut( $object ) {
		if ( empty( $object ) || empty( $object->data['payment_method'] ) ) {
			return '-';
		}

		if ( empty( $this->status[ $object->data['payment_method'] ] ) ) {
			return '-';
		}

		if ( ( isset( $object->data['billed'] ) && 1 === $object->data['billed'] ) ||
			( isset( $object->data['paye'] ) && 1 === $object->data['paye'] ) ) {
			return $this->status[ $object->data['payment_method'] ]['billed'];
		} else {
			return $this->status[ $object->data['payment_method'] ]['publish'];
		}

		return '-';
	}
}

Payment::g();
