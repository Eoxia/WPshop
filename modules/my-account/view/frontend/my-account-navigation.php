<?php
/**
 * La vue affichant la navigation.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string $this    A faire.
 * @var string $key     A faire.
 * @var string $element A faire.
 * @var string $tab     A faire.
 */
?>

<ul class="wps-account-navigation gridw-2">
	<?php if ( ! empty( $this->menu ) ) :
		foreach ( $this->menu as $key => $element ) : ?>
			<li class="wps-account-navigation-item">
				<a class="<?php echo ( $key === $tab ) ? 'active' : ''; ?>"
					href="<?php echo esc_attr( $element['link'] ); ?>">
					<i class="navigation-icon <?php echo esc_attr( $element['icon'] ); ?>"></i>
					<?php echo esc_html( $element['title'] ); ?>
				</a>
			</li>
		<?php endforeach;
	endif; ?>
</ul>
