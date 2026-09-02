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
			<span class="wps-erp-label-text"><?php esc_html_e( 'Dolibarr Secret Key', 'wpshop' ); ?></span>
			<span class="wpeo-tooltip-event wps-erp-tooltip-icon" aria-label="<?php esc_attr_e( 'Secret key used for sell with Dolibarr', 'wpshop' ); ?>">?</span>
			<?php if (Settings::g()->dolibarr_is_active()): 
				$sync_settings = get_option( 'wps_sync_settings', array() );
				$color_ok      = ! empty( $sync_settings['color_ok'] ) ? $sync_settings['color_ok'] : '#47e58e';
			?>
				<span class="wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Connected to Dolibarr', 'wpshop' ); ?>" style="display:inline-block; width:10px; height:10px; border-radius:50%; background-color:<?php echo esc_attr( $color_ok ); ?>; margin-left:8px; vertical-align:middle; cursor:help;"></span>
				<?php 
				$connected_user = get_transient( 'wps_connected_erp_user' );
				if ( ! empty( $connected_user ) ) {
					$doli_url = rtrim( $dolibarr_option['dolibarr_url'], '/' );
					$user_link = $doli_url . '/user/card.php?id=' . $connected_user->id;
					echo '<span style="margin-left: 10px; font-weight: normal; font-size: 13px;">Connecté en tant que : <a href="' . esc_url( $user_link ) . '" target="_blank"><strong>' . esc_html( $connected_user->name ) . '</strong></a> ('. esc_html($connected_user->login) .')</span>';
				}
				?>
			<?php else: ?>
				<span class="wpeo-tooltip-event wps-erp-status-icon wps-erp-status-icon--error" aria-label="<?php esc_attr_e( 'Connection to dolibarr failed', 'wpshop' ); ?>">✖</span>
			<?php endif; ?>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_secret" value="<?php echo esc_attr( $dolibarr_option['dolibarr_secret'] ); ?>" />
		</label>
	</div>

	<div class="wps-erp-actions">
		<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
		<button type="submit" name="test_connection" value="1" class="wpeo-button button-secondary">
			<?php esc_html_e( 'Test Connection', 'wpshop' ); ?>
		</button>
	</div>
</form>
