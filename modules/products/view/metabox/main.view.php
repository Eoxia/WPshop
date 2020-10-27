<?php
/**
 * La vue principale de la page des produits.
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
 * @var Product $product          Les données d'un produit.
 */
?>

<div class="wpeo-wrap">
	<?php if ( Settings::g()->dolibarr_is_active() ) : ?>
		<div class="wps-metabox-subtitle"><?php esc_html_e( 'Description', 'wpshop' ); ?></div>
		<div class="wps-product-description"><p><?php echo $product->data['content']; ?></p></div>

		<div class="wpeo-gridlayout grid-3">
			<div>
				<div class="wps-metabox-subtitle"><?php esc_html_e( 'Price HT(€)', 'wpshop' ); ?></div>
				<div class="wps-metabox-content"><?php echo $product->data['price'] != 0 ? esc_html( $product->data['price'] ) : ''; ?></div>
			</div>
			<div>
				<div class="wps-metabox-subtitle"><?php esc_html_e( 'VAT Rate', 'wpshop' ); ?></div>
				<div class="wps-metabox-content"><?php echo esc_html( $product->data['tva_tx'] ); ?>%</div>
			</div>
			<div>
				<div class="wps-metabox-subtitle"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
				<div class="wps-metabox-content"><?php echo esc_html( $product->data['price_ttc'] ); ?></div>
			</div>
		</div>
	<?php else : ?>
		<div class="wpeo-notice notice-info">
			<div class="notice-content">
				<div class="notice-subtitle">
					<a href="<?php echo esc_url( admin_url('admin.php?page=wps-settings') ); ?>"><?php esc_html_e( 'Connect WPshop to your ERP to edit product datas', 'wpshop' ); ?></a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
