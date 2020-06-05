<?php
/**
 * Gestion des actions du tunnel de vente.
 *
 * @package   WPshop\Classes
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout Action Class.
 */
class Checkout_Action {

	/**
	 * Constructeur pour la classe Class_Checkout_Action. Ajoutes les
	 * actions pour le tunnel de vente.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( Checkout_Shortcode::g(), 'callback_init' ) );
		add_action( 'init', array( $this, 'init_endpoint' ) );

		add_action( 'wps_after_cart_resume', array( $this, 'callback_after_cart_table' ), 20 );

		add_action( 'wps_before_cart_resume_lines', array( $this, 'callback_before_resume' ) );

		add_action( 'wps_checkout_shipping', array( $this, 'callback_checkout_shipping' ), 10, 2 );
		add_action( 'wps_checkout_order_review', array( $this, 'callback_checkout_order_review' ), 10, 4 );
		add_action( 'wps_checkout_payment', array( $this, 'callback_checkout_payment' ) );

		add_action( 'checkout_create_third_party', array( $this, 'callback_checkout_create_third' ) );

		if ( ! Settings::g()->dolibarr_is_active() ) {
			add_action('checkout_create_proposal', array($this, 'callback_checkout_proposal'), 10, 2);
		} else {
			add_action('checkout_create_proposal', array($this, 'callback_checkout_doli_proposal'), 10, 2);
		}

		add_action( 'wps_review_order_after_submit', array( $this, 'add_terms' ), 10 );
		add_action( 'wps_review_order_after_submit', array( $this, 'add_place_order_button' ), 20 );
		add_action( 'wps_review_order_after_submit', array( $this, 'add_devis_button' ), 30 );

		add_action( 'wp_ajax_wps_place_order', array( $this, 'callback_place_order' ) );
		add_action( 'wp_ajax_nopriv_wps_place_order', array( $this, 'callback_place_order' ) );
	}

	/**
	 * Initialise l'endpoint order.
	 *
	 * @since 2.0.0
	 */
	public function init_endpoint() {
		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );

		if ( Settings::g()->dolibarr_is_active() ) {
			add_rewrite_endpoint( 'order/(.*)', EP_ALL );
			add_rewrite_endpoint( 'pay/(.*)', EP_ALL );
			add_rewrite_rule( get_post_field( 'post_name', $page_ids_options['checkout_id'] ) . '/pay/([0-9]+)/?$', 'index.php?page_id=' . $page_ids_options['checkout_id'] . '&id=$matches[1]', 'top' );
		}

		add_rewrite_endpoint( 'received/(.*)/(.*)', EP_ALL );
		add_rewrite_rule( get_post_field( 'post_name', $page_ids_options['checkout_id'] ) . '/received/(order|proposal|doli-proposal)/([0-9]+)/?$', 'index.php?page_id=' . $page_ids_options['checkout_id'] . '&type=received&object_type=$matches[1]&id=$matches[2]', 'top' );

		do_action( 'wps_checkout_endpoint' );

