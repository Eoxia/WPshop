<?php
/**
 * La classe gérant les actions de l'API.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.5.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\Rest_Class;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * API Action Class.
 */
class API_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_check_cap', array( $this, 'check_cap' ), 1, 2 );

		add_action( 'rest_api_init', array( $this, 'callback_rest_api_init' ) );

		add_action( 'show_user_profile', array( $this, 'callback_edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_edit_user_profile' ) );

		add_action( 'wp_ajax_generate_api_key', array( $this, 'callback_generate_api_key' ) );
	}

	/**
	 * Vérifie que l'utilisateur à les droits.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  boolean         $cap     True ou false.
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return boolean                  True ou false.
	 */
	public function check_cap( $cap, $request ) {
		$headers = $request->get_headers();

		if ( empty( $headers['wpapikey'] ) ) {
			return false;
		}

		$wp_api_key = $headers['wpapikey'];

		$user = API::g()->get_user_by_token( $wp_api_key[0] );

		if ( empty( $user ) ) {
			return false;
		}

		wp_set_current_user( $user->ID );

		return true;
	}

	/**
	 * Initialise les routes de l'API.
	 *
	 * @since   2.0.0
	 * @version 2.5.0
	 */
	public function callback_rest_api_init() {
		register_rest_route( 'wpshop/v2', '/statut', array(
			'methods'             => array( 'GET' ),
			'callback'            => array( $this, 'check_statut' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/product/search', array(
			'methods'  => array( 'GET' ),
			'callback' => array( $this, 'callback_search' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/order', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_order' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/propal', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_propal' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/invoice', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_invoice' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/payment', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_payment' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/sync', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_wps_sync_from_dolibarr' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/wpml', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_wpml_object_id' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/wpml_insert_data', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_wpml_insert_data' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/wpml_delete_data', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_wpml_delete_data' ),
			'permission_callback' => function( $request ) {
				return Rest_Class::g()->check_cap( 'get', $request );
			},
		) );
	}

	/**
	 * Ajoute le champ clé API dans le compte utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param WP_User $user L'objet contenant la définition complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user ) {
		$token = get_user_meta( $user->ID, '_wpshop_api_key', true );

		View_Util::exec( 'wpshop', 'api', 'field-api', array(
			'id'    => $user->ID,
			'token' => $token,
		) );
	}

	/**
	 * Génère une clé API pour un utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_generate_api_key() {
		check_ajax_referer( 'generate_api_key' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$token = API::g()->generate_token();
		update_user_meta( $id, '_wpshop_api_key', $token );

		ob_start();
		View_Util::exec( 'wpshop', 'api', 'field-api', array(
			'id'    => $id,
			'token' => $token,
		) );

		wp_send_json_success( array(
			'namespace'        => 'wpshop',
			'module'           => 'API',
			'callback_success' => 'generatedAPIKey',
			'view'             => ob_get_clean(),
		) );
	}

	/**
	 * Permet de vérifier que l'application externe soit bien connecté.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return WP_REST_Response         La réponse au format JSON.
	 */
	public function check_statut( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_REST_Response( false );
		}

		return new \WP_REST_Response( true );
	}

	/**
	 * Recherche un produit depuis l'API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return WP_REST_Response         Les produits trouvés.
	 */
	public function callback_search( $request ) {
		$param    = $request->get_params();
		$products = Product::g()->get( array( 's' => $param['s'] ) );

		$response_products = array();

		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				$response_products[] = $product->data;
			}
		}

		$response = new \WP_REST_Response( $response_products );
		return $response;
	}

	 /**
 	 * Gestion de la route pour synchroniser un objet depuis dolibarr.
 	 *
 	 * @since   2.0.0
	 * @version 2.0.0
 	 *
 	 * @todo: Validate data request
 	 *
 	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return WP_REST_Response         Le statut de synchronisation.
 	 */
 	public function callback_wps_sync_from_dolibarr( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		if ( empty( $param['type'] ) || empty( $param['doli_id'] ) ) {
			$response->set_status( 400 );
			$response->set_data( array( 'status_code' => 404 ) );
			return $response;
		}

		$sync_status = Doli_Sync::g()->sync( 0, $param['doli_id'], $param['type'] );

		update_post_meta( $sync_status['wp_object']->data['id'], '_external_id', $param['doli_id'] );

		$response = new \WP_REST_Response( $sync_status );
		return $response;
	}

	/**
	 * Gestion de la route pour synchroniser un objet depuis dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return WP_REST_Response         Le statut de synchronisation.
	 */
	public function callback_wpml_object_id( $request ) {
		$param = $request->get_params();
		$wpml_id = 10;
		//$wpml_id = apply_filters( 'wpml_object_id', $id, 'post', false, "fr");
		return $wpml_id;
	}

	/**
	 * Gestion de la route pour synchroniser un objet depuis dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return WP_REST_Response         Le statut de synchronisation.
	 */
	public function callback_wpml_insert_data( $request ) {
		$param = $request->get_params();

		// Create post object
		$my_post = array(
			'post_title'    => $param['label'],
			'post_content'  => $param['description'],
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_category' => array(2)
		);

		$output = wp_insert_post($my_post);

		if ( $output ) {
			// https://wpml.org/wpml-hook/wpml_element_type/
			$wpml_element_type = apply_filters( 'wpml_element_type', 'post' );

			// get the language info of the original post
			// https://wpml.org/wpml-hook/wpml_element_language_details/
			$get_language_args = array('element_id' => $param['fk_product'], 'element_type' => 'post' );
			$original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );

			$set_language_args = array(
				'element_id'           => $output,
				'element_type'         => $wpml_element_type,
				'trid'                 => $param['wpshop_id'],
				'language_code'        => $param['lang'],
				'source_language_code' => $original_post_language_info->language_code
			);

			do_action( 'wpml_set_element_language_details', $set_language_args );
		}

		return $output;
	}

	/**
	 * Gestion de la route pour synchroniser un objet depuis dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return WP_REST_Response         Le statut de synchronisation.
	 */
	public function callback_wpml_delete_data( $request ) {
		$param = $request->get_params();

		$output = wp_delete_post($param['id']);

		return $output;
	}

	/**
	 * Gestion de la route pour créer une commande sur dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return Doli_Order               Les données de la commande crée.
	 */
	public function callback_create_order( $request ) {
 		$param    = $request->get_params();

		$third_party_id = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $param['socid'] );

		if ( empty( $third_party_id ) ) {
			$wp_entry = Third_Party::g()->get( array( 'schema' => true ), true );

			$doli_entry = Request_Util::get( 'thirdparties/' . $param['socid'] );
			$third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_entry, $wp_entry );
			$third_party_id = $third_party->data['id'];
		}

		$data = array(
			'external_id'       => $param['external_id'],
			'title'             => $param['title'],
			'total_ht'          => $param['total_ht'],
			'total_ttc'         => $param['total_ttc'],
			'date_commande'     => date( 'Y-m-d H:i:s', $param['date_commande'] ),
			'date_creation'     => date( 'Y-m-d H:i:s', $param['date_creation'] ),
			'parent_id'         => $third_party_id,
			'status'            => 'draft',
			'date_last_synchro' => current_time( 'mysql' ),
		);

		$order = Doli_Order::g()->create( $data );
		return $order;
	}

	/**
	 * Gestion de la route pour créer une proposition commerciale sur dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return Proposals                Les données de la proposition commerciale crée.
	 */
	public function callback_create_propal( $request ) {
 		$param = $request->get_params();

		$third_party_id = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $param['socid'] );

		if ( empty( $third_party_id ) ) {
			$wp_entry = Third_Party::g()->get( array( 'schema' => true ), true );

			$doli_entry = Request_Util::get( 'thirdparties/' . $param['socid'] );
			$third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_entry, $wp_entry );
			$third_party_id = $third_party->data['id'];
		}

		$data = array(
			'external_id'       => $param['external_id'],
			'title'             => $param['title'],
			'total_ht'          => $param['total_ht'],
			'total_ttc'         => $param['total_ttc'],
			'datec'             => date( 'Y-m-d H:i:s', $param['datec'] ),
			'parent_id'         => $third_party_id,
			'status'            => 'draft',
			'date_last_synchro' => $param['date_last_synchro'],
		);

		$propal = Proposals::g()->create( $data );
		return $propal;
	}

	/**
	 * Gestion de la route pour créer une facture sur dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return Doli_Invoice             Les données de la facture crée.
	 */
	public function callback_create_invoice( $request ) {
 		$param = $request->get_params();

		$third_party_id = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $param['socid'] );

		if ( empty( $third_party_id ) ) {
			$wp_entry = Third_Party::g()->get( array( 'schema' => true ), true );

			$doli_entry = Request_Util::get( 'thirdparties/' . $param['socid'] );
			$third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_entry, $wp_entry );
			$third_party_id = $third_party->data['id'];
		}

		$data = array(
			'external_id'       => $param['external_id'],
			'title'             => $param['title'],
			'total_ht'          => $param['total_ht'],
			'total_ttc'         => $param['total_ttc'],
			'third_party_id'    => $third_party_id,
			'status'            => 'draft',
			'date_last_synchro' => current_time( 'mysql' ),
		);

		if ( ! empty( $param['linked_object']['commande'] ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.

			$order_id            = Doli_Order::g()->get_wp_id_by_doli_id( $param['linked_object']['commande'] );
			$data['post_parent'] = $order_id;

			$order             = Doli_Order::g()->get( array( 'id' => $order_id ), true );
			$data['author_id'] = $order->data['author_id'];
		}

		LOG_Util::log( sprintf( 'POST /create/invoice with data %s', json_encode( $data ) ), 'wpshop2' );
		$invoice = Doli_Invoice::g()->create( $data );
		return $invoice;
	}

	/**
	 * Gestion de la route pour créer un paiement sur dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @todo: Validate data request
	 *
	 * @param  WP_REST_Request $request L'objet contenant les informations de la requête.
	 *
	 * @return Doli_Payment             Les données du paiement crée.
	 */
	public function callback_create_payment( $request ) {
		$param = $request->get_params();

		$invoice_id = Doli_Invoice::g()->get_wp_id_by_doli_id( $param['parent_id'] );

		$data = array(
			'external_id'  => $param['external_id'],
			'title'        => $param['title'],
			'amount'       => $param['amount'],
			'date'         => $param['date'],
			'status'       => 'publish',
			'last_sync'    => $param['last_sync'],
			'parent_id'    => $invoice_id,
			'payment_type' => ! empty( $param['paiementcode'] ) ? Doli_Payment::g()->convert_to_wp( $param['paiementcode'] ) : '-',
		);

		$propal = Doli_Payment::g()->create( $data );

		return $propal;
	}
}

new API_Action();
