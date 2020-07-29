<?php
/**
 * La vue affichant une commande.
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
 * @var Doli_Order $order Les données d'une commande.
 */
?>

<div class="wrap wpeo-wrap page-single">
	<div class="page-header">
		<h2><?php echo esc_html__( 'Order', 'wpshop' ) . ' ' . esc_html( $order->data['title'] ); ?></h2>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-4">
		<?php do_action( 'wps_order', $order ); ?>
	</div>
</div>
