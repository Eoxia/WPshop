<?php
/**
 * La metabox des clients dans le tableau de bord.
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
 * @var string            $dolibarr_url          L'url de dolibarr.
 * @var string            $dolibarr_tiers_lists  L'url de la liste des tiers sur dolibarr.
 * @var array             $third_parties         Le tableau contenant toutes les données des tiers.
 * @var Third_Party_Model $third_party           Les données d'un tier.
 */
?>

<div class="wps-metabox view gridw-3">
	<h3 class="metabox-title"><?php esc_html_e( 'Latest customers', 'wpshop' ); ?></h3>
	<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_tiers_lists ); ?>" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a>

	<div class="wpeo-table table-flex table-3">
		<div class="table-row table-header">
			<div class="table-cell">#</div>
			<div class="table-cell">Nom</div>
			<div class="table-cell">Date</div>
		</div>

		<?php
		if ( ! empty( $third_parties ) ) :
			foreach ( $third_parties as $third_party ) :
				?>
				<div class="table-row">
					<div class="table-cell"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ) ); ?>"><?php echo esc_html( $third_party->data['id'] ); ?></a></div>
					<div class="table-cell break-word"><a href="<?php echo esc_attr( admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ) ); ?>"><?php echo esc_html( $third_party->data['title'] ); ?></a></div>
					<div class="table-cell"><?php echo esc_html( $third_party->data['date']['rendered']['date_time'] ); ?></div>
				</div>
				<?php
			endforeach;
		else :
			?>
			<div class="table-row">
				<div class="table-cell">
					<?php esc_html_e( 'No customer for the moment', 'wpshop' ); ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
