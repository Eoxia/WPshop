<?php
/**
 * La classe gérant les fonctions principales des paiements de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Class;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Payment Class.
 */
class Doli_Payment extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Payment_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-doli-payment';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'doli-payment';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'doli-payment';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	/**
	 * Le nom du post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Doli Payment';

	/**
	 * Synchronise Dolibarr vers WPshop.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  integer      $wp_invoice_id L'id d'une facture.
	 * @param  stdClass     $doli_payment  Les données d'un paiement Dolibarr.
	 * @param  Doli_Payment $wp_payment    Les données d'un paiement WordPress.
	 * @param  boolean      $only_convert  Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Doli_Payment                Les données d'un paiement WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $wp_invoice_id, $doli_payment, $wp_payment, $only_convert = false ) {
		$wp_payment->data['title']        = $doli_payment->ref;
		$wp_payment->data['amount']       = $doli_payment->amount;
		$wp_payment->data['date']         = $doli_payment->date;
		$wp_payment->data['parent_id']    = (int) $wp_invoice_id;
		$wp_payment->data['payment_type'] = $doli_payment->type;
		$wp_payment->data['status']       = 'publish';

		if ( ! $only_convert ) {
			$wp_payment = Doli_Payment::g()->update( $wp_payment->data );
		}

		return $wp_payment;
	}

	/**
	 * Convertit vers l'id de Dolibarr
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $payment_method Les données d'une méthode de paiement.
	 *
	 * @return integer               L'id de Dolibarr.
	 */
	public function convert_to_doli_id( $payment_method ) {
		$payment_methods_option = get_option( 'wps_payment_methods', Payment::g()->default_options );
		$method                 = $payment_methods_option[ $payment_method ];

		$payment_types = Request_Util::get( 'setup/dictionary/payment_types' );

		if ( ! empty( $payment_types ) ) {
			foreach ( $payment_types as $element ) {
				if ( $element->code == $method['doli_type'] ) {
					return $element->id;
				}
			}
		}

		return 0;
	}

	/**
	 * Convertit vers un texte lisible.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $payment_method La méthode de paiement venant de Dolibarr.
	 *
	 * @return string                 Texte lisible ou null
	 */
	public function convert_to_wp( $payment_method ) {
		if ( empty( $payment_method ) ) {
			return '';
		}

		$payment_methods_option = get_option( 'wps_payment_methods', Payment::g()->default_options );

		if ( 'CB' === $payment_method ) {
			return 'carte_bancaire';
		} elseif ( 'CHQ' === $payment_method ) {
			return 'cheque';
		} elseif ( 'LIQ' === $payment_method ) {
			return 'espece';
		}

		return '';
	}
}

Doli_Payment::g();
