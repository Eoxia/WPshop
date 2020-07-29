<?php
/**
 * La vue affichant la page d'un tier lors de l'édition.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
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

<div class="wrap wpeo-wrap page-single">
	<div class="page-header">
		<h2>
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
		</h2>
	</div>
	<div style=""><?php do_action( 'wps_listing_table_end', $third_party, $sync_status = true ); ?></div>
	<div class="wps-page-content wpeo-gridlayout grid-6">
		<?php do_action( 'wps_third_party', $third_party ); ?>
	</div>
</div>
