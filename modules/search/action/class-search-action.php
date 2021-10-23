<?php
/**
 * La classe gérant les actions principales pour la recherche.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Search Action Class.
 */
class Search_Action {

	/**
	 * Le ccnstructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	/**
	 * Enregistre le widget.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function register_widget() {
		register_widget( 'wpshop\Search_Widget' );
	}
}

new Search_Action();
