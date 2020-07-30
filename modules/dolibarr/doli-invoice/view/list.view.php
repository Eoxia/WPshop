<?php
/**
 * La vue affichant la liste des factures dans le backend.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array        $invoices Le tableau contanant toutes les données des factures.
 * @var Doli_Invoice $invoice  Les données d'une facture.
 * @var string       $doli_url L'url de Dolibarr.
 */
?>

<div class="wps-list-product wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-25"><input type="checkbox" class="check-all"/></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Invoice reference', 'wpshop' ); ?></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Order reference', 'wpshop' ); ?></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Billing', 'wpshop' ); ?></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Statut', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Method of payment', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
	</div>

	<?php if ( ! empty( $invoices ) ) :
		foreach ( $invoices as $invoice ) :
			View_Util::exec( 'wpshop', 'doli-invoice', 'item', array(
				'invoice'  => $invoice,
				'doli_url' => $doli_url,
			) );
		endforeach;
	endif; ?>
</div>
