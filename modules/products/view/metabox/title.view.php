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

			<?php 
			// Ajout du statut de synchronisation
			if ( \wpshop\Settings::g()->dolibarr_is_active() && class_exists('\wpshop\Doli_Sync') ) {
				$status_color = 'grey';
				$message_tooltip = __( 'Looking for sync status', 'wpshop' );
				$can_sync = ! empty( $product->data['external_id'] );
				
				if ( ! $can_sync ) {
					$message_tooltip = __('No associated to an ERP Entity', 'wpshop');
				} else {
					$response = \wpshop\Doli_Sync::g()->check_status( $product->data['id'], 'wps-product' );
					if ( $response && $response['status'] ) {
						switch ( $response['status_code'] ) {
							case '0x0':
								$status_color = 'green';
								break;
							case '0x3':
								$status_color = 'red';
								break;
							case '0x4':
								$status_color = 'orange';
								break;
						}
						$message_tooltip = isset( $response['status_message'] ) ? $response['status_message'] : __( 'Error not defined', 'wpshop' );
					}
				}
				
				$sync_settings = get_option( 'wps_sync_settings', array() );
				$bg_color = '#ececec';
				if ( $status_color === 'green' ) { $bg_color = ! empty( $sync_settings['color_ok'] ) ? $sync_settings['color_ok'] : '#47e58e'; }
				elseif ( $status_color === 'red' ) { $bg_color = ! empty( $sync_settings['color_error'] ) ? $sync_settings['color_error'] : '#e05353'; }
				elseif ( $status_color === 'orange' ) { $bg_color = ! empty( $sync_settings['color_orange'] ) ? $sync_settings['color_orange'] : '#e9ad4f'; }
				?>
				<div class="button-synchro <?php echo $can_sync ? 'action-attribute' : 'wpeo-modal-event'; ?>"
					 style="cursor: pointer; color: #666; transition: color 0.2s;"
					 data-class="synchro-single wpeo-wrap"
					 data-title="<?php printf( __( 'Associate and synchronize %s', 'wpshop' ), esc_attr( $product->data['title'] ) ); ?>"
					 data-action="<?php echo $can_sync ? 'sync_entry' : 'load_associate_modal'; ?>"
					 data-wp-id="<?php echo esc_attr( $product->data['id'] ); ?>"
					 data-entry-id="<?php echo esc_attr( $product->data['external_id'] ); ?>"
					 data-type="wps-product"
					 data-nonce="<?php echo esc_attr( wp_create_nonce( $can_sync ? 'sync_entry' : 'load_associate_modal' ) ); ?>">
					 <i class="fas fa-sync" onmouseover="this.style.color='#1897e7';" onmouseout="this.style.color='#666';"></i>
				</div>
				<div class="wpeo-tooltip-event" data-direction="left" aria-label="<?php echo esc_attr( $message_tooltip ); ?>" style="width: 14px; height: 14px; border-radius: 50%; background-color: <?php echo esc_attr( $bg_color ); ?>; margin-left: 5px; cursor: help; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
				<?php
			}
			?>
		</div>
	</div>

	<div class="wps-product-description-container" style="margin-top: 20px;">
		<h4 style="margin: 0 0 10px 0; font-size: 1.1em; color: #333; font-weight: normal;"><?php esc_html_e( 'Description', 'wpshop' ); ?></h4>
		<div class="wps-product-description" style="border: 1px solid #ddd; padding: 10px 15px; border-radius: 3px; background: #fafafa; color: #555; max-height: 250px; overflow-y: auto;">
			<?php 
			if ( ! empty( $product->data['content'] ) ) {
				echo wp_kses_post( $product->data['content'] ); 
			} else {
				echo '<p style="font-style: italic; color: #999; margin: 0;">' . esc_html__( 'No description.', 'wpshop' ) . '</p>';
			}
			?>
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

