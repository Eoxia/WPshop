<?php
/**
 * La vue affichant la metabox des factures dans le tableau de bord.
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
 * @var string       $dolibarr_invoices_lists L'url de la liste des factures sur dolibarr.
 * @var array        $invoices                Le tableau contenant toutes les données des factures.
 * @var Doli_Invoice $invoice                 Les données d'une facture.
 */
?>

<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Latest invoices', 'wpshop' ); ?></h3>
	<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_invoices_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell"><?php esc_html_e( 'Customer', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
		</div>

		<?php if ( ! empty( $invoices ) ) :
			foreach ( $invoices as $invoice ) : ?>
				<div class="table-row">
					<div class="table-cell"><a href="<?php echo esc_attr( $dolibarr_url . '/compta/facture/card.php?facid=' . $invoice->data['external_id'] ); ?>"><?php echo esc_html( $invoice->data['title'] ); ?></a></div>
				<?php if ( ! empty( $invoice->data['third_party']->data['id'] ) ): ?>
					<div class="table-cell break-word"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $invoice->data['third_party']->data['id'] ) ); ?>"><?php echo esc_html( $invoice->data['third_party']->data['title'] ); ?></a></div>
				<?php else : ?>
					<div class="table-cell"><?php esc_html_e('unknown', 'wpshop' ); ?></div>
				<?php endif; ?>
					<div class="table-cell"><?php echo esc_html( number_format( $invoice->data['total_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><?php echo esc_html( date( 'd/m/Y H:i', strtotime( $invoice->data['date_invoice'] ) ) ); ?></div>
				</div>
			<?php endforeach;
		else : ?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No invoice for the moment', 'wpshop' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
