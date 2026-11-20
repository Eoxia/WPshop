<?php
/**
 * Registers the WPshop abilities with the WordPress Abilities API (6.9+).
 *
 * Nothing happens on older WordPress versions: the hooks below never fire.
 *
 * @package WPshop
 *
 * @since 2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

class Mcp_Abilities {

	const CATEGORY = 'wpshop';

	public static function init() {
		add_action( 'wp_abilities_api_categories_init', array( __CLASS__, 'register_category' ) );
		add_action( 'wp_abilities_api_init', array( __CLASS__, 'register_abilities' ) );
	}

	/**
	 * @return Mcp_Ability[]
	 */
	public static function get_abilities() {
		$classes = array(
			Mcp_Ability_List_Sync_Status::class,
			Mcp_Ability_Get_Product::class,
			Mcp_Ability_Sync::class,
			Mcp_Ability_Propose_Products::class,
			Mcp_Ability_Propose_Categories::class,
			Mcp_Ability_Set_Publication_Status::class,
			Mcp_Ability_Add_Product_Image::class,
		);

		$abilities = array();
		foreach ( $classes as $class ) {
			$abilities[] = new $class();
		}

		return $abilities;
	}

	public static function register_category() {
		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			self::CATEGORY,
			array(
				'label'       => __( 'Shop (WPshop)', 'wpshop' ),
				'description' => __( 'Shop catalogue: sync with Dolibarr, products and categories to create in Dolibarr, publication on the shop.', 'wpshop' ),
			)
		);
	}

	public static function register_abilities() {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		foreach ( self::get_abilities() as $ability ) {
			wp_register_ability( $ability->get_name(), $ability->get_registration_args() );
		}
	}

	/**
	 * Clamps a page number.
	 *
	 * @param array  $input Input.
	 * @param string $key   Key.
	 * @param int    $min   Minimum.
	 * @param int    $max   Maximum.
	 * @param int    $def   Default.
	 *
	 * @return int
	 */
	public static function int_input( $input, $key, $min, $max, $def ) {
		$value = isset( $input[ $key ] ) ? (int) $input[ $key ] : $def;

		return max( $min, min( $max, $value ) );
	}

	/**
	 * Positive unique integers of a list input.
	 *
	 * @param array  $input Input.
	 * @param string $key   Key.
	 *
	 * @return int[]
	 */
	public static function ids_input( $input, $key ) {
		$ids = isset( $input[ $key ] ) ? array_map( 'intval', (array) $input[ $key ] ) : array();

		return array_values( array_unique( array_filter( $ids, function ( $id ) {
			return $id > 0;
		} ) ) );
	}

	/**
	 * Value of an enum input.
	 *
	 * @param array    $input   Input.
	 * @param string   $key     Key.
	 * @param string[] $allowed Allowed values.
	 * @param string   $def     Default.
	 *
	 * @return string
	 */
	public static function enum_input( $input, $key, $allowed, $def ) {
		$value = isset( $input[ $key ] ) ? (string) $input[ $key ] : $def;

		return in_array( $value, $allowed, true ) ? $value : $def;
	}
}

Mcp_Abilities::init();
