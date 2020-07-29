<?php
/**
 * La vue affichant le contenu de la modal de synchronisation pour les propositions commerciales.
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
 * @var array          $entries Le tableau contenant toutes les données des propositions commerciales.
 * @var string         $key     La proposition commerciale.
 * @var Doli_Proposals $entry   Les données d'une proposition commerciale.
 */
?>

<div class="wpeo-gridlayout grid-2">
	<?php if ( ! empty( $entries ) ) :
		foreach ( $entries as $key => $entry ) : ?>
			<div class="choose <?php echo esc_attr( $key ); ?>">
				<h2><?php echo $entry['title']; ?>

				<?php // translators: Last update the 06/06/2019 10:00:00. ?>
				<p><?php printf( __( 'Last update the %s', 'wpshop' ), $entry['data']['date']['rendered']['date_time'] ); ?></p>

				<ul>
					<li><strong><?php esc_html_e( 'Ref', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['title'] ) ? esc_html( $entry['data']['title'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['total_ht'] ) ? esc_html( $entry['data']['total_ht'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Tva Amount', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['tva_amount'] ) ? esc_html( $entry['data']['tva_amount'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['total_ttc'] ) ? esc_html( $entry['data']['total_ttc'] ) : 'Non définie'; ?></li>
				</ul>
			</div>
		<?php endforeach;
	endif; ?>
</div>
