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

<div class="wps-metabox gridw-1 wps-shipment-tracking">
	<h3 class="metabox-title"><?php esc_html_e( 'Shipment Tracking', 'wpshop' ); ?></h3>

	<div class="wpeo-form">
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Tracking URL', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<input type="text" name="tracking_url" class="form-field" value="<?php echo esc_html( $order->data['tracking_link'] ); ?>"/>
			</label>
		</div>

		<div>
			<div class="wpeo-button button-main action-input"
				data-parent="wps-shipment-tracking"
				data-action="mark_as_delivery"
				data-id="<?php echo esc_attr( $order->data['id'] ); ?>">
				<span>
					<?php
					if ( $order->data['delivered'] ) :
						esc_html_e( 'Update tracking link', 'wpshop' );
					else:
						esc_html_e( 'Mark as delivery', 'wpshop' );
					endif;
					?>
				</span>
			</div>
		</div>
	</div>
</div>
