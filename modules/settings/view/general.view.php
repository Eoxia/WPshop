<?php
/**
 * La vue affichant la page "GENERAL" dans les réglages.
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
 * @var array $dolibarr_option Le tableau contenant toutes les données des options de dolibarr.
 */
?>

<form class="wpeo-form wpeo-grid grid-2 grid-padding-1" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_general_settings' ); ?>" />
	<input type="hidden" name="tab" value="general" />
	<?php wp_nonce_field( 'callback_update_general_settings' ); ?>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Shop Email', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="shop_email" value="<?php echo esc_attr( $dolibarr_option['shop_email'] ); ?>" />
		</label>
	</div>

	<div>
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'WPshop width size', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="thumbnail_size[width]" value="<?php echo esc_attr( $dolibarr_option['thumbnail_size']['width'] ); ?>" />
			</label>
		</div>
	</div>

	<div>
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'WPshop height size', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="thumbnail_size[height]" value="<?php echo esc_attr( $dolibarr_option['thumbnail_size']['height'] ); ?>" />
			</label>
		</div>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" id="use_quotation" class="form-field" name="use_quotation" <?php echo $dolibarr_option['use_quotation'] ? 'checked="checked"' : ''; ?> />
			<label for="use_quotation"><?php esc_html_e( 'Enable wish list', 'wpshop' ); ?></label>
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" id="split_product" class="form-field" name="split_product" <?php echo $dolibarr_option['split_product'] ? 'checked="checked"' : ''; ?> />
			<label for="split_product"><?php esc_html_e( 'Split product', 'wpshop' ); ?></label>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Mininum price per product', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="price_min" value="<?php echo ! empty( $dolibarr_option['price_min'] ) ? esc_attr( $dolibarr_option['price_min'] ) : 0; ?>" />
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" id="debug_mode" class="form-field" name="debug_mode" <?php echo $debug_mode ? 'checked="checked"' : ''; ?> />
			<label for="debug_mode"><?php esc_html_e( 'Debug mode', 'wpshop' ); ?></label>
		</label>
	</div>

	<div>
		<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
	</div>
</form>
