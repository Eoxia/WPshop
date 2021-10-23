<?php
/**
 * La vue principale du tableau de bord.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wrap wpeo-wrap">
<!--	<h2>--><?php //esc_html_e( 'Dashboard', 'wpshop' ); ?><!--</h2>-->

	<div class="wpeo-gridlayout grid-2">
		<?php do_action( 'wps_dashboard' ); ?>
	</div>
</div>
