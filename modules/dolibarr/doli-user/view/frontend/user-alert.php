<?php
/**
 * La vue affichant la notice d'alerte.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-notice notice-warning">
	<div class="notice-content">
		<div class="notice-title"><?php esc_html_e( 'There is a problem connecting your account to our ERP', 'wpshop' ); ?></div>
		<div class="notice-subtitle"><?php esc_html_e( sprintf( 'Please contact %s at %s to reactivate your account with this message: %s', get_bloginfo( 'name' ), get_bloginfo( 'admin_email' ), $response['status_code'] . ' - ' . $response['status_message'] ), 'wpshop' ); ?></div>
	</div>
	<div class="notice-close"><i class="fas fa-times"></i></div>
</div>
