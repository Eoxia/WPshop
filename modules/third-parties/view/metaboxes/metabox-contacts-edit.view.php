<?php
/**
 * La vue principale de la page des produits (wps-third-party)
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

<div class="table-row <?php echo empty( $contact->data['id'] ) ? 'new' : 'edit'; ?>">
	<div class="table-cell"><input type="text" name="contact[lastname]" placeholder="<?php esc_html_e( 'Name', 'wpshop' ); ?>" value="<?php echo esc_attr( $contact->data['lastname'] ); ?>" /></div>
	<div class="table-cell"><input type="text" name="contact[firstname]" placeholder="<?php esc_html_e( 'Firstname', 'wpshop' ); ?>" value="<?php echo esc_attr( $contact->data['firstname'] ); ?>" /></div>
	<div class="table-cell"><input type="text" name="contact[email]" placeholder="<?php esc_html_e( 'Email', 'wpshop' ); ?>" value="<?php echo esc_attr( $contact->data['email'] ); ?>" /></div>
	<div class="table-cell"><input type="text" name="contact[phone]" placeholder="<?php esc_html_e( 'Phone', 'wpshop' ); ?>" value="<?php echo esc_attr( $contact->data['phone'] ); ?>" /></div>
	<div class="table-cell table-end">
		<input type="hidden" name="contact[id]" value="<?php echo esc_attr( $contact->data['id'] ); ?>" />
		<div data-parent="row"
			data-parent-id="<?php echo esc_attr( $third_party_id ); ?>"
			data-action="third_party_save_contact"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'save_and_associate_contact' ) ); ?>"
			class="action-input wpeo-button button-square-30 button-rounded <?php echo ! empty( $contact->data['id'] ) ? 'button-green' : 'button-main'; ?>">
			<i class="button-icon fas <?php echo ! empty( $contact->data['id'] ) ? 'fa-save' : 'fa-plus'; ?>"></i>
		</div>
	</div>
</div>
