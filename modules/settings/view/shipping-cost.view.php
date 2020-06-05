<?php
/**
 * La vue principale de la page des frais de port
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


<form class="wpeo-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_shipping_cost' ); ?>" />
	<input type="hidden" name="tab" value="shipping_cost" />
	<?php wp_nonce_field( 'callback_update_shipping_cost' ); ?>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Select shipping product', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<select name="shipping_product_id" class="form-field">
				<?php
				if ( ! empty( $products ) ) :
					foreach ( $products as $product ) :
						$selected = '';

						if ( $product->data['id'] === $shipping_cost_option['shipping_product_id'] ) :
							$selected = 'selected="selected"';
						endif;
						?>
						<option <?php echo $selected; ?> value="<?php echo esc_attr( $product->data['id'] ); ?>"><?php echo esc_html( $product->data['title'] ); ?></option>
						<?php
					endforeach;
				endif;
				?>
			</select>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Free delivery starting from (€) HT', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" name="from_price_ht" class="form-field" placeholder="00.00" value="<?php echo esc_attr( $shipping_cost_option['from_price_ht'] ); ?>" />
		</label>
	</div>

	<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
</form>
