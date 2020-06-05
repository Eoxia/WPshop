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

<div class="notice notice-warning is-dismissible notice-erp">
	<p>
		<?php echo esc_attr( $error ); ?>
		<a class="wpeo-button button-main" target="_blank" href="https://wpshop.fr/documentation/pages/installation/erp-dolibarr/"><span><?php _e( 'Follow this guide', 'wpshop' ); ?></span></a>

		<a href="#" style="float: right;" class="action-attribute" data-action="wps_hide_notice_erp"
			data-nonce="<?php echo wp_create_nonce( 'wps_hide_notice_erp' ); ?>"
			data-type="error_erp">
			<span><?php esc_html_e( 'Do not show this message again', 'wpshop' ); ?></span>
		</a>
	</p>
</div>
