<?php
/**
 * La vue affichant le footer de la modal de synchronisation.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-button button-light modal-close">
	<span><?php esc_html_e( 'Cancel', 'wpshop' ); ?></span>
</div>

<div class="wpeo-button button-main action-input"
	data-action="load_compare_modal"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_compare_modal' ) ); ?>"
	data-parent="wpeo-modal">
	<span><?php esc_html_e( 'Compare elements', 'wpshop' ); ?></span>
</div>
