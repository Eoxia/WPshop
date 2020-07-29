<?php
/**
 * La vue affichant le contenu de la modal de synchronisation pour les tiers.
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
 * @var array       $entries Le tableau contenant toutes les données des tiers.
 * @var string      $key     Le tier.
 * @var Third_Party $entry   Les données d'une tier.
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
					<li><strong><?php esc_html_e( 'Email', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['email'] ) ? esc_html( $entry['data']['email'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Tier name', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['title'] ) ? esc_html( $entry['data']['title'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Address', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['address'] ) ? esc_html( $entry['data']['address'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Postcode / ZIP', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['zip'] ) ? esc_html( $entry['data']['zip'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Town', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['town'] ) ? esc_html( $entry['data']['town'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Country', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['country'] ) ? esc_html( $entry['data']['country'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Phone number', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['phone'] ) ? esc_html( $entry['data']['phone'] ) : 'Non définie'; ?></li>
				</ul>
			</div>
		<?php endforeach;
	endif; ?>
</div>
