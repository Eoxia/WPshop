<?php
/**
 * Ability wpshop/list-sync-status: sync status between Dolibarr and the shop.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_List_Sync_Status extends Mcp_Ability {

	public function get_name() {
		return 'wpshop/list-sync-status';
	}

	public function get_label() {
		return __( 'Sync status of the catalogue', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Lists the products, services and categories of the shop with their sync status with Dolibarr, or the Dolibarr ones missing on the shop.', 'wpshop' );
	}

	public function get_description() {
		return 'Sync status between Dolibarr (the only source of the catalogue) and the WPshop shop, page by page. Reads only: nothing is changed, neither on the shop nor in Dolibarr. '
			. 'scope "shop" (default): shop products/services (object "product") or product categories (object "category"), each with its status: ok, different (data changed in Dolibarr since the last sync; "fields" names them), categories_or_media (data ok but categories or image differ), unreachable (Dolibarr object not found or Dolibarr did not answer), linked_to_other_post, not_linked (created on the WordPress side, which should not happen). '
			. 'scope "dolibarr_only": Dolibarr objects that are not on the shop yet. '
			. 'Each shop object costs several Dolibarr requests: keep per_page small and use has_more to go on. only_out_of_sync filters the page, it does not change the paging. '
			. 'To fix a status, use wpshop/sync with the doli_id values.';
	}

	public function get_risk() {
		return self::RISK_READ;
	}

	public function get_input_schema() {
		return array(
			'type'                 => 'object',
			'properties'           => array(
				'object'           => array(
					'type'        => 'string',
					'enum'        => array( 'product', 'category' ),
					'default'     => 'product',
					'description' => 'product = products and services; category = product categories.',
				),
				'scope'            => array(
					'type'        => 'string',
					'enum'        => array( 'shop', 'dolibarr_only' ),
					'default'     => 'shop',
					'description' => 'shop: status of the shop objects. dolibarr_only: Dolibarr objects missing on the shop.',
				),
				'kind'             => array(
					'type'        => 'string',
					'enum'        => array( 'all', 'product', 'service' ),
					'default'     => 'all',
					'description' => 'For object "product": products, services or both.',
				),
				'only_out_of_sync' => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'scope "shop": leave out the objects in sync.',
				),
				'page'             => array(
					'type'    => 'integer',
					'minimum' => 1,
					'default' => 1,
				),
				'per_page'         => array(
					'type'    => 'integer',
					'minimum' => 1,
					'maximum' => 25,
					'default' => 10,
				),
			),
			'additionalProperties' => false,
		);
	}

	public function execute( $input ) {
		$object   = Mcp_Abilities::enum_input( $input, 'object', array( 'product', 'category' ), 'product' );
		$scope    = Mcp_Abilities::enum_input( $input, 'scope', array( 'shop', 'dolibarr_only' ), 'shop' );
		$kind     = Mcp_Abilities::enum_input( $input, 'kind', array( 'all', 'product', 'service' ), 'all' );
		$page     = Mcp_Abilities::int_input( $input, 'page', 1, 100000, 1 );
		$per_page = Mcp_Abilities::int_input( $input, 'per_page', 1, 25, 10 );

		if ( 'dolibarr_only' === $scope ) {
			return Mcp_Catalog::list_missing_on_shop( $object, $kind, $page, $per_page );
		}

		return Mcp_Catalog::list_shop_status( $object, $kind, ! empty( $input['only_out_of_sync'] ), $page, $per_page );
	}
}
