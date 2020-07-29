<?php
/**
 * La classe gérant les fonctions principales des factures de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Class;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Invoice Class.
 */
class Doli_Invoice extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Invoice_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-doli-invoice';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'doli-invoice';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'doli-invoice';

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
	protected $post_type_name = 'Doli Invoice';

	/**
	 * Appel la vue "list" du module "doli-invoice".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display() {
		$invoices = $this->get( array(
			'post_status' => 'any',
		) );

		if ( ! empty( $invoices ) ) {
			foreach ( $invoices as &$element ) {
				$element->data['tier']  = null;
				$element->data['order'] = null;

				if ( ! empty( $element->data['parent_id'] ) ) {
					$element->data['order'] = Doli_Order::g()->get( array( 'id' => $element->data['parent_id'] ), true );
				}

				if ( ! empty( $element->data['third_party_id'] ) ) {
					$element->data['tier'] = Third_Party::g()->get( array( 'id' => $element->data['third_party_id'] ), true );
				}
			}
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		View_Util::exec( 'wpshop', 'doli-invoice', 'list', array(
			'invoices' => $invoices,
			'doli_url' => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Appel la vue "item" d'une facture.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Invoice $invoice  Les données d'une facture.
	 * @param string       $doli_url L'url de Dolibarr.
	 */
	public function display_item( $invoice, $doli_url = '' ) {
		View_Util::exec( 'wpshop', 'doli-invoice', 'item', array(
			'invoice' => $invoice,
		) );
	}

	/**
	 * Convertit un tableau Invoice Object provenant de Dolibarr vers un format Invoice Object WPShop afin de normisé pour l'affichage.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  \stdClass $doli_invoices Le tableau ontenant toutes les données des factures provenant de Dolibarr.
	 *
	 * @return Doli_Invoice             Le tableau contenant toutes les données des factures convertis depuis le format de Dolibarr.
	 */
	public function convert_to_wp_invoice_format( $doli_invoices ) {
		$wp_invoices = array();

		if ( ! empty( $doli_invoices ) ) {
			foreach ( $doli_invoices as $doli_invoice ) {
				$wp_invoice    = $this->get( array( 'schema' => true ), true );
				$wp_invoices[] = $this->doli_to_wp( $doli_invoice, $wp_invoice, true );
			}
		}

		return $wp_invoices;
	}

	/**
	 * Synchronise depuis Dolibarr vers WP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  \stdClass    $doli_invoice Les données d'une facture Dolibarr.
	 * @param  Doli_Invoice $wp_invoice   Les données d'une facture WordPress.
	 * @param  boolean      $only_convert Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Doli_Invoice              Les données de la facture WP avec les données de Dolibarr.
	 */
	public function doli_to_wp( $doli_invoice, $wp_invoice, $only_convert = false ) {
		$order = null;

		$doli_invoice                    = Request_Util::get( 'invoices/' . $doli_invoice->id ); // Charges par la route single des factures pour avoir accès à linkedObjectsIds->commande.
		$wp_invoice->data['external_id'] = (int) $doli_invoice->id;

		// @todo: Vérifier les conséquences
//		if ( ! empty( $doli_invoice->linkedObjectsIds->commande ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.
//			$order_id                        = Doli_Order::g()->get_wp_id_by_doli_id( end( $doli_invoice->linkedObjectsIds->commande ) );
//			$wp_invoice->data['post_parent'] = $order_id;
//
//			$order                         = Doli_Order::g()->get( array( 'id' => $order_id ), true );
//			$wp_invoice->data['author_id'] = $order->data['author_id'];
//		}

		$wp_invoice->data['title']          = $doli_invoice->ref;
		$wp_invoice->data['datec']          = date( 'Y-m-d H:i:s', $doli_invoice->date_creation );
		$wp_invoice->data['date_invoice']   = date( 'Y-m-d H:i:s', $doli_invoice->datem );
		$wp_invoice->data['total_ttc']      = $doli_invoice->total_ttc;
		$wp_invoice->data['total_ht']       = $doli_invoice->total_ht;
		$wp_invoice->data['resteapayer']    = $doli_invoice->remaintopay;
		$wp_invoice->data['totalpaye']      = $doli_invoice->totalpaid;
		// @todo: Faut-il réelement convertir le méthode de paiement ?
		$wp_invoice->data['payment_method'] = Doli_Payment::g()->convert_to_wp( $doli_invoice->mode_reglement_code );
		$wp_invoice->data['paye']           = (int) $doli_invoice->paye;
		$wp_invoice->data['third_party_id'] = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $doli_invoice->socid );
		$wp_invoice->data['payments']       = array();

		$wp_invoice->data['lines'] = null;

		if ( ! empty( $doli_invoice->lines ) ) {
			$wp_invoice->data['lines'] = array();
			foreach ( $doli_invoice->lines as $line ) {
				$line_data = array(
					'fk_facture' => $doli_invoice->id,
					'fk_product' => $line->fk_product,
					'qty'        => $line->qty,
					'total_tva'  => $line->total_tva,
					'total_ht'   => $line->total_ht,
					'total_ttc'  => $line->total_ttc,
					'libelle'    => ! empty( $line->libelle ) ? $line->libelle : $line->desc,
					'tva_tx'     => $line->tva_tx,
					'subprice'   => $line->subprice,
					'rowid'      => $line->rowid,
				);

				$wp_invoice->data['lines'][] = $line_data;
			}
		}

		$status = '';

		switch ( $doli_invoice->statut ) {
			case -1:
				$status = 'wps-canceled';
				break;
			case 0:
				$status = 'draft';
				break;
			case 1:
				$status = 'publish';
				break;
			case 2:
				$status = 'wps-billed';
				break;
			case 3:
				$status = 'wps-abandoned';
				break;
			default:
				$status = 'publish';
				break;
		}

		$wp_invoice->data['status'] = $status;

		$wp_invoice->data['avoir'] = ( 2 === (int) $doli_invoice->type ) ? 1 : 0;

		if ( ! $only_convert ) {
			$wp_invoice = Doli_Invoice::g()->update( $wp_invoice->data );
		}

		$doli_payments = Request_Util::get( 'invoices/' . $wp_invoice->data['external_id'] . '/payments' );

		if ( ! empty( $doli_payments ) ) {
			foreach ( $doli_payments as $doli_payment ) {
				$wp_payment                     = Doli_Payment::g()->get( array( 'schema' => true ), true );
				$wp_invoice->data['payments'][] = Doli_Payment::g()->doli_to_wp( $wp_invoice->data['id'], $doli_payment, $wp_payment, $only_convert );
			}
		}

		if ( ! empty( $doli_invoice->linkedObjectsIds ) ) {
			foreach ( $doli_invoice->linkedObjectsIds as $key => $values ) {
				$type = '';
				switch ( $key ) {
					case 'propal':
						$type = new Proposals();
						$wp_order->data['linked_objects_ids'][ 'wp_' . $key ] = array();
						break;
				}

				$values = (array) $values;
				$wp_invoice->data['linked_objects_ids'][ $key ] = array();

				if ( ! empty( $values ) ) {
					foreach ( $values as $value ) {
						if ( ! empty( $type ) ) {
							$object = $type::g()->get( array(
								'meta_key'   => '_external_id',
								'meta_value' => (int) $value,
							), true );

							$wp_invoice->data['linked_objects_ids'][ 'wp_' . $key ][] = (int) $object->data['id'];
						}

						$wp_invoice->data['linked_objects_ids'][ $key ][] = (int) $value;
					}
				}
			}
		}

		// @todo: Vérifier si cette action est connecté, et que fait-il ?
		do_action( 'wps_synchro_invoice', $wp_invoice->data, $doli_invoice );

		return $wp_invoice;
	}

	/**
	 * Ajoute une ligne sur la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order      Les données d'une commande.
	 * @param array      $line_data  La donnée à ajouté.
	 */
	public function add_line( $order, $line_data ) {
		$order->data['lines'][] = $line_data;

		$this->update( $order->data );
	}

	/**
	 * Met à jour une ligne sur la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order      Les données d'une commande.
	 * @param array      $line_data  La donnée à ajouté.
	 */
	public function update_line( $order, $line_data ) {
		$founded_line = null;
		$key_line     = null;
		// Search line by rowid.
		if ( ! empty( $order->data['lines'] ) ) {
			foreach ( $order->data['lines'] as $key => $line ) {
				if ( $line['rowid'] == $line_data['rowid'] ) {
					$founded_line = $line;
					$key_line     = $key;
					break;
				}
			}
		}

		if ( $founded_line != null ) {
			array_splice( $order->data['lines'], $key_line, 1 );

			$order->data['lines'][] = $line_data;

			$this->update( $order->data );
		}
	}

	/**
	 * Supprime une ligne sur la commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order   Les données d'une commande.
	 * @param integer    $row_id  L'id de la ligne.
	 */
	public function delete_line( $order, $row_id ) {
		$founded_line = null;
		$key_line     = null;
		// Search line by rowid.
		if ( ! empty( $order->data['lines'] ) ) {
			foreach ( $order->data['lines'] as $key => $line ) {
				if ( $line['rowid'] == $row_id ) {
					$founded_line = $line;
					$key_line     = $key;
					break;
				}
			}
		}

		if ( $founded_line != null ) {
			array_splice( $order->data['lines'], $key_line, 1 );

			$this->update( $order->data );
		}
	}
}

Doli_Invoice::g();
