<?php
/**
 * La vue affichant la liste des produits dans le résumé du panier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
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
			include( Template_Util::get_template_part( 'products', 'wps-product-list' ) );
		endforeach; ?>
	</div>
<?php endif; ?>
