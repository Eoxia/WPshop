<?php
/**
 * La classe gérant les filtres des paiements de Dolibarr.
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
 * Doli Payment Filter Class.
 */
class Doli_Payment_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_payment_methods', array( $this, 'add_payment_details' ), 10, 1 );
	}

	/**
	 * Ajoute le type de paiement de Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $payment_methods Le tableau contenant toutes les données des méthodes de paiements.
	 *
	 * @return array                  Le nouveau tableau avec l'entrée doli_type en plus.
	 */
	public function add_payment_details( $payment_methods ) {
		$payment_methods['paypal']['doli_type']          = 'CB';
		$payment_methods['stripe']['doli_type']          = 'CB';
		$payment_methods['cheque']['doli_type']          = 'CHQ';
		$payment_methods['payment_in_shop']['doli_type'] = '';

		return $payment_methods;
	}
}

new Doli_Payment_Filter();
