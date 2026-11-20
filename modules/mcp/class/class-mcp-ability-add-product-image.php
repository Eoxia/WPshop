<?php
/**
 * Ability wpshop/add-product-image: gives an image to existing products, through Dolibarr.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_Add_Product_Image extends Mcp_Ability {

	const MAX = 20;

	public function get_name() {
		return 'wpshop/add-product-image';
	}

	public function get_label() {
		return __( 'Add an image to products', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Adds an image to products that have none, when you ask for it. The image goes into the Dolibarr product, then is synchronized to the shop.', 'wpshop' );
	}

	public function get_description() {
		return 'Adds an image to existing products or services that have no image yet, when the user asks for it. '
			. 'The shop image comes from Dolibarr: the image is sent into the documents of the Dolibarr product, then the product is synchronized, which makes it the shop image. Never set the image of the shop page directly, the next sync would overwrite it. '
			. 'image_id is an image of the WordPress media library (jpg, png, gif or webp). Before adding images, ALWAYS ask the user which source they want, as for posts and pages: openly licensed photos from Openverse (eo-tools/search-images, free), or images you generate yourself or with another connected tool (may use credits), or images already in the media library. Import them with eo-tools/import-image (always with "alt") and give the returned media IDs. '
			. 'A product that already has an image in Dolibarr is refused: its image is changed in Dolibarr. Give doli_id or wp_id for each product. At most ' . self::MAX . ' products per call. For a product to create, use image_id in wpshop/propose-products instead.';
	}

	public function get_risk() {
		return self::RISK_WRITE;
	}

	public function needs_dolibarr() {
		return true;
	}

	public function get_input_schema() {
		return array(
			'type'                 => 'object',
			'required'             => array( 'items' ),
			'properties'           => array(
				'items' => array(
					'type'     => 'array',
					'minItems' => 1,
					'maxItems' => self::MAX,
					'items'    => array(
						'type'                 => 'object',
						'required'             => array( 'image_id' ),
						'properties'           => array(
							'doli_id'  => array(
								'type'    => 'integer',
								'minimum' => 1,
							),
							'wp_id'    => array(
								'type'    => 'integer',
								'minimum' => 1,
							),
							'image_id' => array(
								'type'        => 'integer',
								'minimum'     => 1,
								'description' => 'Media library ID of the image (eo-tools/import-image).',
							),
						),
						'additionalProperties' => false,
					),
				),
			),
			'additionalProperties' => false,
		);
	}

	public function execute( $input ) {
		$items  = isset( $input['items'] ) && is_array( $input['items'] ) ? array_slice( $input['items'], 0, self::MAX ) : array();
		$done   = array();
		$errors = array();

		foreach ( $items as $item ) {
			$item     = is_array( $item ) ? $item : array();
			$doli_id  = isset( $item['doli_id'] ) ? absint( $item['doli_id'] ) : 0;
			$wp_id    = isset( $item['wp_id'] ) ? absint( $item['wp_id'] ) : 0;
			$image_id = isset( $item['image_id'] ) ? absint( $item['image_id'] ) : 0;
			$label    = array_filter(
				array(
					'doli_id' => $doli_id,
					'wp_id'   => $wp_id,
				)
			);

			if ( ! $doli_id && $wp_id ) {
				$doli_id = Mcp_Catalog::PRODUCT_POST_TYPE === get_post_type( $wp_id ) ? (int) get_post_meta( $wp_id, '_external_id', true ) : 0;
				if ( ! $doli_id ) {
					$errors[] = $label + array( 'error' => __( 'Not a shop product linked to Dolibarr.', 'wpshop' ) );
					continue;
				}
			}
			if ( ! $doli_id ) {
				$errors[] = $label + array( 'error' => __( 'Give doli_id or wp_id.', 'wpshop' ) );
				continue;
			}

			$result = Mcp_Catalog::add_product_image( $doli_id, $image_id );
			if ( is_wp_error( $result ) ) {
				$errors[] = $label + array(
					'doli_id' => $doli_id,
					'error'   => $result->get_error_message(),
				);
				continue;
			}

			$done[] = $result;
		}

		return array(
			'updated' => $done,
			'errors'  => $errors,
		);
	}
}
