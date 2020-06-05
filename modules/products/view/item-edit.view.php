<?php
/**
 * Affichage d'un produit en mode édition dans le listing de la page des produits (wps-product)
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

<div class="table-row">
	<input type="hidden" name="action" value="quick_save" />
	<input type="hidden" name="id" value="<?php echo $product->data['id']; ?>" />
	<input type="hidden" name="product_data[product_downloadable]" value="<?php echo (int) 1 === (int) $product->data['product_downloadable'] ? 'true' : 'false'; ?>" />


	<div class="table-cell table-50"><input type="checkbox" /></div>
	<div class="table-cell table-100 table-padding-0"><?php echo do_shortcode( '[wpeo_upload id="' . $product->data['id'] . '" single="true" model_name="/wpshop/Product"]' ); ?></div>
	<div class="table-cell table-full">
		<ul class="reference-id">
			<li><i class="fas fa-hashtag"></i>WP : <?php echo esc_html( $product->data['id'] ); ?></li>
			<?php if ( ! empty( $product->data['external_id'] ) ) : ?>
				<li><i class="fas fa-hashtag"></i>Doli : <?php echo esc_html( $product->data['external_id'] ); ?></li>
			<?php endif; ?>
		</ul>
		<div class="reference-title">
			<input type="text" name="title" value="<?php echo $product->data['title']; ?>" />
		</div>
		<ul class="reference-actions">
			<li><a href="#" class="action-attribute" data-id="<?php echo esc_attr( $product->data['id'] ); ?>" data-action="change_mode" data-mode="view"><?php esc_html_e( 'Cancel', 'wpshop' ); ?></a></li>
			<li><a href="#" class="action-input" data-parent="table-row" style="color: green;"><?php esc_html_e( 'Save', 'wpshop' ); ?></a></li>
		</ul>
	</div>
	<div class="table-cell table-100"><input type="text" name="product_data[price]" value="<?php echo $product->data['price']; ?>" /></div>
	<div class="table-cell table-100">
		<select name="product_data[tva_tx]">
			<?php
			$has_selected = false;
			if ( ! empty( Settings::g()->tva ) ) :
				foreach ( Settings::g()->tva as $tva ) :
					$selected = '';
					if ( (float) $tva === (float) $product->data['tva_tx'] || ( ! $has_selected && 20 === $tva ) ) :
						$selected     = 'selected="selected"';
						$has_selected = true;
					endif;
					?>
					<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $tva ); ?>"><?php echo esc_html( $tva ); ?>%</option>
					<?php
				endforeach;
			endif;
			?>
		</select>
	</div>
	<div class="table-cell table-100"><strong><?php echo esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ); ?>€</strong></div>
	<div class="table-cell table-100">
		<?php
		if ( ! empty( $product->data['parent_post'] ) ) :
			echo esc_html( $product->data['parent_post']->post_title );
		else :
			esc_html_e( 'No product parent', 'wpshop' );
		endif;
		?>
	</div>
	<div class="table-cell">
		<div class="form-element stock-field">
			<span class="form-label"><?php esc_html_e( 'Manage Stock', 'wpshop' ); ?></span>
			<input type="hidden" name="product_data[manage_stock]" class="manage_stock" value="<?php echo (int) 1 === (int) $product->data['manage_stock'] ? 'true' : 'false'; ?>" />
			<i style="font-size: 2em;" class="toggle fas fa-toggle-<?php echo (int) 1 === (int) $product->data['manage_stock'] ? 'on' : 'off'; ?>" data-bloc="stock-field" data-input="manage_stock"></i>
		</div>

		<div class="form-element stock-block" style="<?php echo (int) 1 === (int) $product->data['manage_stock'] ? '' : 'display: none;'; ?>">
			<span class="form-label"><?php esc_html_e( 'Stock', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="product_data[stock]" value="<?php echo esc_attr( $product->data['stock'] ); ?>" />
			</label>
		</div>
	</div>
	<?php do_action( 'wps_listing_table_end', $product, 'wpshopapi/product/get/web', 'edit', 'wpshop/Doli_Products_Class', '\wpshop\Product' ); ?>

</div>
