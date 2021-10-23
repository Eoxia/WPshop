<?php
/**
 * La classe gérant les shortcodes des produits.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Product Shortcode Class.
 */
class Products_Shortcode {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_shortcode( 'wps_product', array( $this, 'do_shortcode_product' ) );
		add_shortcode( 'wps_categories', array( $this, 'do_shortcode_categories' ) );
	}

	/**
	 * Le shortcode permattant d'afficher les produits.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param array $atts Les paramètres du shortcode.
	 */
	public function do_shortcode_product( $atts ) {
		if ( ! is_admin() ) {
			$a = shortcode_atts( array(
				'id'         => 0,
				'ids'        => array(),
				'categories' => array(),
				's'          => '',
			), $atts );

			global $post;
			global $wp_query;

			$products = array();
			$args     = array(
				'tax_query' => array(),
			);

			if ( ! empty( $a['id'] ) ) {
				$args['id'] = $a['id'];
			}

			if ( ! empty( $a['ids'] ) ) {
				$a['ids']         = explode( ',', $a['ids'] );
				$args['post__in'] = $a['ids'];
			}

			if ( ! empty( $a['categories'] ) ) {
				$a['categories'] = explode( ',', $a['categories'] );
				foreach ( $a['categories'] as $category_slug ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'wps-product-cat',
						'field'    => 'slug',
						'terms'    => $category_slug,
					);
				}
			}

			if ( ! empty( $a['s'] ) ) {
				$args['s'] = $a['s'];
			}

			$args['post_type'] = 'wps-product';

			$wps_query = new \WP_Query( $args );

			foreach ( $wps_query->posts as $key => &$product ) {
				$product->price_ttc    = get_post_meta( $product->ID, '_price_ttc', true );
				$product->manage_stock = get_post_meta( $product->ID, '_manage_stock', true );
				$product->stock        = get_post_meta( $product->ID, '_stock', true );
			}

			unset( $product );
			setup_postdata( $post );

			include( Template_Util::get_template_part( 'products', 'wps-product-grid-container' ) );
		}
	}

	/**
	 * Le shortcode permattant d'afficher les categories.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $atts Les paramètres du shortcode.
	 *
	 * @return string      La vue.
	 */
	public function do_shortcode_categories( $atts ) {

		global $wpdb;

		$doli_categories = Request_Util::get( 'categories/'  );

		if ( ! empty( $doli_categories )) {
			foreach( $doli_categories as $doli_category) {
				wp_insert_term($doli_category->label, 'wps-product-cat', array('description'=>$doli_category->description ));
			}
		}
		else {
			$wpdb->delete('wp_term_taxonomy', array('taxonomy' => 'wps-product-cat'));
		}
	}
}

new Products_Shortcode();
