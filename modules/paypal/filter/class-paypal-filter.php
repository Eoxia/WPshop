<?php
/**
 * Gestion des filtres PayPal.
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
 * PayPal Filter Class.
 */
class Paypal_Filter {

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_payment_method_paypal_description', array( $this, 'more_paypal_description' ) );
	}

	/**
	 * Ajoute le text pour indiquer que PayPal est en mode sandbox.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $description Description actuelle.
	 *
	 * @return string              Description modifiée.
	 */
	public function more_paypal_description( $description ) {
		$paypal_options = Payment::g()->get_payment_option( 'paypal' );

		if ( $paypal_options['use_paypal_sandbox'] ) {
			$description .= __( ' SANDBOX ENABLED. You can use sandbox testing accounts only. See the <a href="https://developer.paypal.com/docs/classic/lifecycle/ug_sandbox/">PayPal Sandbox Testing Guide</a> for more details.', 'wpshop' );
		}

		return $description;
	}
}

new Paypal_Filter();
