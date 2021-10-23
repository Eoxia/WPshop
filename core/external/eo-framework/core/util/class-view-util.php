<?php
/**
 * Gestion des vues pour les templates.
 *
 * @author Eoxia <technique@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015-2018 Eoxia
 * @package EO_Framework\Core\Util
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\View_Util' ) ) {
	/**
	 * Gestion des vues pour les templates.
	 */
	class View_Util extends Singleton_Util {

		/**
		 * Le constructeur obligatoirement pour utiliser la classe \eoxia\Singleton_Util
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @return void
		 */
		protected function construct() {}

		/**
		 * Appelle la vue avec les paramètres extrait de $args.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param  string $namespace             Le slug du plugin (Défini dans votre config.json principale).
		 * @param  string $module_name           Le nom du module.
		 * @param  string $view_path_without_ext Le chemin vers le fichier à partir du dossier "view" du module.
		 * @param  array  $args                  Les données à transmettre à la vue. Défaut array().
		 * @param  bool   $filter                 Utilisation d'un filtre ou pas. Permet d'ajouter des paramètres au template.
		 *
		 * @return void
		 */
		public static function exec( $namespace, $module_name, $view_path_without_ext, $args = array(), $filter = true ) {
			$path_to_view = Config_Util::$init[ $namespace ]->$module_name->path . '/view/' . $view_path_without_ext . '.view.php';

			if ( $filter ) {
				$args = apply_filters( $module_name . '_' . $view_path_without_ext, $args, $module_name, $view_path_without_ext );
			}
			extract( $args );

			if ( file_exists( $path_to_view ) ) {
				require( $path_to_view );
			} else {

			}
		}
	}
} // End if().
