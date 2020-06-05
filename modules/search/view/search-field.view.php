<?php
/**
 * Champs pour rechercher un produit.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<form role="search" method="get" class="wpshop-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="wpshop-product-search"><?php esc_html_e( 'Search for:', 'wpshop' ); ?></label>
	<input type="search" id="wpshop-product-search" class="search-field" placeholder="<?php esc_attr_e( 'Search products&hellip;', 'wpshop' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" value="<?php echo esc_attr_e( 'Search', 'wpshop' ); ?>"><?php echo esc_html_e( 'Search', 'wpshop' ); ?></button>
	<input type="hidden" name="post_type" value="wps-product" />
</form>
