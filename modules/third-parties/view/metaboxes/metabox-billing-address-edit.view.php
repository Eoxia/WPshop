<?php
/**
 * La vue affichant l'édition dans la métabox "Adresse".
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
 * @var Third_Party $third_party Les données d'un tier.
 */
?>

<div class="wps-metabox wps-billing-address edit gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Billing Address', 'wpshop' ); ?></h3>

	<div data-parent="inside"
		data-action="third_party_save_address"
		data-third-party_id="<?php echo esc_attr( $third_party->data['id'] ); ?>"
		class="action-input metabox-edit wpeo-button button-green button-square-30 button-rounded">
		<i class="button-icon fas fa-save"></i>
	</div>

	<ul class="wpeo-gridlayout grid-2">
		<li><span><?php esc_html_e( 'Name', 'wpshop' ); ?></span> <input type="text" name="third_party[title]" value="<?php echo esc_attr( $third_party->data['title'] ); ?>" /></li>
		<li><span><?php esc_html_e( 'Address', 'wpshop' ); ?></span> <input type="text" name="third_party[address]" value="<?php echo esc_attr( $third_party->data['contact'] ); ?>" /></li>
		<li><span><?php esc_html_e( 'ZIP Code', 'wpshop' ); ?></span> <input type="text" name="third_party[zip]" value="<?php echo esc_attr( $third_party->data['zip'] ); ?>" /></li>
		<li><span><?php esc_html_e( 'City', 'wpshop' ); ?></span> <input type="text" name="third_party[town]" value="<?php echo esc_attr( $third_party->data['town'] ); ?>" /></li>
		<li><span><?php esc_html_e( 'Phone', 'wpshop' ); ?></span> <input type="text" name="third_party[phone]" value="<?php echo esc_attr( $third_party->data['phone'] ); ?>" /></li>
	</ul>
</div>

<?php wp_nonce_field( 'save_billing_address' ); ?>
