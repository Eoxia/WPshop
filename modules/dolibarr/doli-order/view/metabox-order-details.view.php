<?php
/**
 * Affichage des détails de la commande
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

<div class="wps-metabox gridw-2 wps-customer-address">
	<h3 class="metabox-title"><?php esc_html_e( 'Order details', 'wpshop' ); ?></h3>

	<div class="wpeo-gridlayout grid-3">
		<div>
			<h4 class="metabox-list-title"><i class="fas fa-building"></i> <?php esc_html_e( 'Informations', 'wpshop' ); ?></h4>
			<ul class="metabox-list">
				<li><span><?php esc_html_e( 'Date', 'wpshop' ); ?></span> <?php echo esc_html( $order->data['datec']['date_time'] ); ?></li>
				<li><span><?php esc_html_e( 'Customer', 'wpshop' ); ?></span> <a href="<?php echo admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ); ?>"><?php echo esc_html( $third_party->data['title'] ); ?></a></li>
				<li><span><?php esc_html_e( 'Phone', 'wpshop' ); ?></span> <?php echo esc_html( $third_party->data['phone'] ); ?></li>
				<li><span><?php esc_html_e( 'Order status', 'wpshop' ); ?></span> <?php echo Doli_Statut::g()->display_status( $order ); ?></li>
				<li><span><?php esc_html_e( 'Payment method', 'wpshop' ); ?></span> <?php echo esc_html( Payment::g()->get_payment_title( $order->data['payment_method'] ) ); ?></li>
			</ul>
		</div>

		<div>
			<h4 class="metabox-list-title"><i class="fas fa-file-invoice-dollar"></i> <?php esc_html_e( 'Billing contact', 'wpshop' ); ?></h4>
			<ul class="metabox-list">
				<li><span><?php esc_html_e( 'Name', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['title'] ) ? $third_party->data['title'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'Address', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['contact'] ) ? $third_party->data['contact'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'ZIP Code', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['zip'] ) ? $third_party->data['zip'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'City', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['town'] ) ? $third_party->data['town'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'Country', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['country'] ) ? $third_party->data['country'] : 'N/D'; ?></li>
			</ul>
		</div>

		<div>
			<h4 class="metabox-list-title"><i class="fas fa-truck"></i> <?php esc_html_e( 'Shipment contact', 'wpshop' ); ?></h4>
			<ul class="metabox-list">
				<li><span><?php esc_html_e( 'Name', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['title'] ) ? $third_party->data['title'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'Address', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['contact'] ) ? $third_party->data['contact'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'ZIP Code', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['zip'] ) ? $third_party->data['zip'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'City', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['town'] ) ? $third_party->data['town'] : 'N/D'; ?></li>
				<li><span><?php esc_html_e( 'Country', 'wpshop' ); ?></span> <?php echo ! empty( $third_party->data['country'] ) ? $third_party->data['country'] : 'N/D'; ?></li>
			</ul>
		</div>
	</div>
</div>
