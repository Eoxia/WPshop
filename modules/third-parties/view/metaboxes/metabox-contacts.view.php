<?php
/**
 * La vue affichant la metabox "Contacts/Adresse".
 * Page d'un tier.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.3.0
 * @version   2.3.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array         $contacts    Le tableau contenant toutes les données des contacts/adresses.
 * @var Doli_Contacts $contact     Les données d'un contact/adresse.
 * @var Third_Party   $third_party Les données d'un tier.
 */
?>

<div class="wps-metabox wps-contacts-addresses view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Contacts/Addresses', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-2">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Name', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Adresse', 'wpshop' ); ?></div>
		</div>

		<?php if ( ! empty( $contacts ) ) :
			foreach ( $contacts as $contact ) : ?>
				<div class="table-row">
					<div class="table-cell">
						<?php echo esc_html( $contact->data['firstname'] . ' ' . $contact->data['lastname'] ); ?>
					</div>
					<div class="table-cell">
						<ul>
							<li><?php echo esc_html( $contact->data['address'] . ', ' . $contact->data['zip'] . ' ' . $contact->data['town'] . ', ' . $contact->data['country'] ); ?></li>
							<li><i class="fas fa-phone"></i> <?php echo esc_html( $contact->data['phone_pro'] ); ?></li>
							<li><i class="fas fa-envelope"></i> <?php echo esc_html( $contact->data['email'] ); ?></li>
						</ul>
					</div>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</div>

