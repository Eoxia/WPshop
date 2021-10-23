<?php
/**
 * La vue affichant la métabox des informations du produit sur une facture.
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
 * @var Doli_Invoice $invoice   Les données d'une facture.
 * @var array        $line      Le tableau contenant toutes les données d'une ligne.
 * @var array        $tva_lines Le tableau contenant toutes les données des tva.
 * @var string       $key       A faire.
 * @var integer      $tva_line  Les données d'une tva.
 */
?>

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
			<?php if ( ! empty( $invoice->data['lines'] ) ) :
				foreach ( $invoice->data['lines'] as $line ) : ?>
					<tr>
						<td>
							<?php if ( ! empty( $line['fk_product'] ) ) :
								echo $line['libelle'];
							else :
								echo $line['desc'];
							endif; ?>
						</td>
						<td><?php echo number_format( $line['tva_tx'], 2, ',', '' ); ?>%</td>
						<td>
							<?php if ( ! empty( $line['fk_product'] ) ) :
								echo number_format( $line['price'], 2, ',', '' );
								?>
								<span>€</span>
								<?php
							else :
								echo number_format( $line['subprice'], 2, ',', '' );
								?>
								<span>€</span>
							<?php endif; ?>
						</td>
						<td><?php echo $line['qty']; ?></td>
						<td>
							<?php echo number_format( $line['total_ht'], 2, ',', '' ); ?>€
						</td>
					</tr>
				<?php endforeach;
			endif; ?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="4"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
				<td><?php echo number_format( $invoice->data['total_ht'], 2, ',', '' ); ?>€</td>
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
				<td colspan="4"><strong><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></strong></td>
				<td><strong><?php echo number_format( $invoice->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
			</tr>
		</tfoot>
	</table>
</div>
