<?php
/**
 * Ability wpshop/sync: synchronizes Dolibarr objects to the shop.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_Sync extends Mcp_Ability {

	const MAX = 20;

	public function get_name() {
		return 'wpshop/sync';
	}

	public function get_label() {
		return __( 'Synchronize from Dolibarr', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Synchronizes products, services or categories from Dolibarr to the shop, like the WPshop sync screen.', 'wpshop' );
	}

	public function get_description() {
		return 'Synchronizes Dolibarr objects to the shop, exactly like the WPshop sync screen: the shop takes the Dolibarr data (label, description, prices, VAT, stock, barcode, categories, image, publication status). An object not on the shop yet is created there. Nothing is changed in Dolibarr, except the link back to the shop. '
			. 'Use the doli_id values given by wpshop/list-sync-status or wpshop/get-product. At most ' . self::MAX . ' objects per call.';
	}

	public function get_risk() {
		return self::RISK_WRITE;
	}

	public function get_input_schema() {
		return array(
			'type'                 => 'object',
			'required'             => array( 'doli_ids' ),
			'properties'           => array(
				'object'   => array(
					'type'        => 'string',
					'enum'        => array( 'product', 'category' ),
					'default'     => 'product',
					'description' => 'product = products and services; category = product categories.',
				),
				'doli_ids' => array(
					'type'        => 'array',
					'items'       => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
					'minItems'    => 1,
					'maxItems'    => self::MAX,
					'description' => 'Dolibarr IDs to synchronize.',
				),
			),
			'additionalProperties' => false,
		);
	}

	public function execute( $input ) {
		$object  = Mcp_Abilities::enum_input( $input, 'object', array( 'product', 'category' ), 'product' );
		$ids     = array_slice( Mcp_Abilities::ids_input( $input, 'doli_ids' ), 0, self::MAX );
		$results = array();
		$errors  = array();

		foreach ( $ids as $doli_id ) {
			$synced = Mcp_Catalog::sync( $object, $doli_id );
			if ( is_wp_error( $synced ) ) {
				$errors[] = array(
					'doli_id' => $doli_id,
					'error'   => $synced->get_error_message(),
				);
			} else {
				$results[] = $synced;
			}
		}

		return array(
			'synced' => $results,
			'errors' => $errors,
		);
	}
}
