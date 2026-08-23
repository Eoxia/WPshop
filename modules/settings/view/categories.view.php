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

	<table class="form-table">
		<thead>
			<tr>
				<th>Catégorie WordPress</th>
				<th>Synchroniser avec Dolibarr</th>
				<th>Catégorie parente sur Dolibarr</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $wp_categories ) && ! is_wp_error( $wp_categories ) ) : ?>
				<?php foreach ( $wp_categories as $wp_cat ) : 
					$sync_enabled = isset( $settings[ $wp_cat->term_id ]['sync'] ) ? $settings[ $wp_cat->term_id ]['sync'] : false;
					$parent_id = isset( $settings[ $wp_cat->term_id ]['parent'] ) ? $settings[ $wp_cat->term_id ]['parent'] : 0;
				?>
				<tr>
					<td>
						<strong><?php echo esc_html( $wp_cat->name ); ?></strong>
					</td>
					<td>
						<input type="checkbox" name="wps_sync_categories[<?php echo esc_attr( $wp_cat->term_id ); ?>][sync]" value="1" <?php checked( $sync_enabled, 1 ); ?> />
					</td>
					<td>
						<select name="wps_sync_categories[<?php echo esc_attr( $wp_cat->term_id ); ?>][parent]">
							<option value="0"><?php esc_html_e( 'Aucun', 'wpshop' ); ?></option>
							<?php if ( ! empty( $doli_categories ) && ! isset( $doli_categories->error ) ) : ?>
								<?php foreach ( $doli_categories as $doli_cat ) : ?>
									<option value="<?php echo esc_attr( $doli_cat->id ); ?>" <?php selected( $parent_id, $doli_cat->id ); ?>>
										<?php echo esc_html( $doli_cat->label ); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
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

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>">
	</p>
</form>
