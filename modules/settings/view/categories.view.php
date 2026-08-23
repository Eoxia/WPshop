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

	<p><em>Cette liste récapitule uniquement les catégories qui ont été synchronisées depuis Dolibarr. L'arborescence et la création sont pilotées directement depuis Dolibarr.</em></p>
	<table class="form-table">
		<thead>
			<tr>
				<th>Catégorie WordPress</th>
				<th>Id Dolibarr</th>
				<th>Nom Dolibarr</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $wp_categories ) && ! is_wp_error( $wp_categories ) ) : ?>
				<?php foreach ( $wp_categories as $wp_cat ) : 
					$external_id = get_term_meta( $wp_cat->term_id, '_external_id', true );
					if ( empty( $external_id ) ) {
						continue; // Only list categories created/synced from WPShop (having an external ID)
					}
					
					// Find the corresponding Dolibarr category name and check wps_id
					$doli_name = '-';
					$is_created_in_dolibarr = false;
					if ( ! empty( $doli_categories ) && ! isset( $doli_categories->error ) ) {
						foreach ( $doli_categories as $doli_cat ) {
							if ( $doli_cat->id == $external_id ) {
								if ( ! empty( $doli_cat->array_options->options__wps_id ) ) {
									$doli_name = $doli_cat->label;
									$is_created_in_dolibarr = true;
								}
								break;
							}
						}
					}
					
					if ( ! $is_created_in_dolibarr ) {
						continue; // Only show categories explicitly marked as synced in Dolibarr
					}
				?>
				<tr>
					<td>
						<strong><?php echo esc_html( $wp_cat->name ); ?></strong>
					</td>
					<td>
						<?php echo esc_html( $external_id ); ?>
					</td>
					<td>
						<?php echo esc_html( $doli_name ); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="3"><?php esc_html_e( 'Aucune catégorie WordPress trouvée.', 'wpshop' ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>

</form>
