<?php
/**
 * La vue de l'onglet Catégories
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * @var array $wp_categories
 * @var array $doli_categories
 * @var array $settings
 */
?>

<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="wps_update_categories_settings">
	<input type="hidden" name="tab" value="categories">
	<?php wp_nonce_field( 'callback_update_categories_settings' ); ?>

	<div style="margin-bottom: 20px; padding: 15px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
		<h3><?php esc_html_e( 'Outils de nettoyage', 'wpshop' ); ?></h3>
		<p><?php esc_html_e( 'Analysez et nettoyez votre catalogue WordPress en supprimant définitivement toutes les catégories vides (sans aucun produit ni sous-catégorie).', 'wpshop' ); ?></p>
		<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=tool_delete_empty_categories' ), 'wps_tool_delete_empty_categories' ) ); ?>" class="button button-secondary">
			<?php esc_html_e( 'Supprimer les catégories vides', 'wpshop' ); ?>
		</a>
	</div>

	<p><em>Cette liste récapitule uniquement les catégories de produits/services qui ont été synchronisées depuis Dolibarr. L'arborescence et la création sont pilotées directement depuis Dolibarr.</em></p>
	<table class="form-table">
		<thead>
			<tr>
				<th>ID WP</th>
				<th>Nom</th>
				<th>ID Dolibarr</th>
				<th>Nom Dolibarr</th>
				<th>Nbre Produits</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $wp_categories ) && ! is_wp_error( $wp_categories ) ) : 
				$wps_dolibarr = get_option( 'wps_dolibarr' );
				$dolibarr_url = !empty($wps_dolibarr['dolibarr_url']) ? rtrim($wps_dolibarr['dolibarr_url'], '/') : '';
				?>
				<?php foreach ( $wp_categories as $wp_cat ) : 
					$external_id = get_term_meta( $wp_cat->term_id, '_external_id', true );
					if ( empty( $external_id ) ) {
						continue; // Only list categories created/synced from WPShop (having an external ID)
					}
					
					// Find the corresponding Dolibarr category name
					$doli_name = '-';
					$wps_id_present = false;
					if ( ! empty( $doli_categories ) && ! isset( $doli_categories->error ) ) {
						foreach ( $doli_categories as $doli_cat ) {
							if ( $doli_cat->id == $external_id ) {
								$doli_name = $doli_cat->label;
								if ( ! empty( $doli_cat->array_options->options__wps_id ) ) {
									$wps_id_present = true;
								}
								break;
							}
						}
					}
				?>
				<tr>
					<td>
						<div style="display:flex; align-items:center; gap:5px;">
							<img src="<?php echo esc_url( PLUGIN_WPSHOP_URL . '/core/asset/image/logo-wordpress.jpg' ); ?>" style="width:18px; height:18px; border-radius:50%;" />
							<?php
							$view_link = get_term_link( (int) $wp_cat->term_id, 'wps-product-cat' );
							if ( ! is_wp_error( $view_link ) ) : ?>
								<a href="<?php echo esc_url( $view_link ); ?>" target="_blank" style="display:inline-block; padding: 2px 6px; background: #0073aa; color: #fff; border-radius: 3px; font-size: 11px; text-decoration: none;">#<?php echo esc_html( $wp_cat->term_id ); ?></a>
							<?php else : ?>
								<span style="display:inline-block; padding: 2px 6px; background: #0073aa; color: #fff; border-radius: 3px; font-size: 11px;">#<?php echo esc_html( $wp_cat->term_id ); ?></span>
							<?php endif; ?>
						</div>
					</td>
					<td>
						<strong><?php echo esc_html( $wp_cat->name ); ?></strong>
						<?php if ( ! $wps_id_present ): ?>
							<span style="color: #888; font-size: 11px;">(Auto)</span>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( $dolibarr_url ) : ?>
							<div style="display:flex; align-items:center; gap:5px;">
								<img src="<?php echo esc_url( PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg' ); ?>" style="width:18px; height:18px; border-radius:50%;" />
								<a href="<?php echo esc_url( $dolibarr_url . '/categories/viewcat.php?id=' . $external_id . '&type=product' ); ?>" target="_blank" style="display:inline-block; padding: 2px 6px; background: #855b8e; color: #fff; border-radius: 3px; font-size: 11px; text-decoration: none;">#<?php echo esc_html( $external_id ); ?></a>
							</div>
						<?php else : ?>
							<div style="display:flex; align-items:center; gap:5px;">
								<img src="<?php echo esc_url( PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg' ); ?>" style="width:18px; height:18px; border-radius:50%;" />
								<span style="display:inline-block; padding: 2px 6px; background: #855b8e; color: #fff; border-radius: 3px; font-size: 11px;">#<?php echo esc_html( $external_id ); ?></span>
							</div>
						<?php endif; ?>
					</td>
					<td>
						<?php echo esc_html( $doli_name ); ?>
					</td>
					<td>
						<?php echo esc_html( $wp_cat->count ); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="5"><?php esc_html_e( 'Aucune catégorie WordPress trouvée.', 'wpshop' ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>

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

</form>
