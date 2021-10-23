<?php
/**
 * La vue principale affichant la page mon compte.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string $tab Le menu de navigation courrant.
 */
?>

<div class="wps-account-page wpeo-gridlayout grid-6 alignwide">
	<?php do_action( 'wps_account_navigation', $tab ); ?>

	<div class="wps-account gridw-4">
		<?php do_action( 'wps_account_' . $tab ); ?>
	</div>
</div>
