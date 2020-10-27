<?php
/**
 * La vue affichant les configurations WordPress du produit.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product          Les données d'un produit.
 * @var string  $sync_status      True si on affiche le statut de la synchronisation.
 * @var string  $doli_url         L'url de Dolibarr.
 * @var boolean $has_selected     True si le produit est selectionné.
 * @var array   $tva              Les types de TVA.
 * @var string  $selected         L'attribut HTML "selected".
 * @var array   $similar_products Le tableau  contenant toutes les données des produits similaires.
 * @var Product $similar_product  Les données d'un produit similaire.
 */
?>

<div class="wpeo-wrap">
	<div class="wpeo-form">
		<?php wp_nonce_field( basename( __FILE__ ), 'wpshop_data_fields' ); ?>

		<div class="form-element stock-field">
			<span class="form-label"><?php esc_html_e( 'Manage Stock', 'wpshop' ); ?></span>
			<input type="hidden" name="product_data[manage_stock]" class="manage_stock" value="<?php echo (int) 1 === (int) $product->data['manage_stock'] ? 'true' : 'false'; ?>" />
			<i style="font-size: 2em;" class="toggle fas fa-toggle-<?php echo $product->data['manage_stock'] ? 'on' : 'off'; ?>" data-bloc="label-upload" data-input="manage_stock"></i>
		</div>

		<div class="form-element stock-block" style="<?php echo $product->data['manage_stock'] ? '' : 'display: none;'; ?>">
			<span class="form-label"><?php esc_html_e( 'Stock', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="product_data[stock]" value="<?php echo esc_attr( $product->data['stock'] ); ?>" />
			</label>
		</div>

<!--		<div class="form-element">-->
<!--			<span class="form-label">--><?php //esc_html_e( 'Product Downloadable', 'wpshop' ); ?><!--</span>-->
<!--			<input type="hidden" name="product_data[product_downloadable]" class="product_downloadable" value="--><?php //echo (int) 1 === (int) $product->data['product_downloadable'] ? 'true' : 'false'; ?><!--" />-->
<!--			<i style="font-size: 2em;" class="toggle fas fa-toggle---><?php //echo $product->data['product_downloadable'] ? 'on' : 'off'; ?><!--" data-bloc="label-upload" data-input="product_downloadable"></i>-->
<!--			<label class="label-upload form-field-container" style="--><?php //echo $product->data['product_downloadable'] ? '' : 'display: none;'; ?><!--">-->
<!--				--><?php //echo do_shortcode( '[wpeo_upload id="' . $id . '" upload_dir="wpshop_uploads" field_name="downloadable_product_id" single="false" model_name="/wpshop/Product" mime_type="" display_type="list"]' ); ?>
<!--			</label>-->
<!--		</div>-->

		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Similar products', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<select id="monselect" name="similar_products_id[]" class="form-field similar-product" multiple="true">
					<?php
					if ( ! empty( $similar_products ) ) :
						foreach ( $similar_products as $similar_product ) :
							?>
							<option selected="selected" value="<?php echo $similar_product->data['id']; ?>"><?php echo $similar_product->data['title']; ?></option>
						<?php
						endforeach;
					endif;
					?>
				</select>
			</label>
		</div>

	<!--			<div class="form-element">-->
	<!--				<span class="form-label">--><?php //esc_html_e( 'Parent Product', 'wpshop' ); ?><!--</span>-->
	<!--				<label class="form-field-container">-->
	<!--					--><?php
	//					if ( ! empty( $product->data['parent_post'] ) ) :
	//						?>
	<!--						<a href="--><?php //echo esc_attr( admin_url( 'post.php?post=' . $product->data['parent_post']->ID . '&action=edit' ) ); ?><!--">--><?php //echo esc_html( $product->data['parent_post']->post_title ); ?><!--</a>-->
	<!--					--><?php
	//					else :
	//						esc_html_e( 'No product parent', 'wpshop' );
	//					endif;
	//					?>
	<!--				</label>-->
	<!--			</div>-->

	</div>
</div>
