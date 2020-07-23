<?php
/**
 * La metabox des envies dans le tableau de bord.
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
 * @var array           $wishlists Le tableau contenant toutes les données des envies.
 * @var Proposals_Model $whishlist Les données d'une envie.
 */
?>

<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Latest wishes', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell break-word"><?php esc_html_e( 'Customer', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Price TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
		</div>

		<?php
		if ( ! empty( $wishlists ) ) :
			foreach ( $wishlists as $whishlist ) :
				?>
				<div class="table-row">
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-proposal&id=' . $whishlist->data['id'] ) ); ?>"><?php echo esc_html( $whishlist->data['title'] ); ?></a></div>
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $whishlist->data['third_party']->data['id'] ) ); ?>"><?php echo esc_html( $whishlist->data['third_party']->data['title'] ); ?></a></div>
					<div class="table-cell"><?php echo esc_html( number_format( $whishlist->data['total_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><?php echo esc_html( $whishlist->data['date']['rendered']['date_time'] ); ?></div>
				</div>
				<?php
			endforeach;
		else :
			?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No wish for the moment', 'wpshop' ); ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
