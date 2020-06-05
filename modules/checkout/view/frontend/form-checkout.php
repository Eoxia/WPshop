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

defined( 'ABSPATH' ) || exit;

do_action( 'wps_before_checkout_form' ); ?>

<div class="wps-checkout wpeo-gridlayout grid-5">
	<div class="gridw-3">
		<?php include( Template_Util::get_template_part( 'checkout', 'form-checkout-step-1' ) ); ?>
		<?php
		if ( Settings::g()->dolibarr_is_active() ) :
			include( Template_Util::get_template_part( 'checkout', 'form-checkout-step-2' ) );
		endif;
		?>
	</div>
	<div class="gridw-2 wpeo-form">
		<?php include( Template_Util::get_template_part( 'checkout', 'form-checkout-step-3' ) ); ?>

		<?php do_action( 'wps_review_order_before_submit' ); ?>

		<?php wp_nonce_field( 'callback_place_order' ); ?>
		<input type="hidden" name="action" value="wps_place_order" />

		<?php do_action( 'wps_review_order_after_submit' ); ?>
	</div>
</div>
