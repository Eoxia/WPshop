<?php
/**
 * Les fonctions principales des paiements.
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
 * Doli Payment Class.
 */
class Doli_Payment extends \eoxia\Post_Class {

	/**
	 * Model name @see ../model/*.model.php.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Payment_Model';

	/**
	 * Post type
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-doli-payment';

	/**
	 * La clé principale du modèle
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'doli-payment';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'doli-payment';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	/**
	 * Le nom du post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Doli Payment';

	/**
	 * Synchronise Dolibarr vers WPshop.
	 *
	 * @since 2.0.0
	 *
	 * @param integer  $wp_invoice_id L'ID de la facture.
	 * @param stdClass $doli_payment  Les données de dolibarr.
	 * @param Payment  $wp_payment    Les données de WP.
	 * @param  Boolean $only_convert  Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Doli_Payment_Model     Les données du paiement WP avec les données de Dolibarr.
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
	 * Convertie vers l'ID de dolibarr
	 *
	 * @since 2.0.0
	 *
	 * @param array $payment_method Méthode de paiement.
	 *
	 * @return integer l'ID de dolibarr.
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
	 * Convertie vers un texte lisible.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $payment_method Méthode de paiement venant de Dolibarr.
	 *
	 * @return mixed                  Texte lisible ou null
	 */
	public function convert_to_wp( $payment_method ) {
		if ( empty( $payment_method ) ) {
			return '';
		}

		$payment_methods_option = get_option( 'wps_payment_methods', Payment::g()->default_options );

		if ( 'CB' === $payment_method ) {
			return 'paypal';
		} elseif ( 'STR' === $payment_method ) {
			return 'stripe';
		} elseif ( 'CHQ' === $payment_method ) {
			return 'cheque';
		} elseif ( 'LIQ' === $payment_method ) {
			return 'espece';
		}

		return '';
	}
}

Doli_Payment::g();
