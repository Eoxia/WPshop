<?php
/**
 * Gestion des actions du tableau de bord.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard Action Class.
 */
class Dashboard_Action {

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		//@todo nom a vérifier
		add_action( 'load-toplevel_page_wps-third-party', array( $this, 'callback_load' ), 10 );
	}

	/**
	 * Charge les script WP pour les metaboxes.
	 *
	 * @since 2.0.0
	 */
	public function callback_load() {
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
	}
}

new Dashboard_Action();
