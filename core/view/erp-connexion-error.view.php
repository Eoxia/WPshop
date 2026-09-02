<?php
/**
 * La notice pour informer l'utilisateur d'une erreur de connexion avec son ERP.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="notice notice-error is-dismissible">
	<p>
		<strong><?php esc_html_e( 'Connection failed with your ERP', 'wpshop' ); ?></strong>
	</p>
	<p>
		<?php echo esc_html( Error_Util::get( 'WPS-ERP-002' ) ); ?>
	</p>
	<?php 
	$detailed_error = get_transient( 'wps_request_error' );
	if ( ! empty( $detailed_error ) ) : ?>
		<p>
			<strong><?php _e( 'Details:', 'wpshop' ); ?></strong><br> 
			<?php echo nl2br( esc_html( $detailed_error ) ); ?>
		</p>
	<?php endif; ?>
	<p>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wps-settings&tab=erp' ) ); ?>" class="button button-primary">
			<?php _e( 'Configure ERP', 'wpshop' ); ?>
		</a>
		<a href="https://wpshop.fr/documentation/" target="_blank" style="margin-left: 10px;">
			<?php _e( 'Need help ? Follow this guide', 'wpshop' ); ?>
		</a>
	</p>
</div>
