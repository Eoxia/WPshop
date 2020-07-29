<?php
/**
 * La vue affichant les informations de livraison d'une commande.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Order $order Les données d'une commande.
 */
?>

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
					<?php if ( $order->data['delivered'] ) :
						esc_html_e( 'Update tracking link', 'wpshop' );
					else:
						esc_html_e( 'Mark as delivery', 'wpshop' );
					endif; ?>
				</span>
			</div>
		</div>
	</div>
</div>
