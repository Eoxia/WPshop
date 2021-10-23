<?php
/**
 * La vue affichant une proposition commerciale dans la liste des propositions commerciales.
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
 * @var Proposals $proposal Les données d'une proposition commerciale.
 * @var string    $doli_url L'url de Dolibarr.
 */
?>

<div class="table-row" data-id="<?php echo esc_attr( $proposal->data['external_id'] ); ?>">
	<div class="table-cell table-full">
		<ul class="reference-id">
			<?php if ( ! empty( $proposal->data['external_id'] ) ) : ?>
				<li><i class="fas fa-hashtag"></i>Doli : <?php echo esc_html( $proposal->data['external_id'] ); ?></li>
			<?php endif; ?>
			<li><i class="fas fa-calendar-alt"></i> <?php echo esc_html( $proposal->data['datec']['date_time'] ); ?></li>
		</ul>
		<div class="reference-title">
			<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-proposal-doli&id=' . $proposal->data['external_id'] ) ); ?>"><?php echo esc_html( $proposal->data['title'] ); ?></a>
		</div>
		<ul class="reference-actions">
			<li><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-proposal-doli&id=' . $proposal->data['external_id'] ) ); ?>"><?php esc_html_e( 'See', 'wpshop' ); ?></a></li>
			<?php if ( ! empty( $proposal->data['external_id'] ) ) : ?>
				<li><a href="<?php echo esc_attr( $doli_url ); ?>/comm/propal/card.php?id=<?php echo $proposal->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="table-cell table-200">
		<div><strong><?php echo esc_html( ! empty( $proposal->data['tier'] ) ? $proposal->data['tier']->data['title'] : '' ); ?></strong></div>
		<div><?php echo esc_html( ! empty( $proposal->data['tier'] ) ? $proposal->data['tier']->data['address'] : '' ); ?></div>
		<div><?php echo esc_html( ! empty( $proposal->data['tier'] ) ? $proposal->data['tier']->data['zip'] : '' ) . ' ' . esc_html( ! empty(  $proposal->data['tier'] ) ? $proposal->data['tier']->data['country'] : '' ); ?></div>
		<div><?php echo ! empty( $proposal->data['tier']->data['phone'] ) ? esc_html( $proposal->data['tier']->data['phone'] ) : ''; ?></div>
	</div>
	<div class="table-cell table-150"><?php echo Doli_Statut::g()->display_status( $proposal ); ?></div>
	<div class="table-cell table-100"><?php echo esc_html( Payment::g()->get_payment_title( $proposal->data['payment_method'] ) ); ?></div>
	<div class="table-cell table-100"><strong><?php echo esc_html( number_format( $proposal->data['total_ttc'], 2, ',', '' ) ); ?>€</strong></div>
	<?php apply_filters( 'wps_proposal_table_tr', $proposal ); ?>

</div>
