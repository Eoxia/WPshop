<?php
/**
 * Bouton pour aller au panier.
 *
 * @package   WPshop\Templates
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product_Model $product La donnée d'un produit.
 * @var integer       $qty     La quantité d'un produit.
 *
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
	<div class="notification-close"><i class="far fa-times"></i></div>
</div>
