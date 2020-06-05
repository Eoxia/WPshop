<?php
/**
 * Item in modal search.
 *
 * @todo: Translate to english.
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
 * @var string $label Entity Name.
 * @var int    $wp_id ID Entity on WP.
 * @var string $type  Type of the Entity (Can be product, propal, third-party).
 */

defined( 'ABSPATH' ) || exit; ?>

<li data-id="<?php echo esc_attr( $entry->id ); ?>">
	<?php echo apply_filters( 'wps_associate_entry', '#' . $entry->id . ' ' . $entry->name, $entry ); ?>
</li>
