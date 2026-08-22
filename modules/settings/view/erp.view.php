<?php
/**
 * La vue affichant la page "ERP" dans les réglages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.1.0
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
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_erp_settings' ); ?>" />
	<input type="hidden" name="tab" value="erp" />
	<?php wp_nonce_field( 'callback_update_erp_settings' ); ?>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr URL', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_url" placeholder="ex: http://localhost/dolibarr/htdocs" value="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] ); ?>" />
		</label>
		<p class="description"><?php _e( 'Enter the URL pointing to the root of your Dolibarr installation (usually ending with /htdocs).', 'wpshop' ); ?></p>
	</div>

	<div class="form-element">
		<span class="form-label">
			<span style="vertical-align: middle;"><?php esc_html_e( 'Dolibarr Secret Key', 'wpshop' ); ?></span>
			<span class="wpeo-tooltip-event" style="display: inline-block; width: 18px; height: 18px; line-height: 18px; text-align: center; border-radius: 50%; background: #007cba; color: #fff; font-size: 11px; font-weight: bold; cursor: help; margin-left: 5px; vertical-align: middle;" aria-label="<?php esc_attr_e( 'Secret key used for sell with Dolibarr', 'wpshop' ); ?>">?</span>
			<?php if (Settings::g()->dolibarr_is_active()): ?>
				<span class="wpeo-tooltip-event" style="display: inline-block; width: 18px; height: 18px; line-height: 18px; text-align: center; color: #46b450; font-size: 14px; margin-left: 5px; vertical-align: middle;" aria-label="<?php esc_attr_e( 'Connected to Dolibarr', 'wpshop' ); ?>">✔</span>
			<?php else: ?>
				<span class="wpeo-tooltip-event" style="display: inline-block; width: 18px; height: 18px; line-height: 18px; text-align: center; color: #dc3232; font-size: 14px; margin-left: 5px; vertical-align: middle;" aria-label="<?php esc_attr_e( 'Connection to dolibarr failed', 'wpshop' ); ?>">✖</span>
			<?php endif; ?>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_secret" value="<?php echo esc_attr( $dolibarr_option['dolibarr_secret'] ); ?>" />
		</label>
	</div>

	<div style="display: flex; gap: 10px; align-items: center; margin-top: 15px;">
		<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" style="margin: 0;" />
		<button type="submit" name="test_connection" value="1" class="wpeo-button button-secondary" style="margin: 0;">
			<?php esc_html_e( 'Test Connection', 'wpshop' ); ?>
		</button>
	</div>
</form>
