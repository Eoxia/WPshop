<?php
/**
 * La vue affichant la metabox des paiements dans le tableau de bord.
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
 * @var string       $dolibarr_payments_lists L'url de la liste des paiements sur dolibarr.
 * @var array        $payments                Le tableau contenant toutes les données des paiements.
 * @var Doli_Payment $payment                 Les données d'un paiement.
 */
?>

<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Latest payments', 'wpshop' ); ?></h3>
	<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_payments_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>

	<div class="wpeo-table table-flex table-5">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell"><?php esc_html_e( 'Invoice', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Payment method', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
		</div>

		<?php if ( ! empty( $payments ) ) :
			foreach ( $payments as $payment ) : ?>
				<div class="table-row">
					<div class="table-cell"><?php echo esc_html( $payment->data['title'] ); ?></div>
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-invoice&id=' . $payment->data['invoice']->data['id'] ) ); ?>"><?php echo esc_html( $payment->data['invoice']->data['title'] ); ?></a></div>
					<div class="table-cell"><?php echo esc_html( $payment->data['payment_type'] ); ?></div>
					<div class="table-cell"><?php echo esc_html( number_format( $payment->data['amount'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><?php echo esc_html( Date_util::readable_date( $payment->data['date'], 'date_time' ) ); ?></div>
				</div>
			<?php endforeach;
		else : ?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No payment for the moment', 'wpshop' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
