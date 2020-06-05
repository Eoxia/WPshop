<?php
/**
 * Affichage les produits téléchargables dans la page "Mon compte"
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

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
