<?php
/**
 * La vue affichant la liste des produits dans le frontend.
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
 * @var Product $product Les données d'un produit.
 */
?>

<div itemscope itemtype="https://schema.org/Product" class="wps-product">
	<figure class="wps-product-thumbnail">
		<?php if ( ! empty( $product['thumbnail_id'] ) ) :
			echo wp_get_attachment_image( $product['thumbnail_id'], 'thumbnail', '', array(
				'class'    => 'attachment-wps-product-thumbnail',
				'itemprop' => 'image',
			) );
		else :
			echo '<img src="' . PLUGIN_WPSHOP_URL . '/core/asset/image/default-product-thumbnail-min.jpg" class="attachment-wps-product-thumbnail" itemprop="image" /> ';
		endif; ?>
	</figure>
	<div class="wps-product-content">
		<div itemprop="name" class="wps-product-title"><?php echo esc_html( $product['title'] ); ?></div>
		<ul class="wps-product-attributes">
			<li class="wps-product-attributes-item"><?php echo esc_html_e( 'Unit price:', 'wpshop' ) . ' ' . esc_html( number_format( $product['price_ttc'], 2, '.', '' ) ); ?>€</li>
		</ul>
		<div class="wps-product-footer">
			<div class="wps-product-quantity"><?php echo esc_html( $product['qty'] ); ?></div>
			<?php if ( ! empty( $product['price_ttc'] ) ) : ?>
				<div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
					<span itemprop="price" content="<?php echo esc_html( number_format( $product['price_ttc'] * $product['qty'], 2, '.', '' ) ); ?>"><?php echo esc_html( number_format( $product['price_ttc'] * $product['qty'], 2, '.', '' ) ); ?></span>
					<span itemprop="priceCurrency" content="EUR"><?php echo esc_html( '€', 'wpshop' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
