<?php
/**
 * La vue pour l'affichage du titre d'un tier.
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

<div>
	<?php printf( __( 'Tier <span style="font-weight: 700;">%s</span>', 'wpshop' ), esc_html( $third_party->data['title'] ) ); ?>
</div>
