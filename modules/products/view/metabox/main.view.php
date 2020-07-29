<?php
/**
 * La vue principale de la page des produits.
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
	<h2 style="font-size: 1.6em; font-weight: bold; display: inline-flex;">Modification du produit <?php echo $product->data['title']; ?> 	</h2><span style="display: inline-flex; position: relative; left: 35%"> <?php do_action( 'wps_listing_table_end', $product, $sync_status ); ?> </span>
	<?php
	if ( ! empty( $product->data['external_id'] ) ) :
		?>
		<p style="font-size: 1.2em; font-weight: 700">
			<span>La modification du titre, de la description et du prix est contrôlée par dolibarr</span>
			<a class="wpeo-button button-blue" href="<?php echo esc_attr( $doli_url ); ?>/product/card.php?id=<?php echo $product->data['external_id']; ?>"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a>
		</p>
		<?php
	endif;
	?>

	<h2 style="font-size: 1.6em;"><?php echo esc_html_e( 'Autres informations du produit', 'wpshop' ); ?></h2>

	<div class="wpeo-form">
		<?php wp_nonce_field( basename( __FILE__ ), 'wpshop_data_fields' ); ?>

		<div class="wpeo-gridlayout grid-3">
			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'Price HT(€)', 'wpshop' ); ?></span>
				<label class="form-field-container">
					<input type="text" <?php echo Settings::g()->dolibarr_is_active() ? 'readonly': 'class="form-field"'; ?>  name="product_data[price]" placeholder="0" value="<?php echo esc_attr( $product->data['price'] ) != 0 ? esc_attr( $product->data['price'] ) : ''; ?>" />
				</label>
			</div>

			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'VAT Rate', 'wpshop' ); ?></span>

				<label class="form-field-container">
					<?php
					if ( Settings::g()->dolibarr_is_active() ) :
						?>
						<input type="text" readonly value="<?php echo $product->data['tva_tx']; ?>%" />
						<?php
					else:
						?>
						<select name="product_data[tva_tx]" class="form-field">
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
						<?php
					endif;
					?>
				</label>
			</div>

			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></span>
				<label class="form-field-container">
					<input type="text" readonly value="<?php echo $product->data['price_ttc']; ?>€" />
				</label>
			</div>

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

			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'Product Downloadable', 'wpshop' ); ?></span>
				<input type="hidden" name="product_data[product_downloadable]" class="product_downloadable" value="<?php echo (int) 1 === (int) $product->data['product_downloadable'] ? 'true' : 'false'; ?>" />
				<i style="font-size: 2em;" class="toggle fas fa-toggle-<?php echo $product->data['product_downloadable'] ? 'on' : 'off'; ?>" data-bloc="label-upload" data-input="product_downloadable"></i>
				<label class="label-upload form-field-container" style="<?php echo $product->data['product_downloadable'] ? '' : 'display: none;'; ?>">
					<?php echo do_shortcode( '[wpeo_upload id="' . $id . '" upload_dir="wpshop_uploads" field_name="downloadable_product_id" single="false" model_name="/wpshop/Product" mime_type="" display_type="list"]' ); ?>
				</label>
			</div>

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
</div>
