<?php
/**
 * La classe gérant les filtres Stripe.
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
 * Stripe Filter Class.
 */
class Stripe_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_payment_method_stripe_description', array( $this, 'more_stripe_description' ) );
	}

	/**
	 * Ajoute le text pour indiquer que Stripe est en mode sandbox.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $description Description actuelle.
	 *
	 * @return string              Description modifiée.
	 */
	public function more_stripe_description( $description ) {
		$stripe_options = Payment::g()->get_payment_option( 'stripe' );

		if ( $stripe_options['use_stripe_sandbox'] ) {
			$description .= __( ' SANDBOX ENABLED', 'wpshop' );
		}

		return $description;
	}
}

new Stripe_Filter();
