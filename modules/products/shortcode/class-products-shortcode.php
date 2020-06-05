<?php
/**
 * Gestion des shortcodes des produits.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Product Shortcode Class.
 */
class Products_Shortcode {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_shortcode( 'wps_product', array( $this, 'do_shortcode_product' ) );
		add_shortcode( 'wps_categories', array( $this, 'do_shortcode_categories' ) );
	}

	/**
	 * Shortcode permattant d'afficher les produits
	 *
	 * @since 2.0.0
	 *
	 * @param  array $atts Les paramètres du shortcode. (Voir shorcode_atts
	 * ci dessous pour les paramètres disponibles).
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
	 * Shortcode permattant d'afficher les categories
	 *
	 * @since 2.0.0
	 *
	 * @param  array $atts Les paramètres du shortcode. (Voir shorcode_atts
	 * ci dessous pour les paramètres disponibles).
	 */
	public function do_shortcode_categories( $atts ) {
		if ( ! is_admin() ) {
			$default_atts = shortcode_atts( array(
				'slug'    => '',
				'id'      => '',
				'orderby' => 'include',
				'order'   => 'ASC',
			), $atts );

			$args = array(
				'taxonomy'   => 'wps-product-cat',
				'hide_empty' => false,
				'orderby'    => $default_atts['orderby'],
				'order'      => $default_atts['order'],
			);

			if ( ! empty( $default_atts['slug'] ) ) {
				$default_atts['slug'] = explode( ',', $default_atts['slug'] );
				$args['slug']         = $default_atts['slug'];
			}

			if ( ! empty( $default_atts['id'] ) ) {
				$default_atts['id'] = explode( ',', $default_atts['id'] );
				$args['include']    = $default_atts['id'];
			}

			$product_taxonomies = get_terms( $args );

			ob_start();
			include( Template_Util::get_template_part( 'products', 'wps-product-taxonomy-container' ) );
			return ob_get_clean();
		}
	}
}

new Products_Shortcode();
