<?php
/**
 * Affichage du listing des tiers dans le backend.
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


<div class="wps-list-third-parties wpeo-table table-flex table-6">
	<div class="table-row table-header">
		<div class="table-cell table-200"><?php esc_html_e( 'Society name', 'wpshop' ); ?></div>
		<div class="table-cell table-300"><?php esc_html_e( 'User', 'wpshop' ); ?></div>
		<div class="table-cell table-350"><?php esc_html_e( 'Commercial', 'wpshop' ); ?></div>
		<div class="table-cell table-full"><?php esc_html_e( 'Actions', 'wpshop' ); ?></div>
		<?php do_action( 'wps_listing_table_header_end', 'thirdparties' ); ?>
	</div>

	<?php
	if ( ! empty( $third_parties ) ) :
		foreach ( $third_parties as $third_party ) :
			\eoxia\View_Util::exec( 'wpshop', 'third-parties', 'item', array(
				'third_party' => $third_party,
				'sync_status' => false,
				'doli_url'    => $doli_url,
			) );
		endforeach;
	endif;
	?>
</div>
