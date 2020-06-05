<?php
/**
 * Appel le formulaire de login pour la page de paiement.
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


<div class="checkout-login <?php echo ! empty( $transient ) ? 'hide' : ''; ?>">
	<div class="checkout-login-toggle"><?php esc_html_e( 'Returning customer ?', 'wpshop' ); ?> <span><?php esc_html_e( 'Click here to login', 'wpshop' ); ?></span></div>
	<div class="content-login" style="<?php echo empty( $transient ) ? 'display: none;' : ''; ?>">
		<?php My_Account::g()->display_form_login( false ); ?>
	</div>
</div>
