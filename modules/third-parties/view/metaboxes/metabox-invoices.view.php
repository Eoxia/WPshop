<?php
/**
 * La vue affichant la métabox "Factures".
 * Page d'un tier.
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
 * @var array        $invoices Le tableau contenant toutes les données des factures.
 * @var Doli_Invoice $invoice  Les données d'une facture.
 * @var string       $doli_url L'url de Dolibarr.
 */
?>

<div class="wps-metabox wps-billing-address view gridw-2">
	<h3 class="metabox-title"><?php esc_html_e( 'Billing', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Billing', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Order', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( '€ TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		</div>

		<?php if ( ! empty( $invoices ) ) :
			foreach ( $invoices as $invoice ) : ?>
				<div class="table-row">
					<div class="table-cell">
						<a href="<?php echo esc_attr( $doli_url . '/compta/facture/card.php?id=' . $invoice->data['external_id'] ); ?>">
							<?php echo esc_html( $invoice->data['title'] ); ?>
						</a>
					</div>
					<div class="table-cell">
						<?php if ( ! empty( $invoice->data['order']->data['external_id'] ) ) : ?>
							<a href="<?php echo esc_attr( $doli_url . '/commande/card.php?id=' . $invoice->data['order']->data['external_id'] ); ?>">
								<?php echo esc_html( $invoice->data['order']->data['title'] ); ?>
							</a>
						<?php else: ?> -
						<?php endif; ?>
					</div>
					<div class="table-cell"><?php echo esc_html( $invoice->data['date']['rendered']['date'] ); ?></div>
					<div class="table-cell"><?php echo esc_html( number_format( $invoice->data['total_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><strong><?php echo Doli_Statut::g()->display_status( $invoice ); ?></strong></div>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</div>
