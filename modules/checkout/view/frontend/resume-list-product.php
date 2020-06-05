<?php
/**
 * Liste des produits dans le résumé du panier.
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

defined( 'ABSPATH' ) || exit;

if ( ! empty( Cart_Session::g()->cart_contents ) ) :
	?>
	<div class="wps-list-product">
		<?php
		foreach ( Cart_Session::g()->cart_contents as $key => $product ) :
			if ( $shipping_cost_option['shipping_product_id'] !== $product['id'] ) :
				include( Template_Util::get_template_part( 'products', 'wps-product-list' ) );
			endif;
		endforeach;
		?>
	</div>
	<?php
endif;
