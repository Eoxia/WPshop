<?php
/**
 * La vue principale de la page de migration des données de WPshop 1.x.x vers
 * WPshop 2.x.x
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

<div class="wrap wpeo-wrap transfert-data">
	<h1>Transfère des tiers de WPshop 1.x.x</h1>
	<?php wp_nonce_field( 'wps_transfert_data' ); ?>
	<input type="hidden" name="number_customers" value="<?php echo esc_attr( $number_customers ); ?>" />
	<input type="hidden" name="key_query" value="1" />
	<input type="hidden" name="index" value="0" />

	<h2>Output</h2>
	<ul class="output"></ul>

	<h2>
		<span>Errors</span>
		<i class="toggle fas fa-toggle-off"></i>
	</h2>
	<ul class="errors hidden"></ul>
</div>
