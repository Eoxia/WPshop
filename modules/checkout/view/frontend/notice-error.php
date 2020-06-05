<?php
/**
 * Affichages des messages d'erreur.
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

<ul class="error notice">
	<?php
	if ( ! empty( $errors->get_error_messages() ) ) :
		foreach ( $errors->get_error_messages() as $message ) :
			?>
			<li><?php echo $message; ?></li>
			<?php
		endforeach;
	endif;
	?>
</ul>
