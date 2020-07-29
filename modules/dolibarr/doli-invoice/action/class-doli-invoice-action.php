<?php
/**
 * La classe gérant les actions des factures de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Invoice Action Class.
 */
class Doli_Invoice_Action {

	/**
	 * Définition des metaboxes sur la page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $metaboxes = null;

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'create_tmp_invoice_dir' ) );

		// add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

		add_action( 'wps_payment_complete', array( $this, 'create_invoice' ), 20, 1 );

		add_action( 'admin_post_wps_download_invoice', array( $this, 'download_invoice' ) );

		$this->metaboxes = array(
			'wps-invoice-customer' => array(
				'callback' => array( $this, 'callback_meta_box' ),
			),
			'wps-invoice-products' => array(
				'callback' => array( $this, 'meta_box_product' ),
			),
		);
	}

	/**
	 * Créer un répertoire temporaire pour les factures.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function create_tmp_invoice_dir() {
		$dir = wp_upload_dir();

		$path = $dir['basedir'] . '/invoices';

		if ( wp_mkdir_p( $path ) && ! file_exists( $path . '/.htaccess' ) ) {
			$f = fopen( $path . '/.htaccess', 'a+' );
			fwrite( $f, "Options -Indexes\r\ndeny from all" );
			fclose( $f );
		}
	}

	/**
	 * Ajoute la metabox details.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function add_meta_box() {
		if ( isset( $_GET['id'] ) && isset( $_GET['page'] ) && 'wps-invoice' === $_GET['page'] ) {
			$id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;

			$invoice = Doli_Invoice::g()->get( array( 'id' => $id ), true );

			$args_metabox = array(
				'invoice' => $invoice,
				'id'      => $id,
			);

			/* translators: Order details CO00010 */
			$box_invoice_detail_title = sprintf( __( 'Invoice details %s', 'wpshop' ), $invoice->data['title'] );

