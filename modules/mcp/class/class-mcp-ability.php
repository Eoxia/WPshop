<?php
/**
 * Base class of the WPshop abilities.
 *
 * One ability = one class. Mcp_Abilities registers them with the WordPress
 * Abilities API (6.9+). They declare `meta.eo_tools`, so that EO Tools relays them
 * to the AI connected to the site (toggles, risk levels, EO Tools MCP server).
 * They are not published on the default MCP server (`meta.mcp.public` false).
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

abstract class Mcp_Ability {

	const RISK_READ      = 'read';
	const RISK_WRITE     = 'write';
	const RISK_SENSITIVE = 'sensitive';

	/**
	 * Full name, e.g. "wpshop/list-sync-status".
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * @return string
	 */
	abstract public function get_label();

	/**
	 * Description read by the AI to decide when and how to use the ability.
	 *
	 * @return string
	 */
	abstract public function get_description();

	/**
	 * One or two lines for the administrator (EO Tools abilities tab).
	 *
	 * @return string
	 */
	abstract public function get_summary();

	/**
	 * @return string One of the RISK_* constants.
	 */
	abstract public function get_risk();

	/**
	 * @return array JSON Schema of the input. Static: MCP clients cache the tool list.
	 */
	abstract public function get_input_schema();

	/**
	 * @param array $input Validated input.
	 *
	 * @return array|\WP_Error
	 */
	abstract public function execute( $input );

	/**
	 * Shop administrators only.
	 *
	 * @param array $input Input.
	 *
	 * @return bool
	 */
	public function has_permission( $input ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Whether the ability needs the Dolibarr connection.
	 *
	 * @return bool
	 */
	public function needs_dolibarr() {
		return true;
	}

	/**
	 * Arguments for wp_register_ability().
	 *
	 * @return array
	 */
	public function get_registration_args() {
		$readonly = self::RISK_READ === $this->get_risk();

		return array(
			'label'               => $this->get_label(),
			'description'         => $this->get_description(),
			'category'            => Mcp_Abilities::CATEGORY,
			'input_schema'        => $this->get_input_schema(),
			'output_schema'       => array( 'type' => 'object' ),
			'execute_callback'    => function ( $input = array() ) {
				$input = is_array( $input ) ? $input : array();

				if ( $this->needs_dolibarr() && ! Settings::g()->dolibarr_is_active() ) {
					return new \WP_Error( 'wpshop_dolibarr_not_connected', __( 'WPshop is not connected to Dolibarr. An administrator must set the Dolibarr URL and API key in WPshop > Settings > ERP.', 'wpshop' ) );
				}

				return $this->execute( $input );
			},
			'permission_callback' => function ( $input = array() ) {
				return $this->has_permission( is_array( $input ) ? $input : array() );
			},
			'meta'                => array(
				'annotations'  => array(
					'readonly'    => $readonly,
					'destructive' => self::RISK_SENSITIVE === $this->get_risk(),
					'idempotent'  => $readonly,
				),
				'show_in_rest' => false,
				'mcp'          => array(
					'public' => false,
					'type'   => 'tool',
				),
				'eo_tools'     => array(
					'risk'     => $this->get_risk(),
					'summary'  => $this->get_summary(),
					'provider' => 'WPshop',
				),
			),
		);
	}
}
