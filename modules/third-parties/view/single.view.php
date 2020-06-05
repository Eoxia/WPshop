<?php
/**
 * La vue single de la page d'un tier lors de l'édition.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wrap wpeo-wrap page-single">
	<div class="page-header">
		<h2>
			<?php
			if ( 0 === $third_party->data['id'] ) :
				\eoxia\View_Util::exec( 'wpshop', 'third-parties', 'single-title-edit', array(
					'third_party' => $third_party,
				) );
			else :
				\eoxia\View_Util::exec( 'wpshop', 'third-parties', 'single-title', array(
					'third_party' => $third_party,
				) );
			endif;
			?>
		</h2>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-6">
		<?php do_action( 'wps_third_party', $third_party ); ?>
	</div>
</div>
