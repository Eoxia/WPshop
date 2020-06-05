<?php
/**
 * Modal Associate first step. Search element by type.
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

use eoxia\View_Util;

/**
 * @var string $label Entity Name.
 * @var int    $wp_id ID Entity on WP.
 * @var string $type  Type of the Entity (Can be product, propal, third-party).
 */

defined( 'ABSPATH' ) || exit; ?>

<p>
	<?php
	printf( __( 'This %s is not associated to a %s in Dolibarr', 'wpshop' ), esc_html( $label ), esc_html( $label ) );
	?>
</p>

<p>
	<?php
	printf( __( 'Please select a %s to associate in the next elements:', 'wpshop' ), esc_html( $label ) );
	?>
</p>

<p>
	<?php
	printf( __( '%s available in your ERP', 'wpshop' ), ucfirst( esc_html( $label ) ) );
	?>
</p>

<!-- Dolibarr id -->
<input type="hidden" name="entry_id" />

<!-- WP id -->
<input type="hidden" name="wp_id" value="<?php echo esc_attr( $wp_id ); ?>" />

<!-- Type can be product, propal or third-party -->
<input type="hidden" name="type" value="<?php echo esc_attr( $type ); ?>" />

<input type="text" class="filter-entry" placeholder="<?php esc_attr_e( 'Search...', 'wpshop' ); ?>" />

<ul class="select">
	<?php
	if ( ! empty( $entries ) ) :
		foreach ( $entries as $entry ) :
			View_Util::exec( 'wpshop', 'doli-associate', 'item-' . $type, array(
				'entry' => $entry,
			) );
		endforeach;
	endif;
	?>
</ul>