		if ( ! get_option( 'plugin_permalinks_flushed' ) ) {
			flush_rewrite_rules( false );
			update_option( 'plugin_permalinks_flushed', 1 );
		}
	}

	/**
	 * Ajoute le bouton "Passer à la commande".
	 *
	 * @since 2.0.0
	 */
	public function callback_after_cart_table() {
		$link_checkout = Pages::g()->get_checkout_link();
		include( Template_Util::get_template_part( 'checkout', 'proceed-to-checkout-button' ) );
	}

	/**
	 * Ajoute le prix de la livraison dans le tableau du résumé.
	 *
	 * @since 2.0.0
	 */
	public function callback_before_resume() {
		if ( Pages::g()->is_checkout_page() ) {
			$shipping_cost_option = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );

			include( Template_Util::get_template_part( 'checkout', 'resume-list-product' ) );
		}
	}

	/**
	 * Affiche le formulaire pour l'adresse de livraison.
	 *
	 * @since 2.0.0
	 *
	 * @param Third_Party_Model $third_party Les données du tier.
	 * @param Contact_Model     $contact     Les données du contact.
	 * vue d'édition ou false.
	 */
	public function callback_checkout_shipping( $third_party, $contact ) {
		include( Template_Util::get_template_part( 'checkout', 'form-shipping' ) );
	}

	/**
	 * Le tableau récapitulatif de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param integer $total_price_no_shipping Le prix total sans le frais de livraison.
	 * @param integer $tva_amount              Le total de la TVA.
	 * @param integer $total_ttc               Le total TTC.
	 * @param integer $shipping_cost           Le frais de livraison.
	 */
	public function callback_checkout_order_review( $total_price_no_shipping, $tva_amount, $total_ttc, $shipping_cost ) {
		Cart::g()->display_cart_resume( $total_price_no_shipping, $tva_amount, $total_ttc, $shipping_cost );
	}

	/**
	 * Affiches les méthodes de paiement
	 *
	 * @since 2.0.0
	 */
	public function callback_checkout_payment() {
		$payment_methods = get_option( 'wps_payment_methods', Payment::g()->default_options );

		include( Template_Util::get_template_part( 'checkout', 'payment' ) );
	}

	/**
	 * Créer la commande et passe au paiement
	 *
	 * @since 2.0.0
	 */
	public function callback_place_order() {
		check_ajax_referer( 'callback_place_order' );

		// @todo: Explain fast_pay.
		$fast_pay     = isset( $_POST['fast_pay'] ) && 'true' == $_POST['fast_pay'] ? true : false;
		$type_payment = ! empty( $_POST['type_payment'] ) ? sanitize_text_field( $_POST['type_payment'] ) : '';
		$type         = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		if ( ! in_array( $type, array( 'order', 'proposal' ) ) ) {
			wp_send_json_error();
		}

		// @todo: Explain fast_pay.
		if ( ! $fast_pay ) {
			do_action( 'checkout_create_third_party' );
		}

		// Nothing attached in wpshop.
		do_action( 'wps_before_checkout_process' );

		// Nothing attached in wpshop.
		do_action( 'wps_checkout_process' );

		if ( 'order' === $type ) {
			$proposal = Request_Util::g()->get( 'proposals/' . Cart_Session::g()->external_data['doli_proposal_id'] );
			$order = apply_filters( 'wps_checkout_create_order', $proposal );

			// @todo: Check stock need to be an action.
			$stock_status = Cart::g()->check_stock();

			if ( $stock_status['is_valid'] ) {
				Cart::g()->decrease_stock();
				Checkout::g()->process_order_payment( $order );
			} else {
				$errors = new \WP_Error();

				if ( ! empty( $stock_status['errors'] ) ) {
					foreach ( $stock_status['errors'] as $message ) {
						$errors->add( 'no-stock', apply_filters( 'wps_product_no_stock', $message ) );
					}
				}

				ob_start();
				include( Template_Util::get_template_part( 'checkout', 'notice-error' ) );
				$template = ob_get_clean();

				wp_send_json_success( array(
					'namespace'        => 'wpshopFrontend',
					'module'           => 'checkout',
					'callback_success' => 'checkoutErrors',
					'errors'           => $errors,
					'template'         => $template,
				) );
			}
		} else {
			if ( isset( Cart_Session::g()->external_data['doli_proposal_id'] ) ) {
				$url = Pages::g()->get_checkout_link() . '/received/doli-proposal/' . Cart_Session::g()->external_data['doli_proposal_id'] . '/';
			} else {
				$url = Pages::g()->get_checkout_link() . '/received/proposal/' . Cart_Session::g()->external_data['proposal_id'] . '/';
			}
			Cart_Session::g()->destroy();
			wp_send_json_success( array(
				'namespace'        => 'wpshopFrontend',
				'module'           => 'checkout',
				'callback_success' => 'redirect',
				'url'              => $url,
			) );
		}
	}


	/**
	 * Créer le tier lors du tunnel de vente
	 *
	 * @since 2.0.0
	 */
	public function callback_checkout_create_third() {
		$errors      = new \WP_Error();
		$posted_data = Checkout::g()->get_posted_data();

		Checkout::g()->validate_checkout( $posted_data, $errors );

		if ( 0 === count( $errors->error_data ) ) {
			if ( empty( $posted_data['third_party']['title'] ) ) {
				$exploded_email = explode( '@', $posted_data['contact']['email'] );
				$posted_data['third_party']['title'] = $exploded_email[0];
			}

			$country = get_from_id( $posted_data['third_party']['country_id'] );

			$posted_data['third_party']['country_id'] = (int) $posted_data['third_party']['country_id'];
			$posted_data['third_party']['country']    = $country['label'];
			$posted_data['third_party']['phone']      = $posted_data['contact']['phone'];
			$posted_data['third_party']['email']      = $posted_data['contact']['email'];

			if ( ! is_user_logged_in() ) {
				$third_party = Third_Party::g()->update( $posted_data['third_party'] );
				// Call wpshop to update attached ERP.
				do_action( 'wps_checkout_create_third_party', $third_party );

				$posted_data['contact']['login']          = sanitize_user( current( explode( '@', $posted_data['contact']['email'] ) ), true );
				$posted_data['contact']['password']       = wp_generate_password();
				$posted_data['contact']['third_party_id'] = $third_party->data['id'];
				$posted_data['contact']['lastname']       = $posted_data['third_party']['title'];

				$contact = User::g()->update( $posted_data['contact'] );

				$third_party->data['contact_ids'][] = $contact->data['id'];
				$third_party                        = Third_Party::g()->update( $third_party->data );

				// Call wpshop to update attached ERP.
				do_action( 'wps_checkout_create_contact', $contact );

				$signon_data = array(
					'user_login'    => $posted_data['contact']['login'],
					'user_password' => $posted_data['contact']['password'],
				);

				$user = wp_signon( $signon_data, is_ssl() );

				$key = get_password_reset_key( $user );

				$trackcode = get_user_meta( $contact->data['id'], 'p_user_registration_code', true );
				$track_url = get_option( 'siteurl' ) . '/wp-login.php?action=rp&key=' . $key . '&login=' . $posted_data['contact']['login'];

				Emails::g()->send_mail( $posted_data['contact']['email'], 'customer_new_account', array_merge( $posted_data, array( 'url' => $track_url ) ) );

				// translators: Checkout: Create new third party and contact {json_data}.
				\eoxia\LOG_Util::log( sprintf( 'Checkout: Create new third party and contact %s', json_encode( $posted_data ) ), 'wpshop2' );
			} else {
				// If user is connected, check the link with his third party and his contact.
				$current_user = wp_get_current_user();

				$contact = User::g()->get( array(
					'search' => $current_user->user_email,
					'number' => 1,
				), true );

				$third_party = Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

				$posted_data['third_party']['id'] = $third_party->data['id'];

				if ( ! empty( $third_party->data['id'] ) ) {
					$third_party                     = Third_Party::g()->update( $posted_data['third_party'] );
					$contact->data['third_party_id'] = $third_party->data['id'];


					// Call wpshop to update attached ERP.
					do_action( 'wps_checkout_create_third_party', $third_party );
				} else {
					$posted_data['third_party']['contact_ids'][] = $contact->data['id'];
					$third_party                                 = Third_Party::g()->update( $posted_data['third_party'] );
					$contact->data['third_party_id']             = $third_party->data['id'];


					// Call wpshop to update attached ERP.
					do_action( 'wps_checkout_create_third_party', $third_party );
				}

				// Connect what u want.
				do_action( 'wps_checkout_create_contact', $contact );

				$contact->data['firstname'] = ! empty( $posted_data['contact']['firstname'] ) ? $posted_data['contact']['firstname'] : $contact->data['firstname'];
				$contact->data['lastname']  = ! empty( $posted_data['third_party']['title'] ) ? $posted_data['third_party']['title'] : $contact->data['lastname'];
				$contact                    = User::g()->update( $contact->data );

				// translators: Checkout: Update third party and contact {json_data}.
				\eoxia\LOG_Util::log( sprintf( 'Checkout: Update third party and contact %s', json_encode( $posted_data ) ), 'wpshop2' );
			}

			do_action( 'checkout_create_proposal', $third_party, $contact );
		} else {
			ob_start();
			include( Template_Util::get_template_part( 'checkout', 'notice-error' ) );
			$template = ob_get_clean();

			wp_send_json_success( array(
				'namespace'        => 'wpshopFrontend',
				'module'           => 'checkout',
				'callback_success' => 'checkoutErrors',
				'errors'           => $errors,
				'template'         => $template,
			) );
		}
	}

	public function callback_checkout_proposal( $third_party, $contact ) {
		$type         = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'proposal';
		$type_payment = ! empty( $_POST['type_payment'] ) ? sanitize_text_field( $_POST['type_payment'] ) : '';

		$proposal = Proposals::g()->get( array( 'schema' => true ), true );

		// Real WPshop Ref if not ERP to control it.
		$last_ref = Proposals::g()->get_last_ref();

		if ( ! is_int( $last_ref ) ) {
			\eoxia\LOG_Util::log( sprintf('Numérotation impossible' ), 'wpshop2' );
			return;
		} else {
			$last_ref++;
		}

		$proposal->data['prefix']                  = 'DE';
		$proposal->data['title']                   = sprintf( 'DE%06d', $last_ref );
		//$proposal->data['ref']                   = sprintf( 'DE%06d', $last_ref );
		$proposal->data['number']                  = $last_ref;
		$proposal->data['datec']                   = current_time( 'mysql' );
		$proposal->data['parent_id']               = $third_party->data['id'];
		$proposal->data['author_id']               = $contact->data['id'];
		$proposal->data['status']                  = 'publish';
		$proposal->data['lines']                   = array();
		$proposal->data['total_price_no_shipping'] = Cart_Session::g()->total_price_no_shipping;
		$proposal->data['tva_amount']              = Cart_Session::g()->tva_amount;
		$proposal->data['shipping_cost']           = Cart_Session::g()->shipping_cost;
		$proposal->data['payment_method']          = $type_payment;

		$total_ht  = 0;
		$total_ttc = 0;

		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach ( Cart_Session::g()->cart_contents as $content ) {
				$content['total_ht']       = $content['price'] * $content['qty'];
				$content['total_ttc']      = $content['price_ttc'] * $content['qty'];
				$proposal->data['lines'][] = $content;

				$total_ht  += $content['total_ht'];
				$total_ttc += $content['total_ttc'];
			}
		}

		$proposal->data['total_ht']  = $total_ht;
		$proposal->data['total_ttc'] = $total_ttc;

		$proposal = Proposals::g()->update( $proposal->data );
		\eoxia\LOG_Util::log( sprintf( 'Checkout: Create proposal %s', json_encode( $proposal->data ) ), 'wpshop2' );

		Cart_Session::g()->add_external_data( 'proposal_id', $proposal->data['id'] );
		Cart_Session::g()->update_session();
	}

	// @todo: A déplacer dans doli-proposals-action quand on a le temps.
	public function callback_checkout_doli_proposal( $third_party, $contact ) {
 		$type_payment = ! empty( $_POST['type_payment'] ) ? sanitize_text_field( $_POST['type_payment'] ) : '';

		$proposal_data = array(
			'socid'             => $third_party->data['external_id'],
			'date'              => current_time( 'timestamp' ),
			'mode_reglement_id' => Doli_Payment::g()->convert_to_doli_id( $type_payment ),
		);

		\eoxia\LOG_Util::log( sprintf( 'Dolibarr call POST proposals with data %s', json_encode( $proposal_data ) ), 'wpshop2' );
		$doli_proposal_id = Request_Util::post( 'proposals', $proposal_data );

		if ( ! empty( Cart_Session::g()->cart_contents ) ) {
			foreach (Cart_Session::g()->cart_contents as $content) {
				Request_Util::post( 'proposals/' . $doli_proposal_id . '/lines', array(
					'desc'                    => $content['content'],
					'fk_product'              => $content['external_id'],
					'product_type'            => 1,
					'qty'                     => $content['qty'],
					'tva_tx'                  => $content['tva_tx'],
					'subprice'                => $content['price'],
					'remice_percent'          => 0,
					'rang'                    => 1,
					'total_ht'                => $content['price'],
					'total_tva'               => 0,
					'total_ttc'               => $content['price_ttc'],
					'product_label'           => $content['title'],
					'multicurrency_code'      => 'EUR',
					'multicurrency_subprice'  => $content['price'],
					'multicurrency_total_ht'  => $content['price'],
					'multicurrency_total_tva' => 0,
					'multicurrency_total_ttc' => $content['price_ttc'],
				) );
			}
		}

		$doli_proposal = Request_Util::post( 'proposals/' . (int) $doli_proposal_id . '/validate', array(
			'notrigger' => 1,
		) );

		$doli_proposal = Request_Util::post( 'proposals/' . (int) $doli_proposal_id . '/close', array(
			'status'    => 2,
			'notrigger' => 1,
		) );

		Request_Util::put( 'documents/builddoc', array(
			'modulepart'   => 'propal',
			'original_file' => $doli_proposal->ref . '/' . $doli_proposal->ref . '.pdf',
		) );

		Cart_Session::g()->add_external_data( 'doli_proposal_id', $doli_proposal_id );
		Cart_Session::g()->update_session();
	}

	/**
	 * Ajoutes la case à cocher pour confirmer les termes.
	 *
	 * @since 2.0.0
	 */
	public function add_terms() {
		$page_ids             = get_option( 'wps_page_ids', Pages::g()->default_options );
		$privacy_policy_id    = (int) get_option( 'wp_page_for_privacy_policy', true );
		$condition_general_id = ! empty( $page_ids['general_conditions_of_sale'] ) ? (int) $page_ids['general_conditions_of_sale'] : 0;

		if ( ! empty( $privacy_policy_id ) || ! empty( $condition_general_id ) ) {
			$privacy_policy    = get_post( $privacy_policy_id );
			$condition_general = get_post( $condition_general_id );
			$terms_message     = __( 'I accept ', 'wpshop' );

			if ( ! empty( $condition_general_id ) && null !== $condition_general ) {
				// translators: The <a href="condition_general_link">Condition general name</a> and .
				$terms_message .= sprintf( __( 'the <a target="_blank" href="%1$s">%2$s</a> and ', 'wpshop' ), get_permalink( $condition_general->ID ), $condition_general->post_title );
			}

			if ( ! empty( $privacy_policy_id ) && null !== $privacy_policy ) {
				// translators: the <a href="privacy_policy_link">Privacy policy name</a>.
				$terms_message .= sprintf( __( 'the <a target="_blank" href="%1$s">%2$s</a>', 'wpshop' ), get_permalink( $privacy_policy->ID ), $privacy_policy->post_title );
			}

			include( Template_Util::get_template_part( 'checkout', 'terms' ) );
		}
	}

	/**
	 * Ajoutes le bouton "Demande de devis".
	 *
	 * @since 2.0.0
	 */
	public function add_devis_button() {
		include( Template_Util::get_template_part( 'checkout', 'devis-button' ) );
	}

	/**
	 * Ajoutes le bouton "Passer commande".
	 *
	 * @since 2.0.0
	 */
	public function add_place_order_button() {
		if ( Settings::g()->dolibarr_is_active() ) {
			include( Template_Util::get_template_part( 'checkout', 'place-order-button' ) );
		}
	}
}

new Checkout_Action();
