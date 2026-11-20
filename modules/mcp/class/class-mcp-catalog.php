<?php
/**
 * Shop catalogue operations used by the WPshop abilities.
 *
 * Dolibarr is the only source of the catalogue: products, services and product
 * categories are created and changed in Dolibarr, then synchronized to WordPress
 * with the existing WPshop sync (Doli_Sync). Nothing here creates catalogue data
 * on the WordPress side by itself.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Catalog {

	const OBJECT_PRODUCT  = 'product';
	const OBJECT_CATEGORY = 'category';

	const PRODUCT_POST_TYPE = 'wps-product';
	const CATEGORY_TAXONOMY = 'wps-product-cat';

	/**
	 * Dolibarr product types.
	 */
	const DOLI_TYPE_PRODUCT = 0;
	const DOLI_TYPE_SERVICE = 1;

	/**
	 * Largest product image sent to Dolibarr (base64 in a JSON body).
	 */
	const IMAGE_MAX_BYTES = 5242880;

	/* ---------------------------------------------------------------------
	 * Dolibarr requests
	 * ------------------------------------------------------------------ */

	/**
	 * GET on the Dolibarr API.
	 *
	 * @param string $endpoint Endpoint, e.g. "products/12".
	 *
	 * @return mixed|\WP_Error Decoded answer.
	 */
	public static function doli_get( $endpoint ) {
		delete_transient( 'wps_request_error' );
		$response = Request_Util::get( $endpoint );

		if ( false === $response ) {
			$message = get_transient( 'wps_request_error' );
			delete_transient( 'wps_request_error' );

			return new \WP_Error( 'wpshop_dolibarr_request', $message ? $message : __( 'Dolibarr did not answer.', 'wpshop' ), array( 'endpoint' => $endpoint ) );
		}

		return $response;
	}

	/**
	 * GET on the Dolibarr API, where "not found" is a normal answer (empty list).
	 *
	 * @param string $endpoint Endpoint.
	 *
	 * @return array|\WP_Error
	 */
	public static function doli_list( $endpoint ) {
		$response = self::doli_get( $endpoint );

		if ( is_wp_error( $response ) ) {
			// The Dolibarr API answers 404 to an empty list.
			$message = $response->get_error_message();
			if ( false !== strpos( $message, '404' ) || false !== stripos( $message, 'not found' ) || false !== stripos( $message, 'No product found' ) ) {
				return array();
			}

			return $response;
		}

		return is_array( $response ) ? $response : array();
	}

	/**
	 * POST / PUT on the Dolibarr API.
	 *
	 * @param string $endpoint Endpoint.
	 * @param array  $data     Body.
	 * @param string $method   POST or PUT.
	 *
	 * @return mixed|\WP_Error Decoded answer.
	 */
	public static function doli_send( $endpoint, $data, $method = 'POST' ) {
		$response = Request_Util::post( $endpoint, $data, $method );

		if ( null === $response || false === $response ) {
			return new \WP_Error( 'wpshop_dolibarr_request', __( 'Dolibarr did not answer.', 'wpshop' ), array( 'endpoint' => $endpoint ) );
		}

		if ( is_object( $response ) && isset( $response->error ) ) {
			$message = isset( $response->error->message ) ? $response->error->message : __( 'Dolibarr refused the request.', 'wpshop' );
			$code    = isset( $response->error->code ) ? (int) $response->error->code : 0;

			if ( 403 === $code ) {
				$message = Request_Util::format_missing_rights( $message );
			}

			return new \WP_Error( 'wpshop_dolibarr_refused', $message, array( 'endpoint' => $endpoint, 'code' => $code ) );
		}

		return $response;
	}

	/**
	 * Value of a Dolibarr extrafield on an object returned by the API.
	 *
	 * @param object $object Dolibarr object.
	 * @param string $key    Extrafield key, e.g. "options__wps_id".
	 *
	 * @return mixed|null
	 */
	public static function doli_extrafield( $object, $key ) {
		if ( empty( $object->array_options ) ) {
			return null;
		}
		if ( is_object( $object->array_options ) ) {
			return isset( $object->array_options->$key ) ? $object->array_options->$key : null;
		}

		return isset( $object->array_options[ $key ] ) ? $object->array_options[ $key ] : null;
	}

	/**
	 * Dolibarr product or service by its ref.
	 *
	 * Searches the product list rather than GET products/ref/{ref}: a ref containing a slash
	 * never reaches Dolibarr in a URL path (the web server answers 404).
	 *
	 * @param string $ref Ref.
	 *
	 * @return object|null|\WP_Error Product, null if none has this ref.
	 */
	public static function find_doli_product_by_ref( $ref ) {
		$value = self::sqlfilter_value( $ref );

		if ( null === $value ) {
			$product = self::doli_get( 'products/ref/' . rawurlencode( $ref ) );
			if ( is_wp_error( $product ) && false !== stripos( $product->get_error_message(), 'not found' ) ) {
				return null;
			}

			return $product;
		}

		$found = self::doli_list( 'products?' . http_build_query( array( 'sqlfilters' => "(t.ref:=:'" . $value . "')", 'limit' => 1 ) ) );
		if ( is_wp_error( $found ) ) {
			return $found;
		}

		return empty( $found ) ? null : self::doli_get( 'products/' . (int) $found[0]->id );
	}

	/**
	 * Escapes a value for a Dolibarr "sqlfilters" criterion, or returns null if it cannot be used safely.
	 *
	 * @param string $value Value.
	 *
	 * @return string|null
	 */
	private static function sqlfilter_value( $value ) {
		$value = trim( (string) $value );

		if ( '' === $value || preg_match( '/[\'"()\\\\%]/', $value ) ) {
			return null;
		}

		return $value;
	}

	/* ---------------------------------------------------------------------
	 * WordPress side
	 * ------------------------------------------------------------------ */

	/**
	 * WordPress ID of a synchronized Dolibarr object.
	 *
	 * @param string $object  OBJECT_* constant.
	 * @param int    $doli_id Dolibarr ID.
	 *
	 * @return int 0 if not on the shop.
	 */
	public static function get_wp_id( $object, $doli_id ) {
		if ( self::OBJECT_CATEGORY === $object ) {
			$terms = get_terms(
				array(
					'taxonomy'   => self::CATEGORY_TAXONOMY,
					'hide_empty' => false,
					'meta_key'   => '_external_id',
					'meta_value' => (int) $doli_id,
					'number'     => 1,
					'fields'     => 'ids',
				)
			);

			return ! is_wp_error( $terms ) && ! empty( $terms ) ? (int) $terms[0] : 0;
		}

		$posts = get_posts(
			array(
				'post_type'      => self::PRODUCT_POST_TYPE,
				'post_status'    => 'any',
				'meta_key'       => '_external_id',
				'meta_value'     => (int) $doli_id,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		return ! empty( $posts ) ? (int) $posts[0] : 0;
	}

	/**
	 * Dolibarr IDs of every synchronized object of the shop.
	 *
	 * @param string $object OBJECT_* constant.
	 *
	 * @return int[] WordPress IDs keyed by Dolibarr ID.
	 */
	public static function get_linked_map( $object ) {
		global $wpdb;

		if ( self::OBJECT_CATEGORY === $object ) {
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT tm.meta_value AS doli_id, tm.term_id AS wp_id FROM {$wpdb->termmeta} tm INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = tm.term_id WHERE tm.meta_key = '_external_id' AND tt.taxonomy = %s",
					self::CATEGORY_TAXONOMY
				)
			);
		} else {
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT pm.meta_value AS doli_id, pm.post_id AS wp_id FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = '_external_id' AND p.post_type = %s AND p.post_status <> 'trash'",
					self::PRODUCT_POST_TYPE
				)
			);
		}

		$map = array();
		foreach ( (array) $rows as $row ) {
			if ( (int) $row->doli_id > 0 ) {
				$map[ (int) $row->doli_id ] = (int) $row->wp_id;
			}
		}

		return $map;
	}

	/**
	 * Shop data of a product.
	 *
	 * @param \WP_Post $post Product post.
	 *
	 * @return array
	 */
	public static function describe_wp_product( $post ) {
		$type = (int) get_post_meta( $post->ID, '_fk_product_type', true );

		return array(
			'wp_id'       => (int) $post->ID,
			'doli_id'     => (int) get_post_meta( $post->ID, '_external_id', true ),
			'ref'         => (string) get_post_meta( $post->ID, '_ref', true ),
			'title'       => get_the_title( $post ),
			'kind'        => self::DOLI_TYPE_SERVICE === $type ? 'service' : 'product',
			'post_status' => $post->post_status,
			'price'       => (float) get_post_meta( $post->ID, '_price', true ),
			'price_ttc'   => (float) get_post_meta( $post->ID, '_price_ttc', true ),
			'categories'  => wp_get_object_terms( $post->ID, self::CATEGORY_TAXONOMY, array( 'fields' => 'names' ) ),
			'url'         => get_permalink( $post ),
		);
	}

	/**
	 * Shop data of a category.
	 *
	 * @param \WP_Term $term Category term.
	 *
	 * @return array
	 */
	public static function describe_wp_category( $term ) {
		return array(
			'wp_id'   => (int) $term->term_id,
			'doli_id' => (int) get_term_meta( $term->term_id, '_external_id', true ),
			'title'   => $term->name,
			'parent'  => (int) $term->parent,
			'count'   => (int) $term->count,
		);
	}

	/**
	 * Fields of a Dolibarr product that WPshop reads at sync (see Doli_Products::doli_to_wp()).
	 *
	 * @param object $doli_product Dolibarr product.
	 *
	 * @return array
	 */
	public static function describe_doli_product( $doli_product ) {
		$wps_status = self::doli_extrafield( $doli_product, 'options__wps_status' );

		return array(
			'doli_id'      => (int) $doli_product->id,
			'ref'          => (string) $doli_product->ref,
			'label'        => (string) $doli_product->label,
			'description'  => (string) $doli_product->description,
			'kind'         => self::DOLI_TYPE_SERVICE === (int) $doli_product->type ? 'service' : 'product',
			'price'        => (float) $doli_product->price,
			'price_ttc'    => (float) $doli_product->price_ttc,
			'tva_tx'       => (float) $doli_product->tva_tx,
			'barcode'      => (string) $doli_product->barcode,
			'stock'        => isset( $doli_product->stock_reel ) ? (float) $doli_product->stock_reel : null,
			'parent_id'    => isset( $doli_product->fk_product_parent ) ? (int) $doli_product->fk_product_parent : 0,
			'on_sale'      => ! empty( $doli_product->status ),
			'shop_status'  => ( empty( $wps_status ) || 1 == $wps_status || 'publish' === $wps_status ) ? 'publish' : 'draft', // phpcs:ignore Universal.Operators.StrictComparisons
			'shop_post_id' => (int) self::doli_extrafield( $doli_product, 'options__wps_id' ),
		);
	}

	/* ---------------------------------------------------------------------
	 * Sync status
	 * ------------------------------------------------------------------ */

	/**
	 * Sync status of a shop object, without writing anything (neither in WordPress nor in Dolibarr).
	 *
	 * @param string $object OBJECT_* constant.
	 * @param int    $wp_id  WordPress ID.
	 *
	 * @return array status (ok | different | categories_or_media | unreachable | linked_to_other_post | not_linked), fields, notes.
	 */
	public static function get_sync_state( $object, $wp_id ) {
		$type    = self::OBJECT_CATEGORY === $object ? self::CATEGORY_TAXONOMY : self::PRODUCT_POST_TYPE;
		$doli_id = self::OBJECT_CATEGORY === $object ? (int) get_term_meta( $wp_id, '_external_id', true ) : (int) get_post_meta( $wp_id, '_external_id', true );

		if ( ! $doli_id ) {
			return array(
				'status' => 'not_linked',
				'fields' => array(),
				'notes'  => array( __( 'Not linked to Dolibarr: this object was created on the WordPress side. Dolibarr is the only source of the catalogue.', 'wpshop' ) ),
			);
		}

		$check  = Doli_Sync::g()->check_status( $wp_id, $type, true );
		$debug  = isset( $check['debug'] ) ? $check['debug'] : array();
		$codes  = array(
			'0x0' => 'ok',
			'0x1' => 'unreachable',
			'0x2' => 'linked_to_other_post',
			'0x3' => 'different',
			'0x4' => 'categories_or_media',
		);
		$code   = isset( $check['status_code'] ) ? $check['status_code'] : '';
		$state  = array(
			'status' => isset( $codes[ $code ] ) ? $codes[ $code ] : 'unknown',
			'fields' => ! empty( $debug['field_diff'] ) ? array_keys( $debug['field_diff'] ) : array(),
			'notes'  => array(),
		);

		if ( isset( $debug['sha_match'] ) && ! $debug['sha_match'] && empty( $state['fields'] ) ) {
			$state['fields'][] = 'data (sync needed)';
		}
		if ( isset( $debug['categories_match'] ) && ! $debug['categories_match'] ) {
			$state['fields'][] = 'categories';
		}
		if ( isset( $debug['image'] ) && 'categories_or_media' === $state['status'] ) {
			$state['fields'][] = 'image';
		}
		if ( ! empty( $debug['dolibarr_link_missing'] ) ) {
			$state['notes'][] = __( 'The link back (_wps_id) is missing in Dolibarr: a sync repairs it.', 'wpshop' );
		}
		if ( 'unreachable' === $state['status'] ) {
			$state['notes'][] = __( 'Dolibarr object not found, or Dolibarr did not answer. The link is kept.', 'wpshop' );
		}
		if ( 'linked_to_other_post' === $state['status'] && ! empty( $debug['doli_wps_id'] ) ) {
			/* translators: %d: WordPress ID. */
			$state['notes'][] = sprintf( __( 'In Dolibarr, this object is linked to another shop object (#%d).', 'wpshop' ), (int) $debug['doli_wps_id'] );
		}
		$state['fields'] = array_values( array_unique( $state['fields'] ) );

		return $state;
	}

	/**
	 * Sync status of the shop objects, page by page.
	 *
	 * @param string $object           OBJECT_* constant.
	 * @param string $kind             For products: all | product | service.
	 * @param bool   $only_out_of_sync Leave out the objects in sync.
	 * @param int    $page             Page, from 1.
	 * @param int    $per_page         Objects checked per page.
	 *
	 * @return array
	 */
	public static function list_shop_status( $object, $kind, $only_out_of_sync, $page, $per_page ) {
		$items = array();

		if ( self::OBJECT_CATEGORY === $object ) {
			$total = (int) wp_count_terms( array( 'taxonomy' => self::CATEGORY_TAXONOMY, 'hide_empty' => false ) );
			$terms = get_terms(
				array(
					'taxonomy'   => self::CATEGORY_TAXONOMY,
					'hide_empty' => false,
					'orderby'    => 'term_id',
					'number'     => $per_page,
					'offset'     => ( $page - 1 ) * $per_page,
				)
			);
			foreach ( is_wp_error( $terms ) ? array() : $terms as $term ) {
				$items[] = array_merge( self::describe_wp_category( $term ), self::get_sync_state( $object, $term->term_id ) );
			}
		} else {
			$args = array(
				'post_type'      => self::PRODUCT_POST_TYPE,
				'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'posts_per_page' => $per_page,
				'paged'          => $page,
			);
			if ( 'all' !== $kind ) {
				$args['meta_query'] = array(
					array(
						'key'   => '_fk_product_type',
						'value' => 'service' === $kind ? self::DOLI_TYPE_SERVICE : self::DOLI_TYPE_PRODUCT,
					),
				);
			}
			$query = new \WP_Query( $args );
			$total = (int) $query->found_posts;
			foreach ( $query->posts as $post ) {
				$items[] = array_merge( self::describe_wp_product( $post ), self::get_sync_state( $object, $post->ID ) );
			}
		}

		$checked = count( $items );
		if ( $only_out_of_sync ) {
			$items = array_values(
				array_filter(
					$items,
					function ( $item ) {
						return 'ok' !== $item['status'];
					}
				)
			);
		}

		return array(
			'scope'    => 'shop',
			'page'     => $page,
			'per_page' => $per_page,
			'checked'  => $checked,
			'total'    => $total,
			'has_more' => $page * $per_page < $total,
			'items'    => $items,
		);
	}

	/**
	 * Dolibarr objects that are not on the shop, page by page (Dolibarr pages).
	 *
	 * @param string $object   OBJECT_* constant.
	 * @param string $kind     For products: all | product | service.
	 * @param int    $page     Page, from 1.
	 * @param int    $per_page Dolibarr objects read per page.
	 *
	 * @return array|\WP_Error
	 */
	public static function list_missing_on_shop( $object, $kind, $page, $per_page ) {
		$query = array(
			'sortfield' => 't.rowid',
			'sortorder' => 'ASC',
			'limit'     => $per_page,
			'page'      => $page - 1,
		);

		if ( self::OBJECT_CATEGORY === $object ) {
			$query['type'] = 'product';
			$endpoint      = 'categories';
		} else {
			if ( 'product' === $kind ) {
				$query['mode'] = 1;
			} elseif ( 'service' === $kind ) {
				$query['mode'] = 2;
			}
			$endpoint = 'products';
		}

		$entries = self::doli_list( $endpoint . '?' . http_build_query( $query ) );
		if ( is_wp_error( $entries ) ) {
			return $entries;
		}

		$linked = self::get_linked_map( $object );
		$items  = array();
		foreach ( $entries as $entry ) {
			if ( isset( $linked[ (int) $entry->id ] ) ) {
				continue;
			}
			if ( self::OBJECT_CATEGORY === $object ) {
				$items[] = array(
					'doli_id'   => (int) $entry->id,
					'title'     => (string) $entry->label,
					'parent_id' => isset( $entry->fk_parent ) ? (int) $entry->fk_parent : 0,
					'status'    => 'not_on_shop',
				);
			} else {
				$items[] = array_merge( self::describe_doli_product( $entry ), array( 'status' => 'not_on_shop' ) );
			}
		}

		return array(
			'scope'    => 'dolibarr_only',
			'page'     => $page,
			'per_page' => $per_page,
			'checked'  => count( $entries ),
			'has_more' => count( $entries ) >= $per_page,
			'items'    => $items,
		);
	}

	/* ---------------------------------------------------------------------
	 * Sync
	 * ------------------------------------------------------------------ */

	/**
	 * Synchronizes one Dolibarr object to the shop (creates it on the shop if needed), as the WPshop sync screen does.
	 *
	 * @param string $object  OBJECT_* constant.
	 * @param int    $doli_id Dolibarr ID.
	 *
	 * @return array|\WP_Error
	 */
	public static function sync( $object, $doli_id ) {
		$doli_id  = (int) $doli_id;
		$endpoint = self::OBJECT_CATEGORY === $object ? 'categories/' : 'products/';
		$exists   = self::doli_get( $endpoint . $doli_id );
		if ( is_wp_error( $exists ) ) {
			return $exists;
		}

		$wp_id  = self::get_wp_id( $object, $doli_id );
		$type   = self::OBJECT_CATEGORY === $object ? self::CATEGORY_TAXONOMY : self::PRODUCT_POST_TYPE;
		$result = Doli_Sync::g()->sync( $wp_id, $doli_id, $type );

		if ( ! empty( $result['wp_error'] ) && $result['wp_error']->has_errors() ) {
			return $result['wp_error'];
		}
		if ( empty( $result['wp_object'] ) || empty( $result['wp_object']->data['id'] ) ) {
			return new \WP_Error( 'wpshop_sync_failed', __( 'The sync did not create nor update anything on the shop.', 'wpshop' ) );
		}

		$new_wp_id = (int) $result['wp_object']->data['id'];

		// The category sync does not write the link back in Dolibarr: write it, as for products.
		// Through the core API: the doliwpshop route "associatecategory" calls Categorie::update() with
		// the wrong arguments and always fails.
		if ( self::OBJECT_CATEGORY === $object && (int) self::doli_extrafield( $exists, 'options__wps_id' ) !== $new_wp_id ) {
			self::doli_send( 'categories/' . $doli_id, array( 'array_options' => array( 'options__wps_id' => $new_wp_id ) ), 'PUT' );
		}

		return array(
			'doli_id' => $doli_id,
			'wp_id'   => $new_wp_id,
			'created' => 0 === $wp_id,
			'state'   => self::get_sync_state( $object, $new_wp_id ),
		);
	}

	/* ---------------------------------------------------------------------
	 * Duplicates and creation in Dolibarr
	 * ------------------------------------------------------------------ */

	/**
	 * Possible duplicates of a product to create.
	 *
	 * @param array $product Product fields (ref, label, barcode).
	 *
	 * @return array blocking (string[]: same ref in Dolibarr), warnings (string[]).
	 */
	public static function find_product_duplicates( $product ) {
		$blocking = array();
		$warnings = array();

		$ref = self::sqlfilter_value( $product['ref'] );
		if ( null !== $ref ) {
			$found = self::doli_list( 'products?' . http_build_query( array( 'sqlfilters' => "(t.ref:=:'" . $ref . "')", 'limit' => 1 ) ) );
			if ( ! is_wp_error( $found ) && ! empty( $found ) ) {
				/* translators: 1: product ref, 2: Dolibarr ID. */
				$blocking[] = sprintf( __( 'The ref %1$s already exists in Dolibarr (#%2$d).', 'wpshop' ), $product['ref'], (int) $found[0]->id );
			}
		}

		$barcode = ! empty( $product['barcode'] ) ? self::sqlfilter_value( $product['barcode'] ) : null;
		if ( null !== $barcode ) {
			$found = self::doli_list( 'products?' . http_build_query( array( 'sqlfilters' => "(t.barcode:=:'" . $barcode . "')", 'limit' => 1 ) ) );
			if ( ! is_wp_error( $found ) && ! empty( $found ) ) {
				/* translators: 1: barcode, 2: product ref. */
				$warnings[] = sprintf( __( 'Barcode %1$s already used by %2$s in Dolibarr.', 'wpshop' ), $product['barcode'], $found[0]->ref );
			}
		}

		$label = self::sqlfilter_value( $product['label'] );
		if ( null !== $label ) {
			$found = self::doli_list( 'products?' . http_build_query( array( 'sqlfilters' => "(t.label:=:'" . $label . "')", 'limit' => 3 ) ) );
			if ( ! is_wp_error( $found ) && ! empty( $found ) ) {
				/* translators: 1: product label, 2: product refs. */
				$warnings[] = sprintf( __( 'Same label as %2$s in Dolibarr.', 'wpshop' ), $product['label'], implode( ', ', wp_list_pluck( $found, 'ref' ) ) );
			}
		}

		$on_shop = get_posts(
			array(
				'post_type'      => self::PRODUCT_POST_TYPE,
				'post_status'    => 'any',
				'title'          => $product['label'],
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $on_shop ) {
			/* translators: %d: WordPress ID. */
			$warnings[] = sprintf( __( 'A shop product already has this title (#%d).', 'wpshop' ), (int) $on_shop[0] );
		}

		return array(
			'blocking' => $blocking,
			'warnings' => $warnings,
		);
	}

	/**
	 * Possible duplicates of a category to create.
	 *
	 * @param array $category Category fields (label, parent_id).
	 *
	 * @return array blocking (string[]), warnings (string[]).
	 */
	public static function find_category_duplicates( $category ) {
		$blocking = array();
		$label    = self::sqlfilter_value( $category['label'] );

		if ( null !== $label ) {
			$found = self::doli_list( 'categories?' . http_build_query( array( 'type' => 'product', 'sqlfilters' => "(t.label:=:'" . $label . "')", 'limit' => 10 ) ) );
			if ( ! is_wp_error( $found ) ) {
				foreach ( $found as $doli_category ) {
					if ( (int) $doli_category->fk_parent === (int) $category['parent_id'] ) {
						/* translators: 1: category label, 2: Dolibarr ID. */
						$blocking[] = sprintf( __( 'The category %1$s already exists at this place in Dolibarr (#%2$d).', 'wpshop' ), $category['label'], (int) $doli_category->id );
					}
				}
			}
		}

		return array(
			'blocking' => $blocking,
			'warnings' => array(),
		);
	}

	/**
	 * Creates a product or service in Dolibarr, puts it in its categories, then synchronizes it to the shop.
	 *
	 * Resumable: when a previous attempt already created it in Dolibarr ($doli_id), it is not created again.
	 *
	 * @param array $product  Validated product fields.
	 * @param int   $doli_id  Dolibarr ID if already created by a previous attempt.
	 *
	 * @return array|\WP_Error doli_id, wp_id. On error after the creation, the error data holds doli_id.
	 */
	public static function create_product( $product, $doli_id = 0 ) {
		if ( ! $doli_id ) {
			$duplicates = self::find_product_duplicates( $product );
			if ( $duplicates['blocking'] ) {
				return new \WP_Error( 'wpshop_duplicate', implode( ' ', $duplicates['blocking'] ) );
			}

			$body = array(
				'ref'             => $product['ref'],
				'label'           => $product['label'],
				'description'     => $product['description'],
				'type'            => 'service' === $product['kind'] ? self::DOLI_TYPE_SERVICE : self::DOLI_TYPE_PRODUCT,
				'price_base_type' => $product['price_base_type'],
				'tva_tx'          => $product['tva_tx'],
				'status'          => 1,
				'status_buy'      => 'service' === $product['kind'] ? 0 : 1,
				'array_options'   => array(
					'options__wps_status' => $product['shop_status'],
				),
			);
			if ( 'TTC' === $product['price_base_type'] ) {
				$body['price_ttc'] = $product['price'];
			} else {
				$body['price'] = $product['price'];
			}
			if ( '' !== $product['barcode'] ) {
				$body['barcode'] = $product['barcode'];
			}

			$created = self::doli_send( 'products', $body );
			if ( is_wp_error( $created ) ) {
				return $created;
			}
			$doli_id = (int) $created;
			if ( $doli_id <= 0 ) {
				return new \WP_Error( 'wpshop_dolibarr_refused', __( 'Dolibarr did not return the ID of the new product.', 'wpshop' ) );
			}
		}

		foreach ( $product['category_ids'] as $category_id ) {
			$linked = self::doli_send( 'categories/' . (int) $category_id . '/objects/product/' . $doli_id, array() );
			if ( is_wp_error( $linked ) && false === stripos( $linked->get_error_message(), 'already' ) ) {
				$linked->add_data( array( 'doli_id' => $doli_id ) );

				return $linked;
			}
		}

		// The image goes into the documents of the Dolibarr product; the sync then makes it the shop image.
		if ( ! empty( $product['image_id'] ) ) {
			$uploaded = self::upload_product_image( $product['ref'], $product['image_id'] );
			if ( is_wp_error( $uploaded ) ) {
				$uploaded->add_data( array( 'doli_id' => $doli_id ) );

				return $uploaded;
			}
		}

		$synced = self::sync( self::OBJECT_PRODUCT, $doli_id );
		if ( is_wp_error( $synced ) ) {
			$synced->add_data( array( 'doli_id' => $doli_id ) );

			return $synced;
		}

		return array(
			'doli_id' => $doli_id,
			'wp_id'   => $synced['wp_id'],
		);
	}

	/**
	 * File of a media library image that can become a product image.
	 *
	 * The shop sync only takes jpg, png, gif and webp images from Dolibarr.
	 *
	 * @param int $attachment_id Media library ID.
	 *
	 * @return string|\WP_Error Absolute path.
	 */
	public static function get_product_image_file( $attachment_id ) {
		$attachment_id = (int) $attachment_id;
		$file          = $attachment_id && wp_attachment_is_image( $attachment_id ) ? get_attached_file( $attachment_id ) : '';

		if ( ! $file || ! file_exists( $file ) ) {
			/* translators: %d: media ID. */
			return new \WP_Error( 'wpshop_invalid_image', sprintf( __( 'Image #%d not found in the media library.', 'wpshop' ), $attachment_id ) );
		}

		if ( ! preg_match( '/\.(jpe?g|png|gif|webp)$/i', $file ) ) {
			return new \WP_Error( 'wpshop_invalid_image', __( 'The product image must be a jpg, png, gif or webp file.', 'wpshop' ) );
		}

		// A very large original goes through the "large" size, lighter to send to Dolibarr.
		if ( filesize( $file ) > self::IMAGE_MAX_BYTES ) {
			$large = image_get_intermediate_size( $attachment_id, 'large' );
			if ( $large && ! empty( $large['path'] ) ) {
				$uploads = wp_get_upload_dir();
				$path    = trailingslashit( $uploads['basedir'] ) . $large['path'];
				if ( file_exists( $path ) && filesize( $path ) <= self::IMAGE_MAX_BYTES ) {
					return $path;
				}
			}

			return new \WP_Error( 'wpshop_invalid_image', __( 'The product image is too large (5 MB at most).', 'wpshop' ) );
		}

		return $file;
	}

	/**
	 * Sends a media library image into the documents of a Dolibarr product.
	 *
	 * Sending it again replaces the file, so a retried validation does not duplicate it.
	 *
	 * @param string $ref           Dolibarr product ref.
	 * @param int    $attachment_id Media library ID.
	 *
	 * @return true|\WP_Error
	 */
	public static function upload_product_image( $ref, $attachment_id ) {
		$file = self::get_product_image_file( $attachment_id );
		if ( is_wp_error( $file ) ) {
			return $file;
		}

		$timeout = function () {
			return 60;
		};
		add_filter( 'http_request_timeout', $timeout );
		$result = self::doli_send(
			'documents/upload',
			array(
				'filename'          => sanitize_file_name( wp_basename( $file ) ),
				'modulepart'        => 'product',
				'ref'               => $ref,
				'filecontent'       => base64_encode( file_get_contents( $file ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				'fileencoding'      => 'base64',
				'overwriteifexists' => 1,
			)
		);
		remove_filter( 'http_request_timeout', $timeout );

		if ( is_wp_error( $result ) ) {
			/* translators: %s: error message. */
			return new \WP_Error( $result->get_error_code(), sprintf( __( 'Image not sent to Dolibarr: %s', 'wpshop' ), $result->get_error_message() ), $result->get_error_data() );
		}

		return true;
	}

	/**
	 * Creates a product category in Dolibarr, then synchronizes it to the shop.
	 *
	 * @param array $category Validated category fields.
	 * @param int   $doli_id  Dolibarr ID if already created by a previous attempt.
	 *
	 * @return array|\WP_Error
	 */
	public static function create_category( $category, $doli_id = 0 ) {
		if ( ! $doli_id ) {
			$duplicates = self::find_category_duplicates( $category );
			if ( $duplicates['blocking'] ) {
				return new \WP_Error( 'wpshop_duplicate', implode( ' ', $duplicates['blocking'] ) );
			}

			$created = self::doli_send(
				'categories',
				array(
					'label'       => $category['label'],
					'description' => $category['description'],
					'type'        => 'product',
					'fk_parent'   => (int) $category['parent_id'],
				)
			);
			if ( is_wp_error( $created ) ) {
				return $created;
			}
			$doli_id = (int) $created;
			if ( $doli_id <= 0 ) {
				return new \WP_Error( 'wpshop_dolibarr_refused', __( 'Dolibarr did not return the ID of the new category.', 'wpshop' ) );
			}
		}

		$synced = self::sync( self::OBJECT_CATEGORY, $doli_id );
		if ( is_wp_error( $synced ) ) {
			$synced->add_data( array( 'doli_id' => $doli_id ) );

			return $synced;
		}

		return array(
			'doli_id' => $doli_id,
			'wp_id'   => $synced['wp_id'],
		);
	}

	/**
	 * Publishes or unpublishes a product on the shop: changes "_wps_status" in Dolibarr, then synchronizes.
	 *
	 * The shop post is never published directly: the next sync would overwrite it.
	 *
	 * @param int    $doli_id Dolibarr product ID.
	 * @param string $status  publish | draft.
	 *
	 * @return array|\WP_Error
	 */
	public static function set_shop_status( $doli_id, $status ) {
		$updated = self::doli_send( 'products/' . (int) $doli_id, array( 'array_options' => array( 'options__wps_status' => $status ) ), 'PUT' );
		if ( is_wp_error( $updated ) ) {
			return $updated;
		}

		$synced = self::sync( self::OBJECT_PRODUCT, $doli_id );
		if ( is_wp_error( $synced ) ) {
			return $synced;
		}

		$post = get_post( $synced['wp_id'] );

		return array(
			'doli_id'     => (int) $doli_id,
			'wp_id'       => $synced['wp_id'],
			'post_status' => $post ? $post->post_status : '',
			'url'         => $post ? get_permalink( $post ) : '',
		);
	}
}
