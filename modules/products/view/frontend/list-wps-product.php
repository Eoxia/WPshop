<?php
/**
 * Taxonomy Product view.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 * @package   WPshop\Templates
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( ! empty( $products ) ) :
	?>
	<div class="wpeo-gridlayout grid-4">
	<?php
	foreach ( $products as $product ) :
		?>
		<div class="wps-product">
			<a href="<?php echo get_permalink( $product->data['id'] ); ?>">
				<figure class="wps-product-thumbnail">
					<?php echo wp_get_attachment_image( $product->data['thumbnail_id'] ); ?>
				</figure>
				<div class="wps-product-content">
					<div class="wps-product-title"><?php echo $product->data['title']; ?></div>
					<div class="wpeo-gridlayout grid-2">
						<div class="wps-product-price"><?php echo ! empty( $product->data['price_ttc'] ) ? esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ) . ' €' : ''; ?></div>
						<div class="wps-product-buy wpeo-button button-square-40 action-attribute"
							data-action="add_to_cart"
							data-nonce="<?php echo wp_create_nonce( 'add_to_cart' ); ?>"
							data-id="<?php echo esc_attr( $product->data['id'] ); ?>"><i class="button-icon fas fa-cart-arrow-down"></i></div>
					</div>
				</div>
			</a>
		</div>
		<?php
	endforeach;
	?>
	</div>
	<?php
endif;
