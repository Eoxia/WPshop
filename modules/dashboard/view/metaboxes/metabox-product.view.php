<?php
/**
 * La metabox des produits dans le tableau de bord.
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
 * @var string        $dolibarr_url            L'url de dolibarr.
 * @var string        $dolibarr_products_lists L'url de la liste des produits sur dolibarr.
 * @var array         $products                Le tableau contenant toutes les données des produits.
 * @var Product_Model $product                 Les données d'un produit.
 */
?>


<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Latest products', 'wpshop' ); ?></h3>
	<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_products_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell"><?php esc_html_e( 'Title', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
		</div>

		<?php
		if ( ! empty( $products ) ) :
			foreach ( $products as $product ) :
				?>
				<div class="table-row">
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'post.php?action=edit&post=' . $product->data['id'] ) ); ?>"><?php echo esc_html( $product->data['id'] ); ?></a></div>
					<div class="table-cell break-word"><a href="<?php echo esc_attr( admin_url( 'post.php?action=edit&post=' . $product->data['id'] ) ); ?>"><?php echo esc_html( $product->data['title'] ); ?></a></div>
					<div class="table-cell"><?php echo esc_html( number_format( $product->data['price_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><?php echo esc_html( $product->data['date']['rendered']['date_time'] ); ?></div>
				</div>
				<?php
			endforeach;
		else :
			?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No product for the moment', 'wpshop' ); ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
