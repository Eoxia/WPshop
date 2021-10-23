<?php
/**
 * La vue affichant les détails de la commande.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Order $order     Les données d'une commande.
 * @var string     $line      La ligne dans une commande.
 * @var array      $tva_lines Le tableau contenant toutes les données des tva.
 * @var string     $key       A faire.
 * @var integer    $tva_line  Les données d'une tva.
 */
?>

<?php do_action( 'wps_email_before_order_table', $order ); ?>

<h2>
	<?php
	/* translators: %s: Order ID. */
	echo wp_kses_post( sprintf( __( 'Order #%1$s (<time datetime="%2$s">%3$s</time>)', 'wpshop' ), $order->ref, date( 'd/m/Y H:i:s', $order->date_modification ), date( 'd/m/Y H:i:s', $order->date_modification ) ) );
	?>
</h2>

<table style="width: 100%;">
	<thead>
		<tr>
			<th style="text-align: left;" data-title="<?php esc_html_e( 'Product name', 'wpshop' ); ?>"><?php esc_html_e( 'Product name', 'wpshop' ); ?></th>
			<th style="text-align: left;" data-title="<?php esc_html_e( 'VAT', 'wpshop' ); ?>"><?php esc_html_e( 'VAT', 'wpshop' ); ?></th>
			<th style="text-align: left;" data-title="<?php esc_html_e( 'P.U. HT', 'wpshop' ); ?>"><?php esc_html_e( 'P.U HT', 'wpshop' ); ?></th>
			<th style="text-align: left;" data-title="<?php esc_html_e( 'Quantity', 'wpshop' ); ?>"><?php esc_html_e( 'Quantity', 'wpshop' ); ?></th>
			<th style="text-align: left;" data-title="<?php esc_html_e( 'Total HT', 'wpshop' ); ?>"><?php esc_html_e( 'Total HT', 'wpshop' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( ! empty( $order->lines ) ) :
			foreach ( $order->lines as $line ) : ?>
				<tr>
					<td><a href="<?php echo esc_url( get_permalink( $line->wp_id ) ); ?>"><?php echo esc_html( $line->libelle ); ?></a></td>
					<td><?php echo esc_html( number_format( $line->tva_tx, 2, ',', '' ) ); ?>%</td>
					<td><?php echo esc_html( number_format( $line->price, 2, ',', '' ) ); ?>€</td>
					<td><?php echo esc_html( $line->qty ); ?></td>
					<td><?php echo esc_html( number_format( $line->price * $line->qty, 2, ',', '' ) ); ?>€</td>
				</tr>
			<?php endforeach;
		endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
			<td><?php echo number_format( $order->total_ht, 2, ',', '' ); ?>€</td>
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
			<td><strong><?php echo number_format( $order->total_ttc, 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>

<strong><a href="<?php echo esc_attr( Pages::g()->get_account_link() ); ?>"><?php esc_html_e( 'See my orders or my downloadable products', 'wpshop' ); ?></a></strong>

<?php do_action( 'wps_email_after_order_table', $order ); ?>
