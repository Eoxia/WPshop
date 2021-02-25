<?php
/**
 * Les actions relatives aux devis avec Dolibarr.
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
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Order Action Class.
 */
class Proposals_Action {
	/**
	 * Définition des metabox sur la page.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $metaboxes = null;

	/**
	 * Initialise les actions liées aux proposals.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 40 );

		$this->metaboxes = array(
			'wps-proposal-details' => array(
				'callback' => array( $this, 'metabox_proposal_details' ),
			),
			'wps-proposal-address' => array(
				'callback' => array( $this, 'metabox_proposal_address' ),
			),
			'wps-proposal-review'  => array(
				'callback' => array( $this, 'metabox_proposal_review' ),
			),
		);
	}

	/**
	 * Initialise la page "Liste d'envies".
	 *
	 * @since 2.0.0
	 * @version 2.4.0
	 */
	public function callback_admin_menu() {
		if ( Settings::g()->use_quotation() ) {

//			$hook = add_submenu_page( 'wpshop', __( 'Wish List', 'wpshop' ), __( 'Wish List', 'wpshop' ), 'manage_options', 'wps-proposal', array( $this, 'callback_add_menu_page' ) );
//
//			if ( ! isset( $_GET['id'] ) ) {
//				add_action( 'load-' . $hook, array( $this, 'callback_add_screen_option' ) );
//			}

			if ( user_can( get_current_user_id(), 'manage_options' ) ) {
				CMH::register_menu( 'wpshop', __( 'Wish List', 'wpshop' ), __( 'Wish List', 'wpshop' ), 'manage_options', 'wps-proposal', array( $this, 'callback_add_menu_page' ), 'fas fa-list', 4 );
			}
		}
	}

