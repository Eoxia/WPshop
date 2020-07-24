<?php
/**
 * La liste des produits dans le résumé du panier.
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
 * @todo a revoir
 *
 * @var Cart_Session $cart_contents        Tableau contenant toutes les données du panier.
 * @var string       $key                  Un produit.
 * @var Product      $product              Les données d'un produit.
 * @var string       $shipping_cost_option L'option pour les frais de transport.
 */
?>

<?php if ( ! empty( Cart_Session::g()->cart_contents ) ) : ?>
	<div class="wps-list-product">
		<?php foreach ( Cart_Session::g()->cart_contents as $key => $product ) :
			if ( $shipping_cost_option['shipping_product_id'] !== $product['id'] ) :
				include( Template_Util::get_template_part( 'products', 'wps-product-list' ) );
			endif;
		endforeach; ?>
	</div>
<?php endif; ?>
