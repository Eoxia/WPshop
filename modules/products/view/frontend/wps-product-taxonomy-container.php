<?php
/**
 * La vue affichant les catégories de produits.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array $product_taxonomies Le tableau contenant toutes les données des catégories de produits.
 * @var array $product_taxonomy   Les données de la catégorie d'un produit.
 */
?>

<?php if ( ! empty( $product_taxonomies ) ) : ?>
	<div class="wps-list-taxonomy wpeo-gridlayout grid-3 grid-margin-2">
		<?php foreach ( $product_taxonomies as $product_taxonomy ) :
			include( Template_Util::get_template_part( 'products', 'wps-product-taxonomy' ) );
		endforeach; ?>
	</div>
<?php endif; ?>
