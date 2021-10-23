<?php
/**
 * La vue affichant le titre et le statut de synchronisation
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product          Les données d'un produit.
 * @var string  $sync_status      True si on affiche le statut de la synchronisation.
 * @var string  $doli_url         L'url de Dolibarr.
 */
?>

<div class="wpeo-wrap">

	<div class="wps-product-title-container">
		<div class="wps-product-title"><?php echo esc_html( $product->data['title'] ); ?></div>
		<div class="wps-product-title-actions">
			<a class="button <?php echo empty( $product->data['external_id'] ) ? 'disabled' : ''; ?>" href="<?php echo esc_attr( $doli_url ); ?>/product/card.php?id=<?php echo $product->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a>
			<a class="button" href="<?php echo esc_url( get_post_permalink( $product->data['id'] ) ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'wpshop' ); ?></a>
		</div>
	</div>
	<?php do_action( 'wps_listing_table_end', $product, $sync_status ); ?>

</div>
