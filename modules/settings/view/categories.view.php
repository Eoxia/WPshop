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
						<span style="display:inline-block; padding: 2px 6px; background: #0073aa; color: #fff; border-radius: 3px; font-size: 11px;">
							<?php echo esc_html( $wp_cat->term_id ); ?>
						</span>
					</td>
					<td>
						<strong><?php echo esc_html( $wp_cat->name ); ?></strong>
						<?php if ( ! $wps_id_present ): ?>
							<span style="color: #888; font-size: 11px;">(Auto)</span>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( $dolibarr_url ) : ?>
							<a href="<?php echo esc_url( $dolibarr_url . '/categories/viewcat.php?id=' . $external_id . '&type=product' ); ?>" target="_blank" style="display:inline-block; padding: 2px 6px; background: #855b8e; color: #fff; border-radius: 3px; font-size: 11px; text-decoration: none;">
								<?php echo esc_html( $external_id ); ?>
							</a>
						<?php else : ?>
							<span style="display:inline-block; padding: 2px 6px; background: #855b8e; color: #fff; border-radius: 3px; font-size: 11px;">
								<?php echo esc_html( $external_id ); ?>
							</span>
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

</form>
