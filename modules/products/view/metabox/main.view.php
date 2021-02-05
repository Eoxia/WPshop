<?php
/**
 * La vue principale de la page des produits.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.3
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product          Les données d'un produit.
 */
?>

<div class="wpeo-wrap">
	<?php if ( ! Settings::g()->dolibarr_is_active() ) : ?>
		<!--		<div class="wps-metabox-subtitle">--><?php //esc_html_e( 'Description', 'wpshop' ); ?><!--</div>-->
		<!--		<div class="wps-product-description"><p>--><?php //echo $product->data['content']; ?><!--</p></div>-->
		<!---->
		<!--		<div class="wpeo-gridlayout grid-3">-->
		<!--			<div>-->
		<!--				<div class="wps-metabox-subtitle">--><?php //esc_html_e( 'Price HT(€)', 'wpshop' ); ?><!--</div>-->
		<!--				<div class="wps-metabox-content">--><?php //echo $product->data['price'] != 0 ? esc_html( $product->data['price'] ) : ''; ?><!--</div>-->
		<!--			</div>-->
		<!--			<div>-->
		<!--				<div class="wps-metabox-subtitle">--><?php //esc_html_e( 'VAT Rate', 'wpshop' ); ?><!--</div>-->
		<!--				<div class="wps-metabox-content">--><?php //echo esc_html( $product->data['tva_tx'] ); ?><!--%</div>-->
		<!--			</div>-->
		<!--			<div>-->
		<!--				<div class="wps-metabox-subtitle">--><?php //esc_html_e( 'Price TTC(€)', 'wpshop' ); ?><!--</div>-->
		<!--				<div class="wps-metabox-content">--><?php //echo esc_html( $product->data['price_ttc'] ); ?><!--</div>-->
		<!--			</div>-->
		<!--		</div>-->

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
						<?php if ( Settings::g()->dolibarr_is_active() ) : ?>
							<input type="text" readonly value="<?php echo $product->data['tva_tx']; ?>%" />
						<?php else: ?>
							<select name="product_data[tva_tx]" class="form-field">
								<?php $has_selected = false;
								if ( ! empty( Settings::g()->tva ) ) :
									foreach ( Settings::g()->tva as $tva ) :
										$selected = '';
										if ( (float) $tva === (float) $product->data['tva_tx'] || ( ! $has_selected && 20 === $tva ) ) :
											$selected     = 'selected="selected"';
											$has_selected = true;
										endif; ?>
										<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $tva ); ?>"><?php echo esc_html( $tva ); ?>%</option>
									<?php endforeach;
								endif; ?>
							</select>
						<?php endif; ?>
					</label>
				</div>
				<div class="form-element">
					<span class="form-label"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></span>
					<label class="form-field-container">
						<input type="text" readonly value="<?php echo $product->data['price_ttc']; ?>€" />
					</label>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="wpeo-notice notice-info">
			<div class="notice-content">
				<div class="notice-subtitle">
					<a href="<?php echo esc_url( admin_url('admin.php?page=wps-settings') ); ?>"><?php esc_html_e( 'To modify the data of this product, go to Dolibarr', 'wpshop' ); ?></a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
