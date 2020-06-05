<?php
/**
 * Affichage de la page mon compte
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

<ul class="wps-account-navigation gridw-2">
	<?php
	if ( ! empty( $this->menu ) ) :
		foreach ( $this->menu as $key => $element ) :
			?>
			<li class="wps-account-navigation-item">
				<a class="<?php echo ( $key === $tab ) ? 'active' : ''; ?>"
					href="<?php echo esc_attr( $element['link'] ); ?>">
					<i class="navigation-icon <?php echo esc_attr( $element['icon'] ); ?>"></i>
					<?php echo esc_html( $element['title'] ); ?>
				</a>
			</li>
			<?php
		endforeach;
	endif;
	?>
</ul>
