<?php
/**
 * Le footer de la modal de comparaison.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019-2020 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

/**
 * @var string $type     Can be product, third-party or propal.
 * @var int    $entry_id Dolibarr entry ID.
 * @var int    $wp_id    WP Entry ID.
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-button button-light modal-close">
	<span><?php esc_html_e( 'Close', 'wpshop' ); ?></span>
</div>
