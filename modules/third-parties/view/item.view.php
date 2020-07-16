<?php
/**
 * Affichage d'un tier dans le listing de la page des tiers (wps-third-party)
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

<div class="table-row" data-id="<?php echo esc_attr( $third_party->data['id'] ); ?>">
	<div class="table-cell table-200">
		<div class="reference-title">
			<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ) ); ?>"><?php echo esc_html( $third_party->data['title'] ); ?></a>
		</div>
		<?php if ( ! empty( $third_party->data['phone'] ) ) : ?>
			<div class="reference-content">
				<i class="fas fa-phone"></i> <?php echo esc_html( $third_party->data['phone'] ); ?>
			</div>
		<?php endif; ?>
		<ul class="reference-actions">
			<li><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ) ); ?>"><?php esc_html_e( 'See', 'wpshop' ); ?></a></li>
			<?php if ( ! empty( $third_party->data['external_id'] ) ) : ?>
				<li><a href="<?php echo esc_attr( $doli_url ); ?>/societe/card.php?id=<?php echo $third_party->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="table-cell table-300"><?php User::g()->display( $third_party ); ?></div>
	<div class="table-cell table-350"><?php Third_Party::g()->display_commercial( $third_party->data ); ?></div>
	<div class="table-cell table-full"></div>
	<?php do_action( 'wps_listing_table_end', $third_party, $sync_status ); ?>
</div>
