<?php
/**
 * La vue affichant la metabox "Tier".
 * Page d'un tier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array       $contacts    Le tableau contenant toutes les données des utilisateurs.
 * @var User        $contact     Les données d'un utilisateur.
 * @var Third_Party $third_party Les données d'un tier.
 */
?>

<div class="wps-metabox wps-billing-contact view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Tiers', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-5">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Name', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Firstname', 'wpshop' ); ?></div>
			<div class="table-cell"><i class="fas fa-envelope"></i></div>
			<div class="table-cell"><i class="fas fa-phone"></i></div>
			<div class="table-cell"></div>
		</div>

		<?php if ( ! empty( $contacts ) ) :
			foreach ( $contacts as $contact ) :
				View_Util::exec( 'wpshop', 'third-parties', 'metaboxes/metabox-contacts-item', array(
					'third_party_id' => $third_party->data['id'],
					'contact'        => $contact,
				) );
			endforeach;
		endif; ?>
	</div>
</div>
