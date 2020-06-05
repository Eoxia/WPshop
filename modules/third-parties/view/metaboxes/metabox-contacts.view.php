<?php
/**
 * La vue affichant les contact d'un tier dans la page single d'un tier.
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

<div class="wps-metabox wps-billing-contact view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Users', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-5">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Name', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Firstname', 'wpshop' ); ?></div>
			<div class="table-cell"><i class="fas fa-envelope"></i></div>
			<div class="table-cell"><i class="fas fa-phone"></i></div>
			<div class="table-cell"></div>
		</div>

		<?php
		if ( ! empty( $contacts ) ) :
			foreach ( $contacts as $contact ) :
				\eoxia\View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-contacts-item', array(
					'third_party_id' => $third_party->data['id'],
					'contact'        => $contact,
				) );
			endforeach;
		endif;
		?>
	</div>
</div>
