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

$auto_sync_list = isset( $sync_settings['auto_sync_list'] ) ? $sync_settings['auto_sync_list'] : 1;
$auto_sync_edit = isset( $sync_settings['auto_sync_edit'] ) ? $sync_settings['auto_sync_edit'] : 0;
$auto_sync_shop = isset( $sync_settings['auto_sync_shop'] ) ? $sync_settings['auto_sync_shop'] : 0;
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

<?php
$auto_sync_list = isset( $sync_settings['auto_sync_list'] ) ? $sync_settings['auto_sync_list'] : 1;
$auto_sync_edit = isset( $sync_settings['auto_sync_edit'] ) ? $sync_settings['auto_sync_edit'] : 0;
$auto_sync_shop = isset( $sync_settings['auto_sync_shop'] ) ? $sync_settings['auto_sync_shop'] : 0;
$auto_sync_ttl  = isset( $sync_settings['auto_sync_ttl'] ) ? (int) $sync_settings['auto_sync_ttl'] : 4;
?>

	<div style="margin-top: 40px;">
		<h3 style="margin-top: 0px; font-weight: 600; font-size: 16px; color: #1d2327; margin-bottom: 24px;"><?php esc_html_e( 'Déclenchements automatiques', 'wpshop' ); ?></h3>
        
        <p class="description" style="margin-bottom: 25px;"><?php esc_html_e( 'Choisissez à quel moment le système doit lancer automatiquement la vérification de la synchronisation des produits.', 'wpshop' ); ?></p>

		<style>
		.wps-toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; background: #fff; border: 1px solid #e2e4e7; border-radius: 6px; margin-bottom: 10px; }
		.wps-toggle-text { font-size: 14px; }
		.wps-toggle-desc { color: #646970; font-size: 13px; margin-left: 5px; }
		.wps-toggle-switch { position: relative; display: inline-block; width: 40px; height: 22px; }
		.wps-toggle-switch input { opacity: 0; width: 0; height: 0; }
		.wps-toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .3s; border-radius: 22px; }
		.wps-toggle-slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
		.wps-toggle-switch input:checked + .wps-toggle-slider { background-color: #2271b1; }
		.wps-toggle-switch input:focus + .wps-toggle-slider { box-shadow: 0 0 1px #2271b1; }
		.wps-toggle-switch input:checked + .wps-toggle-slider:before { transform: translateX(18px); }
		</style>

		<div class="wps-toggle-row">
			<div class="wps-toggle-text">
				<strong><?php esc_html_e( 'Liste des produits (Back-office)', 'wpshop' ); ?></strong>
				<span class="wps-toggle-desc">(wp-admin/admin.php?page=wps-product)</span>
			</div>
			<label class="wps-toggle-switch">
				<input type="checkbox" name="wps_auto_sync_list" value="1" <?php checked( $auto_sync_list, 1 ); ?>>
				<span class="wps-toggle-slider"></span>
			</label>
		</div>

		<div class="wps-toggle-row">
			<div class="wps-toggle-text">
				<strong><?php esc_html_e( 'Édition d\'un produit unitaire (Back-office)', 'wpshop' ); ?></strong>
				<span class="wps-toggle-desc">(wp-admin/post.php?post=X&action=edit)</span>
			</div>
			<label class="wps-toggle-switch">
				<input type="checkbox" name="wps_auto_sync_edit" value="1" <?php checked( $auto_sync_edit, 1 ); ?>>
				<span class="wps-toggle-slider"></span>
			</label>
		</div>

		<div class="wps-toggle-row" style="border-left: 3px solid #d63638;">
			<div class="wps-toggle-text">
				<strong><?php esc_html_e( 'Boutique publique (Front-end)', 'wpshop' ); ?></strong>
				<br><span class="wps-toggle-desc" style="color: #d63638; margin-left:0;"><?php esc_html_e( 'Attention : La synchronisation asynchrone affichera un "loader" à la place du prix pendant le chargement.', 'wpshop' ); ?></span>
			</div>
			<label class="wps-toggle-switch">
				<input type="checkbox" name="wps_auto_sync_shop" value="1" <?php checked( $auto_sync_shop, 1 ); ?>>
				<span class="wps-toggle-slider"></span>
			</label>
		</div>

		<div class="wps-toggle-row" style="margin-top: 20px; background: #f6f7f7;">
			<div class="wps-toggle-text">
				<strong><?php esc_html_e( 'Fréquence de rafraichissement Front-end', 'wpshop' ); ?></strong>
				<br><span class="wps-toggle-desc" style="margin-left:0;"><?php esc_html_e( 'Un produit ne sera pas resynchronisé si sa dernière mise à jour est plus récente que ce délai (Péremption).', 'wpshop' ); ?></span>
			</div>
			<div>
				<input type="number" name="wps_auto_sync_ttl" value="<?php echo esc_attr( $auto_sync_ttl ); ?>" min="0" step="1" style="width: 70px;">
				<span style="font-size: 14px; margin-left: 5px;">heures</span>
			</div>
		</div>

	</div>

	<div style="margin-top: 40px; padding: 20px; background: #fff; border: 1px solid #c3c4c7; border-left: 4px solid #00a0d2; border-radius: 4px;">
		<h3 style="margin-top: 0px; font-weight: 600; font-size: 16px; color: #1d2327; margin-bottom: 15px;"><?php esc_html_e( 'Contrôle de l\'arborescence des catégories', 'wpshop' ); ?></h3>
		<p class="description" style="margin-bottom: 20px;"><?php esc_html_e( 'Utilisez cet outil pour forcer la vérification et la reconstruction de l\'arborescence (catégories parentes/enfants) de toutes les catégories importées depuis Dolibarr.', 'wpshop' ); ?></p>
		
		<button type="button" id="wps-sync-category-tree-btn" class="wpeo-button button-secondary">
			<i class="fas fa-sitemap" style="margin-right: 8px;"></i>
			<?php esc_html_e( 'Reconstruire l\'arborescence', 'wpshop' ); ?>
		</button>
		<span id="wps-sync-category-tree-status" style="margin-left: 15px; font-weight: 600;"></span>

		<script>
		document.getElementById('wps-sync-category-tree-btn').addEventListener('click', function(e) {
			e.preventDefault();
			var btn = this;
			var status = document.getElementById('wps-sync-category-tree-status');
			
			btn.disabled = true;
			status.style.color = '#2271b1';
			status.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reconstruction en cours... cela peut prendre quelques minutes.';

			jQuery.post(ajaxurl, {
				action: 'wps_sync_category_tree'
			}, function(response) {
				btn.disabled = false;
				if (response.success) {
					status.style.color = '#00a32a';
					status.innerHTML = '<i class="fas fa-check"></i> ' + response.data.message;
				} else {
					status.style.color = '#d63638';
					status.innerHTML = '<i class="fas fa-times"></i> Erreur lors de la reconstruction.';
				}
			}).fail(function() {
				btn.disabled = false;
				status.style.color = '#d63638';
				status.innerHTML = '<i class="fas fa-times"></i> Erreur serveur.';
			});
		});
		</script>
	</div>

	<div style="margin-top: 40px;">
		<button type="submit" class="wpeo-button button-main button-right">
			<i class="fas fa-save" style="margin-right: 8px;"></i>
			<?php esc_html_e( 'Enregistrer les changements', 'wpshop' ); ?>
		</button>
		<button type="submit" name="wps_sync_reset" value="1" class="wpeo-button button-secondary button-right" style="margin-right: 15px;" onclick="return confirm('<?php esc_attr_e( 'Êtes-vous sûr de vouloir réinitialiser les couleurs ?', 'wpshop' ); ?>');">
			<i class="fas fa-undo" style="margin-right: 8px;"></i>
			<?php esc_html_e( 'Remettre par défaut', 'wpshop' ); ?>
		</button>
	</div>
</form>
