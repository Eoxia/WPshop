<?php
/**
 * La vue principale de la page des devis
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

<div class="wrap wpeo-wrap wpeo-page-single">
	<div class="wps-page-header">
		<div class="wps-page-header-title-container">
			<div class="wps-page-header-title"><?php echo esc_html__( 'Proposal', 'wpshop' ) . ' <strong>' . esc_html( $proposal->data['title'] ) . '</strong>'; ?></div>
			<div class="wps-page-header-actions">
				<?php if ( ! empty( $proposal->data['external_id'] ) ) : ?>
					<a class="button <?php echo empty( $proposal->data['external_id'] ) ? 'disabled' : ''; ?>" href="<?php echo esc_attr( $doli_url ); ?>/comm/propal/card.php?id=<?php echo $proposal->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-3">
		<?php do_action( 'wps_proposal', $proposal ); ?>
	</div>
</div>
