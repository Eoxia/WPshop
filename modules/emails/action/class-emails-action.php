<?php
/**
 * La classe gérant les actions des emails.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Config_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Emails Action Class.
 */
class Emails_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wps_email_order_details', array( $this, 'order_details' ) );
		add_action( 'wps_email_order_details', array( $this, 'type_payment' ), 20, 1 );

		// Before WPshop::default_install() (init 10), which creates and migrates the email posts.
		add_action( 'init', array( $this, 'register_post_type' ), 5 );
		add_action( 'load-edit.php', array( $this, 'redirect_email_list' ) );
		add_filter( 'parent_file', array( $this, 'highlight_settings_menu' ) );
		add_filter( 'submenu_file', array( $this, 'highlight_settings_submenu' ) );
	}

	/**
	 * Registers the email templates post type: editable with the block editor,
	 * but kept out of the Pages list, the front, the search and the link pickers.
	 *
	 * @since 2.7.0
	 */
	public function register_post_type() {
		register_post_type( Emails::POST_TYPE, array(
			'labels'              => array(
				'name'               => __( 'E-mails', 'wpshop' ),
				'singular_name'      => __( 'E-mail', 'wpshop' ),
				'edit_item'          => __( 'Edit the e-mail', 'wpshop' ),
				'view_item'          => __( 'View the e-mail', 'wpshop' ),
				'item_updated'       => __( 'E-mail updated.', 'wpshop' ),
				'not_found'          => __( 'No e-mail found.', 'wpshop' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => true,
			'rewrite'             => false,
			'query_var'           => false,
			'has_archive'         => false,
			'hierarchical'        => false,
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor', 'revisions' ),
		) );
	}

	/**
	 * The emails are managed from WPshop → Settings → E-mails: send their list there
	 * (e.g. the block editor "back" button).
	 *
	 * @since 2.7.0
	 */
	public function redirect_email_list() {
		if ( isset( $_GET['post_type'] ) && Emails::POST_TYPE === $_GET['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( Emails::g()->get_settings_url() );
			exit;
		}
	}

	/**
	 * Highlights the WPshop menu while an email is being edited.
	 *
	 * @since 2.7.0
	 *
	 * @param string $parent_file The parent menu slug.
	 *
	 * @return string
	 */
	public function highlight_settings_menu( $parent_file ) {
		return $this->is_email_screen() ? 'wpshop' : $parent_file;
	}

	/**
	 * Highlights WPshop → Settings while an email is being edited.
	 *
	 * @since 2.7.0
	 *
	 * @param string|null $submenu_file The submenu slug.
	 *
	 * @return string|null
	 */
	public function highlight_settings_submenu( $submenu_file ) {
		return $this->is_email_screen() ? 'wps-settings' : $submenu_file;
	}

	/**
	 * Whether the current admin screen edits an email.
	 *
	 * @since 2.7.0
	 *
	 * @return bool
	 */
	private function is_email_screen() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		return $screen && Emails::POST_TYPE === $screen->post_type;
	}

	/**
	 * Ajoute les détails de la commande dans l'email.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order Les données de la commande.
	 */
	public function order_details( $order ) {
		$tva_lines = array();

		if ( ! empty( $order->lines ) ) {
			foreach ( $order->lines as &$line ) {
				if ( empty( $tva_lines[ $line->tva_tx ] ) ) {
					$tva_lines[ $line->tva_tx ] = 0;
				}

				$tva_lines[ $line->tva_tx ] += $line->total_tva;

				$wp_product = Product::g()->get( array(
					'meta_key'   => '_external_id',
					'meta_value' => (int) $line->fk_product,
				), true );

				$line->wp_id = $wp_product->data['id'];
			}
		}

		unset( $line );

		include( Template_Util::get_template_part( 'emails', 'order-details' ) );
	}

	/**
	 * Ajoute les informations de paiement dans l'email.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order Les données de la commande.
	 */
	public function type_payment( $order ) {
		$payment_methods = get_option( 'wps_payment_methods', Payment::g()->default_options );

		include( Template_Util::get_template_part( 'emails', 'type-payment' ) );
	}
}

new Emails_Action();
