<?php
/**
 * La vue affichant la metabox des paiements dans le tableau de bord.
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
 * @var string       $dolibarr_url            L'url de dolibarr.
 * @var string       $dolibarr_payments_lists L'url de la liste des paiements sur dolibarr.
 * @var array        $payments                Le tableau contenant toutes les données des paiements.
 * @var Doli_Payment $payment                 Les données d'un paiement.
 */
?>

<div class="wps-dashboard-card gridw-3">
	<div class="wps-dashboard-card-header">
		<h3 class="wps-dashboard-card-title">
			<?php esc_html_e( 'Latest payments', 'wpshop' ); ?>
			<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_payments_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>
		</h3>
	</div>

	<table class="wps-dashboard-table">
		<thead>
			<tr>
				<th>#</th>
				<th><?php esc_html_e( 'Invoice', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Payment method', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $payments ) ) :
				foreach ( $payments as $payment ) : ?>
					<tr>
						<td><?php echo esc_html( $payment->data['title'] ); ?></td>
						<td>
							<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-invoice&id=' . $payment->data['invoice']->data['id'] ) ); ?>">
								<?php echo esc_html( $payment->data['invoice']->data['title'] ); ?>
							</a>
						</td>
						<td><?php echo esc_html( $payment->data['payment_type'] ); ?></td>
						<td><?php echo esc_html( number_format( $payment->data['amount'], 2, ',', ' ' ) ); ?> €</td>
						<td><?php echo esc_html( Date_util::readable_date( $payment->data['date'], 'date_time' ) ); ?></td>
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td colspan="5" style="text-align: center; color: #999;">
						<?php esc_html_e( 'No payment for the moment', 'wpshop' ); ?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
