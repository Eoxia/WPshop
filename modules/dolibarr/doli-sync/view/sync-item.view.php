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
 * @var string  $title           Le titre d'une synchronisation.
 * @var string  $type            Le type d'entité.
 * @var string  $status_color    La couleur du statut d'une synchronisation.
 * @var string  $message_tooltip Le message de la tooltip.
 */
?>
<div class="table-cell table-200 wps-sync">
	<div style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; font-size: 13px;">
		
		<div style="display: inline-flex; align-items: center; gap: 4px;">
			<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-wordpress.jpg'; ?>" style="width: 14px; height: 14px; border-radius: 50%;" />
			<strong>#<?php echo esc_html( $object->data['id'] ); ?></strong>
		</div>

		<div style="display: inline-flex; align-items: center; gap: 4px;">
			<img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg'; ?>" style="width: 14px; height: 14px; border-radius: 50%; <?php echo empty( $object->data['external_id'] ) ? 'filter: grayscale(100%); opacity: 0.5;' : ''; ?>" />
			<strong>#<?php echo ! empty( $object->data['external_id'] ) ? esc_html( $object->data['external_id'] ) : "N/A"; ?></strong>
		</div>

		<div style="display: inline-flex; align-items: center; gap: 6px; margin-left: auto;">
			<?php if ( $status_color != 'green' ) : ?>
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
			<?php endif; ?>

			<?php 
			$bg_color = '#ececec'; // grey
			if ( $status_color === 'green' ) { $bg_color = '#47e58e'; }
			elseif ( $status_color === 'red' ) { $bg_color = '#e05353'; }
			elseif ( $status_color === 'orange' ) { $bg_color = '#e9ad4f'; }
			?>
			<div class="statut wpeo-tooltip-event" data-direction="left" aria-label="<?php echo esc_attr( $message_tooltip ); ?>" style="width: 12px; height: 12px; border-radius: 50%; background-color: <?php echo esc_attr( $bg_color ); ?>; cursor: help; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
		</div>

	</div>
</div>
