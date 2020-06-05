<?php
/**
 * Le footer de la modal de synchronisation.
 *
 * @todo: Translate to english.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019-2020 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
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
