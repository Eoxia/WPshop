<?php
/**
 * La classe gérant les fonctions principales de My account.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * My Account Class.
 */
class My_Account extends Singleton_Util {

	/**
	 * Éléments du menu.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $menu = array();

	/**
	 * Le constructor.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Initialise l'endpoint.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function init_endpoint() {
		add_rewrite_endpoint( 'lost-password', EP_PAGES );
		add_rewrite_endpoint( 'details', EP_PAGES );
		add_rewrite_endpoint( 'quotations', EP_PAGES );
		add_rewrite_endpoint( 'dolibarr-quotations', EP_PAGES );
		add_rewrite_endpoint( 'orders', EP_PAGES );
		add_rewrite_endpoint( 'invoices', EP_PAGES );
		add_rewrite_endpoint( 'download', EP_PAGES );
		add_rewrite_endpoint( 'support', EP_PAGES );

		do_action( 'wps_account_navigation_endpoint' );

		if ( ! get_option( 'plugin_permalinks_flushed' ) ) {
			flush_rewrite_rules(false);
			update_option('plugin_permalinks_flushed', 1);
		}
	}

	/**
	 * Appel la vue pour afficher le formulaire de login dans la page de paiement.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function checkout_form_login() {
		if ( ! is_user_logged_in() ) {
			$transient = get_option( 'login_error_' . $_COOKIE['PHPSESSID'] );

			include( Template_Util::get_template_part( 'my-account', 'checkout-login' ) );
		}
	}

	/**
	 * Appel la vue pour afficher le formulaire de login dans la page mon compte.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param boolean $account_page True s'il s'agit de la page mon compte.
	 */
	public function display_form_login( $account_page = true) {
		global $post;

		$transient_lost_password = get_option( 'lost_password_notice_' . $_COOKIE['PHPSESSID'] );
		update_option( 'lost_password_notice_' . $_COOKIE['PHPSESSID'], '', false );

		$transient = get_option( 'login_error_' . $_COOKIE['PHPSESSID'] );
		update_option( 'login_error_' . $_COOKIE['PHPSESSID'], '', false );

		include( Template_Util::get_template_part( 'my-account', 'form-login' ) );
	}

	/**
	 * Appel la vue pour afficher le formulaire de perte de mot de passe dans la page mon compte.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_lost_password() {
		global $post;

		$transient = get_option( 'lost_password_error_' . $_COOKIE['PHPSESSID'] );
		update_option( 'lost_password_error_' . $_COOKIE['PHPSESSID'], '', false );

		include( Template_Util::get_template_part( 'my-account', 'lost-password' ) );
	}

	/**
	 * Affiche le menu de navigation.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $tab Le slug de l'onglet actuel.
	 */
	public function display_navigation( $tab ) {
		$menu_def = array(
			'details'    => array(
				'link'  => Pages::g()->get_account_link() . 'details/',
				'icon'  => 'fas fa-user' ,
				'title' => __( 'My account', 'wpshop' ),
			),
			'quotations' => array(
				'link'  => Pages::g()->get_account_link() . 'quotations/',
				'icon'  => 'fas fa-file-signature',
				'title' => __( 'Wish List', 'wpshop' ),
			),
			'dolibarr-quotations' => array(
				'link'  => Pages::g()->get_account_link() . 'dolibarr-quotations/',
				'icon'  => 'fas fa-file-signature',
				'title' => __( 'Dolibarr Quotation', 'wpshop' ),
			),
			'orders' => array(
				'link'  => Pages::g()->get_account_link() . 'orders/',
				'icon'  => 'fas fa-shopping-cart',
				'title' => __( 'Orders', 'wpshop' ),
			),
			'invoices' => array(
				'link'  => Pages::g()->get_account_link() . 'invoices/',
				'icon'  => 'fas fa-file-invoice-dollar',
				'title' => __( 'Invoices', 'wpshop' ),
			),
			'download' => array(
				'link'  => Pages::g()->get_account_link() . 'download/',
				'icon'  => 'fas fa-file-download',
				'title' => __( 'Downloads', 'wpshop' ),
			),
			'logout'     => array(
				'link'  => wp_logout_url( home_url() ),
				'icon'  => 'fas fa-sign-out-alt',
				'title' => __( 'Logout', 'wpshop' ),
			),
		);

		if (Settings::g()->dolibarr_is_active()){
			unset($menu_def['quotations']);
		}

		if ( class_exists( '\user_switching' ) ) {
			$old_user = \user_switching::get_old_user();

			if ( $old_user ) {
				$link = \user_switching::switch_back_url( $old_user );

				$menu_def['switch'] = array(
					'link'  => $link,
					'icon'  => 'fas fa-random',
					'title' => __( 'Switch back', 'wpshop' ),
				);
			}
		}

		$this->menu = apply_filters( 'wps_account_navigation_items', $menu_def );

		include( Template_Util::get_template_part( 'my-account', 'my-account-navigation' ) );
	}

