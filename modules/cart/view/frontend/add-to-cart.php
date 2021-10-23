<?php
/**
 * La vue affichant le bouton pour ajouter un produit au panier.
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
 * @Todo changer le nom de la variable.
 *
 * @var Product $product La donnée d'un produit.
 * @var string  $a       Texte du bouton.
 */
?>

<div class="wps-product-action">
	<div class="wps-product-buy wpeo-button action-attribute <?php echo apply_filters( 'wps_product_add_to_cart_class', '', $product ); ?>"
		<?php echo apply_filters( 'wps_product_add_to_cart_attr', '', $product ); ?>
		data-action="add_to_cart"
		data-nonce="<?php echo wp_create_nonce( 'add_to_cart' ); ?>"
		data-id="<?php echo esc_attr( $product->data['id'] ); ?>"><?php echo esc_html( $a['text'] ); ?>
	</div>
</div>
