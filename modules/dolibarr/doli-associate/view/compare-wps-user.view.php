<?php
/**
 * La vue affichant le contenu de la modal de synchronisation pour les utilisateurs.
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
 * @var array  $entries Le tableau contenant toutes les données des utilisateurs.
 * @var string $key     L'utilisateur.
 * @var User   $entry   Les données d'un utilisateur.
 */
?>

<div class="wpeo-gridlayout grid-2">
	<?php if ( ! empty( $entries ) ) :
		foreach ( $entries as $key => $entry ) : ?>
			<div class="choose <?php echo esc_attr( $key ); ?>">
				<h2><?php echo $entry['title']; ?>

				<?php // translators: Last update the 06/06/2019 10:10:10. ?>

				<ul>
					<li><strong><?php esc_html_e( 'Display name', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['displayname'] ) ? esc_html( $entry['data']['displayname'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Lastname', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['lastname'] ) ? esc_html( $entry['data']['lastname'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Firstname', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['firstname'] ) ? esc_html( $entry['data']['firstname'] ) : 'Non définie'; ?></li>
					<li><strong><?php esc_html_e( 'Email', 'wpshop' ); ?></strong>: <?php echo ! empty( $entry['data']['email'] ) ? esc_html( $entry['data']['email'] ) : 'Non définie'; ?></li>
				</ul>
			</div>
		<?php endforeach;
	endif; ?>
</div>
