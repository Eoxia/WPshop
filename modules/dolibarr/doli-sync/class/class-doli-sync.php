<?php
/**
 * La classe gérant les fonctions principales des synchronisations des entités de dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.1
 */

namespace wpshop;

use eoxia\Singleton_Util;
use eoxia\View_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 *  Doli Sync Class.
 */
class Doli_Sync extends Singleton_Util {

	/**
	 * Le tableau contenant toutes les donnés des synchronisations à effectuer.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $sync_infos = array();

	/**
	 * Limite de synchronisation par requête.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var integer
	 */
	public $limit_entries_by_request = 50;

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {
		$this->sync_infos = array(
			'wps-third-party' => array(
				'title'              => __( 'Third parties', 'wpshop' ),
				'action'             => 'sync_third_parties',
				'nonce'              => 'sync_third_parties',
				'endpoint'           => 'thirdparties',
				'associate_endpoint' => 'Thirdparty',
				'wp_class'           => '\wpshop\\Third_Party', // @todo: Plural and not plural.
				'doli_class'         => '\wpshop\\Doli_Third_Parties',
				'doli_type'          => 'third_party',
			),
			'wps-product'      => array(
				'title'              => __( 'Products', 'wpshop' ),
				'action'             => 'sync_products',
				'nonce'              => 'sync_products',
				'endpoint'           => 'Products',
				'associate_endpoint' => 'Product',
				'wp_class'           => '\wpshop\\Product',
				'doli_class'         => '\wpshop\\Doli_Products',
				'doli_type'          => 'product',
			),
			'wps-proposal'     => array(
				'title'              => __( 'Proposals', 'wpshop' ),
				'action'             => 'sync_proposals',
				'nonce'              => 'sync_proposals',
				'endpoint'           => 'proposals',
				'associate_endpoint' => 'Proposal',
				'wp_class'           => '\wpshop\\Proposals',
				'doli_class'         => '\wpshop\\Doli_Proposals',
				'doli_type'          => 'propal',
			),
			'wps-product-cat'     => array(
				'title'              => __( 'Categories', 'wpshop' ),
				'action'             => 'sync_categories',
				'nonce'              => 'sync_categories',
				'endpoint'           => 'categories',
				'associate_endpoint' => 'Category',
				'wp_class'           => '\wpshop\\Doli_Category',
				'doli_class'         => '\wpshop\\Doli_Category',
				'doli_type'          => 'category',
			),
		);
	}

	/**
	 * Get sync info by type.
	 *
	 * @todo: Mal nommé
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $post_type Le type de l'entité.
	 *
	 * @return array             Les données d'une synchronisation.
	 */
	public function get_sync_infos( $post_type ) {
		return $this->sync_infos[ $post_type ];
	}

	/**
	 * Compte le nombre d'entrée.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $sync_info Les informations de synchro.
	 *
	 * @return array            Les informations de synchro avec le nombre total d'élement en plus.
	 */
	public function count_entries( $sync_info ) {
		if ( ! empty( $sync_info['endpoint'] ) ) {
			$args = array(
				'limit' => $this->limit_entries_by_request,
				'page'  => $sync_info['page'],
			);

			$args = implode( '&', array_map( function( $v, $k ) {
				return $k . '=' . $v;
			}, $args, array_keys( $args ) ) );

			$tmp = Request_Util::get( $sync_info['endpoint'] . '?' . $args );

			if ( $tmp ) {
				$count                      = count( $tmp );
				$sync_info['total_number'] += count( $tmp );

				if ( $count >= $this->limit_entries_by_request ) {
					$sync_info['page']++;
					$sync_info = $this->count_entries( $sync_info );
				}
			}
		}

		return $sync_info;
	}

