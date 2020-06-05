<?php
/**
 * Les actions principales pour la recherche.
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
 * Search Action Class.
 */
class Search_Action {

	/**
	 * Déclares l'action.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	/**
	 * Enregistre le widget.
	 *
	 * @since 2.0.0
	 */
	public function register_widget() {
		register_widget( 'wpshop\Search_Widget' );
	}
}

new Search_Action();
