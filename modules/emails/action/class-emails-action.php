<?php
/**
 * La classe gérant les actions des emails.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Config_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Emails Action Class.
 */
class Emails_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wps_email_order_details', array( $this, 'order_details' ) );
		add_action( 'wps_email_order_details', array( $this, 'type_payment' ), 20, 1 );
	}

	/**
	 * Ajoute les détails de la commande dans l'email.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order Les données de la commande.
	 */
	public function order_details( $order ) {
		$tva_lines = array();

		if ( ! empty( $order->lines ) ) {
			foreach ( $order->lines as &$line ) {
				if ( empty( $tva_lines[ $line->tva_tx ] ) ) {
					$tva_lines[ $line->tva_tx ] = 0;
				}

				$tva_lines[ $line->tva_tx ] += $line->total_tva;

				$wp_product = Product::g()->get( array(
					'meta_key'   => '_external_id',
					'meta_value' => (int) $line->fk_product,
				), true );

				$line->wp_id = $wp_product->data['id'];
			}
		}

		unset( $line );

		include( Template_Util::get_template_part( 'emails', 'order-details' ) );
	}

	/**
	 * Ajoute les informations de paiement dans l'email.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order Les données de la commande.
	 */
	public function type_payment( $order ) {
		$payment_methods = get_option( 'wps_payment_methods', Payment::g()->default_options );

		include( Template_Util::get_template_part( 'emails', 'type-payment' ) );
	}
}

new Emails_Action();
