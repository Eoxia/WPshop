<?php
/**
 * La vue affichant le container du produit.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var \WP_Query $wps_query La requête pour récupérer les données d'un produit.
 * @var integer   $big       Un grand nombre.
 */
?>

<?php if ( $wps_query->have_posts() ) : ?>
	<div class="wps-product-grid wpeo-gridlayout grid-4">

		<?php while ( $wps_query->have_posts() ) :
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
endif; ?>
