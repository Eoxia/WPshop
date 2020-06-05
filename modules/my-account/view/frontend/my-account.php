<?php
/**
 * Affichage de la page mon compte
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

defined( 'ABSPATH' ) || exit;

?>
<div class="wps-account-page wpeo-gridlayout grid-6 alignwide">
	<?php do_action( 'wps_account_navigation', $tab ); ?>

	<div class="wps-account gridw-4">
		<?php do_action( 'wps_account_' . $tab ); ?>
	</div>
</div>
