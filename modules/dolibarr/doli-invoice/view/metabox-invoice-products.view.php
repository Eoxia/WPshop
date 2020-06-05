<?php
/**
 * La vue principale de la page des produits (wps-third-party)
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

<div class="wps-metabox gridw-1 wps-customer-payment">
	<h3 class="metabox-title"><?php esc_html_e( 'Payment', 'wpshop' ); ?></h3>

	<table class="wpeo-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Product name', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'VAT', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'P.U HT', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Total HT', 'wpshop' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			if ( ! empty( $invoice->data['lines'] ) ) :
				foreach ( $invoice->data['lines'] as $line ) :
					?>
					<tr>
						<td>
							<?php
							if ( ! empty( $line['fk_product'] ) ) :
								echo $line['libelle'];
							else :
								echo $line['desc'];
							endif;
							?>
						</td>
						<td><?php echo number_format( $line['tva_tx'], 2, ',', '' ); ?>%</td>
						<td>
							<?php
							if ( ! empty( $line['fk_product'] ) ) :
								echo number_format( $line['price'], 2, ',', '' );
								?>
								<span>€</span>
								<?php
							else :
								echo number_format( $line['subprice'], 2, ',', '' );
								?>
								<span>€</span>
								<?php
							endif;
							?>
						</td>
						<td><?php echo $line['qty']; ?></td>
						<td>
							<?php echo number_format( $line['total_ht'], 2, ',', '' ); ?>€
						</td>
					</tr>
					<?php
				endforeach;
			endif;
			?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="4"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
				<td><?php echo number_format( $invoice->data['total_ht'], 2, ',', '' ); ?>€</td>
			</tr>
			<?php
			if ( ! empty( $tva_lines ) ) :
				foreach ( $tva_lines as $key => $tva_line ) :
					?>
					<tr>
						<td colspan="4"><strong><?php esc_html_e( 'Total VAT', 'wpshop' ); ?> <?php echo number_format( $key, 2, ',', '' ); ?>%</strong></td>
						<td><?php echo number_format( $tva_line, 2, ',', '' ); ?>€</td>
					</tr>
					<?php
				endforeach;
			endif;
			?>

			<tr>
				<td colspan="4"><strong>Total TTC</strong></td>
				<td><strong><?php echo number_format( $invoice->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
			</tr>
		</tfoot>
	</table>
</div>
