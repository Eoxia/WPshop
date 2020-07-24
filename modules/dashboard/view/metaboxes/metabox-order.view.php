<?php
/**
 * La metabox des commandes dans le tableau de bord.
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
 * @var string     $dolibarr_url          L'url de dolibarr.
 * @var string     $dolibarr_orders_lists L'url de la liste des commandes sur dolibarr.
 * @var array      $orders                Le tableau contenant toutes les données des commandes.
 * @var Doli_Order $order                 Les données d'une commande.
 */
?>

<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Latest orders', 'wpshop' ); ?></h3>
	<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_orders_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell break-word"><?php esc_html_e( 'Customer', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
		</div>

		<?php
		if ( ! empty( $orders ) ) :
			foreach ( $orders as $order ) :
				?>
				<div class="table-row">
					<div class="table-cell"><a href="<?php echo esc_attr( $dolibarr_url . '/commande/card.php?id=' . $order->data['external_id'] ); ?>"><?php echo esc_html( $order->data['title'] ); ?></a></div>
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $order->data['third_party']->data['id'] ) ); ?>"><?php echo esc_html( $order->data['third_party']->data['title'] ); ?></a></div>
					<div class="table-cell"><?php echo esc_html( number_format( $order->data['total_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><?php echo esc_html( date( 'd/m/Y H:i', strtotime( $order->data['datec'] ) ) ); ?></div>
				</div>
				<?php
			endforeach;
		else :
			?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No order for the moment', 'wpshop' ); ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
