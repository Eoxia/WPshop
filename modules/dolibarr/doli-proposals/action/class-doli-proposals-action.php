<?php
/**
 * La classe gérant les actions des des propositions commerciales de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Proposals Action Class.
 */
class Doli_Proposals_Action {

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
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 50 );

		add_action( 'admin_post_wps_download_proposal', array( $this, 'download_proposal' ) );

		add_action( 'admin_post_convert_to_order_and_pay', array( $this, 'convert_to_order_and_pay' ) );

		add_action( 'wps_payment_complete', array( $this, 'set_to_billed' ), 40, 1 );

		$this->metaboxes = array(
			'wps-proposal-details' => array(
				'callback' => array( $this, 'metabox_proposal_details' ),
			),
			'wps-proposal-address' => array(
				'callback' => array( $this, 'metabox_proposal_address' ),
			),
			'wps-proposal-review'  => array(
				'callback' => array( $this, 'metabox_proposal_review' ),
			),
		);
	}

	/**
	 * Initialise la page "Propositions commerciales".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_admin_menu() {
		if ( Settings::g()->dolibarr_is_active() ) {

//			$hook = add_submenu_page( 'wpshop', __( 'Dolibarr Proposals', 'wpshop' ), __( 'Dolibarr Proposals', 'wpshop' ), 'manage_options', 'wps-proposal-doli', array( $this, 'callback_add_menu_page' ) );
//
//			if ( ! isset( $_GET['id'] ) ) {
//				add_action( 'load-' . $hook, array( $this, 'callback_add_screen_option' ) );
//			}

			if ( user_can( get_current_user_id(), 'manage_options' ) ) {
				CMH::register_menu( 'wpshop', __( 'Dolibarr Proposals', 'wpshop' ), __( 'Dolibarr Proposals', 'wpshop' ), 'manage_options', 'wps-proposal-doli', array( $this, 'callback_add_menu_page' ), 'fas fa-file-signature', 5 );
			}
		}
	}

	/**
	 * Ajoute la page "Options de l'écran" pour les propositions commerciales.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_screen_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => _x( 'Proposals Dolibarr', 'Proposal Dolibarr per page', 'wpshop' ),
				'default' => Doli_Proposals::g()->limit,
				'option'  => Doli_Proposals::g()->option_per_page,
			)
		);
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
			$doli_proposal   = Request_Util::get( 'proposals/' . $id );
			$wp_proposal     = Proposals::g()->get( array( 'schema' => true ), true );
			$wp_proposal     = Doli_Proposals::g()->doli_to_wp( $doli_proposal, $wp_proposal, true );
			//$proposal      = Proposals::g()->get( array( 'id' => $id ), true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

			$third_party = Third_Party::g()->get( array( 'id' => $wp_proposal->data['parent_id'] ), true );

			if ( ! empty( $this->metaboxes ) ) {
				foreach ( $this->metaboxes as $key => $metabox ) {
					add_action( 'wps_proposal', $metabox['callback'], 10, 1 );
				}
			}

			View_Util::exec( 'wpshop', 'proposals', 'single', array(
				'third_party' => $third_party,
				'proposal'    => $wp_proposal,
				'doli_url'    => $dolibarr_option['dolibarr_url'],
			) );
		} else {
			$per_page = get_user_meta( get_current_user_id(), Doli_Proposals::g()->option_per_page, true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
			$dolibarr_url = $dolibarr_option['dolibarr_url'];
			$dolibarr_create_proposal    = $dolibarr_option['dolibarr_create_proposal'];

			if ( empty( $per_page ) || 1 > $per_page ) {
				$per_page = Third_Party::g()->limit;
			}

			$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

			$count        = Doli_Proposals::g()->search( $s, array(), true );
			$number_page  = ceil( $count / $per_page );
			$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

			$base_url = admin_url( 'admin.php?page=wps-proposal-doli' );

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

			View_Util::exec( 'wpshop', 'doli-proposals', 'main', array(
				'number_page'  => $number_page,
				'current_page' => $current_page,
				'count'        => $count,
				'begin_url'    => $begin_url,
				'end_url'      => $end_url,
				'prev_url'     => $prev_url,
				'next_url'     => $next_url,
				's'            => $s,

				'dolibarr_create_proposal' => $dolibarr_create_proposal,
				'dolibarr_url' => $dolibarr_url,
			) );
		}
	}

	/**
	 * La metabox des détails d'une proposition commerciale.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Proposals $proposal Les données d'une proposition commerciale.
	 */
	public function metabox_proposal_details( $proposal ) {
		$third_party  = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
		$invoice      = null;
		$link_invoice = '';

		if ( Settings::g()->dolibarr_is_active() ) {
			$invoice = Doli_Invoice::g()->get( array( 'post_parent' => $proposal->data['id'] ), true );

			if ( ! empty( $invoice ) ) {
				$invoice->data['payments'] = array();
				$invoice->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $invoice->data['id'] ) );
				$link_invoice              = admin_url( 'admin-post.php?action=wps_download_invoice_wpnonce=' . wp_create_nonce( 'download_invoice' ) . '&proposal_id=' . $proposal->data['id'] );
			}
		}

		View_Util::exec( 'wpshop', 'proposals', 'metabox-proposal-details', array(
			'proposal'     => $proposal,
			'third_party'  => $third_party,
			'invoice'      => $invoice,
			'link_invoice' => $link_invoice,
		) );
	}

	/**
	 * La metabox contenant l'adresse du client.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Proposals $proposal Les données d'une proposition commerciale.
	 */
	public function metabox_proposal_address( $proposal ) {
		$third_party  = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
		$invoice      = null;
		$link_invoice = '';

		if ( Settings::g()->dolibarr_is_active() ) {
			$invoice = Doli_Invoice::g()->get( array( 'post_parent' => $proposal->data['id'] ), true );

			if ( ! empty( $invoice ) ) {
				$invoice->data['payments'] = array();
				$invoice->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $invoice->data['id'] ) );
				$link_invoice              = admin_url( 'admin-post.php?action=wps_download_invoice_wpnonce=' . wp_create_nonce( 'download_invoice' ) . '&proposal_id=' . $proposal->data['id'] );
			}
		}

		View_Util::exec( 'wpshop', 'proposals', 'metabox-proposal-address', array(
			'proposal'     => $proposal,
			'third_party'  => $third_party,
			'invoice'      => $invoice,
			'link_invoice' => $link_invoice,
		) );
	}

	/**
	 * La métabox affichant un résumé d'une proposition commerciale.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Proposals $proposal Les données d'une proposition commerciale.
	 */
	public function metabox_proposal_review( $proposal ) {
		$tva_lines = array();
		if ( ! empty( $proposal->data['lines'] ) ) {
			foreach ( $proposal->data['lines'] as &$line ) {
				if ( empty( $tva_lines[ $line['tva_tx'] ] ) ) {
					$tva_lines[ $line['tva_tx'] ] = 0;
				}

				$tva_lines[ $line['tva_tx'] ] += empty( $line['total_tva'] ) ? $line['tva_amount'] * $line['qty'] : $line['total_tva'];

				if ( empty( $line['libelle'] ) && ! empty( $line['title'] ) ) {
					$line['libelle'] = $line['title'];
				}

				if ( empty( $line['subprice'] ) && isset( $line['price'] ) ) {
					$line['subprice'] = $line['price'];
				}

				if ( empty( $line['total_ht'] ) && isset( $line['price'] ) ) {
					$line['total_ht'] = $line['price'] * $line['qty'];
				}
			}
		}

		if ( Settings::g()->dolibarr_is_active() ) {
			View_Util::exec( 'wpshop', 'order', 'review-order', array(
				'object'    => $proposal,
				'tva_lines' => $tva_lines,
			) );
		} else {
			View_Util::exec( 'wpshop', 'proposals', 'metabox-proposal-products', array(
				'proposal'  => $proposal,
				'tva_lines' => $tva_lines,
			) );
		}
	}

	/**
	 * Télécharges le une proposition commerciale.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function download_proposal() {
		check_admin_referer( 'download_proposal' );

		$proposal_id = ! empty( $_GET['proposal_id'] ) ? (int) $_GET['proposal_id'] : 0;

		if ( ! $proposal_id ) {
			exit;
		}

		$contact       = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party   = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );
		$doli_proposal = Request_Util::get( 'proposals/' . $proposal_id );

		if ( $doli_proposal->socid != $third_party->data['external_id'] && ! current_user_can( 'administrator' ) ) {
			exit;
		}

		// tanslators: Contact test download proposal test@eoxia.com.
		\eoxia\LOG_Util::log( sprintf( 'Contact %s download proposal %s', $doli_proposal->ref, $contact->data['email'] ), 'wpshop2' );

		$proposal_file = Request_Util::get( 'documents/download?modulepart=propale&original_file=' . $doli_proposal->ref . '/' . $doli_proposal->ref . '.pdf' );
		$content       = base64_decode( $proposal_file->content );

		header( 'Cache-Control: no-cache' );
		header( 'Content-Type: application/pdf' );
		header( 'Content-Disposition: inline; filename="' . $doli_proposal->ref . '.pdf"' );
		header( 'Content-Length: ' . strlen( $content ) );

		echo $content;

		exit;
	}

	/**
	 * Create Order from Proposal and go to pay page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: nonce
	 */
	public function convert_to_order_and_pay() {
		$id = ! empty( $_GET['proposal_id'] ) ? (int) $_GET['proposal_id'] : 0;

		if ( empty( $id ) ) {
			wp_die();
		}

		$wp_proposal = Proposals::g()->get( array( 'id' => $id ), true );

		$doli_proposal_id = get_post_meta( $wp_proposal->data['id'], '_external_id', true );

		$doli_order = Request_Util::post( 'orders/createfromproposal/' . $doli_proposal_id );
		$doli_order = Request_Util::post( 'orders/' . $doli_order->id . '/validate' );

		$wp_order = Doli_Order::g()->get( array( 'schema' => true ), true );
		$wp_order = Doli_Order::g()->doli_to_wp( $doli_order, $wp_order );

		$current_user = wp_get_current_user();
		Checkout::g()->reorder( $wp_order->data['id'] );
		$wp_order->data['total_price_no_shipping'] = Cart_Session::g()->total_price_no_shipping;
		$wp_order->data['tva_amount']              = Cart_Session::g()->tva_amount;
		$wp_order->data['shipping_cost']           = Cart_Session::g()->shipping_cost;
		$wp_order->data['author_id']               = $current_user->ID;

		$order = Doli_Order::g()->update( $wp_order->data );

		Checkout::g()->do_pay( $order->data['id'] );

		wp_redirect( Pages::g()->get_checkout_link() . '/pay/' . $order->data['id'] );
	}

	// @todo: Old Data Database
	/**
	 * Passe la proposition commerciale à payée.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param array $data Les données d'une proposition commerciale.
	 */
	public function set_to_billed( $data ) {
		$wp_order = Doli_Order::g()->get( array( 'id' => (int) $data['custom'] ), true );

		$proposals = Proposals::g()->get( array( 'post__in' => $wp_order->data['linked_objects_ids']['propal'] ) );

		if ( ! empty( $proposals ) ) {
			foreach ( $proposals as $proposal ) {
				$doli_proposal = Request_Util::post( 'proposals/' . $proposal->data['external_id'] . '/setinvoiced' );
				Doli_Proposals::g()->doli_to_wp( $doli_proposal, $proposal );
			}
		}
	}
}

new Doli_Proposals_Action();
