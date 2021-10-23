<?php
/**
 * La vue affichant l'édition de la liste des produits dans le frontend.
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
 * @var string  $key     Le type de produit.
 * @var Product $product Les données d'un produit.
 */
?>

<input type="hidden" name="products[<?php echo esc_attr( $key ); ?>][id]" value="<?php echo esc_attr( $product['id'] ); ?>" />

<div itemscope itemtype="https://schema.org/Product" class="wps-product">
	<a href="#" class="wps-delete-product action-attribute"
		data-action="delete_product_from_cart"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'ajax_delete_product_from_cart' ) ); ?>"
		data-key="<?php echo esc_attr( $key ); ?>">
		<i class="wps-delete-product-icon fas fa-times-circle"></i>
	</a>

	<figure class="wps-product-thumbnail">
		<?php
		if ( ! empty( $product['thumbnail_id'] ) ) :
			echo wp_get_attachment_image( $product['thumbnail_id'], 'thumbnail', '', array(
				'class'    => 'attachment-wps-product-thumbnail',
				'itemprop' => 'image',
			) );
		else :
			echo '<img src="' . PLUGIN_WPSHOP_URL . '/core/asset/image/default-product-thumbnail-min.jpg" class="attachment-wps-product-thumbnail" itemprop="image" /> ';
		endif;
		?>
	</figure>

	<div class="wps-product-content">
		<div itemprop="name" class="wps-product-title"><?php echo esc_html( $product['title'] ); ?></div>
		<ul class="wps-product-attributes">
			<li class="wps-product-attributes-item"><?php echo esc_html_e( 'Unit price:', 'wpshop' ) . ' ' . esc_html( number_format( $product['price_ttc'], 2, '.', '' ) ); ?>€</li>
		</ul>
		<div class="wps-product-footer">
			<?php if ( ! Settings::g()->split_product() ) : ?>
				<div class="wps-product-quantity" style="font-size: 1.4em;">
					<input type="hidden" name="products[<?php echo esc_attr( $key ); ?>][qty]" value="<?php echo esc_attr( $product['qty'] ); ?>" />
					<span class="wps-quantity-minus fas fa-minus-circle"></span>
					<span class="qty"><?php echo esc_html( $product['qty'] ); ?></span>
					<span class="wps-quantity-plus fas fa-plus-circle"></span>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $product['price_ttc'] ) ) : ?>
				<div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
					<span itemprop="price" content="<?php echo esc_html( number_format( $product['price_ttc'] * $product['qty'], 2, '.', '' ) ); ?>"><?php echo esc_html( number_format( $product['price_ttc'] * $product['qty'], 2, '.', '' ) ); ?></span>
					<span itemprop="priceCurrency" content="EUR"><?php echo esc_html( '€', 'wpshop' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
