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
	<h3 class="metabox-title"><?php esc_html_e( 'Tasks' ); ?></h3>

	<?php
	ob_start();
	\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/metabox-create-buttons', array( 'parent_id' => $post->ID ) );
	$buttons = ob_get_clean();

	echo apply_filters( 'tm_posts_metabox_buttons', $buttons );

	\task_manager\Task_Class::g()->callback_render_metabox( $post, array(), array(), $post->ID );
	?>
</div>
