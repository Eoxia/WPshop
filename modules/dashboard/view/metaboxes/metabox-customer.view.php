<?php
/**
 * La vue affichant la metabox des clients dans le tableau de bord.
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
 * @var string      $dolibarr_url         L'url de dolibarr.
 * @var string      $dolibarr_tiers_lists L'url de la liste des tiers sur dolibarr.
 * @var array       $third_parties        Le tableau contenant toutes les données des tiers.
 * @var Third_Party $third_party          Les données d'un tier.
 */
?>

<div class="wps-dashboard-card gridw-3">
	<div class="wps-dashboard-card-header">
		<h3 class="wps-dashboard-card-title">
			<?php esc_html_e( 'Latest customers', 'wpshop' ); ?>
			<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_tiers_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>
		</h3>
	</div>

	<table class="wps-dashboard-table">
		<thead>
			<tr>
				<th>#</th>
				<th><?php esc_html_e( 'Name', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $third_parties ) ) :
				foreach ( $third_parties as $third_party ) : ?>
					<tr>
						<td>
							<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ) ); ?>">
								<?php echo esc_html( $third_party->data['id'] ); ?>
							</a>
						</td>
						<td>
							<i class="far fa-building wps-icon-customer"></i>
							<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ) ); ?>">
								<?php echo esc_html( $third_party->data['title'] ); ?>
							</a>
						</td>
						<td><?php echo esc_html( $third_party->data['date']['rendered']['date_time'] ); ?></td>
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td colspan="3" style="text-align: center; color: #999;">
						<?php esc_html_e( 'No customer for the moment', 'wpshop' ); ?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
