<?php
/**
 * Affichage des détails de paiement la commande
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

defined( 'ABSPATH' ) || exit; ?>

<div class="wps-metabox wps-order-related-object">
	<h3 class="metabox-title"><?php esc_html_e( 'Related object', 'wpshop' ); ?></h3>

	<table class="wpeo-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Type', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Ref', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'State', 'wpshop' ); ?></th>
			</tr>
		</thead>

		<tbody>

		</tbody>
	</table>
</div>
