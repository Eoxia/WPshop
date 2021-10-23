<?php
/**
 * La vue affichant le formulaire pour payer sa commande.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<?php do_action( 'wps_checkout_before_payment' ); ?>

<div class="wps-checkout-step-2">
	<?php do_action( 'wps_checkout_payment' ); ?>
</div>

<?php do_action( 'wps_checkout_after_payment' ); ?>
