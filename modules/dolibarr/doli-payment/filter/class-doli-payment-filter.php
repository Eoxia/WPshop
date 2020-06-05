<?php
/**
 * Les filtres principaux des paiements dolibarr.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Payment Filter Class.
 */
class Doli_Payment_Filter {

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_payment_methods', array( $this, 'add_payment_details' ), 10, 1 );
	}

	/**
	 * Ajoutes le type de paiement de dolibarr.
	 *
	 * @since 2.0.0
	 *
	 * @param array $payment_methods Les méthodes de paiement de WP.
	 *
	 * @return array                 Avec l'entrée doli_type en plus.
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
