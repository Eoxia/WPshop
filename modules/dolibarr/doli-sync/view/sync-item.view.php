<?php
/**
 * La vue affichant l'état de synchronisation d'une entité dans les listing.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
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
	<div class="wps-sync-container">
		<ul class="reference-id">
			<li><img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-wordpress.jpg'; ?>" /> <strong><i class="fas fa-hashtag"></i><?php echo esc_html( $object->data['id'] ); ?></strong></li>
			<li><img src="<?php echo PLUGIN_WPSHOP_URL . '/core/asset/image/logo-dolibarr.jpg'; ?>" /> <strong><i class="fas fa-hashtag"></i><?php echo ! empty( $object->data['external_id'] ) ? esc_html( $object->data['external_id'] ) : "N/A"; ?></strong></li>
		</ul>
		<div class="sync-action">
			<div class="button-synchro <?php echo $can_sync ? 'action-attribute' : 'wpeo-modal-event'; ?>"
				 data-class="synchro-single wpeo-wrap"
				<?php // translators: Associate and synchronize object name. ?>
				 data-title="<?php printf( __( 'Associate and synchronize %s', 'wpshop' ), $title ); ?>"
				 data-action="<?php echo $can_sync ? 'sync_entry' : 'load_associate_modal'; ?>"
				 data-wp-id="<?php echo esc_attr( $object->data['id'] ); ?>"
				 data-entry-id="<?php echo esc_attr( $object->data['external_id'] ); ?>"
				 data-type="<?php echo esc_attr( $type ); ?>"
				 data-nonce="<?php echo esc_attr( wp_create_nonce( $can_sync ? 'sync_entry' : 'load_associate_modal' ) ); ?>"><i class="fas fa-sync"></i></div>

			<div class="statut statut-<?php echo esc_attr( $status_color ); ?> wpeo-tooltip-event" data-direction="left" aria-label="<?php echo esc_html( $message_tooltip ); ?>"></div>
		</div>
	</div>
</div>
