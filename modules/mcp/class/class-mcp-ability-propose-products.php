<?php
/**
 * Ability wpshop/propose-products: products and services to create in Dolibarr, validated by a human.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_Propose_Products extends Mcp_Ability {

	const MAX = 20;

	/**
	 * Allowed characters of a Dolibarr ref.
	 */
	const REF_PATTERN = '^[A-Za-z0-9][A-Za-z0-9._\-]{0,127}$';

	public function get_name() {
		return 'wpshop/propose-products';
	}

	public function get_label() {
		return __( 'Propose products to create in Dolibarr', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Proposes products or services to create in Dolibarr. Nothing is created until you validate them in EO Tools > MCP > À valider.', 'wpshop' );
	}

	public function get_description() {
		return 'Proposes products or services to create. Dolibarr is the only source of the catalogue: they are created in Dolibarr, never on the shop directly. Nothing is created now: each proposal waits for a human in "EO Tools > MCP > À valider". On validation, the product is created in Dolibarr, put in its categories, then synchronized to the shop with its publication status. '
			. 'Before proposing, check that the product does not exist yet (wpshop/get-product with the ref, wpshop/list-sync-status). Proposals whose ref already exists in Dolibarr, or is already proposed, are refused; possible duplicates (same label, same barcode) are shown to the human. '
			. 'category_ids are Dolibarr category IDs (wpshop/list-sync-status with object "category"); a category that does not exist yet must be proposed with wpshop/propose-categories and validated first. '
			. 'shop_status "draft" (default) keeps the product hidden on the shop; publish it later with wpshop/set-publication-status. '
			. 'Image (one per product): a shop product needs an image. Unless the user said they want no image, plan one for EVERY product before calling this tool: do not propose products without image by default. image_id is an image of the WordPress media library (jpg, png, gif or webp). Before adding images, ALWAYS ask the user which source they want, as for posts and pages: openly licensed photos from Openverse (eo-tools/search-images, free), or images you generate yourself or with another connected tool (may use credits), or an image already in the media library. Import it with eo-tools/import-image (always with "alt") and give the returned media ID. On validation, the image goes into the documents of the Dolibarr product, then the sync makes it the shop image. '
			. 'Stock is not handled here: it is managed in Dolibarr. At most ' . self::MAX . ' products per call.';
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
			'required'             => array( 'products' ),
			'properties'           => array(
				'products' => array(
					'type'     => 'array',
					'minItems' => 1,
					'maxItems' => self::MAX,
					'items'    => array(
						'type'                 => 'object',
						'required'             => array( 'kind', 'ref', 'label', 'price', 'tva_tx' ),
						'properties'           => array(
							'kind'            => array(
								'type' => 'string',
								'enum' => array( 'product', 'service' ),
							),
							'ref'             => array(
								'type'        => 'string',
								'pattern'     => self::REF_PATTERN,
								'description' => 'Unique Dolibarr ref: letters, digits, dot, dash, underscore.',
							),
							'label'           => array(
								'type'      => 'string',
								'minLength' => 1,
								'maxLength' => 255,
							),
							'description'     => array(
								'type'        => 'string',
								'description' => 'Description shown on the shop (plain text or simple HTML).',
							),
							'price'           => array(
								'type'        => 'number',
								'minimum'     => 0,
								'description' => 'Selling price, excluding or including VAT according to price_base_type.',
							),
							'price_base_type' => array(
								'type'    => 'string',
								'enum'    => array( 'HT', 'TTC' ),
								'default' => 'HT',
							),
							'tva_tx'          => array(
								'type'        => 'number',
								'minimum'     => 0,
								'maximum'     => 100,
								'description' => 'VAT rate in %, e.g. 20.',
							),
							'barcode'         => array(
								'type'    => 'string',
								'pattern' => '^[A-Za-z0-9\-]{0,64}$',
							),
							'category_ids'    => array(
								'type'  => 'array',
								'items' => array(
									'type'    => 'integer',
									'minimum' => 1,
								),
							),
							'shop_status'     => array(
								'type'    => 'string',
								'enum'    => array( 'draft', 'publish' ),
								'default' => 'draft',
							),
							'image_id'        => array(
								'type'        => 'integer',
								'minimum'     => 1,
								'description' => 'Media library ID of the product image (eo-tools/import-image). Ask the user for the image source first.',
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
		$products = isset( $input['products'] ) && is_array( $input['products'] ) ? array_slice( $input['products'], 0, self::MAX ) : array();

		$pending_refs = array();
		foreach ( Mcp_Proposals::get_by_kind( Mcp_Proposals::KIND_PRODUCT ) as $proposal ) {
			$pending_refs[ strtoupper( $proposal['data']['ref'] ) ] = true;
		}

		$without_image    = array();
		$known_categories = array();
		$proposed         = array();
		$refused          = array();

		foreach ( $products as $raw ) {
			$product = $this->sanitize( $raw );
			if ( is_wp_error( $product ) ) {
				$refused[] = array(
					'ref'    => isset( $raw['ref'] ) ? (string) $raw['ref'] : '',
					'reason' => $product->get_error_message(),
				);
				continue;
			}

			$key = strtoupper( $product['ref'] );
			if ( isset( $pending_refs[ $key ] ) ) {
				$refused[] = array(
					'ref'    => $product['ref'],
					'reason' => __( 'A proposal with this ref is already waiting for validation.', 'wpshop' ),
				);
				continue;
			}

			$missing = array();
			foreach ( $product['category_ids'] as $category_id ) {
				if ( ! isset( $known_categories[ $category_id ] ) ) {
					$known_categories[ $category_id ] = ! is_wp_error( Mcp_Catalog::doli_get( 'categories/' . $category_id ) );
				}
				if ( ! $known_categories[ $category_id ] ) {
					$missing[] = $category_id;
				}
			}
			if ( $missing ) {
				$refused[] = array(
					'ref'    => $product['ref'],
					/* translators: %s: Dolibarr category IDs. */
					'reason' => sprintf( __( 'Unknown Dolibarr categories: %s.', 'wpshop' ), implode( ', ', $missing ) ),
				);
				continue;
			}

			$duplicates = Mcp_Catalog::find_product_duplicates( $product );
			if ( $duplicates['blocking'] ) {
				$refused[] = array(
					'ref'    => $product['ref'],
					'reason' => implode( ' ', $duplicates['blocking'] ),
				);
				continue;
			}

			$id = Mcp_Proposals::add( Mcp_Proposals::KIND_PRODUCT, $product, $duplicates['warnings'] );
			if ( is_wp_error( $id ) ) {
				$refused[] = array(
					'ref'    => $product['ref'],
					'reason' => $id->get_error_message(),
				);
				continue;
			}

			$pending_refs[ $key ] = true;
			$proposed[]           = array(
				'proposal_id' => $id,
				'ref'         => $product['ref'],
				'warnings'    => $duplicates['warnings'],
			);
			if ( empty( $product['image_id'] ) ) {
				$without_image[] = $product['ref'];
			}
		}

		$result = array(
			'proposed'   => $proposed,
			'refused'    => $refused,
			'validation' => admin_url( 'admin.php?page=eo-tools-mcp&tab=proposals' ),
			'note'       => __( 'Nothing is created yet: ask the user to validate the proposals in EO Tools > MCP > À valider.', 'wpshop' ),
		);

		if ( $without_image ) {
			$result['without_image'] = array(
				'refs' => $without_image,
				'note' => 'These products have no image. Unless the user said they want none, tell the user, ask for the image source, and add the images after validation with wpshop/add-product-image.',
			);
		}

		return $result;
	}

	/**
	 * Validates and cleans one product.
	 *
	 * @param mixed $raw Product from the input.
	 *
	 * @return array|\WP_Error
	 */
	private function sanitize( $raw ) {
		if ( ! is_array( $raw ) ) {
			return new \WP_Error( 'wpshop_invalid', __( 'Invalid product.', 'wpshop' ) );
		}

		$ref   = isset( $raw['ref'] ) ? trim( (string) $raw['ref'] ) : '';
		$label = isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '';

		if ( ! preg_match( '/' . self::REF_PATTERN . '/', $ref ) ) {
			return new \WP_Error( 'wpshop_invalid_ref', __( 'Invalid ref: letters, digits, dot, dash and underscore only.', 'wpshop' ) );
		}
		if ( '' === $label ) {
			return new \WP_Error( 'wpshop_invalid_label', __( 'The label is required.', 'wpshop' ) );
		}
		if ( ! isset( $raw['price'] ) || ! is_numeric( $raw['price'] ) || (float) $raw['price'] < 0 ) {
			return new \WP_Error( 'wpshop_invalid_price', __( 'Invalid price.', 'wpshop' ) );
		}
		if ( ! isset( $raw['tva_tx'] ) || ! is_numeric( $raw['tva_tx'] ) || (float) $raw['tva_tx'] < 0 || (float) $raw['tva_tx'] > 100 ) {
			return new \WP_Error( 'wpshop_invalid_vat', __( 'Invalid VAT rate.', 'wpshop' ) );
		}

		$barcode = isset( $raw['barcode'] ) ? (string) $raw['barcode'] : '';
		if ( '' !== $barcode && ! preg_match( '/^[A-Za-z0-9\-]{1,64}$/', $barcode ) ) {
			return new \WP_Error( 'wpshop_invalid_barcode', __( 'Invalid barcode.', 'wpshop' ) );
		}

		$image_id = isset( $raw['image_id'] ) ? absint( $raw['image_id'] ) : 0;
		if ( $image_id ) {
			$image_file = Mcp_Catalog::get_product_image_file( $image_id );
			if ( is_wp_error( $image_file ) ) {
				return $image_file;
			}
		}

		return array(
			'kind'            => Mcp_Abilities::enum_input( $raw, 'kind', array( 'product', 'service' ), 'product' ),
			'ref'             => $ref,
			'label'           => $label,
			'description'     => isset( $raw['description'] ) ? wp_kses_post( $raw['description'] ) : '',
			'price'           => round( (float) $raw['price'], 6 ),
			'price_base_type' => Mcp_Abilities::enum_input( $raw, 'price_base_type', array( 'HT', 'TTC' ), 'HT' ),
			'tva_tx'          => round( (float) $raw['tva_tx'], 3 ),
			'barcode'         => $barcode,
			'category_ids'    => Mcp_Abilities::ids_input( $raw, 'category_ids' ),
			'shop_status'     => Mcp_Abilities::enum_input( $raw, 'shop_status', array( 'draft', 'publish' ), 'draft' ),
			'image_id'        => $image_id,
		);
	}
}
