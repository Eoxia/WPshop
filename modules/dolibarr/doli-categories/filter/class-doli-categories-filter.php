<?php
/**
 * La classe gérant les filtres des catégories de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Dolibarr Category Filter Class.
 */
class Doli_Category_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	public function __construct() {
		if ( Settings::g()->dolibarr_is_active() ) {
			//add_filter( 'wps-product-cat_after_get', array( $this, 'auto_sync' ), 10, 2 );
			add_filter( 'wps_category_filter_sync', array( $this, 'category_sync' ), 10, 1 );
		}
	}

	/**
	 * La synchronisation automatique d'une catégorie.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param  Doli_Category $object  Les données d'une catégorie.
	 * @param  array   $args_cb Les arguments supplémentaires.
	 *
	 * @return Doli_Category          Les données d'une catégorie synchronisé.
	 */
	public function auto_sync( $object, $args_cb ) {
		if ( empty( $object->data['external_id'] ) ) {
			return $object;
		}
		$status = Doli_Sync::g()->check_status( $object->data['id'], $object->data['type'] );

		if ($status['status_code'] != '0x3') {
			return $object;
		}

		$doli_category = Request_Util::get( 'categories/' . $object->data['external_id'] );
		$object       = Doli_Category::g()->doli_to_wp( $doli_category, $object );

		return $object;
	}

	/**
	 * La synchronisation manuelle d'unecatégorie.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param  Doli_Category $category Les données d'une catégorie.
	 *
	 * @return Doli_Category          Les données d'une catégorie synchronisé.
	 */
	public function category_sync( $category ) {
		$external_id = get_term_meta( $category->ID, '_external_id', true );
		$status = Doli_Sync::g()->check_status( $category->ID, $category->term_type );

		if ($status['status_code'] != '0x3') {
			return $category;
		}

		$doli_category = Request_Util::get( 'categories/' . $external_id );
		$object       = Doli_Category::g()->get( array( 'id' => $category->ID ), true );
		$object       = Doli_Category::g()->doli_to_wp( $doli_category, $object );

		$category = get_term( $category->ID );

		return $category;
	}
}

new Doli_Category_Filter();
