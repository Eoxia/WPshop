<?php
/**
 * La classe gérant les fonctions utilitaires des templates.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Template Util Class.
 */
class Template_Util extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Get template can be overwrited in "wpshop" folder in your theme.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $module   Le nom du module.
	 * @param string $template Le nom du template.
	 *
	 * @return string          Le chemin du template
	 */
	public static function get_template_part( $module, $template ) {
		/*
		 * Theme override, most specific first:
		 * - wpshop/<module>/<template>.php (since 2.7.0, avoids name collisions between modules);
		 * - wpshop/<template>.php (historical location, still supported).
		 */
		$path_to_theme = locate_template( array(
			'wpshop/' . $module . '/' . $template . '.php',
			'wpshop/' . $template . '.php',
		) );

		if ( ! empty( $path_to_theme ) ) {
			$single_template = $path_to_theme;
		} else {
			$single_template = str_replace( '\\', '/', PLUGIN_WPSHOP_PATH . '/modules/' . $module . '/view/frontend/' . $template . '.php' );
		}

		/**
		 * Filters the path of a WPshop front view.
		 *
		 * @since 2.7.0
		 *
		 * @param string $single_template Path of the view.
		 * @param string $module          Module name.
		 * @param string $template        View name.
		 */
		$single_template = apply_filters( 'wps_get_template_part', $single_template, $module, $template );

		// Some views were removed over time: include an empty view instead of raising a PHP warning.
		if ( ! file_exists( $single_template ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf( 'WPshop: missing view "%s" (module "%s").', $template, $module ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			}

			$single_template = PLUGIN_WPSHOP_PATH . 'core/view/empty.php';
		}

		return $single_template;
	}
}
