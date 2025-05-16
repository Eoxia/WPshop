<?php
/**
 * Gestion des routes
 *
 * @author Eoxia <technique@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework\EO_Model\Class
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Rest_Class' ) ) {
	/**
	 * Gestion des utilisateurs (POST, PUT, GET, DELETE)
	 */
	class Rest_Class extends Singleton_Util {

		/**
		 * [construct description]
		 */
		protected function construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ), 20 );
		}

		/**
		 * Check user capability to access to element
		 *
		 * @param string $cap The capability name to check.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return string The rest api base for current element
		 */
		public function check_cap( $cap, $request ) {
			$can = apply_filters( 'eo_model_check_cap', true, $request );

			if ( ! $can ) {
				return false;
			}

			return true;
		}
	}

}
