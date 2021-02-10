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
use stdClass;

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
	 * La limite par page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var integer
	 */
	public $limit = 10;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	public $option_per_page = 'invoice_doli_per_page';

	/**
	 * Appel la vue "list" du module "doli-invoice".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$per_page        = get_user_meta( get_current_user_id(), Doli_Invoice::g()->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Doli_Invoice::g()->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$route = 'invoices?sortfield=t.rowid&sortorder=DESC&limit=' . $per_page . '&page=' . ( $current_page - 1 );

		if ( ! empty( $s ) ) {
			// La route de dolibarr ne fonctionne pas avec des caractères en base10
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_invoices = Request_Util::get( $route );
		$invoices      = $this->convert_to_wp_invoice_format( $doli_invoices );

		if ( ! empty( $invoices ) ) {
			foreach ( $invoices as &$element ) {

				$element->data['tier']  = null;
				$element->data['order'] = null;

				if ( isset( $element->data['linked_objects_ids']['commande'][0] ) ) {
					$doli_order = Request_Util::get( 'orders/' . $element->data['linked_objects_ids']['commande'][0] );
					$wp_order   = Doli_Order::g()->get( array( 'schema' => true ), true );
					$element->data['order'] = Doli_Order::g()->doli_to_wp( $doli_order, $wp_order, true );
				}

				if ( ! empty( $element->data['third_party_id'] ) ) {
					$element->data['tier'] = Third_Party::g()->get( array( 'id' => $element->data['third_party_id'] ), true );
				}
			}
		}

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
	 * Convertit un tableau Invoice Object provenant de Dolibarr vers un format Invoice Object WPshop afin de normisé pour l'affichage.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  stdClass $doli_invoices Le tableau contenant toutes les données des factures provenant de Dolibarr.
	 *
	 * @return Doli_Invoice            Le tableau contenant toutes les données des factures convertis depuis le format de Dolibarr.
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
	 * @param  stdClass     $doli_invoice Les données d'une facture Dolibarr.
	 * @param  Doli_Invoice $wp_invoice   Les données d'une facture WordPress.
	 * @param  boolean      $only_convert Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Doli_Invoice               Les données d'une facture WordPress avec ceux de Dolibarr.
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
		$wp_invoice->data['datec']          = ! empty( $doli_invoice->date_creation ) ? date( 'Y-m-d H:i:s', $doli_invoice->date_creation ) : false;
		$wp_invoice->data['date_invoice']   = ! empty( $doli_invoice->date ) ? date( 'Y/m/d', $doli_invoice->date ) : false;
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
	 * Fonction de recherche.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre d'éléments trouvés.
	 */
	public function search( $s = '', $default_args = array(), $count = false ) {
		$route = 'invoices?sortfield=t.rowid&sortorder=DESC';

		if ( ! empty( $s ) ) {
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_invoices = Request_Util::get( $route );

		if ( $count && ! empty( $doli_invoices ) ) {
			return count( $doli_invoices );
		} else {
			return 0;
		}
	}
}

Doli_Invoice::g();
