<?php
/**
 * La vue affichant les détails de paiement d'une commande.
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
 * @var array        $invoices           Le tableau contenant toutes Les données des factures.
 * @var Doli_Invoice $invoice            Les données d'une facture.
 * @var Doli_Payment $payment            Les données d'un paiement.
 * @var integer      $already_paid       Le montant déja payé.
 * @var integer      $total_ttc_invoices Le montant total TTC.
 * @var integer      $remaining_unpaid   Le montant restant à payer.
 */
?>

<div class="wps-metabox wps-order-payment gridw-2">
	<h3 class="metabox-title"><?php esc_html_e( 'Payments', 'wpshop' ); ?></h3>

	<table class="wpeo-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Payments', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Type', 'wpshop' ); ?></th>
				<th class="table-end"><?php esc_html_e( 'Amount TTC', 'wpshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php if ( ! empty( $invoices ) ) :
				foreach ( $invoices as $invoice ) :
					if ( ! empty( $invoice->data['payments'] ) ) :
						foreach ( $invoice->data['payments'] as $payment ) : ?>
							<tr>
								<td><?php echo esc_html( $payment->data['title'] ); ?></td>
								<td><?php echo esc_html( $payment->data['date']['rendered']['date'] ); ?></td>
								<td><?php echo esc_html( $payment->data['payment_type'] ); ?></td>
								<td class="table-end"><?php echo number_format( $payment->data['amount'], 2, ',', '' ); ?>€</td>
							</tr>
						<?php endforeach;
					endif;
				endforeach;
			endif; ?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="3"><?php esc_html_e( 'Already settled', 'wpshop' ); ?></td>
				<td class="table-end"><?php echo ( ! empty( $already_paid ) ) ? number_format( $already_paid, 2, ',', '' ) : '00,00'; ?>€</td>
			</tr>
			<tr>
				<td colspan="3"><?php esc_html_e( 'Billed','wpshop' ); ?></td>
				<td class="table-end"><?php echo ( ! empty( $total_ttc_invoices ) ) ? number_format( $total_ttc_invoices, 2, ',', '' ) : number_format( $order->data['total_ttc'], 2, ',', '' ); ?>€</td>
			</tr>
			<tr>
				<td colspan="3"><?php esc_html_e( 'Remaining unpaid', 'wpshop' ); ?></td>
				<td class="table-end"><strong><?php echo number_format( $remaining_unpaid, 2, ',', '' ); ?>€</strong></td>
			</tr>
		</tfoot>
	</table>
</div>
