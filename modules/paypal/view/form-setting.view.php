<?php
/**
 * La vue principale de la page de réglages
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

<div class="form-element">
	<span class="form-label"><?php esc_html_e( 'Paypal email', 'wpshop' ); ?></span>
	<label class="form-field-container">
		<input type="text" class="form-field" name="paypal_email" value="<?php echo esc_attr( $paypal_options['paypal_email'] ); ?>" />
	</label>
</div>

<div class="form-element form-align-horizontal">
	<div class="form-field-inline">
		<input type="checkbox" id="use-paypal-sandbox" class="form-field" <?php echo $paypal_options['use_paypal_sandbox'] ? 'checked' : ''; ?> name="use_paypal_sandbox" />
		<label for="use-paypal-sandbox"><?php esc_html_e( 'PayPal Sandbox', 'wpshop' ); ?></label>
	</div>
</div>
