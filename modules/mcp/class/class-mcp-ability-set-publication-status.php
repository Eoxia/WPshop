<?php
/**
 * Ability wpshop/set-publication-status: publishes or hides products on the shop, through Dolibarr.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_Set_Publication_Status extends Mcp_Ability {

	const MAX = 20;

	public function get_name() {
		return 'wpshop/set-publication-status';
	}

	public function get_label() {
		return __( 'Publish or hide products', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Publishes products on the shop, or puts them back in draft, when you ask for it. The status is changed in Dolibarr, then synchronized.', 'wpshop' );
	}

	public function get_description() {
		return 'Publishes products or services on the shop (status "publish"), or hides them (status "draft"). Only when the user explicitly asks for it. '
			. 'The shop status is a Dolibarr field ("_wps_status"): it is changed in Dolibarr, then the product is synchronized to the shop. The shop page is never published directly, the next sync would overwrite it. '
			. 'Give doli_ids (Dolibarr IDs) or wp_ids (shop IDs). At most ' . self::MAX . ' products per call.';
	}

	public function get_risk() {
		return self::RISK_SENSITIVE;
	}

	public function get_input_schema() {
		return array(
			'type'                 => 'object',
			'required'             => array( 'status' ),
			'properties'           => array(
				'status'   => array(
					'type' => 'string',
					'enum' => array( 'publish', 'draft' ),
				),
				'doli_ids' => array(
					'type'     => 'array',
					'items'    => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
					'maxItems' => self::MAX,
				),
				'wp_ids'   => array(
					'type'     => 'array',
					'items'    => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
					'maxItems' => self::MAX,
				),
			),
			'additionalProperties' => false,
		);
	}

	public function execute( $input ) {
		$status = Mcp_Abilities::enum_input( $input, 'status', array( 'publish', 'draft' ), '' );
		if ( '' === $status ) {
			return new \WP_Error( 'wpshop_invalid_status', __( 'The status must be "publish" or "draft".', 'wpshop' ) );
		}

		$doli_ids = Mcp_Abilities::ids_input( $input, 'doli_ids' );
		$errors   = array();

		foreach ( Mcp_Abilities::ids_input( $input, 'wp_ids' ) as $wp_id ) {
			$doli_id = Mcp_Catalog::PRODUCT_POST_TYPE === get_post_type( $wp_id ) ? (int) get_post_meta( $wp_id, '_external_id', true ) : 0;
			if ( $doli_id ) {
				$doli_ids[] = $doli_id;
			} else {
				$errors[] = array(
					'wp_id' => $wp_id,
					'error' => __( 'Not a shop product linked to Dolibarr.', 'wpshop' ),
				);
			}
		}

		$doli_ids = array_slice( array_values( array_unique( $doli_ids ) ), 0, self::MAX );
		if ( ! $doli_ids && ! $errors ) {
			return new \WP_Error( 'wpshop_missing_id', __( 'Give doli_ids or wp_ids.', 'wpshop' ) );
		}

		$done = array();
		foreach ( $doli_ids as $doli_id ) {
			$result = Mcp_Catalog::set_shop_status( $doli_id, $status );
			if ( is_wp_error( $result ) ) {
				$errors[] = array(
					'doli_id' => $doli_id,
					'error'   => $result->get_error_message(),
				);
			} else {
				$done[] = $result;
			}
		}

		return array(
			'updated' => $done,
			'errors'  => $errors,
		);
	}
}
