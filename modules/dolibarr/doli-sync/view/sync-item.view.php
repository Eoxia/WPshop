<?php
/**
 * Ajoutes l'état de synchronisation d'une entité dans les listing.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

/**
 * @var mixed $object Object can be Product/Propal/Third_Party.
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="table-cell table-100 wps-sync">
	<ul class="reference-id">
		<li><i class="fas fa-hashtag"></i>WP : <?php echo esc_html( $object->data['id'] ); ?></li>
		<li><i class="fas fa-hashtag"></i>Doli : <?php echo ! empty( $object->data['external_id'] ) ? esc_html( $object->data['external_id'] ) : "N/A"; ?></li>
	</ul>
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
