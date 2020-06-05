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

<div class="wps-metabox gridw-1 wps-customer-details">
	<h3 class="metabox-title"><?php esc_html_e( 'Details', 'wpshop' ); ?></h3>
	<h3><?php esc_html_e( 'Payment', 'wpshop' ); ?></h3>
	<p><strong><?php esc_html_e( 'Payment method', 'wpshop' ); ?></strong> : <?php echo esc_html( Payment::g()->get_payment_title( $proposal->data['payment_method'] ) ); ?></p>
	<p><strong><?php esc_html_e( 'Payment status', 'wpshop' ); ?></strong> : <?php echo Doli_Statut::g()->display_status( $proposal ); ?></p>
</div>
