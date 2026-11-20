<?php
/**
 * Creations in Dolibarr proposed by the AI, waiting for a human.
 *
 * Creating products in an ERP is not trivial: the AI never creates anything in
 * Dolibarr by itself. Its proposals are stored here and listed in the "À valider"
 * tab of EO Tools (filter `eo_tools_mcp_proposal_types`). Only when a human
 * validates one is the object created in Dolibarr, then synchronized to the shop.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Proposals {

	const OPTION = 'wps_mcp_proposals';

	const KIND_PRODUCT  = 'product';
	const KIND_CATEGORY = 'category';

	/**
	 * Proposal types declared to EO Tools, keyed by kind.
	 */
	const EO_TOOLS_TYPES = array(
		self::KIND_PRODUCT  => 'wpshop-dolibarr-product',
		self::KIND_CATEGORY => 'wpshop-dolibarr-category',
	);

	/**
	 * Pending proposals at most, to keep the option small.
	 */
	const MAX = 200;

	public static function init() {
		add_filter( 'eo_tools_mcp_proposal_types', array( __CLASS__, 'add_eo_tools_types' ) );
	}

	/**
	 * @return array[] Keyed by proposal ID.
	 */
	public static function get_all() {
		$proposals = get_option( self::OPTION, array() );

		return is_array( $proposals ) ? $proposals : array();
	}

	/**
	 * @param string $kind KIND_* constant.
	 *
	 * @return array[]
	 */
	public static function get_by_kind( $kind ) {
		return array_filter(
			self::get_all(),
			function ( $proposal ) use ( $kind ) {
				return isset( $proposal['kind'] ) && $kind === $proposal['kind'];
			}
		);
	}

	/**
	 * @param string $id Proposal ID.
	 *
	 * @return array|null
	 */
	public static function get( $id ) {
		$proposals = self::get_all();

		return isset( $proposals[ $id ] ) ? $proposals[ $id ] : null;
	}

	/**
	 * Stores a proposal.
	 *
	 * @param string   $kind     KIND_* constant.
	 * @param array    $data     Validated fields.
	 * @param string[] $warnings Possible duplicates, shown to the human.
	 *
	 * @return string|\WP_Error Proposal ID.
	 */
	public static function add( $kind, $data, $warnings ) {
		$proposals = self::get_all();
		if ( count( $proposals ) >= self::MAX ) {
			/* translators: %d: maximum number of proposals. */
			return new \WP_Error( 'wpshop_too_many_proposals', sprintf( __( '%d proposals are already waiting for validation. Ask the user to validate or reject them first.', 'wpshop' ), self::MAX ) );
		}

		$id               = strtolower( wp_generate_password( 10, false ) );
		$proposals[ $id ] = array(
			'id'       => $id,
			'kind'     => $kind,
			'data'     => $data,
			'warnings' => array_values( $warnings ),
			'created'  => time(),
			'user'     => get_current_user_id(),
			'doli_id'  => 0,
		);
		update_option( self::OPTION, $proposals, false );

		return $id;
	}

	/**
	 * @param string $id Proposal ID.
	 */
	public static function remove( $id ) {
		$proposals = self::get_all();
		unset( $proposals[ $id ] );
		update_option( self::OPTION, $proposals, false );
	}

	/**
	 * Remembers that a proposal was created in Dolibarr, so that a retry does not create it twice.
	 *
	 * @param string $id      Proposal ID.
	 * @param int    $doli_id Dolibarr ID.
	 */
	private static function set_doli_id( $id, $doli_id ) {
		$proposals = self::get_all();
		if ( isset( $proposals[ $id ] ) ) {
			$proposals[ $id ]['doli_id'] = (int) $doli_id;
			update_option( self::OPTION, $proposals, false );
		}
	}

	/**
	 * Creates the object of a proposal in Dolibarr and synchronizes it (human validation).
	 *
	 * @param string $id Proposal ID.
	 *
	 * @return true|\WP_Error
	 */
	public static function apply( $id ) {
		$proposal = self::get( $id );
		if ( ! $proposal ) {
			return new \WP_Error( 'wpshop_proposal_missing', __( 'This proposal no longer exists.', 'wpshop' ) );
		}

		if ( ! Settings::g()->dolibarr_is_active() ) {
			return new \WP_Error( 'wpshop_dolibarr_not_connected', __( 'WPshop is not connected to Dolibarr.', 'wpshop' ) );
		}

		if ( self::KIND_CATEGORY === $proposal['kind'] ) {
			$result = Mcp_Catalog::create_category( $proposal['data'], $proposal['doli_id'] );
		} else {
			$result = Mcp_Catalog::create_product( $proposal['data'], $proposal['doli_id'] );
		}

		if ( is_wp_error( $result ) ) {
			$error_data = $result->get_error_data();
			if ( is_array( $error_data ) && ! empty( $error_data['doli_id'] ) ) {
				self::set_doli_id( $id, $error_data['doli_id'] );

				/* translators: 1: error message, 2: Dolibarr ID. */
				return new \WP_Error( $result->get_error_code(), sprintf( __( '%1$s Already created in Dolibarr (#%2$d): validating again only finishes the sync.', 'wpshop' ), $result->get_error_message(), (int) $error_data['doli_id'] ) );
			}

			return $result;
		}

		self::remove( $id );

		return true;
	}

	/**
	 * Rejects a proposal: nothing is created.
	 *
	 * @param string $id Proposal ID.
	 *
	 * @return true
	 */
	public static function reject( $id ) {
		self::remove( $id );

		return true;
	}

	/**
	 * Declares the proposal types to EO Tools ("À valider" tab).
	 *
	 * @param array[] $types Proposal types.
	 *
	 * @return array[]
	 */
	public static function add_eo_tools_types( $types ) {
		$types[ self::EO_TOOLS_TYPES[ self::KIND_PRODUCT ] ] = array(
			'label'       => __( 'Products and services to create in Dolibarr', 'wpshop' ),
			'description' => __( 'Proposed by the AI. Validating creates them in Dolibarr, puts them in their categories, then synchronizes them to the shop with their publication status. Rejecting creates nothing.', 'wpshop' ),
			'provider'    => 'WPshop',
			'capability'  => 'manage_options',
			'items'       => function () {
				return self::get_eo_tools_items( self::KIND_PRODUCT );
			},
			'apply'       => array( __CLASS__, 'apply' ),
			'reject'      => array( __CLASS__, 'reject' ),
		);

		$types[ self::EO_TOOLS_TYPES[ self::KIND_CATEGORY ] ] = array(
			'label'       => __( 'Product categories to create in Dolibarr', 'wpshop' ),
			'description' => __( 'Proposed by the AI. Validating creates them in Dolibarr, then synchronizes them to the shop. Rejecting creates nothing.', 'wpshop' ),
			'provider'    => 'WPshop',
			'capability'  => 'manage_options',
			'items'       => function () {
				return self::get_eo_tools_items( self::KIND_CATEGORY );
			},
			'apply'       => array( __CLASS__, 'apply' ),
			'reject'      => array( __CLASS__, 'reject' ),
		);

		return $types;
	}

	/**
	 * Pending proposals of a kind, in the format of EO Tools.
	 *
	 * @param string $kind KIND_* constant.
	 *
	 * @return array[]
	 */
	public static function get_eo_tools_items( $kind ) {
		$items = array();
		foreach ( self::get_by_kind( $kind ) as $proposal ) {
			$data = $proposal['data'];
			$item = array(
				'id'      => $proposal['id'],
				'title'   => self::KIND_CATEGORY === $kind ? $data['label'] : $data['ref'] . ' – ' . $data['label'],
				'details' => self::KIND_CATEGORY === $kind ? self::category_details( $data ) : self::product_details( $data ),
				'created' => (int) $proposal['created'],
				'warning' => implode( ' ', $proposal['warnings'] ),
			);
			if ( ! empty( $proposal['doli_id'] ) ) {
				/* translators: %d: Dolibarr ID. */
				$item['warning'] = trim( sprintf( __( 'Already created in Dolibarr (#%d), sync to finish.', 'wpshop' ), (int) $proposal['doli_id'] ) . ' ' . $item['warning'] );
			}
			$items[] = $item;
		}

		return $items;
	}

	/**
	 * Fields of a product proposal, as HTML.
	 *
	 * @param array $data Product fields.
	 *
	 * @return string
	 */
	private static function product_details( $data ) {
		$rows = array(
			__( 'Type', 'wpshop' )           => 'service' === $data['kind'] ? __( 'Service', 'wpshop' ) : __( 'Product', 'wpshop' ),
			/* translators: 1: price, 2: HT or TTC. */
			__( 'Price', 'wpshop' )          => sprintf( __( '%1$s € %2$s', 'wpshop' ), number_format_i18n( $data['price'], 2 ), $data['price_base_type'] ),
			__( 'VAT', 'wpshop' )            => number_format_i18n( $data['tva_tx'], 2 ) . ' %',
			__( 'Barcode', 'wpshop' )        => $data['barcode'],
			__( 'Categories', 'wpshop' )     => self::category_names( $data['category_ids'] ),
			__( 'On the shop', 'wpshop' )    => 'publish' === $data['shop_status'] ? __( 'Published', 'wpshop' ) : __( 'Draft', 'wpshop' ),
			__( 'Description', 'wpshop' )    => wp_trim_words( wp_strip_all_tags( $data['description'] ), 30 ),
		);

		$image = '';
		if ( ! empty( $data['image_id'] ) ) {
			$image = wp_get_attachment_image( (int) $data['image_id'], 'thumbnail', false, array( 'style' => 'max-width:120px;height:auto;' ) );
			if ( ! $image ) {
				/* translators: %d: media ID. */
				$rows[ __( 'Image', 'wpshop' ) ] = sprintf( __( 'Image #%d not found in the media library.', 'wpshop' ), (int) $data['image_id'] );
			}
		}

		return ( $image ? '<p>' . $image . '</p>' : '' ) . self::rows_html( $rows );
	}

	/**
	 * Fields of a category proposal, as HTML.
	 *
	 * @param array $data Category fields.
	 *
	 * @return string
	 */
	private static function category_details( $data ) {
		return self::rows_html(
			array(
				__( 'Parent category', 'wpshop' ) => $data['parent_id'] ? self::category_names( array( $data['parent_id'] ) ) : __( 'None (top level)', 'wpshop' ),
				__( 'Description', 'wpshop' )     => wp_trim_words( wp_strip_all_tags( $data['description'] ), 30 ),
			)
		);
	}

	/**
	 * Names of Dolibarr categories, from the shop when they are synchronized.
	 *
	 * @param int[] $doli_ids Dolibarr category IDs.
	 *
	 * @return string
	 */
	private static function category_names( $doli_ids ) {
		$names = array();
		foreach ( (array) $doli_ids as $doli_id ) {
			$wp_id   = Mcp_Catalog::get_wp_id( Mcp_Catalog::OBJECT_CATEGORY, $doli_id );
			$term    = $wp_id ? get_term( $wp_id, Mcp_Catalog::CATEGORY_TAXONOMY ) : null;
			$names[] = $term && ! is_wp_error( $term ) ? $term->name . ' (#' . (int) $doli_id . ')' : '#' . (int) $doli_id;
		}

		return implode( ', ', $names );
	}

	/**
	 * @param string[] $rows Values keyed by label. Empty values are left out.
	 *
	 * @return string
	 */
	private static function rows_html( $rows ) {
		$html = '<ul class="eo-mcp-changes">';
		foreach ( $rows as $label => $value ) {
			if ( '' === (string) $value ) {
				continue;
			}
			$html .= '<li><strong>' . esc_html( $label ) . '</strong> : ' . esc_html( $value ) . '</li>';
		}

		return $html . '</ul>';
	}
}

Mcp_Proposals::init();
