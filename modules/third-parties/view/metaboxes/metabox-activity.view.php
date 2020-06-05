<?php
/**
 * La vue principale de la page des produits (wps-third-party)
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

<div class="wps-metabox wps-billing-address view gridw-6">
	<h3 class="metabox-title"><?php esc_html_e( 'Activity' ); ?></h3>

	<?php
	\task_manager\Indicator_Class::g()->callback_my_daily_activity( $post, array(), array(), $post->ID );
	?>
</div>
