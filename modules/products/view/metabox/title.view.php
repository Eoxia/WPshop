<?php
/**
 * La vue affichant le titre et le statut de synchronisation
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product          Les données d'un produit.
 * @var string  $sync_status      True si on affiche le statut de la synchronisation.
 * @var string  $doli_url         L'url de Dolibarr.
 */
?>

<div class="wpeo-wrap">

	<div class="wps-product-title-container" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 20px;">
		
		<div class="wps-product-title" style="flex-grow: 1;">
			<input type="text" readonly value="<?php echo esc_attr( $product->data['title'] ); ?>" style="width: 100%; font-size: 1.2em; border: none; border-bottom: 1px solid #000; background: transparent; padding: 5px 0; outline: none; box-shadow: none; color: #333;" />
		</div>

		<div class="wps-product-title-actions" style="display: flex; gap: 10px; align-items: center; padding-bottom: 5px;">
			<a class="wps-badge-link" href="<?php echo esc_url( get_post_permalink( $product->data['id'] ) ); ?>" target="_blank" title="<?php esc_attr_e( 'Preview', 'wpshop' ); ?>" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 1px solid #1897e7; padding: 5px 10px; border-radius: 3px; color: #333; background: #fff;">
				<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-wordpress.jpg'; ?>" style="width: 20px; height: 20px; border-radius: 50%;" />
				<strong>#<?php echo esc_html( $product->data['id'] ); ?></strong>
				<i class="fas fa-external-link-alt" style="color: #333;"></i>
			</a>

			<?php if ( ! empty( $product->data['external_id'] ) ) : ?>
			<a class="wps-badge-link" href="<?php echo esc_attr( $doli_url ); ?>/product/card.php?id=<?php echo $product->data['external_id']; ?>" target="_blank" title="<?php esc_attr_e( 'Edit in Dolibarr', 'wpshop' ); ?>" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 1px solid #1897e7; padding: 5px 10px; border-radius: 3px; color: #333; background: #fff;">
				<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg'; ?>" style="width: 20px; height: 20px; border-radius: 50%;" />
				<strong>#<?php echo esc_html( $product->data['external_id'] ); ?></strong>
				<i class="fas fa-pen" style="color: #333;"></i>
			</a>
			<?php else : ?>
			<div class="wps-badge-link" style="display: inline-flex; align-items: center; gap: 8px; border: 1px solid #ccc; padding: 5px 10px; border-radius: 3px; color: #999; background: #f9f9f9; cursor: not-allowed;">
				<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg'; ?>" style="width: 20px; height: 20px; border-radius: 50%; filter: grayscale(100%); opacity: 0.5;" />
				<strong>N/A</strong>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
	// On conserve le hook pour afficher le statut de synchro (pastille couleur) s'il y a d'autres éléments branchés dessus,
	// mais on le place dans un div caché si on ne veut pas l'afficher sous forme de table-cell qui casse le layout.
	// Idéalement, le statut de synchro devrait être géré proprement hors d'un tableau HTML.
	?>
	<div style="display: none;">
		<?php do_action( 'wps_listing_table_end', $product, $sync_status ); ?>
	</div>
</div>
