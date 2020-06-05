<?php
/**
 * La vue principale de la page de réglages
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

<div class="wrap wpeo-wrap">
	<h2><?php esc_html_e( 'Tools', 'wpshop' ); ?></h2>

	<div class="wpeo-tab">
		<ul class="tab-list">
			<a href="<?php echo esc_url( admin_url( 'admin-post.php?action=wps_load_tools_tab&_wpnonce=' . wp_create_nonce( 'callback_load_tab' ) . '&tab=general' ) ); ?>" class="tab-element <?php echo ( 'general' === $tab ) ? 'tab-active' : ''; ?>"><?php esc_html_e( 'General', 'wpshop' ); ?></a>
		</ul>

		<div class="tab-container">
			<div class="tab-content tab-active">
				<?php call_user_func( array( Tools::g(), 'display_' . $tab ), $section ); ?>
			</div>
		</div>
	</div>
</div>
