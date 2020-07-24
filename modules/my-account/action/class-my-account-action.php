<?php
/**
 * Gestion des actions dans la page mon compte.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * My Account Action Class.
 */
class My_Account_Action {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( My_Account_Shortcode::g(), 'init_shortcode' ) );
		add_action( 'init', array( My_Account::g(), 'init_endpoint' ) );

		add_action( 'wps_before_checkout_form', array( My_Account::g(), 'checkout_form_login' ) );

		add_action( 'admin_post_wps_login', array( $this, 'handle_login' ) );
		add_action( 'admin_post_nopriv_wps_login', array( $this, 'handle_login' ) );

		add_action( 'admin_post_nopriv_wps_lost_password', array( $this, 'handle_lost_password' ) );

		add_action( 'wps_account_navigation', array( My_Account::g(), 'display_navigation' ) );
		add_action( 'wps_account_details', array( My_Account::g(), 'display_details' ) );
		add_action( 'wps_account_orders', array( My_Account::g(), 'display_orders' ) );
		add_action( 'wps_account_invoices', array( My_Account::g(), 'display_invoices' ) );
		add_action( 'wps_account_download', array( My_Account::g(), 'display_downloads' ) );
		add_action( 'wps_account_quotations', array( My_Account::g(), 'display_quotations' ) );
		add_action( 'wps_account_dolibarr_quotations', array( My_Account::g(), 'display_dolibarr_quotations' ) );

		add_action( 'admin_post_update_account_details', array( $this, 'update_account_details' ) );

		add_action( 'wp_ajax_reorder', array( $this, 'do_reorder' ) );
		add_action( 'admin_post_wps_pay_order', array( $this, 'do_pay' ) );

		add_action( 'wps_my_account_dolibarr_proposals_actions', array( $this, 'add_proposal_pdf' ), 10, 1 );
	}

	/**
	 * Gestion de la connexion, si les identifiants sont correctes,
	 * rediriges à la page indiqué dans $_POST['page'].
	 *
	 * Si les identifiants sont incorrectes, redigires à la page indiquée dans
	 * $_POST['page'] avec une erreur.
	 *
	 * @use wp_signon.
	 *
	 * @since 2.0.0
	 */
	public function handle_login() {
		check_admin_referer( 'handle_login' );

		$page = ! empty( $_POST['page'] ) ? sanitize_text_field( $_POST['page'] ) : 'my-account';

		if ( empty( $_POST['username'] ) || empty( $_POST['password'] ) ) {
			wp_redirect( site_url( $page ) );
			exit;
		}

		$user = wp_signon( array(
			'user_login'    => $_POST['username'],
			'user_password' => $_POST['password'],
		), is_ssl() );

		if ( is_wp_error( $user ) ) {
			update_option( 'login_error_' . $_COOKIE['PHPSESSID'], __( 'Your username or password is incorrect.', 'wpshop' ) );

			wp_redirect( site_url( $page ) );
			exit;
		} else {
			wp_redirect( site_url( $page ) );
			exit;
		}
	}


	/* Passer par WordPress

	public function handle_lost_password() {
		check_admin_referer( 'wps_lost_password' );

		$page = ! empty( $_POST['page'] ) ? sanitize_text_field( $_POST['page'] ) : 'my-account';

		//do_action( 'retrieve_password', $_POST['user_login'] );

		//update_option( 'lost_password_notice_' . $_COOKIE['PHPSESSID'], __( 'An email for reset password', 'wpshop' ) );

		wp_redirect( admin_url( 'wp-login.php?action=lostpassword') );
	}

	*/
/* Fonction de changement de mot de pass à supprimer à terme
	public function update_account_details() {
		check_admin_referer( 'update_account_details' );

		$errors               = array();
		$email                = ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$new_password         = ! empty( $_POST['new_password'] ) ? sanitize_text_field( $_POST['new_password'] ) : '';
		$confirm_new_password = ! empty( $_POST['confirm_new_password'] ) ? sanitize_text_field( $_POST['confirm_new_password'] ) : '';

		if ( empty( $email ) ) {
			$errors['empty_email'] = __( 'Please fill email field', 'wpshop' );
			//@todo ligne à controler
			set_transient( 'wps_update_account_details_errors', $errors, 0 );
			//@todo fin de ligne à controler à priori a supprimer

			wp_redirect( Pages::g()->get_account_link() . 'details/' );
			die();
		}

		$user = wp_get_current_user();

		$user_update_status = wp_update_user( array(
			'ID'         => get_current_user_id(),
			'user_email' => $email,
		) );

		if ( is_wp_error( $user_update_status ) ) {
			$errors['unknow_error'] = __( 'Unknown error', 'wpshop' );
			set_transient( 'wps_update_account_details_errors', $errors, 0 );
			wp_redirect( Pages::g()->get_account_link() . 'details/' );
			die();
		}

		if ( ! empty( $new_password ) ) {
			if ( $new_password === $confirm_new_password ) {
				$user_update_status = wp_update_user( array(
					'ID'        => get_current_user_id(),
					'user_pass' => $new_password,
				) );
			} else {
				$errors['new_password_and_confirm'] = __( 'New password and confirm new password are different', 'wpshop' );
				set_transient( 'wps_update_account_details_errors', $errors, 0 );
				wp_redirect( Pages::g()->get_account_link() . 'details/' );
				die();
			}
		}

		if ( is_wp_error( $user_update_status ) ) {
			$errors['unknow_error'] = __( 'Unknown error', 'wpshop' );
			set_transient( 'wps_update_account_details_errors', $errors, 0 );
			wp_redirect( Pages::g()->get_account_link() . 'details/' );
			die();
		}

		if ( ! empty( $errors ) ) {
			set_transient( 'wps_update_account_details_errors', $errors, 0 );
		}

		wp_redirect( Pages::g()->get_account_link() . 'details/' );
		die();
	}*/

	/**
	 * Repasse la même commande. Remet les mêmes données de la commande dans le panier.
	 *
	 * @since 2.0.0
	 */
	public function do_reorder() {
		check_ajax_referer( 'do_reorder' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		Checkout::g()->reorder( $id );

		wp_send_json_success( array(
			'namespace'        => 'wpshopFrontend',
			'module'           => 'myAccount',
			'callback_success' => 'reorderSuccess',
			'redirect_url'     => Pages::g()->get_cart_link(),
		) );
	}

	/**
	 * Repasse la même commande. Remet les mêmes données de la commande dans le panier.
	 *
	 * @since 2.0.0
	 */
	public function do_pay() {
		//check_admin_referer( 'do_pay' );
		$id = ! empty( $_GET['order_id'] ) ? (int) $_GET['order_id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$order = Doli_Order::g()->get( array( 'id' => $id ), true );

		if ( $order->data['author_id'] != get_current_user_id() ) {
			wp_die( __( 'What do you try to do ?', 'wpshop' ) );
		}

		Checkout::g()->do_pay( $id );

		wp_redirect( Pages::g()->get_checkout_link() . '/pay/' . $id );
	}

	/**
	 * Ajoutes le lien vers la proposition commerciale si dolibarr est active.
	 *
	 * @since 2.0.0
	 *
	 * @param Doli_Proposals $proposal Les données d'une proposition commerciale.
	 */
	public function add_proposal_pdf( $proposal ) {
		if ( Settings::g()->dolibarr_is_active() ) {
			include( Template_Util::get_template_part( 'my-account', 'my-account-proposals-dolibarr-devis' ) );
		}
	}
}

new My_Account_Action();
