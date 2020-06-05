<?php
/**
 * Le formulaire pour créer son adresse de livraison
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


<div class="wpeo-form wps-checkout-step-1">
	<div><?php do_action( 'wps_checkout_billing', $third_party, $contact ); ?></div>
	<div class="shipping-address"><?php do_action( 'wps_checkout_shipping', $third_party, $contact ); ?></div>
</div>

<?php do_action( 'wps_after_checkout_form' ); ?>