	/**
	 * Associe et synchronise les données d'une entité.
	 *
	 * @todo: Translate to english.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 *
	 * @param  integer $wp_id    L'id de l'entitée sur WordPress.
	 * @param  integer $entry_id L'id de l'entitée sur Dolibarr.
	 * @param  string  $type     Le type de l'entitée.
	 *
	 * @return array             Les données d'une synchronisation.
	 *
	 * // @todo: Handle Error sync.
	 */
	public function sync( $wp_id, $entry_id, $type ) {
		global $wpdb;
		$wp_error  = new \WP_Error();
		$wp_object = null;
		$messages  = array();

		switch ( $type ) {
			case 'wps-third-party':
				$doli_third_party = Request_Util::get( 'thirdparties/' . $entry_id );
				$wp_third_party   = Third_Party::g()->get( array( 'id' => $wp_id ), true );

				$wp_third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_third_party, $wp_third_party );

				// translators: Erase date for the third party <strong>Eoxia</strong> with the <strong>dolibarr</strong> data.
				$messages[] = sprintf( __( 'Erase data for the third party <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop' ), $wp_third_party->data['title'] );

				$wp_object = $wp_third_party;
				break;
			case 'wps-product':
				$doli_product = Request_Util::get( 'products/' . $entry_id );
				$wp_product   = Product::g()->get( array( 'id' => $wp_id ), true );
				$wp_product   = Doli_Products::g()->doli_to_wp( $doli_product, $wp_product );

				$messages[] = sprintf( __( 'Erase data for the product <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop' ), $wp_product->data['title'] );

				echo do_shortcode('[wps_categories]');

				$doli_categories = Request_Util::get( 'categories/object/product/' . $entry_id . '?' );
				$wpdb->delete($wpdb->prefix . 'term_relationships', array( 'object_id' => $wp_product->data['id'] ) );
				if ( ! empty( $doli_categories ) ) {
					foreach ( $doli_categories as $doli_category ) {
						$term_taxonomy_id = get_term_by('name', $doli_category->label, 'wps-product-cat' )->term_id;
						$wpdb->insert( $wpdb->prefix . 'term_relationships', array( 'object_id' => $wp_product->data['id'] , 'term_taxonomy_id' => $term_taxonomy_id, 'term_order' => 0 ) );
					}
				}

				$wp_object = $wp_product;
				break;
			case 'wps-proposal':
				$doli_proposal = Request_Util::get( 'proposals/' . $entry_id );
				$wp_proposal   = Proposals::g()->get( array( 'id' => $wp_id ), true );

				Doli_Proposals::g()->doli_to_wp( $doli_proposal, $wp_proposal );

				$wp_object = $wp_proposal;
				break;
			case 'wps-product-cat':
				$doli_category = Request_Util::get( 'categories/' . $entry_id );
				$wp_category   = Doli_Category::g()->get( array( 'id' => $wp_id ), true );
				$wp_category   = Doli_Category::g()->doli_to_wp( $doli_category, $wp_category);

				$wp_object = $wp_category;
				break;
			default:
				break;
		}

