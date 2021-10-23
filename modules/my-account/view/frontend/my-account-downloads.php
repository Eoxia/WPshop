<?php
/**
 * La vue affichant les produits téléchargables.
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
 * @var array                $products_downloadable Le tableau contenant toutes les données des produits téléchargeables.
 * @var Product_Downloadable $product_downloadable  Les données d'un produit téléchargeable.
 */
?>

?>
<div class="wps-list-invoice wps-list-box">
	<?php
	if ( ! empty( $products_downloadable ) ) :
		foreach ( $products_downloadable as $product_downloadable ) :
			?>
			<div class="wps-order wps-box">
				<div class="wps-box-resume">
					<div class="wps-box-primary">
						<div class="wps-box-title"><?php echo esc_attr( $product_downloadable->data['title'] ); ?></div>
						<ul class="wps-box-attributes">
							<li class="wps-box-subtitle-item"><i class="wps-box-subtitle-icon fas fa-shopping-cart"></i> <?php echo esc_html( $product_downloadable->data['date']['rendered']['date'] ); ?></li>
							<li class="wps-box-subtitle-item"><i class="wps-box-subtitle-icon fas fa-shopping-basket"></i> <?php echo esc_html( get_the_title( $product_downloadable->data['product_id'] ) ); ?></li>
						</ul>
					</div>
					<div class="wps-box-action">
						<a href="<?php echo esc_attr( admin_url( 'admin-post.php?action=wps_download_product&_wpnonce=' . wp_create_nonce( 'download_product' ) . '&product_id=' . $product_downloadable->data['id'] ) ); ?>" class="wpeo-button button-primary button-square-50 button-rounded">
							<i class="button-icon fas fa-file-download"></i>
						</a>
					</div>
				</div>
			</div>
			<?php
		endforeach;
	else :
		?>
		<div class="wpeo-notice notice-info">
			<div class="notice-content">
				<div class="notice-title"><?php esc_html_e( 'No downloadable product', 'wpshop' ); ?></div>
			</div>
		</div>
		<?php
	endif;
	?>
</div>
