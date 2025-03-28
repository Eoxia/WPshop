<?php
/**
 * Affichage des détails de la commande
 *
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <technique@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wps-metabox gridw-2 wps-customer-address">
	<h3 class="metabox-title"><?php esc_html_e( 'Customer contact', 'wpshop' ); ?></h3>
	<div class="wpeo-gridlayout grid-3">
		<div>
			<ul>
				<li><strong><?php esc_html_e( 'Customer', 'wpshop' ); ?></strong> : <a href="<?php echo admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ); ?>"><?php echo $third_party->data['title']; ?></a></li>
			<?php
			if ( ! empty( $invoice->data['payments'] ) ) :
				?>
				<li>
					<?php
					foreach ( $invoice->data['payments'] as $payment ) :
						?>
						<ul>
							<li><?php esc_html_e( 'Payment method', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['payment_type'] ); ?></li>
							<li><?php esc_html_e( 'Payment date', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['date']['rendered']['date_human_readable'] ); ?></li>
							<li><?php esc_html_e( 'Payment reference', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['title'] ); ?></li>
							<li><?php esc_html_e( 'Amount', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['amount'] ); ?>€</li>
						</ul>
						<?php
					endforeach;
					?>
				</li>
				<?php
			endif;
			?>
		</div>
		<div>
			<strong><?php esc_html_e( 'Billing', 'wpshop' ); ?></strong>

			<ul>
				<li><?php esc_html_e( 'No billing contact', 'wpshop' ); ?></li>
			</ul>
		</div>
		<div>
			<strong><?php esc_html_e( 'Shipment', 'wpshop' ); ?></strong>

			<ul>
				<li><?php echo ! empty( $third_party->data['title'] ) ? $third_party->data['title'] : 'N/D'; ?></li>
				<li><?php echo ! empty( $third_party->data['contact'] ) ? $third_party->data['contact'] : 'N/D'; ?></li>
				<li>
					<?php echo ! empty( $third_party->data['zip'] ) ? $third_party->data['zip'] : 'N/D'; ?>
					<?php echo ! empty( $third_party->data['town'] ) ? $third_party->data['town'] : 'N/D'; ?>
				</li>
				<li>
					<strong><?php esc_html_e( 'Phone number', 'wpshop' ); ?> :</strong>
					<p><?php echo ! empty( $third_party->data['phone'] ) ? $third_party->data['phone'] : 'N/D'; ?></p>
				</li>
			</ul>
		</div>
	</div>
</div>
