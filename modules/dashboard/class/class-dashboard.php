<?php
/**
 * La classe gérant les fonctions principales du tableau de bord.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard class.
 */
class Dashboard extends Singleton_Util {

	/**
	 * Définition des metaboxes.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	private $metaboxes = array();

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {
		$this->metaboxes = apply_filters( 'wps_dashboard_metaboxes', array(
			'wps-dashboard-customer'  => array(
				'callback' => array( $this, 'metabox_customer' ),
			),
			'wps-dashboard-product'   => array(
				'callback' => array( $this, 'metabox_product' ),
			),
			'wps-dashboard-wishlist' => array(
				'callback' => array( $this, 'metabox_wishlist' ),
			),
			'wps-dashboard-proposal'   => array(
				'callback' => array( $this, 'metabox_proposal' ),
			),
			'wps-dashboard-order'     => array(
				'callback' => array( $this, 'metabox_order' ),
			),
			'wps-dashboard-invoice'   => array(
				'callback' => array( $this, 'metabox_invoice' ),
			),
			'wps-dashboard-payment'   => array(
				'callback' => array( $this, 'metabox_payment' ),
			),
		) );
	}

	/**
	 * Appel la vue "main" du module "dashboard".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_menu_page() {
		if ( ! empty( $this->metaboxes ) ) {
			if ( Settings::g()->dolibarr_is_active() ) {
				foreach ( $this->metaboxes as $key => $metabox ) {
					add_action( 'wps_dashboard', $metabox['callback'], 10, 0 );
				}
			} else {
				add_action( 'wps_dashboard', array( $this, 'metabox_customer' ), 10, 0 );
				add_action( 'wps_dashboard', array( $this, 'metabox_product'), 10, 0 );
				add_action( 'wps_dashboard', array( $this, 'metabox_wishlist'), 10, 0 );
			}
		}

		View_Util::exec( 'wpshop', 'dashboard', 'main' );
	}

	/**
	 * La metabox des 3 derniers clients.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_customer() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url            = $dolibarr_option['dolibarr_url'];
		$dolibarr_tiers_lists    = $dolibarr_option['dolibarr_tiers_lists'];

		$third_parties = Third_Party::g()->get( array( 'posts_per_page' => 3 ) );

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-customer', array(
			'third_parties'        => $third_parties,
			'dolibarr_url'         => $dolibarr_url,
			'dolibarr_tiers_lists' => $dolibarr_tiers_lists,
		) );
	}

	/**
	 * La metabox des 3 derniers produits.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_product() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url               = $dolibarr_option['dolibarr_url'];
		$dolibarr_products_lists    = $dolibarr_option['dolibarr_products_lists'];

		$products = Product::g()->get( array( 'posts_per_page' => 3 ) );

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-product', array(
			'products'                => $products,
			'dolibarr_url'            => $dolibarr_url,
			'dolibarr_products_lists' => $dolibarr_products_lists,
		) );
	}

	/**
	 * La metabox des 3 dernières envies.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_wishlist() {
		$wishlists = Proposals::g()->get( array( 'posts_per_page' => 3 ) );

		if ( ! empty( $wishlists ) ) {
			foreach ( $wishlists as &$wishlist ) {
				$wishlist->data['third_party'] = Third_Party::g()->get( array( 'id' => $wishlist->data['parent_id'] ), true );
			}
		}

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-wishlist', array(
			'wishlists'    => $wishlists,
		) );
	}

	/**
	 * La metabox des 3 dernières propositions commerciales.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_proposal() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url             = $dolibarr_option['dolibarr_url'];
		$dolibarr_proposals_lists = $dolibarr_option['dolibarr_proposals_lists'];

		$doli_proposals = Request_Util::get( 'proposals?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$proposals      = Doli_Proposals::g()->convert_to_wp_proposal_format( $doli_proposals );

		if ( ! empty( $proposals ) ) {
			foreach ( $proposals as &$proposal ) {
				$proposal->data['third_party'] = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
			}
		}

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-proposal', array(
			'proposals'                => $proposals,
			'dolibarr_url'             => $dolibarr_url,
			'dolibarr_proposals_lists' => $dolibarr_proposals_lists,
		) );
	}

	/**
	 * La metabox des 3 dernières commandes.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_order() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url          = $dolibarr_option['dolibarr_url'];
		$dolibarr_orders_lists = $dolibarr_option['dolibarr_orders_lists'];

		$doli_orders = Request_Util::get( 'orders?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$orders      = Doli_Order::g()->convert_to_wp_order_format( $doli_orders );

		if ( ! empty( $orders ) ) {
			foreach ( $orders as &$order ) {
				$order->data['third_party'] = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );
			}
		}

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-order', array(
			'orders'                => $orders,
			'dolibarr_url'          => $dolibarr_url,
			'dolibarr_orders_lists' => $dolibarr_orders_lists,
		) );
	}

	/**
	 * La metabox des 3 derniers paiements.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_payment() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url               = $dolibarr_option['dolibarr_url'];
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

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-payment', array(
			'payments'                => $payments,
			'dolibarr_url'            => $dolibarr_url,
			'dolibarr_payments_lists' => $dolibarr_payments_lists,
		) );
	}

	/**
	 * La metabox des 3 dernières factures.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function metabox_invoice() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$dolibarr_url               = $dolibarr_option['dolibarr_url'];
		$dolibarr_invoices_lists    = $dolibarr_option['dolibarr_invoices_lists'];

		$doli_invoices = Request_Util::get( 'invoices?sortfield=t.rowid&sortorder=DESC&limit=3' );
		$invoices      = Doli_Invoice::g()->convert_to_wp_invoice_format( $doli_invoices );

		if ( ! empty( $invoices ) ) {
			foreach ( $invoices as &$invoice ) {
				$invoice->data['third_party'] = Third_Party::g()->get( array( 'id' => $invoice->data['third_party_id'] ), true );
			}
		}

		View_Util::exec( 'wpshop', 'dashboard', 'metaboxes/metabox-invoice', array(
			'invoices'                => $invoices,
			'dolibarr_url'            => $dolibarr_url,
			'dolibarr_invoices_lists' => $dolibarr_invoices_lists,
		) );
	}
}

Dashboard::g();
