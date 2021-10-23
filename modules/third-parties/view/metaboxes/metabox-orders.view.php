<?php
/**
 * La vue affichant la métabox "Commandes".
 * Page d'un tier.
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
 * @var array      $orders   Le tableau contenant toutes les données des commandes.
 * @var Doli_Order $order    Les données d'une commande.
 * @var string     $doli_url L'url de Dolibarr.
 */
?>

<div class="wps-metabox wps-billing-address view gridw-2">
	<h3 class="metabox-title"><?php esc_html_e( 'Orders', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Order', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( '€ TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		</div>

		<?php if ( ! empty( $orders ) ) :
			foreach ( $orders as $order ) : ?>
				<div class="table-row">
					<div class="table-cell">
						<a href="<?php echo esc_attr( $doli_url . '/commande/card.php?id=' . $order->data['external_id'] ); ?>">
							<?php echo esc_html( $order->data['title'] ); ?>
						</a>
					</div>
					<div class="table-cell"><?php echo esc_html( Date_util::readable_date( $order->data['datec'], 'date' ) ); ?></div>
					<div class="table-cell"><?php echo esc_html( number_format( $order->data['total_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><strong><?php echo Doli_Statut::g()->display_status( $order ); ?></strong></div>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</div>
