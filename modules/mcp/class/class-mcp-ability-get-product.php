<?php
/**
 * Ability wpshop/get-product: a product or service in Dolibarr and on the shop.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_Get_Product extends Mcp_Ability {

	public function get_name() {
		return 'wpshop/get-product';
	}

	public function get_label() {
		return __( 'Read a product (Dolibarr and shop)', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Reads a product or service in Dolibarr and on the shop, and compares them.', 'wpshop' );
	}

	public function get_description() {
		return 'Reads one product or service: its Dolibarr record (the fields WPshop synchronizes: ref, label, description, type, prices, VAT, barcode, stock, shop status), its categories, its shop page if it is synchronized, and the sync status. '
			. 'Give doli_id (Dolibarr ID), wp_id (shop ID) or ref. Reads only.';
	}

	public function get_risk() {
		return self::RISK_READ;
	}

	public function get_input_schema() {
		return array(
			'type'                 => 'object',
			'properties'           => array(
				'doli_id' => array(
					'type'        => 'integer',
					'minimum'     => 1,
					'description' => 'Dolibarr product ID.',
				),
				'wp_id'   => array(
					'type'        => 'integer',
					'minimum'     => 1,
					'description' => 'Shop product ID (WordPress).',
				),
				'ref'     => array(
					'type'        => 'string',
					'description' => 'Dolibarr ref.',
				),
			),
			'additionalProperties' => false,
		);
	}

	public function execute( $input ) {
		$doli_id = isset( $input['doli_id'] ) ? (int) $input['doli_id'] : 0;

		if ( ! $doli_id && ! empty( $input['wp_id'] ) ) {
			$doli_id = (int) get_post_meta( (int) $input['wp_id'], '_external_id', true );
			if ( ! $doli_id ) {
				return new \WP_Error( 'wpshop_not_linked', __( 'This shop product is not linked to Dolibarr.', 'wpshop' ) );
			}
		}

		if ( $doli_id ) {
			$doli_product = Mcp_Catalog::doli_get( 'products/' . $doli_id );
		} elseif ( ! empty( $input['ref'] ) ) {
			$doli_product = Mcp_Catalog::doli_get( 'products/ref/' . rawurlencode( sanitize_text_field( $input['ref'] ) ) );
		} else {
			return new \WP_Error( 'wpshop_missing_id', __( 'Give doli_id, wp_id or ref.', 'wpshop' ) );
		}

		if ( is_wp_error( $doli_product ) ) {
			return $doli_product;
		}

		$result = array( 'dolibarr' => Mcp_Catalog::describe_doli_product( $doli_product ) );

		$categories = Mcp_Catalog::doli_list( 'categories/object/product/' . (int) $doli_product->id );
		$result['dolibarr']['categories'] = array();
		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$result['dolibarr']['categories'][] = array(
					'doli_id' => (int) $category->id,
					'label'   => (string) $category->label,
				);
			}
		}

		$wp_id = Mcp_Catalog::get_wp_id( Mcp_Catalog::OBJECT_PRODUCT, (int) $doli_product->id );
		if ( $wp_id ) {
			$result['shop'] = Mcp_Catalog::describe_wp_product( get_post( $wp_id ) );
			$result['sync'] = Mcp_Catalog::get_sync_state( Mcp_Catalog::OBJECT_PRODUCT, $wp_id );
		} else {
			$result['shop'] = null;
			$result['sync'] = array(
				'status' => 'not_on_shop',
				'fields' => array(),
				'notes'  => array( __( 'Not on the shop yet: wpshop/sync creates it there.', 'wpshop' ) ),
			);
		}

		return $result;
	}
}
