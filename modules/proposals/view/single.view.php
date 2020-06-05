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

<div class="wrap wpeo-wrap page-single">
	<div class="page-header">
		<h2><?php echo esc_html__( 'Proposal', 'wpshop' ) . ' ' . esc_html( $proposal->data['title'] ); ?></h2>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-3">
		<?php do_action( 'wps_proposal', $proposal ); ?>
	</div>
</div>
