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
			'online_payment' => array(
				'active'      => true,
				'logo'        => '<i class="fas fa-credit-card"></i>',
				'title'       => __( 'Online payment', 'wpshop' ),
				'description' => __( 'Pay your product online.', 'wpshop' ),
			),
		);

		$this->default_options = apply_filters( 'wps_payment_methods', $this->default_options );
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
}

Payment::g();
