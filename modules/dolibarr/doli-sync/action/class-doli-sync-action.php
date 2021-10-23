<?php
/**
 * La classe gérant les actions de synchronisations des entités de dolibarr.
 *
 * @todo: Translate to English.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Sync Action Class.
 */
class Doli_Sync_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_modal_sync', array( $this, 'load_modal_sync' ) );

		add_action( 'wp_ajax_sync', array( $this, 'sync' ) );
		add_action( 'wp_ajax_sync_entry', array( $this, 'sync_entry' ) );

		add_action( 'wps_listing_table_header_end', array( $this, 'add_sync_header' ) );
		add_action( 'wps_listing_table_end', array( $this, 'add_sync_item' ), 10, 2 );

		add_action( 'wp_ajax_check_sync_status', array( $this, 'check_sync_status' ) );
	}

	/**
	 * Charge la modal de synchronisation.
	 *
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function load_modal_sync() {
		check_ajax_referer( 'load_modal_sync' );

		$sync_action = ! empty( $_POST['sync'] ) ? sanitize_text_field( $_POST['sync'] ) : '';
		$sync_infos  = Doli_Sync::g()->sync_infos;
		$sync_action = explode( ',', $sync_action );

		if ( ! empty( $sync_infos ) ) {
			foreach ( $sync_infos as $key => &$sync_info ) {
				if ( ! in_array( $key, $sync_action, true ) ) {
					unset ( $sync_infos[ $key ] );
					continue;
				}

				$sync_info['last']         = false;
				$sync_info['total_number'] = 0;
				$sync_info['page']         = 0;
				$sync_info                 = Doli_Sync::g()->count_entries( $sync_info );

				if ( end( $sync_action ) == $key ) {
					$sync_info['last'] = true;
				}
			}
		}

		ob_start();
		View_Util::exec( 'wpshop', 'doli-sync', 'main', array(
			'sync_infos' => $sync_infos,
		) );
		$view = ob_get_clean();

		ob_start();
		View_Util::exec( 'wpshop', 'doli-sync', 'modal-sync-button' );
		$buttons_view = ob_get_clean();
		wp_send_json_success( array(
			'view'         => $view,
			'buttons_view' => $buttons_view,
		) );
	}

	/**
	 * Fait la synchronisation.
	 *
	 * @todo: Refactoring
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function sync() {
		check_ajax_referer( 'sync' );

		$done           = false;
		$updateComplete = false;

		$type = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$done_number  = ! empty( $_POST['done_number'] ) ? (int) $_POST['done_number'] : 0;
		$total_number = ! empty( $_POST['total_number'] ) ? (int) $_POST['total_number'] : 0;
		$last         = ( ! empty( $_POST['last'] ) && '1' == $_POST['last'] ) ? true : false;

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );

		// @todo: Do Array http_build_query.
		$doli_entries = Request_Util::get( $sync_info['endpoint'] . '?sortfield=t.rowid&sortorder=ASC&limit=' . Doli_Sync::g()->limit_entries_by_request . '&page=' . $done_number / Doli_Sync::g()->limit_entries_by_request );

		if ( ! empty( $doli_entries ) ) {
			foreach ( $doli_entries as $doli_entry ) {

				// translators: Try to sync %s.
				LOG_Util::log( sprintf( 'Try to sync %s', json_encode( $doli_entry ) ), 'wpshop2' );
				$wp_entry = $sync_info['wp_class']::g()->get( array(
					'meta_key'   => '_external_id',
					'meta_value' => (int) $doli_entry->id,
				), true );

				if ( empty( $wp_entry ) ) {
					$wp_entry = $sync_info['wp_class']::g()->get( array( 'schema' => true ), true );
				}

				do_action( 'wps_sync_' . $type . '_before', $doli_entry, $wp_entry );
				$sync_info['doli_class']::g()->doli_to_wp( $doli_entry, $wp_entry );
				do_action( 'wps_sync_' . $type . '_after', $doli_entry, $wp_entry );

				// translators: Sync done for the entry {json_data}.
				LOG_Util::log( sprintf( 'Sync done for the entry %s', json_encode( $doli_entry ) ), 'wpshop2' );

				$done_number++;
			}
		}

		if ( $done_number >= $total_number ) {
			$done_number = $total_number;
			$done        = true;

			if ( $last ) {
				$updateComplete = true;
			}
		}

		wp_send_json_success( array(
			'updateComplete'     => $updateComplete,
			'done'               => $done,
			'progression'        => $done_number . '/' . $total_number,
			'progressionPerCent' => 0 !== $total_number ? ( ( $done_number * 100 ) / $total_number ) : 0,
			'doneDescription'    => $done_number . '/' . $total_number,
			'doneElementNumber'  => $done_number,
			'errors'             => null,
		) );
	}

	/**
	 * Synchronise une entrée.
	 *
	 * @todo: Translate to english and comment.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function sync_entry() {
		check_ajax_referer( 'sync_entry' );

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$wp_id   		 = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$entry_id		 = ! empty( $_POST['entry_id'] ) ? (int) $_POST['entry_id'] : 0;
		$type    		 = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$sync_status = Doli_Sync::g()->sync( $wp_id, $entry_id, $type );
		$sync_info   = Doli_Sync::g()->get_sync_infos( $type );

		ob_start();
		// @todo: Add display_item for contact.
		if ( $type !== 'wps-user' || $type !== 'wps-product-cat' ) {
			$sync_info['wp_class']::g()->display_item( $sync_status['wp_object'], true, $dolibarr_option['dolibarr_url'] );
		}

		$item_view = ob_get_clean();

		wp_send_json_success( array(
			'id'               => $wp_id,
			'namespace'        => 'wpshop',
			'module'           => 'doliSync',
			'callback_success' => 'syncEntrySuccess',
			'item_view'        => $item_view,
		) );
	}

	/**
	 * Appel la vue pour ajouter "Synchro" dans le header du listing.
	 *
	 * @todo: Translate to English.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function add_sync_header( $type ) {
		if ( in_array( $type, array( 'products', 'thirdparties', 'proposals' ) ) && Settings::g()->dolibarr_is_active() ) {
			View_Util::exec( 'wpshop', 'doli-sync', 'sync-header' );
		}
	}

	/**
	 * Prépare les données pour l'état de synchronisation de l'entité.
	 * et appel la vue sync-item.
	 *
	 * @todo: Translate to English.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param mixed   $object      Les données d'une entité.
	 * @param boolean $sync_status Le statut de la synchronisation.
	 */
	public function add_sync_item( $object, $sync_status ) {
		if ( Settings::g()->dolibarr_is_active() && in_array( $object->data['type'], array( 'wps-product', 'wps-third-party', 'wps-proposal','wps-product-cat' ) ) ) {
			Doli_Sync::g()->display_sync_status( $object, $object->data['type'], $sync_status );
		}
	}

	/**
	 * Vérifie le statut de la synchronisation.
	 *
	 * @todo: nonce
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function check_sync_status() {
		$wp_id = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$type  = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		if ( empty( $wp_id ) && ! in_array( $type, array( 'wps-product', 'wps-third-party', 'wps-proposals', 'wps-user', 'wps-product-cat' ) ) ) {
			wp_send_json_error();
		}

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );

		$object = $sync_info['wp_class']::g()->get( array( 'id' => $wp_id ), true );

		ob_start();
		$status = Doli_Sync::g()->display_sync_status( $object, $type );
		$view = ob_get_clean();
		wp_send_json_success( array(
			'view'   => $view,
			'id'     => $wp_id,
			'status' => $status,
		) );
	}
}

new Doli_Sync_Action();
