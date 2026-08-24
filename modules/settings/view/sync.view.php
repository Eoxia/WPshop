<?php
/**
 * La vue de l'onglet Synchronisation des réglages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2026 Eoxia <technique@eoxia.com>.
 * @since     2.4.0
 * @version   2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

$sync_settings = get_option( 'wps_sync_settings', array() );
$color_ok     = ! empty( $sync_settings['color_ok'] ) ? $sync_settings['color_ok'] : '#47e58e';
$color_error  = ! empty( $sync_settings['color_error'] ) ? $sync_settings['color_error'] : '#e05353';
$color_orange = ! empty( $sync_settings['color_orange'] ) ? $sync_settings['color_orange'] : '#e9ad4f';
?>

<style>
.wps-sync-color-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 15px 20px;
	margin-bottom: 12px;
	background: #fff;
	border: 1px solid #e2e4e7;
	border-radius: 6px;
	box-shadow: 0 1px 3px rgba(0,0,0,0.04);
	transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.wps-sync-color-row:hover {
	border-color: #2271b1;
	box-shadow: 0 2px 5px rgba(34,113,177,0.1);
}
.wps-sync-color-label {
	font-size: 14px;
	font-weight: 500;
	color: #3c434a;
	margin: 0;
	display: flex;
	align-items: center;
	gap: 12px;
}
.wps-sync-color-dot {
	display: inline-block;
	width: 14px;
	height: 14px;
	border-radius: 50%;
	box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.wps-sync-color-input {
	display: block;
	width: 60px !important;
	height: 40px !important;
	min-width: 60px !important;
	min-height: 40px !important;
	padding: 0 !important;
	margin: 0 !important;
	border: 1px solid #c3c4c7;
	border-radius: 4px;
	cursor: pointer;
	background: #fff;
	box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.wps-sync-color-input::-webkit-color-swatch-wrapper {
	padding: 2px;
}
.wps-sync-color-input::-webkit-color-swatch {
	border: 1px solid #dcdcde;
	border-radius: 2px;
}
.wps-sync-color-input::-moz-color-swatch {
	border: 1px solid #dcdcde;
	border-radius: 2px;
}
</style>

<form class="wpeo-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" style="max-width: 800px;">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_sync_settings' ); ?>" />
	<input type="hidden" name="tab" value="sync" />
	<?php wp_nonce_field( 'callback_update_sync_settings' ); ?>

	<div>
		<h3 style="margin-top: 0px; font-weight: 600; font-size: 16px; color: #1d2327; margin-bottom: 24px;"><?php esc_html_e( 'Personnalisation des statuts de synchronisation', 'wpshop' ); ?></h3>

		<div class="wps-sync-color-row">
			<span class="wps-sync-color-label">
				<span class="wps-sync-color-dot" style="background-color: <?php echo esc_attr( $color_error ); ?>;"></span>
				<?php esc_html_e( 'Désynchronisation majeure (Données principales corrompues)', 'wpshop' ); ?>
			</span>
			<label style="margin: 0;">
				<input type="color" class="wps-sync-color-input" name="wps_sync_color_error" value="<?php echo esc_attr( $color_error ); ?>" />
			</label>
		</div>

		<div class="wps-sync-color-row">
			<span class="wps-sync-color-label">
				<span class="wps-sync-color-dot" style="background-color: <?php echo esc_attr( $color_orange ); ?>;"></span>
				<?php esc_html_e( 'Désynchronisation mineure (Données OK, mais Tags/Catégories/Médias HS)', 'wpshop' ); ?>
			</span>
			<label style="margin: 0;">
				<input type="color" class="wps-sync-color-input" name="wps_sync_color_orange" value="<?php echo esc_attr( $color_orange ); ?>" />
			</label>
		</div>

		<div class="wps-sync-color-row">
			<span class="wps-sync-color-label">
				<span class="wps-sync-color-dot" style="background-color: <?php echo esc_attr( $color_ok ); ?>;"></span>
				<?php esc_html_e( 'Tout est synchronisé (Données + Catégories + Médias)', 'wpshop' ); ?>
			</span>
			<label style="margin: 0;">
				<input type="color" class="wps-sync-color-input" name="wps_sync_color_ok" value="<?php echo esc_attr( $color_ok ); ?>" />
			</label>
		</div>
	</div>

	<div style="margin-top: 30px;">
		<button type="submit" class="wpeo-button button-main button-right">
			<i class="fas fa-save" style="margin-right: 8px;"></i>
			<?php esc_html_e( 'Enregistrer les changements', 'wpshop' ); ?>
		</button>
	</div>
</form>
