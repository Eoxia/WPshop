<?php
/**
 * Affichage du listing des commandes dans le backend.
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

<div class="wps-list-product wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-25"><input type="checkbox" class="check-all"/></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Invoice reference', 'wpshop' ); ?></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Order reference', 'wpshop' ); ?></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Billing', 'wpshop' ); ?></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Statut', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Method of payment', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Synchro', 'wpshop' ); ?></div>
	</div>

	<?php
	if ( ! empty( $invoices ) ) :
		foreach ( $invoices as $invoice ) :
			\eoxia\View_Util::exec( 'wpshop', 'doli-invoice', 'item', array(
				'invoice'  => $invoice,
				'doli_url' => $doli_url,
			) );
		endforeach;
	endif;
	?>
</div>
