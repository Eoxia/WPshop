<?php
/**
 * La vue affichant le bouton pour aller au panier.
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
 * @var Product $product La donnée d'un produit.
 * @var integer $qty     La quantité d'un produit.
 */
?>

<div class="wpeo-notification notification-active notification-add-to-cart notification-blue" style="opacity: 1; background: rgba(255,255,255,1);">
	<i class="notification-icon fas fa-info"></i>
	<div class="notification-title">
		<?php printf( __( 'Product "%1$s" x%2$d added to the card', 'wpshop' ), $product->data['title'], $qty ); ?>

		<a href="<?php echo esc_url( Pages::g()->get_cart_link() ); ?>" class="view-cart wpeo-button button-grey">
			<?php esc_html_e( 'View cart', 'wpshop' ); ?>
		</a>
	</div>
	<div class="notification-close"><i class="fas fa-times"></i></div>
</div>
