<?php
/**
 * La vue affichant la metabox des commandes dans le tableau de bord.
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
 * @var string     $dolibarr_url          L'url de dolibarr.
 * @var string     $dolibarr_orders_lists L'url de la liste des commandes sur dolibarr.
 * @var array      $orders                Le tableau contenant toutes les données des commandes.
 * @var Doli_Order $order                 Les données d'une commande.
 */
?>

<div class="wps-dashboard-card gridw-3">
	<div class="wps-dashboard-card-header">
		<h3 class="wps-dashboard-card-title">
			<?php esc_html_e( 'Latest orders', 'wpshop' ); ?>
			<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_orders_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>
		</h3>
	</div>

	<table class="wps-dashboard-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Order #', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Customer', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$total_amount = 0;
			if ( ! empty( $orders ) ) :
				foreach ( $orders as $order ) : 
					$total_amount += (float) $order->data['total_ttc'];
					?>
					<tr>
						<td>
							<a href="<?php echo esc_attr( $dolibarr_url . '/commande/card.php?id=' . $order->data['external_id'] ); ?>">
								<?php echo esc_html( $order->data['title'] ); ?>
							</a>
						</td>
						<td>
							<i class="far fa-building wps-icon-customer"></i>
							<?php if ( ! empty( $order->data['third_party']->data['id'] ) ): ?>
								<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $order->data['third_party']->data['id'] ) ); ?>"><?php echo esc_html( $order->data['third_party']->data['title'] ); ?></a>
							<?php else : ?>
								<?php esc_html_e('unknown', 'wpshop' ); ?>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( number_format( $order->data['total_ttc'], 2, ',', ' ' ) ); ?> €</td>
						<td><?php echo esc_html( date( 'd/m/Y', strtotime( $order->data['datec'] ) ) ); ?></td>
						<td>
							<span class="wps-status-dot filled-gold"></span>
						</td>
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td colspan="5" style="text-align: center; color: #999;">
						<?php esc_html_e( 'No order for the moment', 'wpshop' ); ?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
		<?php if ( ! empty( $orders ) ) : ?>
		<tfoot>
			<tr class="wps-dashboard-total-row">
				<td colspan="2">Total</td>
				<td colspan="3"><?php echo esc_html( number_format( $total_amount, 2, ',', ' ' ) ); ?> €</td>
			</tr>
		</tfoot>
		<?php endif; ?>
	</table>
</div>
