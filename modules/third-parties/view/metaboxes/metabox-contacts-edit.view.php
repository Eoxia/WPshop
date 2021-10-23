<?php
/**
 * La vue affichant l'édition dans la metabox "Utilisateurs".
 * Page d'un tier.
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
 * @var User    $contact        Les données d'un utilisateur.
 * @var integer $third_party_id L'id d'un tier.
 */
?>

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
