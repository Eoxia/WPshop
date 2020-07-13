<?php
/**
 * Gestion des actions d'association des entités avec dolibarr.
 *
 * @todo: Translate to English.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019-2020 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Associate Action Class.
 */
class Doli_Associate_Action {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_associate_modal', array( $this, 'load_associate_modal' ) );
		add_action( 'wp_ajax_load_compare_modal', array( $this, 'load_compare_modal' ) );
		add_action( 'wp_ajax_associate_entry', array( $this, 'associate_entry' ) );
	}

	/**
	 * Charges la modal d'association.
	 *
	 * @todo: Translate to english.
	 *
	 * @since 2.0.0
	 */
	public function load_associate_modal() {
		check_ajax_referer( 'load_associate_modal' );

		$id   = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$type = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );
		$entries = Request_Util::get( $sync_info['endpoint'] . '?limit=-1' );

		if ( ! empty( $entries ) ) {
			foreach ( $entries as $key => $entry ) {
				$wp_entry = $sync_info['wp_class']::g()->get( array(
					'meta_key'   => '_external_id',
					'meta_value' => (int) $entry->id,
				), true );

				if ( ! empty( $wp_entry ) ) {
					unset( $entries[ $key ] );
				}
			}
		}

		ob_start();
		View_Util::exec( 'wpshop', 'doli-associate', 'main', array(
			'entries' => $entries,
			'wp_id'   => $id,
			'type'    => $type,
			'label'   => $sync_info['title'],
		) );
		$view = ob_get_clean();

		ob_start();
		View_Util::exec( 'wpshop', 'doli-associate', 'single-footer' );
		$buttons_view = ob_get_clean();

		wp_send_json_success( array(
			'view'         => $view,
			'buttons_view' => $buttons_view,
		) );
	}

	/**
	 * Charges la modal pour comparer les données de WordPress et Dolibarr.
	 *
	 * @since 2.0.0
	 */
	public function load_compare_modal() {
		check_ajax_referer( 'load_compare_modal' );

		$wp_id    = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$entry_id = ! empty( $_POST['entry_id'] ) ? (int) $_POST['entry_id'] : 0;
		$type     = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );

		if ( empty( $entry_id ) ) {
			wp_send_json_success( array(
				'namespace'        => 'wpshop',
				'module'           => 'doliAssociate',
				'callback_success' => 'openModalCompareSuccess',
				'error'            => array(
					'status'  => true,
					'message' => __( 'Please select an entry', 'wpshop' ),
				),
			) );
		}

		$wp_entry         = $sync_info['wp_class']::g()->get( array( 'id' => $wp_id ), true );
		$doli_entry       = Request_Util::get( $sync_info['endpoint'] . '/' . $entry_id );

		$doli_to_wp_entry = $sync_info['wp_class']::g()->get( array( 'schema' => true ), true );
		$doli_to_wp_entry = $sync_info['doli_class']::g()->doli_to_wp( $doli_entry, $doli_to_wp_entry, false );

		$entries = array(
			'wordpress' => array( // WPCS: spelling ok.
				'title' => __( 'WordPress', 'wpshop' ),
				'data'  => $wp_entry->data,
				'id'    => $wp_entry->data['id'],
			),
			'dolibarr'  => array(
				'title' => __( 'Dolibarr', 'wpshop' ),
				'data'  => $doli_to_wp_entry->data,
				'id'    => $entry_id,
			),
		);

		ob_start();
		View_Util::exec( 'wpshop', 'doli-associate', 'compare-' . $type, array(
			'entries' => $entries,
			'type'    => $type,
		) );
		$view = ob_get_clean();

		ob_start();
		View_Util::exec( 'wpshop', 'doli-associate', 'compare-footer', array(
			'type'     => $type,
			'wp_id'    => $wp_id,
			'entry_id' => $entry_id,
		) );
		$footer_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'wpshop',
			'module'           => 'doliAssociate',
			'callback_success' => 'openModalCompareSuccess',
			'view'             => $view,
			'footer_view'      => $footer_view,
		) );
	}

	public function associate_entry() {
		$wp_id    = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$entry_id = ! empty( $_POST['entry_id'] ) ? (int) $_POST['entry_id'] : 0;
		$type     = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );

		$data = array(
			'wp_id'   => $wp_id,
			'doli_id' => $entry_id,
		);

		$response = Request_Util::get( 'doliwpshop/associate' . $sync_info['associate_endpoint'] . '?' . http_build_query( $data ) );

		if ( ! $response || ( $response && isset( $response->error ) ) ) {
			wp_send_json_success( array(
				'namespace'        => 'wpshop',
				'module'           => 'doliAssociate',
				'callback_success' => 'associatedEntryError',
			) );
		}

		update_post_meta( $wp_id, '_external_id', $entry_id );

		// Sync.
		$status_sync = Doli_Sync::g()->sync( $wp_id, $entry_id, $type );

		ob_start();
		View_Util::exec( 'wpshop', 'doli-associate', 'modal-associate-result', array(
			'notice' => array(
				'messages' => isset( $status_sync['messages'] ) ? $status_sync['messages'] : array(),
				'errors'   => isset( $status_sync['errors'] ) ? $status_sync['errors'] : array(),
			),
		) );

		$view = ob_get_clean();
		ob_start();
		View_Util::exec( 'wpshop', 'doli-associate', 'modal-associate-footer' );
		$modal_footer = ob_get_clean();

		$wp_entry = $sync_info['wp_class']::g()->get( array( 'id' => $wp_id ), true );

		ob_start();
		$sync_info['wp_class']::g()->display_item( $wp_entry, true );
		$line_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'wpshop',
			'module'           => 'doliAssociate',
			'callback_success' => 'associatedEntrySuccess',
			'view'             => $view,
			'modal_footer'     => $modal_footer,
			'line_view' => $line_view,
			'id'                => $wp_id,
		) );
	}
}

new Doli_Associate_Action();
