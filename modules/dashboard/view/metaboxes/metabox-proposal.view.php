<?php
/**
 * La vue affichant la metabox des propositions commerciales dans le tableau de bord.
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
 * @var string         $dolibarr_url             L'url de dolibarr.
 * @var string         $dolibarr_proposals_lists L'url de la liste des propositions commerciales sur dolibarr.
 * @var array          $proposals                Le tableau contenant toutes les données des propositions commerciales.
 * @var Doli_Proposals $proposal                 Les données d'une proposition commerciale.
 */
?>

<div class="wps-dashboard-card gridw-3">
	<div class="wps-dashboard-card-header">
		<h3 class="wps-dashboard-card-title">
			<?php esc_html_e( 'Latest commercial proposals', 'wpshop' ); ?>
			<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_proposals_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>
		</h3>
	</div>

	<table class="wps-dashboard-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Proposal #', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Customer', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$total_amount = 0;
			if ( ! empty( $proposals ) ) :
				foreach ( $proposals as $proposal ) : 
					$total_amount += (float) $proposal->data['total_ttc'];
					?>
					<tr>
						<td>
							<a href="<?php echo esc_attr( $dolibarr_url . '/comm/propal/card.php?id=' . $proposal->data['external_id'] ); ?>">
								<?php echo esc_html( $proposal->data['title'] ); ?>
							</a>
						</td>
						<td>
							<i class="far fa-building wps-icon-customer"></i>
							<?php if ( ! empty( $proposal->data['third_party']->data['id'] ) ): ?>
								<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $proposal->data['third_party']->data['id'] ) ); ?>"><?php echo esc_html( $proposal->data['third_party']->data['title'] ); ?></a>
							<?php else : ?>
								<?php esc_html_e('unknown', 'wpshop' ); ?>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( number_format( $proposal->data['total_ttc'], 2, ',', ' ' ) ); ?> €</td>
						<td><?php echo esc_html( date( 'd/m/Y', strtotime( $proposal->data['datec'] ) ) ); ?></td>
						<td>
							<span class="wps-status-dot filled-gold"></span>
						</td>
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td colspan="5" style="text-align: center; color: #999;">
						<?php esc_html_e( 'No commercial proposal for the moment', 'wpshop' ); ?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
		<?php if ( ! empty( $proposals ) ) : ?>
		<tfoot>
			<tr class="wps-dashboard-total-row">
				<td colspan="2">Total</td>
				<td colspan="3"><?php echo esc_html( number_format( $total_amount, 2, ',', ' ' ) ); ?> €</td>
			</tr>
		</tfoot>
		<?php endif; ?>
	</table>
</div>
