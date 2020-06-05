<?php
/**
 * Affichage du stock
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 * @package   WPshop\Templates
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<p>
	<?php
	// translators: %s en stock.
	printf( __( '%s in stock', 'wpshop' ), $product->data['stock'] );
	?>
</p>
