<?php
/**
 * Product grid view
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 * @package   WPshop\Templates
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( $wps_query->have_posts() ) :
	?>
	<div class="wps-product-grid wpeo-gridlayout grid-4">

		<?php
		while ( $wps_query->have_posts() ) :
			$wps_query->the_post();
			include( Template_Util::get_template_part( 'products', 'wps-product-grid' ) );
		endwhile;
		wp_reset_postdata();
		?>

	</div>
	<?php
	$big = 999999999;
	echo paginate_links( array(
		'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var('paged') ),
		'total'     => $wps_query->max_num_pages,
		'next_text' => '<i class="dashicons dashicons-arrow-right"></i>',
		'prev_text' => '<i class="dashicons dashicons-arrow-left"></i>',
	) );
endif;
