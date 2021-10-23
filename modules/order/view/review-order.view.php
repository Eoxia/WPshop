<?php
/**
 * La vue affichant un résumé de la commande.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Object  $object    Les données d'un objet.
 * @var string  $line      La ligne dans une commande.
 * @var array   $tva_lines Le tableau contenant toutes les données des tva.
 * @var string  $key       A faire.
 * @var integer $tva_line  Les données d'une tva.
 */
?>

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
		<?php if ( ! empty( $object->data['lines'] ) ) :
			foreach ( $object->data['lines'] as $line ) : ?>
				<tr>
					<td><?php echo $line['libelle']; ?></td>
					<td><?php echo number_format( $line['tva_tx'], 2, ',', '' ); ?>%</td>
					<td><?php echo number_format( $line['subprice'], 2, ',', '' ); ?>€</td>
					<td><?php echo $line['qty']; ?></td>
					<td><?php echo number_format( $line['total_ht'], 2, ',', '' ); ?>€</td>
				</tr>
			<?php endforeach;
		endif; ?>
	</tbody>

	<tfoot>
		<tr>
			<td colspan="4"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
			<td><?php echo number_format( $object->data['total_ht'], 2, ',', '' ); ?>€</td>
		</tr>
		<?php if ( ! empty( $tva_lines ) ) :
			foreach ( $tva_lines as $key => $tva_line ) : ?>
				<tr>
					<td colspan="4"><strong><?php esc_html_e( 'Total VAT', 'wpshop' ); ?> <?php echo number_format( $key, 2, ',', '' ); ?>%</strong></td>
					<td><?php echo number_format( $tva_line, 2, ',', '' ); ?>€</td>
				</tr>
			<?php endforeach;
		endif; ?>
		<tr>
			<td colspan="4"><strong>Total TTC</strong></td>
			<td><strong><?php echo number_format( $object->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>
