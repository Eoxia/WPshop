<?php
/**
 * La vue affichant la notice pour informer l'utilisateur d'activer son ERP.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="notice notice-warning is-dismissible">
	<p>
		<?php _e( 'WPshop: You have not yet set up your ERP ?', 'wpshop' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wps-settings&tab=erp' ) ); ?>"><?php _e( 'Configure', 'wpshop' ); ?></a> -
		<a class="wpeo-button button-main" target="_blank" href="https://wpshop.fr/documentation/"><?php _e( 'Follow this guide', 'wpshop' ); ?></a>
		<a style="float: right"; href="#" class="action-attribute" data-action="wps_hide_notice_erp"
			data-nonce="<?php echo wp_create_nonce( 'wps_hide_notice_erp' ); ?>"
			data-type="activate_erp">
			<?php esc_html_e( 'Do not show this message again.', 'wpshop' ); ?>
		</a>
	</p>
</div>