	/**
	 * Ajoutes la page "Options de l'écran" pour les devis.
	 *
	 * @since 2.0.0
	 */
	public function callback_add_screen_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => __( 'Quotation', 'Quotation per page', 'wpshop' ),
				'default' => Proposals::g()->limit,
				'option'  => Proposals::g()->option_per_page,
			)
		);
	}

	/**
	 * Affichage de la vue du menu
	 *
	 * @since 2.0.0
	 */
	public function callback_add_menu_page() {
		if ( isset( $_GET['id'] ) ) {
			$id              = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;
			$proposal        = Proposals::g()->get( array( 'id' => $id ), true );
			$third_party     = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
			$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

			if ( ! empty( $this->metaboxes ) ) {
				foreach ( $this->metaboxes as $key => $metabox ) {
					add_action( 'wps_proposal', $metabox['callback'], 10, 1 );
				}
			}

			\eoxia\View_Util::exec( 'wpshop', 'proposals', 'single', array(
				'third_party' => $third_party,
				'proposal'    => $proposal,
				'doli_url'    => $dolibarr_option['dolibarr_url'],
			) );
		} else {
			$per_page = get_user_meta( get_current_user_id(), Proposals::g()->option_per_page, true );

			if ( empty( $per_page ) || 1 > $per_page ) {
				$per_page = Third_Party::g()->limit;
			}

			$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

			$count = Proposals::g()->search( $s, array(), true );

			$number_page  = ceil( $count / $per_page );
			$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

			$base_url = admin_url( 'admin.php?page=wps-proposal' );

			$begin_url = $base_url . '&current_page=1';
			$end_url   = $base_url . '&current_page=' . $number_page;

			$prev_url = $base_url . '&current_page=' . ( $current_page - 1 );
			$next_url = $base_url . '&current_page=' . ( $current_page + 1 );

			if ( ! empty( $s ) ) {
				$begin_url .= '&s=' . $s;
				$end_url   .= '&s=' . $s;
				$prev_url  .= '&s=' . $s;
				$next_url  .= '&s=' . $s;
			}

			\eoxia\View_Util::exec( 'wpshop', 'proposals', 'main', array(
				'number_page'  => $number_page,
				'current_page' => $current_page,
				'count'        => $count,
				'begin_url'    => $begin_url,
				'end_url'      => $end_url,
				'prev_url'     => $prev_url,
				'next_url'     => $next_url,
				's'            => $s,
			) );
		}
	}

	/**
	 * La metabox des détails de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Post $post          Les données du post.
	 * @param  array   $callback_args Tableau contenu les données de la commande.
	 */
	public function metabox_proposal_details( $proposal ) {
		$third_party  = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
		$invoice      = null;
		$link_invoice = '';

		if ( Settings::g()->dolibarr_is_active() ) {
			$invoice = Doli_Invoice::g()->get( array( 'post_parent' => $proposal->data['id'] ), true );

			if ( ! empty( $invoice ) ) {
				$invoice->data['payments'] = array();
				$invoice->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $invoice->data['id'] ) );
				$link_invoice              = admin_url( 'admin-post.php?action=wps_download_invoice_wpnonce=' . wp_create_nonce( 'download_invoice' ) . '&proposal_id=' . $proposal->data['id'] );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'proposals', 'metabox-proposal-details', array(
			'proposal'     => $proposal,
			'third_party'  => $third_party,
			'invoice'      => $invoice,
			'link_invoice' => $link_invoice,
		) );
	}

	/**
	 * La metabox contenant l'adresse du client.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Post $post          Les données du post.
	 * @param  array   $callback_args Tableau contenu les données de la commande.
	 */
	public function metabox_proposal_address( $proposal ) {
		$third_party  = Third_Party::g()->get( array( 'id' => $proposal->data['parent_id'] ), true );
		$invoice      = null;
		$link_invoice = '';

		if ( Settings::g()->dolibarr_is_active() ) {
			$invoice = Doli_Invoice::g()->get( array( 'post_parent' => $proposal->data['id'] ), true );

			if ( ! empty( $invoice ) ) {
				$invoice->data['payments'] = array();
				$invoice->data['payments'] = Doli_Payment::g()->get( array( 'post_parent' => $invoice->data['id'] ) );
				$link_invoice              = admin_url( 'admin-post.php?action=wps_download_invoice_wpnonce=' . wp_create_nonce( 'download_invoice' ) . '&proposal_id=' . $proposal->data['id'] );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'proposals', 'metabox-proposal-address', array(
			'proposal'     => $proposal,
			'third_party'  => $third_party,
			'invoice'      => $invoice,
			'link_invoice' => $link_invoice,
		) );
	}

	/**
	 * Box affichant les produits de la commande
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Post $post          Les données du post.
	 * @param  array   $callback_args Tableau contenu les données de la commande.
	 */
	public function metabox_proposal_review( $proposal ) {
		$tva_lines = array();
		if ( ! empty( $proposal->data['lines'] ) ) {
			foreach ( $proposal->data['lines'] as &$line ) {
				if ( empty( $tva_lines[ $line['tva_tx'] ] ) ) {
					$tva_lines[ $line['tva_tx'] ] = 0;
				}

				$tva_lines[ $line['tva_tx'] ] += empty( $line['total_tva'] ) ? $line['tva_amount'] * $line['qty'] : $line['total_tva'];

				if ( empty( $line['libelle'] ) && ! empty( $line['title'] ) ) {
					$line['libelle'] = $line['title'];
				}

				if ( empty( $line['subprice'] ) && isset( $line['price'] ) ) {
					$line['subprice'] = $line['price'];
				}

				if ( empty( $line['total_ht'] ) && isset( $line['price'] ) ) {
					$line['total_ht'] = $line['price'] * $line['qty'];
				}
			}
		}

		if ( Settings::g()->dolibarr_is_active() ) {
			\eoxia\View_Util::exec( 'wpshop', 'order', 'review-order', array(
				'object'    => $proposal,
				'tva_lines' => $tva_lines,
			) );
		} else {
			\eoxia\View_Util::exec( 'wpshop', 'proposals', 'metabox-proposal-products', array(
				'proposal'  => $proposal,
				'tva_lines' => $tva_lines,
			) );
		}
	}

	/**
	 * Box affichant les actions de la commande.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Post $post          Les données du post.
	 * @param  array   $callback_args Tableau contenu les données de la commande.
	 */
	public function callback_proposal_action( $post, $callback_args ) {
		$proposal = $callback_args['args']['proposal'];

		\eoxia\View_Util::exec( 'wpshop', 'proposals', 'metabox-action', array(
			'order' => $order,
		) );
	}
}

new Proposals_Action();
