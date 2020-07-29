<?php
/**
 * La vue affichant la notice pour informer l'utilisateur d'une erreur sur la connexion avec son ERP.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string $error Le message d'erreur.
 */
?>

<div class="notice notice-warning is-dismissible notice-erp">
	<p>
		<?php echo esc_attr( $error ); ?>
		<a class="wpeo-button button-main" target="_blank" href="https://wpshop.fr/documentation/"><span><?php _e( 'Follow this guide', 'wpshop' ); ?></span></a>

		<a href="#" style="float: right;" class="action-attribute" data-action="wps_hide_notice_erp"
			data-nonce="<?php echo wp_create_nonce( 'wps_hide_notice_erp' ); ?>"
			data-type="error_erp">
			<span><?php esc_html_e( 'Do not show this message again', 'wpshop' ); ?></span>
		</a>
	</p>
</div>
