<?php
/**
 * La vue affichant l'état de synchronisation d'une entité dans les listing.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.1
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var mixed   $object          Les données d'une entité.
 * @var boolean $can_sync        True si on peut faire une synchronisation.
 * @var string  $message_tooltip Le message de la tooltip.
 */
?>
<?php
$wp_url = '';
$doli_card_url = '';
$dolibarr_option = get_option( 'wps_dolibarr' );
$dolibarr_url = ! empty( $dolibarr_option['dolibarr_url'] ) ? rtrim($dolibarr_option['dolibarr_url'], '/') : '';

if ( ! empty( $object->data['id'] ) ) {
	switch ( $type ) {
		case 'wps-product':
			$wp_url = admin_url( 'post.php?action=edit&post=' . $object->data['id'] );
			if ( ! empty( $object->data['external_id'] ) ) {
				$doli_card_url = $dolibarr_url . '/product/card.php?id=' . $object->data['external_id'];
			}
			break;
		case 'wps-user':
		case 'wps-third-party':
			$wp_url = admin_url( 'admin.php?page=wps-third-party&id=' . $object->data['id'] );
			if ( ! empty( $object->data['external_id'] ) ) {
				$doli_card_url = $dolibarr_url . '/societe/card.php?socid=' . $object->data['external_id'];
			}
			break;
		case 'wps-order':
			$wp_url = admin_url( 'admin.php?page=wps-order&id=' . $object->data['id'] );
			if ( ! empty( $object->data['external_id'] ) ) {
				$doli_card_url = $dolibarr_url . '/commande/card.php?id=' . $object->data['external_id'];
			}
			break;
		case 'wps-invoice':
			$wp_url = admin_url( 'admin.php?page=wps-invoice&id=' . $object->data['id'] );
			if ( ! empty( $object->data['external_id'] ) ) {
				$doli_card_url = $dolibarr_url . '/compta/facture/card.php?facid=' . $object->data['external_id'];
			}
			break;
		case 'wps-proposal':
			$wp_url = admin_url( 'admin.php?page=wps-proposal&id=' . $object->data['id'] );
			if ( ! empty( $object->data['external_id'] ) ) {
				$doli_card_url = $dolibarr_url . '/comm/propal/card.php?id=' . $object->data['external_id'];
			}
			break;
		default:
			$wp_url = admin_url( 'post.php?action=edit&post=' . $object->data['id'] );
			break;
	}
}
?>
<div class="table-cell table-200 wps-sync-container">
	<div style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; font-size: 13px;">
		
		<div style="display: inline-flex; align-items: center; gap: 4px;">
			<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-wordpress.jpg'; ?>" style="width: 14px; height: 14px; border-radius: 50%;" />
			<strong>
				<?php if ( ! empty( $wp_url ) ) : ?>
					<a href="<?php echo esc_attr( $wp_url ); ?>" style="text-decoration: none; color: #333;" target="_blank">#<?php echo esc_html( $object->data['id'] ); ?></a>
				<?php else : ?>
					#<?php echo esc_html( $object->data['id'] ); ?>
				<?php endif; ?>
			</strong>
		</div>

		<div style="display: inline-flex; align-items: center; gap: 4px;">
			<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg'; ?>" style="width: 14px; height: 14px; border-radius: 50%; <?php echo empty( $object->data['external_id'] ) ? 'filter: grayscale(100%); opacity: 0.5;' : ''; ?>" />
			<strong>
				<?php if ( ! empty( $doli_card_url ) && ! empty( $object->data['external_id'] ) ) : ?>
					<a href="<?php echo esc_attr( $doli_card_url ); ?>" style="text-decoration: none; color: #333;" target="_blank">#<?php echo esc_html( $object->data['external_id'] ); ?></a>
				<?php else : ?>
					#<?php echo ! empty( $object->data['external_id'] ) ? esc_html( $object->data['external_id'] ) : "N/A"; ?>
				<?php endif; ?>
			</strong>
		</div>

		<div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 20px;">
			<div class="button-synchro <?php echo $can_sync ? 'action-attribute' : 'wpeo-modal-event'; ?>"
				 style="cursor: pointer; color: #666; transition: color 0.2s;"
				 data-class="synchro-single wpeo-wrap"
				<?php // translators: Associate and synchronize object name. ?>
				 data-title="<?php printf( __( 'Associate and synchronize %s', 'wpshop' ), $title ); ?>"
				 data-action="<?php echo $can_sync ? 'sync_entry' : 'load_associate_modal'; ?>"
				 data-wp-id="<?php echo esc_attr( $object->data['id'] ); ?>"
				 data-entry-id="<?php echo esc_attr( $object->data['external_id'] ); ?>"
				 data-type="<?php echo esc_attr( $type ); ?>"
				 data-nonce="<?php echo esc_attr( wp_create_nonce( $can_sync ? 'sync_entry' : 'load_associate_modal' ) ); ?>">
				 <i class="fas fa-sync" onmouseover="this.style.color='#1897e7';" onmouseout="this.style.color='#666';"></i>
			</div>

			<?php 
			$sync_settings = get_option( 'wps_sync_settings', array() );
			$bg_color = '#ececec';
			if ( $status_color === 'green' ) { $bg_color = ! empty( $sync_settings['color_ok'] ) ? $sync_settings['color_ok'] : '#47e58e'; }
			elseif ( $status_color === 'red' ) { $bg_color = ! empty( $sync_settings['color_error'] ) ? $sync_settings['color_error'] : '#e05353'; }
			elseif ( $status_color === 'orange' ) { $bg_color = ! empty( $sync_settings['color_orange'] ) ? $sync_settings['color_orange'] : '#e9ad4f'; }
			?>
			<div class="statut wpeo-tooltip-event" data-direction="left" aria-label="<?php echo esc_attr( $message_tooltip ); ?>" style="width: 12px; height: 12px; border-radius: 50%; background-color: <?php echo esc_attr( $bg_color ); ?>; cursor: help; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
		</div>

	</div>
</div>