	/**
	 * Affiche les détails de l'utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_details() {
		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		$transient = get_transient( 'wps_update_account_details_errors' );
		delete_transient( 'wps_update_account_details_errors' );

		include( Template_Util::get_template_part( 'my-account', 'my-account-details' ) );
	}

	/**
	 * Affiche les commandes liées au tier.
	 *
	 * @todo: Doli My Account
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_orders() {
		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );
		$orders = array();

		if ( ! empty( $third_party->data['id'] ) && ! empty( $third_party->data['external_id']) ) {
			$data = array(
				'sortfield'      => 't.rowid',
				'sortorder'      => 'DESC',
				'limit'          => 100,
				'thirdparty_ids' => $third_party->data['external_id'],
			);

			$doli_orders = Request_Util::g()->get( 'orders?' . http_build_query( $data ) );
			$orders      = Doli_Order::g()->convert_to_wp_order_format( $doli_orders );
		}

		include( Template_Util::get_template_part( 'my-account', 'my-account-orders' ) );
	}

	/**
	 * Affiche les factures liées au tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_invoices() {
		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		$invoices = array();

		if ( ! empty( $third_party->data['id'] ) && ! empty( $third_party->data['external_id'] ) ) {
			$data = array(
				'sortfield'      => 't.rowid',
				'sortorder'      => 'DESC',
				'limit'          => 100,
				'thirdparty_ids' => $third_party->data['external_id'],
			);

			$doli_invoices = Request_Util::g()->get( 'invoices?' . http_build_query( $data ) );
			$invoices      = Doli_Invoice::g()->convert_to_wp_invoice_format( $doli_invoices );
		}

		include( Template_Util::get_template_part( 'my-account', 'my-account-invoices' ) );
	}

	/**
	 * Affiche les téléchargements liées au tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_downloads() {
		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		$products_downloadable = array();

		if ( ! empty( $third_party->data['id'] ) ) {
			$products_downloadable = Product_Downloadable::g()->get( array(
				'author' => $contact->data['id'],
			) );
		}

		include( Template_Util::get_template_part( 'my-account', 'my-account-downloads' ) );
	}

	/**
	 * Affiche les devis liés au tiers sans l'ERP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_quotations() {
		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		$proposals = array();

		if ( ! empty( $third_party->data['id'] ) ) {
			$proposals = Proposals::g()->get( array( 'post_parent' => $third_party->data['id'] ) );
		}

		include( Template_Util::get_template_part( 'my-account', 'my-account-proposals' ) );
	}

	/**
	 * Affiche les propositions commerciales liés au tiers.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display_dolibarr_quotations() {
		$contact     = User::g()->get( array( 'id' => get_current_user_id() ), true );
		$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

		$proposals = array();

		if ( ! empty( $third_party->data['id'] ) ) {
			$data = array(
				'sortfield'      => 't.rowid',
				'sortorder'      => 'DESC',
				'limit'          => 100,
				'thirdparty_ids' => $third_party->data['external_id'],
			);

			$doli_proposals = Request_Util::g()->get( 'proposals?' . http_build_query( $data ) );
			$proposals      = Doli_Proposals::g()->convert_to_wp_proposal_format( $doli_proposals );
		}

		include( Template_Util::get_template_part( 'my-account', 'my-account-dolibarr-proposals' ) );
	}
}

My_Account::g();
