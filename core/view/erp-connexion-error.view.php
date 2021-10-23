<?php
/**
 * La notice pour informer l'utilisateur d'une erreur de connexion avec son ERP.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-notification notification-active notification-red" style="opacity: 1; background: rgba(255,255,255,1);">
	<i class="notification-icon fas fa-times"></i>
	<div class="notification-title">
		<?php esc_html_e( 'Connection failed with your ERP', 'wpshop' ); ?>
		<p>
			<?php esc_html_e( 'Your ERP is not connected. Maybe your ERP is not connected or your configuration is not correct', 'wpshop' ); ?>
			<a href="https://wpshop.fr/documentation/" target="_blank"><?php _e( 'Need help ? Follow this guide', 'wpshop' ); ?></a>
		</p>
	</div>
	<div class="notification-close"><i class="fas fa-times"></i></div>
</div>
