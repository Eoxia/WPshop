<?php
/**
 * Affichages du header pour toutes les pages.
 *
 * @author Eoxia <technique@eoxia.com>
 * @since 1.0.0
 * @copyright 2015-2019 Eoxia
 * @package Eoxia
 */

namespace eoxia;

defined( 'ABSPATH' ) || exit; ?>

<div id="top-header" class="ui sticky">
	<?php echo apply_filters( 'eoxia_main_header_before', '' ); ?>

	<div class="nav-left">
		<h1 class="page-title"><?php echo esc_html( apply_filters( 'eoxia_main_header_title', get_admin_page_title() ) ); ?></h1>

		<ul class="nav-header">
			<?php echo apply_filters( 'eoxia_main_header_li', '' ); ?>
		</ul>
	</div>


	<ul class="nav-right">
		<?php echo apply_filters( 'eoxia_main_header_ul_before', '' ); ?>

		<li class="navigation-avatar">
			<?php echo get_avatar( $current_user->ID, 30 ); ?>

			<?php if ( ! empty( $link ) ) :	?>
				(<a href="<?php /*echo $link; */?>">Switch back (<?php /*echo $old_user->display_name; */?>)</a>)
			<?php endif; ?>
		</li>

		<?php echo apply_filters( 'eoxia_main_header_ul_after', '' ); ?>
	</ul>

	<ul class="nav-bottom">
		<?php echo apply_filters( 'eoxia_main_header_nav_bottom', '' ); ?>
	</ul>

	<?php echo apply_filters( 'eoxia_main_header_after', '' ); ?>
</div>
