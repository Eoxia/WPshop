<?php
/**
 * Gestion de PayPal.
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
 * PayPal Class.
 */
class PayPal extends \eoxia\Singleton_Util {
	/**
	 * L'URL vers la page de paiement
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $request_url;

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {}

	/**
	 * Prépares l'URL pour aller à la page de paiement
	 *
	 * @since 2.0.0
	 *
	 * @param  Order_Model $order Les données de la commande.
	 * @return array              L'URL pour aller à la page de paiement.
	 */
	public function process_payment( $order ) {
		$paypal_options = Payment::g()->get_payment_option( 'paypal' );

		$this->request_url = $paypal_options['use_paypal_sandbox'] ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&' : 'https://www.paypal.com/cgi-bin/webscr?';
		$paypal_args       = $this->get_paypal_args( $order );

		return array(
			'url' => $this->request_url . http_build_query( $paypal_args, '', '&' ),
		);
	}

	/**
	 * Récupères les paramètres IPN de PayPal.
	 *
	 * @since 2.0.0.
	 *
	 * @param  Order_Model $order Les données de la commande.
	 *
	 * @return array              Les données IPN.
	 */
	protected function get_paypal_args( $order ) {
		$paypal_args = apply_filters( 'wps_paypal_args', array_merge(
			$this->get_transaction_args( $order ),
			$this->get_line_item_args( $order )
		), $order );

		return $paypal_args;
	}

	/**
	 * Prépares les données IPN
	 *
	 * @since 2.0.0
	 *
	 * @param  Order_Model $order Les données de la commande.
	 *
	 * @return array              Les données IPN.
	 */
	protected function get_transaction_args( $order ) {
		$payment_methods_option = get_option( 'wps_payment_methods', array(
			'paypal' => array(),
			'cheque' => array(),
		) );

		$third_party = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );
		$contact     = User::g()->get( array( 'id' => end( $third_party->data['contact_ids'] ) ), true );

		return array(
			'cmd'           => '_cart',
			'business'      => $payment_methods_option['paypal']['paypal_email'],
			'no_shipping'   => 0,
			'lc'            => 'fr_FR',
			'no_note'       => 0,
			'rm'            => 0,
			'currency_code' => 'EUR',
			'charset'       => 'utf-8',
			'upload'        => 1,
			'return'        => Pages::g()->get_checkout_link() . 'received/order/' . $order->data['external_id'],
			'notify_url'    => site_url( 'wp-json/wpshop/v2/wps_gateway_paypal' ),
			'cancel_return' => '',
			'email'         => $contact->data['email'],
			'custom'        => $order->data['external_id'],
		);
	}

	/**
	 * Ajoutes les lignes pour le paiement PayPal.
	 *
	 * @since 2.0.0
	 *
	 * @param  Order_Model $order Les données de la commande.
	 *
	 * @return array              Les lignes pour le paiement PayPal.
	 */
	protected function get_line_item_args( $order ) {
		$line_item_args = array(
			'item_name_1'   => $order->data['title'],
			'quantity_1'    => 1,
			'amount_1'      => $order->data['total_ttc'],
			'item_number_1' => $order->data['title'],
		);

		return $line_item_args;
	}


}

Paypal::g();
