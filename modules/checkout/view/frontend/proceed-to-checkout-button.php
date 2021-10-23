<?php
/**
 * La vue affichant le bouton pour passer au tunnel de vente.
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
 * @var string $link_checkout L'url du tunnel de vente.
 */
?>

<a href="<?php echo esc_url( $link_checkout ); ?>" class="wpeo-button alignright wps-process-checkout-button">
	<?php echo apply_filters( 'wps_cart_to_checkout_link_title', __( 'Proceed to checkout', 'wpshop' ) ); ?>
</a>
