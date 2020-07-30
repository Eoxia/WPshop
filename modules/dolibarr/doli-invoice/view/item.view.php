<?php
/**
 * La vue affichant une facture dans la liste des factures.
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
 * @var Doli_Invoice $invoice  Les données d'une facture.
 * @var string       $doli_url L'url de Dolibarr.
 */
?>

<div class="table-row">
	<div class="table-cell table-25"><input type="checkbox" class="check"/></div>
	<div class="table-cell table-full">
		<ul class="reference-id">
			<?php if ( ! empty( $invoice->data['external_id'] ) ) : ?>
				<li><i class="fas fa-hashtag"></i>Doli : <?php echo esc_html( $invoice->data['external_id'] ); ?></li>
			<?php endif; ?>
			<li><i class="fas fa-calendar-alt"></i> <?php echo esc_html( $invoice->data['date']['rendered']['date_time'] ); ?></li>
		</ul>
		<div class="reference-title">
			<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-invoice&id=' . $invoice->data['id'] ) ); ?>"><?php echo esc_html( $invoice->data['title'] ); ?></a>
		</div>
		<ul class="reference-actions">
			<li><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-invoice&id=' . $invoice->data['id'] ) ); ?>"><?php esc_html_e( 'See', 'wpshop' ); ?></a></li>
			<?php if ( ! empty( $invoice->data['external_id'] ) ) : ?>
				<li><a href="<?php echo esc_attr( $doli_url ); ?>/compta/facture/card.php?facid=<?php echo $invoice->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="table-cell table-full">
		<ul class="reference-id">
			<?php if ( ! empty( $invoice->data['order']->data['external_id'] ) ) : ?>
				<li><i class="fas fa-hashtag"></i>Doli : <?php echo esc_html( $invoice->data['order']->data['external_id'] ); ?></li>
			<?php endif; ?>
			<li><i class="fas fa-calendar-alt"></i> <?php echo esc_html( date( "d/m/Y h:i" , strtotime( $invoice->data['order']->data['datec'] ) ) ); ?></li>
		</ul>
		<div class="reference-title">
			<?php if ( ! empty( $invoice->data['order'] ) ) : ?>
				<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-order&id=' . $invoice->data['order']->data['external_id'] ) ); ?>"><?php echo esc_html( $invoice->data['order']->data['title'] ); ?></a>
			<?php else : ?>-<?php
			endif; ?>
		</div>
		<ul class="reference-actions">
			<?php if ( ! empty( $invoice->data['order']->data['external_id'] ) ) : ?>
				<li><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-order&id=' . $invoice->data['order']->data['external_id'] ) ); ?>"><?php esc_html_e( 'See', 'wpshop' ); ?></a></li>
				<li><a href="#" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="table-cell table-150">
		<?php if ( ! empty( $invoice->data['tier'] ) ) : ?>
			<div>
				<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $invoice->data['tier']->data['id'] ) ); ?>">
					<strong><?php echo esc_html( $invoice->data['tier']->data['title'] ); ?></strong>
				</a>
			</div>
			<div><?php echo esc_html( $invoice->data['tier']->data['address'] ); ?></div>
			<div><?php echo esc_html( $invoice->data['tier']->data['zip'] ) . ' ' . esc_html( $invoice->data['tier']->data['country'] ); ?></div>
			<div><?php echo esc_html( $invoice->data['tier']->data['phone'] ); ?></div>
		<?php endif; ?>
	</div>
	<div class="table-cell table-150"><?php echo Doli_Statut::g()->display_status( $invoice ); ?></div>
	<div class="table-cell table-100"><?php echo esc_html( Payment::g()->get_payment_title( $invoice->data['payment_method'] ) ); ?></div>
	<div class="table-cell table-100"><strong><?php echo esc_html( number_format( $invoice->data['total_ttc'], 2, ',', '' ) ); ?>€</strong></div>
	<?php apply_filters( 'wps_order_table_tr', $invoice ); ?>
</div>
