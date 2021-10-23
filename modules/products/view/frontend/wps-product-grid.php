<?php
/**
 * La vue affichant les informations du produit dans le panier.
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
 * @var array $post Le tableau contenant toutes les données des produits.
 */
?>

<div itemscope itemtype="https://schema.org/Product" class="wps-product">
	<figure class="wps-product-thumbnail">

		<?php
		$post->data = (array) $post;

		if ( has_post_thumbnail() ) :
			the_post_thumbnail( 'wps-product-thumbnail', array(
				'class'    => 'attachment-wps-product-thumbnail',
				'itemprop' => 'image',
			) );
		else :
			echo '<img src="' . PLUGIN_WPSHOP_URL . '/core/asset/image/default-product-thumbnail.jpg" class="attachment-wps-product-thumbnail" itemprop="image" /> ';
		endif; ?>

		<div class="wps-product-action">
			<a itemprop="url" href="<?php the_permalink(); ?>" class="wpeo-button button-square-40 button-rounded button-light wpeo-tooltip-event"
				aria-label="<?php esc_html_e( 'See the product', 'wpshop' ); ?>">
				<i class="button-icon fas fa-eye"></i>
			</a>

			<?php if ( Cart::g()->can_add_product() ) : ?>
				<div class="wps-product-buy wpeo-button button-square-40 button-rounded button-light wpeo-tooltip-event action-attribute <?php echo apply_filters( 'wps_product_add_to_cart_class', '', $post ); ?>"
					<?php echo apply_filters( 'wps_product_add_to_cart_attr', '', $post ); ?>
					data-action="add_to_cart"
					data-nonce="<?php echo wp_create_nonce( 'add_to_cart' ); ?>"
					data-id="<?php echo esc_attr( the_ID() ); ?>"
					aria-label="<?php esc_html_e( 'Add the product to cart', 'wpshop' ); ?>">
					<i class="button-icon fas fa-cart-arrow-down"></i>
				</div>
			<?php endif ?>

<!--			<div class="wps-wishlist wpeo-button button-square-40 button-rounded button-light wpeo-tooltip-event wpeo-modal-event"-->
<!--				data-action="load_add_to_wishlist"-->
<!--				data-nonce="--><?php //echo wp_create_nonce( 'load_add_to_wishlist' ); ?><!--"-->
<!--				data-id="--><?php //echo esc_attr( the_ID() ); ?><!--"-->
<!--				aria-label="--><?php //esc_html_e( 'Add to wish list', 'wpshop' ); ?><!--">-->
<!--				<i class="button-icon fas fa-plus"></i>-->
<!--			</div>-->
		</div>

		<a itemprop="url" href="<?php the_permalink(); ?>" class="wps-product-link"></a>
	</figure>
	<div class="wps-product-content">
		<div itemprop="name" class="wps-product-title"><?php the_title(); ?></div>
		<?php if ( ! empty( $post->price_ttc ) ) : ?>
			<div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
				<span itemprop="price" content="<?php echo esc_html( number_format( $post->price_ttc, 2, '.', '' ) ); ?>"><?php echo esc_html( number_format( $post->price_ttc, 2, ',', '' ) ); ?></span>
				<span itemprop="priceCurrency" content="EUR"><?php echo esc_html( '€', 'wpshop' ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</div>
