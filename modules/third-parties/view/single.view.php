<?php
/**
 * La vue affichant la page d'un tier lors de l'édition.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Third_Party $third_party Les données d'un tier.
 * @var boolean     $sync_status True si on affiche le statut de la synchronisation.
 */
?>

<div class="wrap wpeo-wrap wpeo-page-single">
	<div class="wps-page-header">
		<div class="wps-page-header-title-container">
			<div class="wps-page-header-title">
				<?php
				if ( 0 === $third_party->data['id'] ) :
					View_Util::exec( 'wpshop', 'third-parties', 'single-title-edit', array(
						'third_party' => $third_party,
					) );
				else :
					View_Util::exec( 'wpshop', 'third-parties', 'single-title', array(
						'third_party' => $third_party,
					) );
				endif;
				?>
			</div>
			<div class="wps-page-header-actions">
				<?php if ( Settings::g()->dolibarr_is_active() ) : ?>
					<a class="button <?php echo empty( $third_party->data['external_id'] ) ? 'disabled' : ''; ?>" href="<?php echo esc_attr( $doli_url ); ?>/societe/card.php?id=<?php echo $third_party->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php do_action( 'wps_listing_table_end', $third_party, $sync_status = true ); ?>
	</div>
	<div class="wps-page-content wpeo-gridlayout grid-6">
		<?php do_action( 'wps_third_party', $third_party ); ?>
	</div>
</div>
