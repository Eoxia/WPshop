<?php
/**
 * La vue affichant la métabox des détails d'une facture.
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
 * @var Doli_Invoice $invoice     Les données d'une facture.
 * @var Third_Party  $third_party Les données d'un tier.
 * @var Doli_Payment $payment     Les données d'un paiement.
 */
?>

<div class="wps-metabox gridw-1 wps-customer-payment">
	<h3 class="metabox-title"><?php esc_html_e( 'Payment', 'wpshop' ); ?></h3>
	<p><strong><?php esc_html_e( 'Order', 'wpshop' ); ?></strong> : <?php echo ! empty( $invoice->data['order'] ) ? '<a href="' . admin_url( 'admin.php?page=wps-order&id=' . $invoice->data['order']->data['external_id'] ) . '">' . $invoice->data['order']->data['title'] . '</a>' : '-'; ?>
	<p><strong><?php esc_html_e( 'Statut', 'wpshop' ); ?></strong> : <?php echo Doli_Statut::g()->display_status( $invoice ); ?></p>
	<p><strong><?php esc_html_e( 'Payment method', 'wpshop' ); ?></strong> : <?php echo esc_html( Payment::g()->get_payment_title( $invoice->data['payment_method'] ) ); ?></p>
	<p><strong><?php esc_html_e( 'Payment status', 'wpshop' ); ?></strong> : <?php echo Payment::g()->make_readable_statut( $invoice ); ?></p>
</div>

<div class="wps-metabox gridw-3 wps-customer-address">
	<h3><?php esc_html_e( 'Customer contact', 'wpshop' ); ?></h3>

	<div class="wpeo-gridlayout grid-3">
		<div>
			<ul>
				<li><strong><?php esc_html_e( 'Customer', 'wpshop' ); ?></strong> : <a href="<?php echo admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ); ?>"><?php echo $third_party->data['title']; ?></a></li>
			<?php if ( ! empty( $invoice->data['payments'] ) ) : ?>
				<li>
					<?php foreach ( $invoice->data['payments'] as $payment ) : ?>
						<ul>
							<li><?php esc_html_e( 'Payment method', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['payment_type'] ); ?></li>
							<li><?php esc_html_e( 'Payment date', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['date']['rendered']['date_human_readable'] ); ?></li>
							<li><?php esc_html_e( 'Payment reference', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['title'] ); ?></li>
							<li><?php esc_html_e( 'Amount', 'wpshop' ); ?> : <?php echo esc_html( $payment->data['amount'] ); ?>€</li>
						</ul>
					<?php endforeach; ?>
				</li>
			<?php endif; ?>
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
				<li><?php echo ! empty( $third_party->data['address'] ) ? $third_party->data['address'] : 'N/D'; ?></li>
				<li>
					<?php echo ! empty( $third_party->data['zip'] ) ? $third_party->data['zip'] : 'N/D'; ?>
					<?php echo ! empty( $third_party->data['town'] ) ? $third_party->data['town'] : 'N/D'; ?>
					<?php echo ! empty( $third_party->data['country'] ) ? $third_party->data['country'] : 'N/D'; ?>
				</li>
				<li>
					<strong><?php esc_html_e( 'Phone number', 'wpshop' ); ?> :</strong>
					<p><?php echo ! empty( $third_party->data['phone'] ) ? $third_party->data['phone'] : 'N/D'; ?></p>
				</li>
			</ul>
		</div>
	</div>
</div>
