<?php
/**
 * La vue principale de la page des produits (wps-third-party)
 *
 * @package   WPshop\Templates
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<table class="wpeo-table <?php echo apply_filters( 'wps_review_order_table_class', '', $object ); ?>">
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
		if ( ! empty( $object->data['lines'] ) ) :
			foreach ( $object->data['lines'] as $line ) :
				?>
				<tr>
					<td><?php echo $line['libelle']; ?></td>
					<td><?php echo number_format( $line['tva_tx'], 2, ',', '' ); ?>%</td>
					<td><?php echo number_format( $line['subprice'], 2, ',', '' ); ?>€</td>
					<td><?php echo $line['qty']; ?></td>
					<td><?php echo number_format( $line['total_ht'], 2, ',', '' ); ?>€</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>

	<tfoot>
		<tr>
			<td colspan="4"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
			<td><?php echo number_format( $object->data['total_ht'], 2, ',', '' ); ?>€</td>
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
			<td><strong><?php echo number_format( $object->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>
