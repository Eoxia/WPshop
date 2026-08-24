<?php
/**
 * La vue affichant la metabox des produits dans le tableau de bord.
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
 * @var string  $dolibarr_url            L'url de dolibarr.
 * @var string  $dolibarr_products_lists L'url de la liste des produits sur dolibarr.
 * @var array   $products                Le tableau contenant toutes les données des produits.
 * @var Product $product                 Les données d'un produit.
 */
?>


<div class="wps-dashboard-card gridw-3">
	<div class="wps-dashboard-card-header">
		<h3 class="wps-dashboard-card-title">
			<?php esc_html_e( 'Latest products', 'wpshop' ); ?>
			<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_products_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>
		</h3>
	</div>

	<table class="wps-dashboard-table">
		<thead>
			<tr>
				<th>#</th>
				<th><?php esc_html_e( 'Title', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></th>
				<th><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $products ) ) :
				foreach ( $products as $product ) : ?>
					<tr>
						<td>
							<a href="<?php echo esc_attr( admin_url( 'post.php?action=edit&post=' . $product->data['id'] ) ); ?>">
								<?php echo esc_html( $product->data['id'] ); ?>
							</a>
						</td>
						<td>
							<a href="<?php echo esc_attr( admin_url( 'post.php?action=edit&post=' . $product->data['id'] ) ); ?>">
								<?php echo esc_html( $product->data['title'] ); ?>
							</a>
						</td>
						<td><?php echo esc_html( number_format( $product->data['price_ttc'], 2, ',', ' ' ) ); ?> €</td>
						<td><?php echo esc_html( $product->data['date']['rendered']['date_time'] ); ?></td>
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td colspan="4" style="text-align: center; color: #999;">
						<?php esc_html_e( 'No product for the moment', 'wpshop' ); ?>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
