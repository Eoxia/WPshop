<?php
/**
 * La classe gérant les fonctions principales de Stripe.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Stripe Class.
 */
class Stripe extends Singleton_Util {
	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Prépare le paiement stripe.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  Doli_Order $order Les données de la commande.
	 *
	 * @return array             L'ID de la session Stripe.
	 */
	public function process_payment( $order ) {
		$stripe_options = Payment::g()->get_payment_option( 'stripe' );

		\Stripe\Stripe::setApiKey( $stripe_options['secret_key'] );
		\Stripe\Stripe::setApiVersion( '2019-03-14; checkout_sessions_beta=v1' );

		$lines = array(
			'amount'   => (int) ( $order->data['total_ttc'] * 100 ),
			'quantity' => 1,
			'name'     => $order->data['title'],
			'currency' => 'eur',
		);

		$session = \Stripe\Checkout\Session::create( array(
			'success_url'          => Pages::g()->get_checkout_link() . '/received/order/' . $order->data['external_id'],
			'cancel_url'           => site_url(),
			'payment_method_types' => array( 'card' ),
			'line_items'           => array( $lines ),
			'metadata'             => array( 'order_id' => $order->data['external_id'] ),
		) );

		return array(
			'id' => $session->id,
		);
	}
}

Stripe::g();
