<?php
/**
 * La vue affichant la métabox "Propostions commerciales".
 * Page d'un tier.
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
 * @var array          $proposals Le tableau contenant toutes les données des propositions commerciales.
 * @var Doli_Proposals $proposal  Les données d'une proposition commerciale.
 */
?>

<div class="wps-metabox wps-billing-address view gridw-2">
	<h3 class="metabox-title"><?php esc_html_e( 'Proposals', 'wpshop' ); ?></h3>

	<div class="wpeo-table table-flex table-4">
		<div class="table-row table-header">
			<div class="table-cell"><?php esc_html_e( 'Proposal', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Date', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( '€ TTC', 'wpshop' ); ?></div>
			<div class="table-cell"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		</div>

		<?php if ( ! empty( $proposals ) ) :
			foreach ( $proposals as $proposal ) : ?>
				<div class="table-row">
					<div class="table-cell">
						<a href="<?php echo admin_url( 'admin.php?page=wps-proposal-doli&id=' . $proposal->data['external_id'] ); ?>">
							<?php echo esc_html( $proposal->data['title'] ); ?>
						</a>
					</div>
					<div class="table-cell"><?php echo esc_html( $proposal->data['date']['rendered']['date'] ); ?></div>
					<div class="table-cell"><?php echo esc_html( number_format( $proposal->data['total_ttc'], 2, ',', '' ) ); ?>€</div>
					<div class="table-cell"><strong><?php echo Doli_Statut::g()->display_status( $proposal ); ?></strong></div>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</div>
