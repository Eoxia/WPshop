<?php
/**
 * Product grid view
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 * @package   WPshop\Templates
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( ! empty( $product_taxonomies ) ) :
	?>
	<div class="wps-list-taxonomy wpeo-gridlayout grid-3 grid-margin-2">
		<?php
		foreach ( $product_taxonomies as $product_taxonomy ) :
			include( Template_Util::get_template_part( 'products', 'wps-product-taxonomy' ) );
		endforeach;
		?>
	</div>
	<?php
endif;