		return array(
			'messages'  => $messages,
			'wp_error'  => $wp_error,
			'wp_object' => $wp_object,
		);
	}

	/**
	 * Vérifie la SHA256 entre une entité WPShop et une entité Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 *
	 * @param   integer $id   L'id de l'entité WordPress.
	 * @param   string  $type Le type de l'entité.
	 *
	 * @return array          Le statut de la synchronisation.
	 */
	public function check_status( $id, $type ) {
		$external_id = 0;
		$sha_256 = 0;
		global $wpdb;

		if ( $type == 'wps-user' ) {
			$external_id = get_user_meta($id, '_external_id', true);
			$sha_256 = get_user_meta($id, '_sync_sha_256', true);
		} elseif  ( $type == 'wps-product-cat' ) {
			$external_id = get_term_meta( $id, '_external_id', true );
			$sha_256     = get_term_meta( $id, '_sync_sha_256', true );
		} elseif ( $type == 'wps-product' ) {
			$external_id = get_post_meta( $id, '_external_id', true );
			$sha_256     = get_post_meta( $id, '_sync_sha_256', true );
		} else {
			$external_id = get_post_meta( $id, '_external_id' , true );
			$sha_256 = get_post_meta( $id, '_sync_sha_256', true );
		}

		$sync_info = $this->sync_infos[ $type ];

		$response = Request_Util::get( $sync_info['endpoint'] . '/' . $external_id );

		// Dolibarr return false when object is not found.
		if ( ! $response ) {
			// @todo: Doublon
			if ( $type == 'wps-user' ) {
				delete_user_meta( $id, '_external_id' );
				delete_user_meta( $id, '_sync_sha_256' );
			} elseif ( $type == 'wps-product-cat' ) {
				delete_term_meta( $id, '_external_id' );
				delete_term_meta( $id, '_sync_sha_256' );
			}  else {
				delete_post_meta( $id, '_external_id' );
				delete_post_meta( $id, '_sync_sha_256' );
			}

			return array(
				'status' => true,
				'status_code' => '0x1',
				'status_message' => 'Dolibarr Object: #' . $external_id . ' not exist. Automatically delete external_id.',
			);
		}

		// Dolibarr Object is not linked to this WP Object.
		if ( $response->array_options->options__wps_id != $id ) {
			// @todo: Doublon
			if ( $type == 'wps-user' ) {
				delete_user_meta( $id, '_external_id' );
				delete_user_meta( $id, '_sync_sha_256' );
			} elseif ( $type == 'wps-product-cat' ) {
				delete_term_meta( $id, '_external_id' );
				delete_term_meta( $id, '_sync_sha_256' );
			} else {
				delete_post_meta( $id, '_external_id' );
				delete_post_meta( $id, '_sync_sha_256' );
			}

			return array(
				'status' => true,
				'status_code' => '0x2',
				'status_message' => 'Dolibarr Object is not linked to this WP Object.',
			);
		}

		$object_id = $response->array_options->options__wps_id;
		$doli_categories = Request_Util::get('categories/object/product/' . $response->id . '?');
		$wp_categories   = $wpdb->get_results("SELECT * FROM ".$wpdb->term_relationships." WHERE object_id = $object_id", ARRAY_A);

		$wp_category_labels = array();
		$doli_category_labels = array();

		if ( ! empty ($doli_categories) ) {
			foreach ($doli_categories as $doli_category) {
				$doli_category_labels[] = get_term_by('name', $doli_category->label, 'wps-product-cat' )->term_id;
			}
		}

		if ( ! empty ( $wp_categories) ) {
			foreach ($wp_categories as $wp_category){
				$wp_category_labels[] = $wp_category['term_taxonomy_id'];
			}
		}

		$response = apply_filters( 'doli_build_sha_' . $type, $response, $id );
		// WP Object is not equal Dolibarr Object.
		if  ( $type == 'wps-product-cat' || $type == 'wps-third-party' ) {
			if ( $response->sha !== $sha_256 ) {
				return array(
					'status' => true,
					'status_code' => '0x3',
					'status_message' => __('WP Object is not equal Dolibarr Object', 'wpshop'),
					'response->sha' => $response->sha,
					'sha_256' => $sha_256,
				);
			}
		} else {
			if ( $response->sha !== $sha_256 || $wp_category_labels != $doli_category_labels ) {
				return array(
					'status' => true,
					'status_code' => '0x3',
					'status_message' => __('WP Object is not equal Dolibarr Object', 'wpshop'),
					'response->sha' => $response->sha,
					'sha_256' => $sha_256,
					'wp_category_labels' => $wp_category_labels,
					'doli_category_labels' => $doli_category_labels,
				);
			}
		}

		return array(
			'status' => true,
			'status_code' => '0x0',
			'status_message' => __('Sync OK', 'wpshop'),
		);
	}

	/**
	 * Affiche le statut de synchronisation d'une entité.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  mixed   $object          Les données de l'entité.
	 * @param  string  $type            Le type de l'entité.
	 * @param  boolean $load_erp_status Le statut de l'ERP.
	 *
	 * @return string                   Le statut de la synchronisation.
	 */
	public function display_sync_status( $object, $type, $load_erp_status = true )
	{
		$data_view = array(
			'object' => $object,
			'type' => $type,
			'status_color' => 'grey',
			'title' => '',
			'message_tooltip' => __( 'Looking for sync status', 'wpshop' ),
			'can_sync' => false,
		);

		if ( ! $load_erp_status ) {
			View_Util::exec('wpshop', 'doli-sync', 'sync-item', $data_view);
			return;
		}

		if ( empty($object->data['external_id'] ) ) {
			$data_view['status_color'] = 'none';
			$data_view['message_tooltip'] = __('No associated to an ERP Entity', 'wpshop');

			View_Util::exec('wpshop', 'doli-sync', 'sync-item', $data_view);
			return;
		}

		$response = Doli_Sync::g()->check_status($object->data['id'], $type);

		if ( ! $response || ! $response['status'] ) {
			$data_view['status_color'] = 'none';
		} else {
			// @todo: Do Const for status_code.
			switch ($response['status_code']) {
				case '0x0':
					$data_view['status_color'] = 'green';
					$data_view['can_sync'] = true;
					break;
//				case '0x1':
//					$data_view['status_color'] = 'none';
//					break;
//				case '0x2':
//					$data_view['status_color'] = 'none';
//					$object->data['external_id'] =  '';
//					break;
				case '0x3':
					$data_view['status_color'] = 'red';
					$data_view['can_sync'] = true;
					break;
			}
		}

		$data_view['message_tooltip'] = isset ( $response['status_message'] ) ? $response['status_message'] : __( 'Error not defined', 'wpshop' );
		View_Util::exec( 'wpshop', 'doli-sync', 'sync-item', $data_view );

		return $response;
	}
}

Doli_Sync::g();
