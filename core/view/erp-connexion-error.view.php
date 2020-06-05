<?php
/**
 * Notice pour informer l'utilisateur d'activer son ERP.
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

<div class="wpeo-notification notification-active notification-red" style="opacity: 1; background: rgba(255,255,255,1);">
	<i class="notification-icon fas fa-times"></i>
	<div class="notification-title">
		<?php esc_html_e( 'Connection failed with your ERP', 'wpshop' ); ?>

		<p>
			<?php esc_html_e( 'Your ERP is not connected. Maybe your ERP is not connected or your configuration is not correct', 'wpshop' ); ?>
			<a href="https://github.com/Eoxia/wpshop/tree/2.0.0"><?php _e( 'Need help ? Follow this guide', 'wpshop' ); ?></a>
		</p>
	</div>
	<div class="notification-close"><i class="far fa-times"></i></div>
</div>
