<?php
/**
 * La vue affichant la métabox "Adresse".
 * Page d'un tier.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Third_Parties $third_party   Les données d'un tier.
 */
?>

<div class="wps-metabox wps-billing-address view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Address', 'wpshop' ); ?></h3>

	<!-- <div data-action="third_party_load_address"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_billing_address' ) ); ?>"
		data-third-party_id="<?php echo esc_attr( $third_party->data['id'] ); ?>"
		class="action-attribute metabox-edit wpeo-button button-grey button-square-30 button-rounded">
		<i class="button-icon fas fa-pen"></i>
	</div> -->

	<ul class="metabox-list wpeo-gridlayout grid-2">
		<li><span><?php esc_html_e( 'Name', 'wpshop' ); ?></span> <?php echo esc_html( $third_party->data['title'] ); ?></li>
		<li><span><?php esc_html_e( 'Address', 'wpshop' ); ?></span> <?php echo $third_party->data['address']; ?></li>
		<li><span><?php esc_html_e( 'ZIP Code', 'wpshop' ); ?></span> <?php echo $third_party->data['zip']; ?></li>
		<li><span><?php esc_html_e( 'City', 'wpshop' ); ?></span> <?php echo $third_party->data['town']; ?></li>
		<li><span><?php esc_html_e( 'Country', 'wpshop' ); ?></span> <?php echo $third_party->data['country']; ?></li>
		<li><span><?php esc_html_e( 'Phone', 'wpshop' ); ?></span> <?php echo $third_party->data['phone']; ?></li>
	</ul>
</div>
