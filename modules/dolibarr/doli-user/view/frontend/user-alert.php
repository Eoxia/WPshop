<?php
/**
 * Notice d'alerte
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
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
