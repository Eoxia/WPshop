<?php
/**
 * Metabox des commandes dans le dashboard
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

<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Last Payments', 'wpshop' ); ?></h3>
	<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_payments_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>

	<div class="wpeo-table table-flex table-5">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell"><?php esc_html_e( 'Invoice', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Payment method', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
		</div>

		<?php
		if ( ! empty( $payments ) ) :
			foreach ( $payments as $payment ) :
				?>
				<div class="table-row">
					<div class="table-cell"><?php echo esc_html( $payment->data['title'] ); ?></div>
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-invoice&id=' . $payment->data['invoice']->data['id'] ) ); ?>"><?php echo esc_html( $payment->data['invoice']->data['title'] ); ?></a></div>
					<div class="table-cell"><?php echo esc_html( $payment->data['payment_type'] ); ?></div>
					<div class="table-cell"><?php echo esc_html( number_format( $payment->data['amount'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><?php echo esc_html( Date_util::readable_date( $payment->data['date'], 'date_time' ) ); ?></div>
				</div>
				<?php
			endforeach;
		else :
			?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No payments for now', 'wpshop' ); ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
