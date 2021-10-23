<?php
/**
 * La vue affichant le formulaire pour créer son adresse de livraison.
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
 * @var Product $third_party Les données d'un tier.
 * @var User    $contact     Les données d'un contact.
 */
?>

<div class="wpeo-form wps-checkout-step-1">
	<div><?php do_action( 'wps_checkout_billing', $third_party, $contact ); ?></div>
	<div class="shipping-address"><?php do_action( 'wps_checkout_shipping', $third_party, $contact ); ?></div>
</div>

<?php do_action( 'wps_after_checkout_form' ); ?>
