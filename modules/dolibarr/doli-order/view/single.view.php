<?php
/**
 * La vue affichant une commande.
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
 * @var Doli_Order $order Les données d'une commande.
 */
?>

<div class="wrap wpeo-wrap page-single">
	<div class="wps-page-header">
		<div class="wps-page-header-title-container">
			<div class="wps-page-header-title"><?php echo esc_html__( 'Order', 'wpshop' ) . ' <strong>' . esc_html( $order->data['title'] ) . '</strong>'; ?></div>
			<div class="wps-page-header-actions">
				<a class="button <?php echo empty( $order->data['external_id'] ) ? 'disabled' : ''; ?>" href="<?php echo esc_attr( $doli_url ); ?>/commande/card.php?id=<?php echo $order->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a>
			</div>
		</div>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-4">
		<?php do_action( 'wps_order', $order ); ?>
	</div>
</div>
