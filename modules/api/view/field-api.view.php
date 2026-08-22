<?php
/**
 * La vue affichant le champ pour générér la clé API.
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
 * @var string  $token Le token généré.
 * @var integer $id    L'id de l'utilisateur WordPress.
 */
?>

<div class="wpshop-fields">
	<h2><?php esc_html_e( 'WPshop API', 'wpshop' ); ?></h2>

	<table class="form-table">
		<tbody>
			<tr class="user-api-wrap">
				<th><label for="api"><?php esc_html_e( 'API Key', 'wpshop' ); ?></label></th>
				<td>
					<input type="text" readonly name="api" id="api" aria-describedby="api-description" value="<?php echo esc_attr( $token ); ?>" class="regular-text ltr">
					<i class="wpeo-button fas fa-redo action-attribute"
						data-action="<?php echo esc_attr( 'generate_api_key' ); ?>"
						title="<?php esc_attr_e('Generate new key', 'wpshop'); ?>"
						data-nonce="<?php echo wp_create_nonce( 'generate_api_key' ); ?>"></i>
					<p class="description">
						<?php _e('This key is used by Dolibarr to authenticate with WPshop.', 'wpshop'); ?><br><br>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=wps-settings&tab=erp' ) ); ?>" class="button button-secondary">
							<?php _e( 'Test / Configure ERP Connection', 'wpshop' ); ?>
						</a>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
