<?php
/**
 * La vue affichant le formulaire de login pour la page de paiement.
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
 * @var string $transient Un message.
 */
?>

<div class="checkout-login <?php echo ! empty( $transient ) ? 'hide' : ''; ?>">
	<div class="checkout-login-toggle"><?php esc_html_e( 'Returning customer ?', 'wpshop' ); ?> <span><?php esc_html_e( 'Click here to login', 'wpshop' ); ?></span></div>
	<div class="content-login" style="<?php echo empty( $transient ) ? 'display: none;' : ''; ?>">
		<?php My_Account::g()->display_form_login( false ); ?>
	</div>
</div>
