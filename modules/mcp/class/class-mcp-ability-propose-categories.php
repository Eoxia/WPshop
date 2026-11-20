<?php
/**
 * Ability wpshop/propose-categories: product categories to create in Dolibarr, validated by a human.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Ability_Propose_Categories extends Mcp_Ability {

	const MAX = 20;

	public function get_name() {
		return 'wpshop/propose-categories';
	}

	public function get_label() {
		return __( 'Propose categories to create in Dolibarr', 'wpshop' );
	}

	public function get_summary() {
		return __( 'Proposes product categories to create in Dolibarr. Nothing is created until you validate them in EO Tools > MCP > À valider.', 'wpshop' );
	}

	public function get_description() {
		return 'Proposes product categories to create. They are created in Dolibarr (the only source of the catalogue), never on the shop directly. Nothing is created now: each proposal waits for a human in "EO Tools > MCP > À valider". On validation, the category is created in Dolibarr then synchronized to the shop. '
			. 'parent_id is the Dolibarr ID of an existing parent category (0 = top level). A parent that does not exist yet must be proposed and validated first. A category with the same label under the same parent is refused. At most ' . self::MAX . ' categories per call.';
	}

	public function get_risk() {
		return self::RISK_WRITE;
	}

	public function get_input_schema() {
		return array(
			'type'                 => 'object',
			'required'             => array( 'categories' ),
			'properties'           => array(
				'categories' => array(
					'type'     => 'array',
					'minItems' => 1,
					'maxItems' => self::MAX,
					'items'    => array(
						'type'                 => 'object',
						'required'             => array( 'label' ),
						'properties'           => array(
							'label'       => array(
								'type'      => 'string',
								'minLength' => 1,
								'maxLength' => 180,
							),
							'description' => array( 'type' => 'string' ),
							'parent_id'   => array(
								'type'    => 'integer',
								'minimum' => 0,
								'default' => 0,
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
		$categories = isset( $input['categories'] ) && is_array( $input['categories'] ) ? array_slice( $input['categories'], 0, self::MAX ) : array();

		$pending = array();
		foreach ( Mcp_Proposals::get_by_kind( Mcp_Proposals::KIND_CATEGORY ) as $proposal ) {
			$pending[ $proposal['data']['parent_id'] . '|' . mb_strtolower( $proposal['data']['label'] ) ] = true;
		}

		$proposed = array();
		$refused  = array();

		foreach ( $categories as $raw ) {
			$category = array(
				'label'       => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
				'description' => isset( $raw['description'] ) ? sanitize_textarea_field( $raw['description'] ) : '',
				'parent_id'   => isset( $raw['parent_id'] ) ? max( 0, (int) $raw['parent_id'] ) : 0,
			);

			if ( '' === $category['label'] ) {
				$refused[] = array(
					'label'  => '',
					'reason' => __( 'The label is required.', 'wpshop' ),
				);
				continue;
			}

			$key = $category['parent_id'] . '|' . mb_strtolower( $category['label'] );
			if ( isset( $pending[ $key ] ) ) {
				$refused[] = array(
					'label'  => $category['label'],
					'reason' => __( 'This category is already waiting for validation.', 'wpshop' ),
				);
				continue;
			}

			if ( $category['parent_id'] && is_wp_error( Mcp_Catalog::doli_get( 'categories/' . $category['parent_id'] ) ) ) {
				$refused[] = array(
					'label'  => $category['label'],
					/* translators: %d: Dolibarr category ID. */
					'reason' => sprintf( __( 'Unknown parent category #%d in Dolibarr.', 'wpshop' ), $category['parent_id'] ),
				);
				continue;
			}

			$duplicates = Mcp_Catalog::find_category_duplicates( $category );
			if ( $duplicates['blocking'] ) {
				$refused[] = array(
					'label'  => $category['label'],
					'reason' => implode( ' ', $duplicates['blocking'] ),
				);
				continue;
			}

			$id = Mcp_Proposals::add( Mcp_Proposals::KIND_CATEGORY, $category, $duplicates['warnings'] );
			if ( is_wp_error( $id ) ) {
				$refused[] = array(
					'label'  => $category['label'],
					'reason' => $id->get_error_message(),
				);
				continue;
			}

			$pending[ $key ] = true;
			$proposed[]      = array(
				'proposal_id' => $id,
				'label'       => $category['label'],
			);
		}

		return array(
			'proposed'   => $proposed,
			'refused'    => $refused,
			'validation' => admin_url( 'admin.php?page=eo-tools-mcp&tab=proposals' ),
			'note'       => __( 'Nothing is created yet: ask the user to validate the proposals in EO Tools > MCP > À valider.', 'wpshop' ),
		);
	}
}
