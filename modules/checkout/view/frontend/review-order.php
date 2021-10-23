<?php
/**
 * La vue affichant le formulaire pour avoir un aperçu de sa commande.
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
 * @todo a revoir
 *
 * @var Cart_Session $cart_contents Tableau contenant toutes les données du panier.
 * @var string       $cart_item     Les données d'un item du panier.
 * @var Proposals    $proposal      Les données d'un devis.
 * @var array        $tva_lines     Le tableau contenant toutes les données des tva.
 * @var string       $key           A faire.
 * @var integer      $tva_line      Les données d'une tva.
 */
?>

<table class="wpeo-table wps-checkout-review-order-table">
	<thead>
		<tr>
			<th></th>
			<th data-title="<?php esc_html_e( 'Product name', 'wpshop' ); ?>"><?php esc_html_e( 'Product name', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'VAT', 'wpshop' ); ?>"><?php esc_html_e( 'VAT', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'P.U. HT', 'wpshop' ); ?>"><?php esc_html_e( 'P.U HT', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'Quantity', 'wpshop' ); ?>"><?php esc_html_e( 'Quantity', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'Total HT', 'wpshop' ); ?>"><?php esc_html_e( 'Total HT', 'wpshop' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'wps_review_order_before_cart_contents' );

		if ( ! empty( $cart_contents ) ) :
			foreach ( $cart_contents as $cart_item ) : ?>
				<tr>
					<td><?php echo get_the_post_thumbnail( $cart_item['id'], array( 80, 80 ) ); ?></td>
					<td data-title="<?php echo esc_html( 'Product name', 'wpshop' ); ?>"><a href="<?php echo esc_url( get_permalink( $cart_item['id'] ) ); ?>"><?php echo esc_html( $cart_item['title'] ); ?></a></td>
					<td data-title="<?php echo esc_html( 'VAT', 'wpshop' ); ?>"><?php echo esc_html( number_format( $cart_item['tva_tx'], 2, ',', '' ) ); ?>%</td>
					<td data-title="<?php echo esc_html( 'P.U. HT', 'wpshop' ); ?>"><?php echo esc_html( number_format( $cart_item['price'], 2, ',', '' ) ); ?>€</td>
					<td data-title="<?php echo esc_html( 'Quantity', 'wpshop' ); ?>"><?php echo esc_html( $cart_item['qty'] ); ?></td>
					<td data-title="<?php echo esc_html( 'Total HT', 'wpshop' ); ?>"><?php echo esc_html( number_format( $cart_item['price'] * $cart_item['qty'], 2, ',', '' ) ); ?>€</td>
				</tr>
			<?php endforeach;
		endif;

		do_action( 'wps_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
			<td><?php echo number_format( $proposal->data['total_ht'], 2, ',', '' ); ?>€</td>
		</tr>
		<?php if ( ! empty( $tva_lines ) ) :
			foreach ( $tva_lines as $key => $tva_line ) : ?>
				<tr>
					<td colspan="5"><strong><?php esc_html_e( 'Total VAT', 'wpshop' ); ?> <?php echo number_format( $key, 2, ',', '' ); ?>%</strong></td>
					<td><?php echo number_format( $tva_line, 2, ',', '' ); ?>€</td>
				</tr>
			<?php endforeach;
		endif; ?>

		<tr>
			<td colspan="5"><strong><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></strong></td>
			<td><strong><?php echo number_format( $proposal->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>

<div class="">

</div>
