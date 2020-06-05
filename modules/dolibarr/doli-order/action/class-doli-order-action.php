<?php
/**
 * Les actions relatives aux commandes avec Dolibarr.
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

use stdClass;
use Stripe\ApiOperations\Request;
use Stripe\Order;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Order Action Class.
 */
class Doli_Order_Action {

	/**
	 * Définition des metabox sur la page.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $metaboxes = null;

	/**
	 * Initialise les actions liées aux proposals.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'create_tmp_order_dir' ) );

		add_action( 'admin_init', array( $this, 'callback_admin_init' ) );

		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );

		add_action( 'wps_checkout_create_order', array( $this, 'create_order' ), 10, 1 );
		add_action( 'wps_payment_complete', array( $this, 'set_to_billed' ), 30, 1 );
		add_action( 'wps_payment_failed', array( $this, 'set_to_failed' ), 30, 1 );

		add_action( 'admin_post_wps_download_order', array( $this, 'download_order' ) );

		add_action( 'wp_ajax_mark_as_delivery', array( $this, 'mark_as_delivery' ) );

		$this->metaboxes = array(
			'wps-order-details' => array(
				'callback' => array( $this, 'metabox_order_details' ),
			),
//			'wps-order-shipment-tracking' => array(
//				'callback' => array( $this, 'metabox_shipment_tracking' ),
//			),
			'wps-order-payment' => array(
				'callback' => array( $this, 'metabox_order_payment' ),
			),
			'wps-order-review'  => array(
				'callback' => array( $this, 'metabox_order_review' ),
			),
			'wps-order-related-object'  => array(
				'callback' => array( $this, 'metabox_order_related_object' ),
			),
		);
	}

	/**
	 * Ajoutes des status dans la commande.
	 *
	 * @since 2.0.0
	 */
	public function callback_admin_init() {
		remove_post_type_support( 'wps-order', 'title' );
		remove_post_type_support( 'wps-order', 'editor' );
		remove_post_type_support( 'wps-order', 'excerpt' );

		register_post_status( 'wps-delivered', array(
			'label'                     => _x( 'Delivered', 'Order status', 'wpshop' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Delivered <span class="count">(%s)</span>', 'Delivered <span class="count">(%s)</span>', 'wpshop' ),
		) );

		register_post_status( 'wps-canceled', array(
			'label'                     => _x( 'Canceled', 'Order status', 'wpshop' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Canceled <span class="count">(%s)</span>', 'Canceled <span class="count">(%s)</span>', 'wpshop' ),
		) );
	}

	/**
	 * Créer un répertoire temporaire pour les factures.
	 *
	 * @since 2.0.0
	 */
	public function create_tmp_order_dir() {
		$dir = wp_upload_dir();

		$path = $dir['basedir'] . '/orders';

		if ( wp_mkdir_p( $path ) && ! file_exists( $path . '/.htaccess' ) ) {
			$f = fopen( $path . '/.htaccess', 'a+' );
			fwrite( $f, "Options -Indexes\r\ndeny from all" );
			fclose( $f );
		}
	}

	/**
	 * Initialise la page "Commande".
	 *
	 * @since 2.0.0
	 */
	public function callback_admin_menu() {
		if ( Settings::g()->dolibarr_is_active() ) {
			$hook = add_submenu_page( 'wpshop', __( 'Orders', 'wpshop' ), __( 'Orders', 'wpshop' ), 'manage_options', 'wps-order', array( $this, 'callback_add_menu_page' ) );

			if ( ! isset( $_GET['id'] ) ) {
				add_action( 'load-' . $hook, array( $this, 'callback_add_screen_option' ) );
			}
		}
	}

	/**
	 * Affichage de la vue du menu
	 *
	 * @since 2.0.0
	 */
	public function callback_add_menu_page() {
		if ( isset( $_GET['id'] ) ) {
			// Single page.
			$id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;

			$doli_order = Request_Util::get( 'orders/' . $id );
			$wp_order   = Doli_Order::g()->get( array( 'schema' => true ), true );
			$wp_order   = Doli_Order::g()->doli_to_wp( $doli_order, $wp_order, true );

			$wp_order->data['datec'] = \eoxia\Date_Util::g()->fill_date( $wp_order->data['datec'] );

			$third_party = Third_Party::g()->get( array( 'id' => $wp_order->data['parent_id'] ), true );

			if ( ! empty( $this->metaboxes ) ) {
				foreach ( $this->metaboxes as $key => $metabox ) {
					add_action( 'wps_order', $metabox['callback'], 10, 1 );
				}
			}

			\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'single', array(
				'third_party' => $third_party,
				'order'       => $wp_order,
			) );
		} else {
			// Listing page.
			// @todo: Doublon avec Class Doli Order display() ?
			$per_page = get_user_meta( get_current_user_id(), Doli_Order::g()->option_per_page, true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
			$dolibarr_url    = $dolibarr_option['dolibarr_url'];
			$dolibarr_create_order    = $dolibarr_option['dolibarr_create_order'];

			if ( empty( $per_page ) || 1 > $per_page ) {
				$per_page = Doli_Order::g()->limit;
			}

			$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

			$count        = Doli_Order::g()->search( $s, array(), true );
			$number_page  = ceil( $count / $per_page );
			$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

			$base_url = admin_url( 'admin.php?page=wps-order' );

			$begin_url = $base_url . '&current_page=1';
			$end_url   = $base_url . '&current_page=' . $number_page;

			$prev_url = $base_url . '&current_page=' . ( $current_page - 1 );
			$next_url = $base_url . '&current_page=' . ( $current_page + 1 );

			if ( ! empty( $s ) ) {
				$begin_url .= '&s=' . $s;
				$end_url   .= '&s=' . $s;
				$prev_url  .= '&s=' . $s;
				$next_url  .= '&s=' . $s;
			}

			\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'main', array(
				'number_page'  => $number_page,
				'current_page' => $current_page,
				'count'        => $count,
				'begin_url'    => $begin_url,
				'end_url'      => $end_url,
				'prev_url'     => $prev_url,
				'next_url'     => $next_url,
				's'            => $s,

				'dolibarr_create_order' => $dolibarr_create_order,
				'dolibarr_url'    => $dolibarr_url,
			) );
		}
	}

	/**
	 * Ajoutes le menu "Options de l'écran".
	 *
	 * @since 2.0.0
	 */
	public function callback_add_screen_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => _x( 'Orders', 'Order per page', 'wpshop' ),
				'default' => Doli_Order::g()->limit,
				'option'  => Doli_Order::g()->option_per_page,
			)
		);
	}

	/**
	 * La metabox des détails de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param Order $order Les données de la commande.
	 */
	public function metabox_order_details( $order ) {
		$third_party  = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );

		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'metabox-order-details', array(
			'order'       => $order,
			'third_party' => $third_party,
		) );
	}

	/**
	 * La metabox des détails de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param  Order $order Les données de la commande.
	 */
	public function metabox_order_payment( $order ) {
		$doli_invoices = array();
		$wp_invoices   = array();

		// Facture is the key getted from Dolibarr. Facture is french name for say "Invoice".
		if ( ! empty( $order->data['linked_objects_ids']['facture'] ) ) {
			$route = 'invoices?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=';
			foreach ( $order->data['linked_objects_ids']['facture'] as $doli_invoice_id ) {
				$route .= '(t.rowid:=:' . $doli_invoice_id . ') or';
			}

			$route = substr( $route, 0, strlen( $route ) - 3 );
			$doli_invoices = Request_Util::get( $route );
		}

		$invoices = Doli_Invoice::g()->convert_to_wp_invoice_format( $doli_invoices );

		$already_paid       = 0;
		$total_ttc_invoices = 0;
		$remaining_unpaid   = 0;

		if ( ! empty( $invoices ) ) {
			foreach ( $invoices as &$invoice ) {
				$invoice->data['payments'] = array();
				$invoice->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $invoice->data['id'] ) );
				$invoice->data['link_pdf'] = admin_url( 'admin-post.php?action=wps_download_invoice_wpnonce=' . wp_create_nonce( 'download_invoice' ) . '&order_id=' . $order->data['id'] );

				$already_paid += $invoice->data['totalpaye'];
				$total_ttc_invoices += $invoice->data['total_ttc'];
			}
		}
		$remaining_unpaid = $total_ttc_invoices - $already_paid;

		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'metabox-order-payment', array(
			'order'              => $order,
			'invoices'           => $invoices,
			'already_paid'       => $already_paid,
			'total_ttc_invoices' => $total_ttc_invoices,
			'remaining_unpaid'   => $remaining_unpaid,
		) );
	}

	public function metabox_shipment_tracking( $order ) {
		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'metabox-shipment-tracking', array(
			'order' => $order,
		) );
	}

	/**
	 * Box affichant les produits de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param Order $order Les données de la commande.
	 */
	public function metabox_order_review( $order ) {
		$tva_lines = array();

		if ( ! empty( $order->data['lines'] ) ) {
			foreach ( $order->data['lines'] as $line ) {
				if ( empty( $tva_lines[ $line['tva_tx'] ] ) ) {
					$tva_lines[ $line['tva_tx'] ] = 0;
				}

				$tva_lines[ $line['tva_tx'] ] += $line['total_tva'];
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'order', 'review-order', array(
			'object'    => $order,
			'tva_lines' => $tva_lines,
		) );
	}

	/**
	 * Box affichant les actions de la commande.
	 *
	 * @since 2.0.0
	 *
	 * @param Order $order Les données de la commande.
	 */
	public function callback_order_action( $order ) {
		$order = $callback_args['args']['order'];

		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'metabox-action', array(
			'order' => $order,
		) );
	}

	/**
	 * Box affichant les produits de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param Order $order Les données de la commande.
	 */
	public function metabox_order_related_object( $order ) {
		// \eoxia\View_Util::exec( 'wpshop', 'doli-order', 'metabox-order-related-object', array() );
	}

	/**
	 * Création d'une commande lors du tunnel de vente.
	 *
	 * @since 2.0.0
	 *
	 * @param  stdClass $proposal Les données du devis.
	 * @return Order_Model           Les données de la commande WP.
	 */
	public function create_order( $proposal ) {
		\eoxia\LOG_Util::log( sprintf( 'Dolibarr call POST /orders/createfromproposal/ with data %s', $proposal->id ), 'wpshop2' );
		$doli_order = Request_Util::post( 'orders/createfromproposal/' . $proposal->id );
		\eoxia\LOG_Util::log( sprintf( 'Dolibarr call POST /orders/createfromproposal/ response %s', json_encode( $doli_order ) ), 'wpshop2' );


		\eoxia\LOG_Util::log( sprintf( 'Dolibarr call POST /orders/%s/validate', $doli_order->id ), 'wpshop2' );
		Request_Util::post( 'orders/' . $doli_order->id . '/validate' );

		$doli_order  = Request_Util::get( 'orders/' . $doli_order->id );
		$third_party = Third_Party::g()->get( array( 'external_id' => $doli_order->socid ), true );

		Request_Util::put( 'documents/builddoc', array(
			'modulepart'    => 'order',
			'original_file' => $doli_order->ref . '/' . $doli_order->ref . '.pdf',
		) );

		$current_user = wp_get_current_user();

		Emails::g()->send_mail( $third_party[0]->data['email'], 'customer_current_order', array(
			'order'       => $doli_order,
			'third_party' => $third_party[0]->data,
		) );

		// translators: Create order 00001 for the third party Eoxia.
		\eoxia\LOG_Util::log( sprintf( 'Create order %s for the third party %s', $doli_order->ref, $third_party->data['title'] ), 'wpshop2' );

		$wp_order = Doli_Order::g()->get( array( 'schema' => true ), true );
		$wp_order = Doli_Order::g()->doli_to_wp( $doli_order, $wp_order, true );

		return $wp_order;
	}

	/**
	 * Passes la commande à payé.
	 *
	 * @since 2.0.0
	 *
	 * @param array $data Les données IPN de PayPal.
	 */
	public function set_to_billed( $data ) {
		$doli_order  = Request_Util::post( 'orders/' . (int) $data['custom'] . '/setinvoiced' );
		$order       = Doli_Order::g()->get( array( 'schema' => true ), true );
		$order       = Doli_Order::g()->doli_to_wp( $doli_order, $order, true );

		//$third_party = Third_Party::g()->get( array( 'external_id' => $doli_order->socid ), true );

		global $wpdb;

		$query = $wpdb->prepare( "
			SELECT post_id FROM $wpdb->postmeta
			WHERE meta_key = '_external_id'
			AND meta_value = %d
		", $doli_order->socid );

		$post_id = $wpdb->get_var($query);

		$third_party_email = get_post_meta( $post_id, 'email', true );

		$order_file = Request_Util::get( 'documents/download?modulepart=order&original_file=' . $doli_order->ref . '/' . $doli_order->ref . '.pdf' );
		$content = base64_decode( $order_file->content );

		$dir       = wp_upload_dir();
		$path      = $dir['basedir'] . '/orders';
		$path_file = $path . '/' . $doli_order->ref . '.pdf';

		$f = fopen( $path . '/' . $doli_order->ref . '.pdf', 'a+' );
		fwrite( $f, $content );
		fclose( $f );

		Emails::g()->send_mail( $third_party_email, 'customer_paid_order', array(
			'order_id'    => $doli_order->id,
			'order'       => $doli_order,
			//'third_party' => $third_party[0]->data,
			'attachments' => array( $path_file ),
		) );

		// translators: Update the order 00001 to billed.
		\eoxia\LOG_Util::log( sprintf( 'Update the order %s to billed', $doli_order->ref ), 'wpshop2' );

		unlink( $path_file );
	}

	/**
	 * Passes la commande à payment échoué.
	 *
	 * @since 2.0.0
	 *
	 * @param array $data Les données IPN de PayPal.
	 *
	 * @todo: Fix
	 */
	public function set_to_failed( $data ) {
		$wp_order = Doli_Order::g()->get( array( 'id' => (int) $data['custom'] ), true );

		$wp_order->data['payment_failed'] = true;
		Doli_Order::g()->update( $wp_order->data );
		update_post_meta( $wp_order->data['id'], '_traitment_in_progress', false );

		// translators: Update the order 00001 to failed.
		\eoxia\LOG_Util::log( sprintf( 'Update the order %s to failed', $wp_order->data['title'] ), 'wpshop2' );
	}

	/**
	 * Télécharges la commande au format PDT.
	 *
	 * @since 2.0.0
	 */
	public function download_order() {
		check_admin_referer( 'download_order' );

		$order_id = ! empty( $_GET['order_id'] ) ? (int) $_GET['order_id'] : 0;

		if ( ! $order_id ) {
			exit;
		}

		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );
		$doli_order  = Request_Util::get( 'orders/' . $order_id );
		$order       = Doli_Order::g()->get( array( 'schema' => true ), true );
		$order       = Doli_Order::g()->doli_to_wp( $doli_order, $order, true );

		if ( ( isset( $third_party->data ) && $order->data['parent_id'] !== $third_party->data['id'] ) && ! current_user_can( 'administrator' ) ) {
			exit;
		}

		// translators: Download the order 00001.
		\eoxia\LOG_Util::log( sprintf( 'Download the order %s', $order->data['title'] ), 'wpshop2' );
		$order_file = Request_Util::get( 'documents/download?modulepart=order&original_file=' . $order->data['title'] . '/' . $order->data['title'] . '.pdf' );
		$content = base64_decode( $order_file->content );

		header( 'Cache-Control: no-cache' );
		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: inline; filename="' . $order->data['title'] . '.pdf"' );
		header( 'Content-Length: ' . strlen( $content ) );

		echo $content;
		exit;
	}

	/**
	 * @todo: nonce
	 * @return [type] [description]
	 */
	public function mark_as_delivery() {
		$id           = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$tracking_url = ! empty( $_POST['tracking_url'] ) ? sanitize_text_field( $_POST['tracking_url'] ) : '';

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$order = Doli_Order::g()->get( array( 'id' => $id ), true );

		$doli_order = Request_Util::post( 'orders/' . $order->data['external_id'] . '/close', array(
			'notrigger' => 1,
		) );

		$order = Doli_Order::g()->doli_to_wp( $doli_order, $order );
		$order->data['tracking_link'] = $tracking_url;
		update_post_meta( $order->data['id'], '_tracking_link', $tracking_url );

		$third_party = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );
		$contact     = Contact::g()->get( array( 'id' => $order->data['author_id'] ), true );
		Emails::g()->send_mail( $contact->data['email'], 'customer_delivered_order', array(
			'order'       => $order,
			'third_party' => $third_party,
			'contact'     => $contact,
		) );

		// // translators: Send the invoice 000001 to the email contact text@eoxia.com.
		\eoxia\LOG_Util::log( sprintf( 'Send the invoice %s to the email contact %s', $wp_invoice->data['title'], $contact->data['email'] ), 'wpshop2' );

		ob_start();
		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'metabox-shipment-tracking', array(
			'order'       => $order,
		) );
		wp_send_json_success( array(
			'view'             => ob_get_clean(),
			'namespace'        => 'wpshop',
			'module'           => 'doliOrder',
			'callback_success' => 'markedAsDelivery',
		) );
	}
}

new Doli_Order_Action();
