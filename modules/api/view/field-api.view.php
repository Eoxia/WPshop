<?php
/**
 * Le champ pour l'API.
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
						data-nonce="<?php echo wp_create_nonce( 'generate_api_key' ); ?>"
						data-id="<?php echo esc_attr( $id ); ?>"></i>
				</td>
			</tr>
		</tbody>
	</table>
</div>
