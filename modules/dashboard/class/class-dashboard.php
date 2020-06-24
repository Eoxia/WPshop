<?php
/**
 * Les fonctions principales du tableau de bord.
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
 * Dashboard class.
 */
class Dashboard extends \eoxia\Singleton_Util {

	/**
	 * Définition des metaboxes
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	private $metaboxes = array();

	/**
	 * Obligatoire pour Singleton_Util
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		$this->metaboxes = apply_filters( 'wps_dashboard_metaboxes', array(
			'wps-dashboard-customers'  => array(
				'callback' => array( $this, 'metabox_customers' ),
			),
			'wps-dashboard-products'   => array(
				'callback' => array( $this, 'metabox_products' ),
			),
			'wps-dashboard-quotations' => array(
				'callback' => array( $this, 'metabox_quotations' ),
			),
			'wps-dashboard-proposals'   => array(
				'callback' => array( $this, 'metabox_proposals' ),
			),
			'wps-dashboard-orders'     => array(
				'callback' => array( $this, 'metabox_orders' ),
			),
			'wps-dashboard-invoices'   => array(
				'callback' => array( $this, 'metabox_invoices' ),
			),
			'wps-dashboard-payments'   => array(
				'callback' => array( $this, 'metabox_payments' ),
			),
		) );
	}

	/**
	 * Appel la vue "main" du module "dashboard".
	 *
	 * @since 2.0.0
	 */
	public function callback_add_menu_page() {
		if ( ! empty( $this->metaboxes ) ) {
			foreach ( $this->metaboxes as $key => $metabox ) {
				add_action( 'wps_dashboard', $metabox['callback'], 10, 0 );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'main' );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_invoices() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_invoices_lists    = $dolibarr_option['dolibarr_invoices_lists'];

		$doli_invoices = Request_Util::get( 'invoices?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$invoices      = Doli_Invoice::g()->convert_to_wp_invoice_format( $doli_invoices );

		if ( ! empty( $invoices ) ) {
			foreach ( $invoices as &$invoice ) {
				$invoice->data['third_party'] = Third_Party::g()->get( array( 'id' => $invoice->data['third_party_id'] ), true );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-invoices', array(
			'invoices'     => $invoices,
			'dolibarr_url' => $dolibarr_url,
			'dolibarr_invoices_lists' => $dolibarr_invoices_lists,
		) );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_orders() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_orders_lists = $dolibarr_option['dolibarr_orders_lists'];

		$orders = Doli_Order::g()->get( array( 'posts_per_page' => 3 ) );

		$doli_orders = Request_Util::get( 'orders?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$orders      = Doli_Order::g()->convert_to_wp_order_format( $doli_orders );

		if ( ! empty( $orders ) ) {
			foreach ( $orders as &$order ) {
				$order->data['third_party'] = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-orders', array(
			'orders'                => $orders,
			'dolibarr_url'          => $dolibarr_url,
			'dolibarr_orders_lists' => $dolibarr_orders_lists,
		) );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_customers() {
		$third_parties = Third_Party::g()->get( array( 'posts_per_page' => 3 ) );
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_tiers_lists    = $dolibarr_option['dolibarr_tiers_lists'];

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-customers', array(
			'third_parties' => $third_parties,
			'dolibarr_tiers_lists' => $dolibarr_tiers_lists,
			'dolibarr_url' => $dolibarr_url,
		) );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_quotations() {
		$proposals = Proposals::g()->get( array( 'posts_per_page' => 3 ) );
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_proposals_lists    = $dolibarr_option['dolibarr_proposals_lists'];

		if ( ! empty( $proposals ) ) {
			foreach ( $proposals as &$proposal ) {
				$proposal->data['third_party'] = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
			}
		}

		unset( $proposal );

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-quotations', array(
			'proposals' => $proposals,
			'dolibarr_proposals_lists' => $dolibarr_proposals_lists,
			'dolibarr_url' => $dolibarr_url,
		) );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_products() {
		$products = Product::g()->get( array( 'posts_per_page' => 3 ) );
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_products_lists    = $dolibarr_option['dolibarr_products_lists'];

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-products', array(
			'products' => $products,
			'dolibarr_products_lists' => $dolibarr_products_lists,
			'dolibarr_url' => $dolibarr_url,
		) );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_payments() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_payments_lists    = $dolibarr_option['dolibarr_payments_lists'];

		$doli_invoices = Request_Util::get( 'invoices?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$invoices      = Doli_Invoice::g()->convert_to_wp_invoice_format( $doli_invoices );

		$payments = array();

		$number_payment = 0;

		if ( ! empty( $invoices ) ) {
			foreach ( $invoices as &$invoice ) {
				$invoice->data['third_party'] = Third_Party::g()->get( array( 'id' => $invoice->data['third_party_id'] ), true );

				if ( ! empty( $invoice->data['payments'] ) ) {
					foreach ( $invoice->data['payments'] as $payment ) {
						$payment->data['invoice'] = $invoice;
						$payments[] = $payment;
						$number_payment++;

						if ( $number_payment > 3 ) {
							break;
						}
					}
				}
			}
		}




		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-payments', array(
			'payments' => $payments,
			'dolibarr_payments_lists' => $dolibarr_payments_lists,
			'dolibarr_url' => $dolibarr_url,

		) );
	}

	/**
	 * La metabox de synchronisation.
	 *
	 * @since 2.0.0
	 */
	public function metabox_proposals() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_url    = $dolibarr_option['dolibarr_url'];
		$dolibarr_proposals_lists = $dolibarr_option['dolibarr_proposals_lists'];

		$doli_proposals = Request_Util::get( 'proposals?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$proposals      = Doli_Proposals::g()->convert_to_wp_proposal_format( $doli_proposals );

		if ( ! empty( $proposals ) ) {
			foreach ( $proposals as &$proposal ) {
				$proposal->data['third_party'] = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-proposals', array(
			'proposals'                => $proposals,
			'dolibarr_url'             => $dolibarr_url,
			'dolibarr_proposals_lists' => $dolibarr_proposals_lists,
		) );
	}
}

Dashboard::g();
