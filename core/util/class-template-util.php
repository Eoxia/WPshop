<?php
/**
 * Gestion des templates.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Template Util Class.
 */
class Template_Util extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 0.2.0
	 */
	protected function construct() {}

	/**
	 * Get template can be overwrited in "wpshop" folder in your theme.
	 *
	 * @since  2.0.0
	 *
	 * @param string $module   Le nom du module.
	 * @param string $template Le nom du template.
	 *
	 * @return string          Le chemin du template
	 */
	public static function get_template_part( $module, $template ) {
		/** Get theme template if exist */
		$path_to_theme = locate_template( 'wpshop/' . $template . '.php' );
		if ( ! empty( $path_to_theme ) ) {
			$single_template = $path_to_theme;
		} else {
			$single_template = str_replace( '\\', '/', PLUGIN_WPSHOP_PATH . '/modules/' . $module . '/view/frontend/' . $template . '.php' );
		}

		return $single_template;
	}
}
