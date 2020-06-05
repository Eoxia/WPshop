<?php
/**
 * Les filtres relatives au devis.
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
 * Proposals Filter Class.
 */
class Proposals_Filter {

	/**
	 * Initialise les filtres liées aux proposals.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_wps-proposal_register_post_type_args', array( $this, 'callback_register_post_type_args' ) );
	}

	/**
	 * Ajoutes des paramètres supplémentaires pour le register post type.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function callback_register_post_type_args() {
		$labels = array(
			'name'               => _x( 'Proposals', 'post type general name', 'wpshop' ),
			'singular_name'      => _x( 'Proposal', 'post type singular name', 'wpshop' ),
			'menu_name'          => _x( 'Proposals', 'admin menu', 'wpshop' ),
			'name_admin_bar'     => _x( 'Proposal', 'add new on admin bar', 'wpshop' ),
			'add_new'            => _x( 'Add New', 'proposal', 'wpshop' ),
			'add_new_item'       => __( 'Add New Proposal', 'wpshop' ),
			'new_item'           => __( 'New Proposal', 'wpshop' ),
			'edit_item'          => __( 'Edit Proposal', 'wpshop' ),
			'view_item'          => __( 'View Proposal', 'wpshop' ),
			'all_items'          => __( 'All Proposals', 'wpshop' ),
			'search_items'       => __( 'Search Proposals', 'wpshop' ),
			'parent_item_colon'  => __( 'Parent Proposals:', 'wpshop' ),
			'not_found'          => __( 'No proposals found.', 'wpshop' ),
			'not_found_in_trash' => __( 'No proposals found in Trash.', 'wpshop' ),
		);

		$args['labels']              = $labels;
		$args['supports']            = array( 'title' );
		$args['public']              = false;
		$args['has_archive']         = false;
		$args['show_ui']             = false;
		$args['show_in_nav_menus']   = false;
		$args['show_in_menu']        = false;
		$args['query_var']           = false;
		$args['publicly_queryable']  = false;
		$args['exclude_from_search'] = true;

		return $args;
	}
}

new Proposals_Filter();
