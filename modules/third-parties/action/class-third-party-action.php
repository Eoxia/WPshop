<?php
/**
 * La classe gérant les actions des tiers.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.4.0
 */

namespace wpshop;

use eoxia\View_Util;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Third_Party Action Class.
 */
class Third_Party_Action {

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
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 20 );

		$this->metaboxes = apply_filters( 'wps_third_party_metaboxes', array(
			'wps-third-party-billing'  => array(
				'callback' => 'metabox_billing_address',
			),
			'wps-third-party-contact' => array(
				'callback' => 'metabox_contacts',
			),
			'wps-third-party-propal'   => array(
				'callback' => 'metabox_proposals',
			),
		) );
	}

	/**
	 * Initialise la page "Third Parties".
	 *
	 * @since   2.0.0
	 * @version 2.4.0
	 */
	public function callback_admin_menu() {
//		$hook = add_submenu_page(
//			'wpshop',
//			__( 'Third Parties', 'wpshop' ),
//			__( 'Third Parties', 'wpshop' ),
//			'manage_options',
//			'wps-third-party',
//			array( $this, 'callback_add_menu_page' )
//		);
//
//		if ( ! isset( $_GET['id'] ) ) {
//			add_action( 'load-' . $hook, array( $this, 'callback_add_screen_option' ) );
//		}
		if ( user_can( get_current_user_id(), 'manage_options' ) ) {
			CMH::register_menu( 'wpshop', __( 'Third Parties', 'wpshop' ), __( 'Third Parties', 'wpshop' ), 'manage_options', 'wps-third-party', array( $this, 'callback_add_menu_page' ), 'fas fa-building', 2 );
		}
	}

	/**
	 * Appel la vue "main" du module "Third Party".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_add_menu_page() {
		// If it is a single page.
		if ( isset( $_GET['id'] ) ) {
			$id              = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;
			$third_party     = Third_Party::g()->get( array( 'id' => $id ), true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
			$dolibarr_url    = $dolibarr_option['dolibarr_url'];
			$args_metabox    = array(
				'third_party' => $third_party,
				'id'          => $id,
			);

			if ( ! empty( $this->metaboxes ) ) {
				foreach ( $this->metaboxes as $key => $metabox ) {
					add_action( 'wps_third_party', array( $this, $metabox['callback'] ), 10, 1 );
				}
			}

			View_Util::exec( 'wpshop', 'third-parties', 'single', array( 'third_party' => $third_party, 'doli_url' => $dolibarr_url ) );
		} else {
			// Or it is the listing.
			$per_page = get_user_meta( get_current_user_id(), Third_Party::g()->option_per_page, true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
			$dolibarr_create_tier    = $dolibarr_option['dolibarr_create_tier'];
			$dolibarr_url            = $dolibarr_option['dolibarr_url'];

			if ( empty( $per_page ) || 1 > $per_page ) {
				$per_page = Third_Party::g()->limit;
			}

			$s     = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
			$count = Third_Party::g()->search( $s, array(), true );

			$number_page  = ceil( $count / $per_page );
			$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

			$base_url = admin_url( 'admin.php?page=wps-third-party' );

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

			View_Util::exec( 'wpshop', 'third-parties', 'main', array(
				'number_page'  => $number_page,
				'current_page' => $current_page,
				'count'        => $count,
				'begin_url'    => $begin_url,
				'end_url'      => $end_url,
				'prev_url'     => $prev_url,
				'next_url'     => $next_url,
				's'            => $s,

				'dolibarr_create_tier' => $dolibarr_create_tier,
				'dolibarr_url'         => $dolibarr_url,
			) );
		}
	}

	/**
	 * Ajoute le menu "Options de l'écran" pour les tiers.
	 *
	 * @since   2.0.0
	 * @version 2.0.0.
	 */
	public function callback_add_screen_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => _x( 'Third parties', 'Third party per page', 'wpshop' ),
				'default' => Third_Party::g()->limit,
				'option'  => Third_Party::g()->option_per_page,
			)
		);
	}

	/**
	 * Appel la vue de la metabox des adresses.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_billing_address( $third_party ) {
		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-billing-address', array(
			'third_party' => $third_party,
		) );
	}

	/**
	 * Appel la vue de la metabox des contact.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_contacts( $third_party ) {
		$contacts = array();

		if ( ! empty( $third_party->data['contact_ids'] ) ) {
			$contacts = User::g()->get( array( 'include' => $third_party->data['contact_ids'] ) );
		}

		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-tier', array(
			'third_party' => $third_party,
			'contacts'    => $contacts,
		) );
	}

	/**
	 * Appel la vue de la metabox des devis.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_proposals( $third_party ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$proposals = Proposals::g()->get( array( 'post_parent' => $third_party->data['id'] ) );

		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-proposals', array(
			'doli_url'  => $dolibarr_option['dolibarr_url'],
			'proposals' => $proposals,
		) );
	}

	/**
	 * Appel la vue de la metabox des dolibarr propal.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_dolibarr_propal( $third_party ) {
		// @todo: Charger dolibarr_option qu'une seule fois.
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$proposals = array();

		if ( Settings::g()->dolibarr_is_active() ) {
			$doli_proposals = Request_Util::get( 'proposals?sortfield=t.rowid&sortorder=DESC&limit=10&thirdparty_ids=' . $third_party->data['external_id'] );

			if ( ! empty( $doli_proposals ) ) {
				foreach ( $doli_proposals as $doli_proposal ) {
					$wp_proposal = Proposals::g()->get( array( 'schema' => true ), true );
					$proposals[] = Doli_Proposals::g()->doli_to_wp( $doli_proposal, $wp_proposal, true );
				}
			}
		}

		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-dolibarr-proposals', array(
			'proposals' => $proposals,
			'doli_url'  => $dolibarr_option['dolibarr_url'],
		) );
	}


	/**
	 * Appel la vue de la metabox des commandes.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_orders( $third_party ) {
		// @todo: Charger dolibarr_option qu'une seule fois.
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$orders = array();

		if ( Settings::g()->dolibarr_is_active() ) {
			$doli_orders = Request_Util::get( 'orders?sortfield=t.rowid&sortorder=DESC&limit=10&thirdparty_ids=' . $third_party->data['external_id'] );

			if ( ! empty( $doli_orders ) ) {
				foreach ( $doli_orders as $doli_order ) {
					$wp_order = Doli_Order::g()->get( array( 'schema' => true ), true );
					$orders[] = Doli_Order::g()->doli_to_wp( $doli_order, $wp_order, true );
				}
			}
		}

		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-orders', array(
			'orders'   => $orders,
			'doli_url' => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Appel la vue de la metabox des factures.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_invoices( $third_party ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$invoices = array();

		if ( Settings::g()->dolibarr_is_active() ) {

			$doli_invoices = Request_Util::get( 'invoices?sortfield=t.rowid&sortorder=ASC&limit=0&thirdparty_ids=' . $third_party->data['external_id'] );

			if ( ! empty( $doli_invoices ) ) {
				foreach ( $doli_invoices as $doli_invoice ) {
					$wp_invoice = Doli_Invoice::g()->get( array( 'schema' => true ), true );
					$invoices[] = Doli_Invoice::g()->doli_to_wp( $doli_invoice, $wp_invoice, true );
				}
			}

			if ( ! empty( $invoices ) ) {
				foreach ( $invoices as &$invoice ) {
					if ( isset( $invoice->data['linked_objects_ids']['commande'][0] ) ) {

						// Load Order.
						// @todo: Invoice should have one or more orders ? Check IT on Dolibarr.
						$doli_order = Request_Util::get( 'orders/' . $invoice->data['linked_objects_ids']['commande'][0] );
						$wp_order   = Doli_Order::g()->get( array( 'schema' => true ), true );
						$invoice->data['order'] = Doli_Order::g()->doli_to_wp( $doli_order, $wp_order, true );
					}
				}
			}
		}

		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-invoices', array(
			'doli_url' => $dolibarr_option['dolibarr_url'],
			'invoices' => $invoices,
		) );
	}

	/**
	 * Appel la vue de la metabox des factures.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données du tiers.
	 */
	public function metabox_contacts_address( $third_party ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$contacts = array();

		if ( Settings::g()->dolibarr_is_active() ) {

			$doli_contacts = Request_Util::get( 'contacts?sortfield=t.rowid&sortorder=ASC&limit=100&thirdparty_ids=' . $third_party->data['external_id'] );

			if ( ! empty( $doli_contacts ) ) {
				foreach ( $doli_contacts as $doli_contact ) {
					$wp_contact = Doli_Contacts::g()->get( array( 'schema' => true ), true );
					$contacts[] = Doli_Contacts::g()->doli_to_wp( $doli_contact, $wp_contact, true );
				}
			}
		}

		View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-contacts', array(
			'doli_url' => $dolibarr_option['dolibarr_url'],
			'contacts' => $contacts,
		) );
	}
}

new Third_Party_Action();
