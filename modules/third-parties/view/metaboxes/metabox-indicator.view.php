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
	<h3 class="metabox-title"><?php esc_html_e( 'Indicator' ); ?></h3>

	<?php
	$currentyear = date( 'Y', strtotime( 'now' ) );

	ob_start();
	\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/metabox-head-indicator', array(
		'parent_id'   => $post->ID,
		'year'        => $currentyear,
		'post_id'     => $post->ID,
		'post_author' => $post->post_author,
	) );
	$button_indicator = ob_get_clean();

	echo apply_filters( 'tm_posts_metabox_buttons', $button_indicator );
	\task_manager\Task_Class::g()->callback_render_indicator( $post, array(), array(), $post->ID );
	?>
</div>