			add_meta_box( 'wps-invoice-customer', $box_invoice_detail_title, array( $this, 'callback_meta_box' ), 'wps-invoice', 'normal', 'default', $args_metabox );
		}
	}

	/**
	 * Initialise la page "Facture".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'wpshop', __( 'Invoices', 'wpshop' ), __( 'Invoices', 'wpshop' ), 'manage_options', 'wps-invoice', array( $this, 'callback_add_menu_page' ) );
	}

	/**
	 * Affichage de la vue du menu.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_menu_page() {
		if ( isset( $_GET['id'] ) ) {
			$id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;

			$invoice = Doli_Invoice::g()->get( array( 'id' => $id ), true );

			if ( ! empty( $this->metaboxes ) ) {
				foreach ( $this->metaboxes as $key => $metabox ) {
					add_action( 'wps_invoice', $metabox['callback'], 10, 1 );
				}
			}

			View_Util::exec( 'wpshop', 'doli-invoice', 'single', array( 'invoice' => $invoice ) );
		} else {
			$args = array(
				'post_type'      => 'wps-doli-invoice',
				'posts_per_page' => -1,
				'post_status'    => 'any',
			);

			$count = count( get_posts( $args ) );

			View_Util::exec( 'wpshop', 'doli-invoice', 'main', array(
				'count' => $count,
			) );
		}
	}

	/**
	 * La metabox des détails de la facture.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Invoice $invoice Les données d'une facture.
	 */
	public function callback_meta_box( $invoice ) {
		$third_party  = Third_Party::g()->get( array( 'id' => $invoice->data['third_party_id'] ), true );
		$link_invoice = '';

		if ( ! empty( $invoice ) ) {
			$invoice->data['payments'] = array();
			$invoice->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $invoice->data['id'] ) );
			$link_invoice              = admin_url( 'admin-post.php?action=wps_download_invoice_wpnonce=' . wp_create_nonce( 'download_invoice' ) . '&invoice_id=' . $invoice->data['id'] );

			$invoice->data['order'] = Doli_Order::g()->get( array( 'id' => $invoice->data['parent_id'] ), true );
		}

		View_Util::exec( 'wpshop', 'doli-invoice', 'metabox-invoice-details', array(
			'invoice'      => $invoice,
			'third_party'  => $third_party,
			'link_invoice' => $link_invoice,
		) );
	}

	/**
	 * La metabox affichant les produits de la commande
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Invoice $invoice Les données d'une facture.
	 */
	public function meta_box_product( $invoice ) {
		$tva_lines = array();

		if ( ! empty( $invoice->data['lines'] ) ) {
			foreach ( $invoice->data['lines'] as $line ) {
				if ( empty( $tva_lines[ $line['tva_tx'] ] ) ) {
					$tva_lines[ $line['tva_tx'] ] = 0;
				}

				$tva_lines[ $line['tva_tx'] ] += $line['total_tva'];
			}
		}

		View_Util::exec( 'wpshop', 'order', 'review-order', array(
			'object'    => $invoice,
			'tva_lines' => $tva_lines,
		) );
	}

	/**
	 * Créer la facture si la commande est payé.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param array $data Data from PayPal.
	 */
	public function create_invoice( $data ) {
		$doli_order   = Request_Util::get( 'orders/' . (int) $data['custom'] );
		$doli_invoice = Request_Util::post( 'invoices/createfromorder/' . (int) $data['custom'] );
		Request_Util::post( 'invoices/' . $doli_invoice->id . '/validate', array(
			'notrigger' => 0,
		) );

		$order = Doli_Order::g()->get( array( 'schema' => true ), true );
		$order = Doli_Order::g()->doli_to_wp( $doli_order, $order, true );

		$invoice = Doli_Invoice::g()->get( array( 'schema' => true ), true );
		$doli_invoice = Request_Util::get( 'invoices/' . $doli_invoice->id );
		LOG_Util::log( sprintf( $doli_invoice->id . '|' . $doli_invoice->socid ), 'wpshop2' );
		$wp_invoice = Doli_Invoice::g()->doli_to_wp( $doli_invoice, $invoice, true );

		$doli_payment = Request_Util::post( 'invoices/' . $doli_invoice->id . '/payments', array(
			'datepaye'          => current_time( 'timestamp' ),
			'paiementid'        => (int) $doli_invoice->mode_reglement_id,
			'closepaidinvoices' => 'yes',
			'accountid'         => 1, // @todo: Handle account id. PayPal can be 1, Stripe can be 2, Crédit agricole can be 3.
		) );


		//$third_party = Third_Party::g()->get( array( 'id' => (int) $doli_invoice->socid ), true );
		//$third_party = Third_Party::g()->get( array( 'external_id' => $doli_invoice->socid ), true );
		//$contact     = User::g()->get( array( 'third_party_id' => $doli_invoice->socid ), true );

		//$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		//$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		global $wpdb;

		$query = $wpdb->prepare( "
			SELECT post_id FROM $wpdb->postmeta
			WHERE meta_key = '_external_id'
			AND meta_value = %d
		", $doli_order->socid );

		$post_id = $wpdb->get_var($query);

		$third_party_email = get_post_meta( $post_id, 'email', true );

		Request_Util::put( 'documents/builddoc', array(
			'modulepart'   => 'invoice',
			'original_file' => $doli_invoice->ref . '/' . $doli_invoice->ref . '.pdf',
		) );

		$invoice_file = Request_Util::get( 'documents/download?modulepart=facture&original_file=' . $doli_invoice->ref . '/' . $doli_invoice->ref . '.pdf' );
		$content      = base64_decode( $invoice_file->content );

		// @todo: Pourquoi déjà ?
		$dir       = wp_upload_dir();
		$path      = $dir['basedir'] . '/invoices';
		$path_file = $path . '/' . $doli_invoice->ref . '.pdf';

		$f = fopen( $path . '/' . $doli_invoice->ref . '.pdf', 'a+' );
		fwrite( $f, $content );
		fclose( $f );

		Emails::g()->send_mail( $third_party_email, 'customer_invoice', array(
			'order'       => $order,
			'invoice'     => $wp_invoice,
			//'third_party' => $third_party[0]->data,
			//'contact'     => $contact,
			'attachments' => array( $path_file ),
		) );

		// // translators: Send the invoice 000001 to the email contact text@eoxia.com.
		LOG_Util::log( sprintf( 'Send the invoice %s to the email contact %s', $wp_invoice->data['title'], $third_party_email ), 'wpshop2' );

		unlink( $path_file );
	}

	/**
	 * Télécharge la facture.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function download_invoice() {
		check_admin_referer( 'download_invoice' );

		$invoice_id = ! empty( $_GET['invoice_id'] ) ? (int) $_GET['invoice_id'] : 0;
		$avoir      = ! empty( $_GET['avoir'] ) ? (int) $_GET['avoir'] : 0;

		if ( ! $invoice_id ) {
			exit;
		}

		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );
		$invoice     =  Request_Util::get( 'invoices/' . $invoice_id );

		if ( ( isset( $third_party->data ) && (int) $invoice->socid !== $third_party->data['external_id'] ) && ! current_user_can( 'administrator' ) ) {
			exit;
		}

		// translators: Contact test@eoxia.com download the invoice 00001.
		LOG_Util::log( sprintf( 'Contact %s download the invoice %s', $contact->data['email'], $invoice->ref ), 'wpshop2' );

		$invoice_file = Request_Util::get( 'documents/download?modulepart=facture&original_file=' . $invoice->ref . '/' . $invoice->ref . '.pdf' );

		if (! $invoice_file ) {
			exit( __( 'Invoice not found', 'wpshop' ) );
		}

		$content = base64_decode( $invoice_file->content );

		header( 'Cache-Control: no-cache' );
		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: inline; filename="' . $invoice->data['title'] . '.pdf"' );
		header( 'Content-Length: ' . strlen( $content ) );

		echo $content;

		exit;
	}
}

new Doli_Invoice_Action();
