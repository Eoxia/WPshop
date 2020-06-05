<?php
/**
 * Affichage du listing des contact dans le tableau des tiers dans le backend.
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


<div class="list-contact">
	<?php
	if ( ! empty( $contacts ) ) :
		foreach ( $contacts as $contact ) :
			\eoxia\View_Util::exec( 'wpshop', 'user', 'item', array(
				'contact' => $contact,
			) );
		endforeach;
	endif;
	?>
</div>
