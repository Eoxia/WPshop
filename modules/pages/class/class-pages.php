<?php
/**
 * Les fonctions principales des pages.
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
 * Pages Class.
 */
class Pages extends \eoxia\Singleton_Util {
	/**
	 * Tableau contenant toutes les pages personnalisables par défaut.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $default_options;

	/**
	 * Tableau contenant toutes les pages personnalisables par défaut.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $mail_page;

	/**
	 * Les titres des pages lisible.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $page_state_titles;

	/**
	 * Les titres des pages non visible.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $page_state_titles_private;

	/**
	 * Tableau contenant toutes les pages personnalisables dans la base de
	 * donnée.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $page_ids;

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		$this->default_options = array(
			'shop_id'                    => 0,
			'cart_id'                    => 0,
			'checkout_id'                => 0,
			'my_account_id'              => 0,
			'general_conditions_of_sale' => 0,
		);

		$this->page_state_titles = array(
			'shop_id'                    => __( 'Shop', 'wpshop' ),
			'cart_id'                    => __( 'Cart', 'wpshop' ),
			'checkout_id'                => __( 'Checkout', 'wpshop' ),
			'my_account_id'              => __( 'My account', 'wpshop' ),
			'general_conditions_of_sale' => __( 'General conditions of sale', 'wpshop' ),
		);

		$this->mail_page = array(
			'customer_new_account'     => 0,
			'customer_current_order'   => 0,
			'customer_completed_order' => 0,
			'customer_paid_order'      => 0,
			'customer_invoice'         => 0,
			'customer_delivered_order' => 0,

		);

		$this->page_state_titles_private = array(
			// Mail envoyé au client lors de création du compte client.
			'customer_new_account'     => __( 'New account', 'wpshop' ),
			// Création de la commande.
			'customer_current_order'   => __( 'Current order', 'wpshop' ),
			// Départ vers la solution de paiement
			'customer_completed_order' => __( 'Completed order', 'wpshop' ),
			// Retour validé du paiement.
			'customer_paid_order'      => __( 'Paid order', 'wpshop' ),
			// Réalisation de la facture.
			'customer_invoice'         => __( 'Invoice', 'wpshop' ),
			// Commande livrée.
			'customer_delivered_order' => __( 'Delivered order', 'wpshop' ),

		);

		$this->page_ids = get_option( 'wps_page_ids', $this->default_options );
	}

	/**
	 * Créer et associer les pages par défaut nécessaires pour le fonctionnement
	 * de WPShop.
	 *
	 * @since 2.0.0
	 */
	public function create_default_page() {
		load_plugin_textdomain( 'wpshop', false, PLUGIN_WPSHOP_DIR . '/core/asset/language/' );

		if ( ! empty( $this->page_state_titles ) ) {
			foreach ( $this->page_state_titles as $key => $page_title ) {
				$page_id = wp_insert_post( array(
					'post_title'  => $page_title,
					'post_type'   => 'page',
					'post_status' => 'publish',
				) );

				if ( ! empty( $page_id ) ) {
					$this->page_ids[ $key ] = $page_id;

					\eoxia\LOG_Util::log( sprintf( 'Create the page %s when activate plugin success', $page_title ), 'wpshop' );
				} else {
					\eoxia\LOG_Util::log( sprintf( 'Error for create the page %s when activate plugin', $page_title ), 'wpshop' );
				}
			}

			update_option( 'wps_page_ids', $this->page_ids );
		}

		if ( ! empty( $this->page_state_titles_private ) ) {
			foreach ( $this->page_state_titles_private as $key => $page_title ) {
				$page_id = wp_insert_post( array(
					'post_title'  => $page_title,
					'post_type'   => 'page',
					'post_name'   => $key,
					'post_status' => 'private',
				) );

				if ( ! empty( $page_id ) ) {
					$this->page_ids[ $key ] = $page_id;

					\eoxia\LOG_Util::log( sprintf( 'Create the private page %s when activate plugin success', $page_title ), 'wpshop' );
				} else {
					\eoxia\LOG_Util::log( sprintf( 'Error create the private page %s when activate plugin', $page_title ), 'wpshop' );
				}
			}

			update_option( 'wps_page_ids', $this->page_ids );
		}
	}

	/**
	 * Récupères le slug de la page shop.
	 *
	 * @since 2.0.0
	 *
	 * @return string Le slug de la page shop.
	 */
	public function get_slug_shop_page() {
		$page = get_page( $this->page_ids['shop_id'] );

		if ( ! $page ) {
			return false;
		}

		return $page->post_name;
	}

	public function get_slug_my_account_page() {
		$page = get_page( $this->page_ids['my_account_id'] );

		if ( ! $page ) {
			return false;
		}

		return $page->post_name;
	}

	public function get_slug_checkout_page() {
		$page = get_page( $this->page_ids['checkout_id'] );

		if ( ! $page ) {
			return false;
		}

		return $page->post_name;
	}

	/**
	 * Récupères le lien vers la page mon compte.
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page mon compte.
	 */
	public function get_account_link() {
		return get_permalink( $this->page_ids['my_account_id'] );
	}

	/**
	 * Récupères le lien vers la page "Mon compte".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Mon compte".
	 */
	public function get_cart_link() {
		return get_permalink( $this->page_ids['cart_id'] );
	}

	/**
	 * Récupères le lien vers la page "Paiement".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Paiement".
	 */
	public function get_checkout_link() {
		return get_permalink( $this->page_ids['checkout_id'] );
	}

	/**
	 * Récupères le lien vers la page "Condition générale de vente".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Condition générale de vente".
	 */
	public function get_general_conditions_of_sale_link() {
		return get_permalink( $this->page_ids['general_conditions_of_sale'] );
	}

	/**
	 * Récupères le lien vers la page "Commande payée".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Commande payée".
	 */
	public function get_customer_paid_order_link() {
		return get_permalink( $this->page_ids['customer_paid_order'] );
	}

	/**
	 * Récupères le lien vers la page "Commande en cours".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Commande en cours".
	 */
	public function get_customer_current_order_link() {
		return get_permalink( $this->page_ids['customer_current_order'] );
	}

	/**
	 * Récupères le lien vers la page "Commande complétée".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Commande complétée".
	 */
	public function get_customer_completed_order_link() {
		return get_permalink( $this->page_ids['customer_completed_order'] );
	}

	/**
	 * Récupères le lien vers la page "Commande livrée".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Commande livrée".
	 */
	public function get_customer_delivered_order_link() {
		return get_permalink( $this->page_ids['customer_delivered_order'] );
	}

	/**
	 * Récupères le lien vers la page "Facture".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Facture".
	 */
	public function get_customer_invoice_link() {
		return get_permalink( $this->page_ids['customer_invoice'] );
	}

	/**
	 * Récupères le lien vers la page "Nouveau compte".
	 *
	 * @since 2.0.0
	 *
	 * @return string Le lien vers la page "Nouveau compte".
	 */
	public function get_customer_new_account_link() {
		return get_permalink( $this->page_ids['customer_new_account'] );
	}

	/**
	 * Est-ce la page "checkout" ?
	 *
	 * @since 2.0.0
	 *
	 * @return boolean True si oui, sinon false.
	 */
	public function is_checkout_page() {
		$this->page_ids = get_option( 'wps_page_ids', $this->default_options );

		return ( get_the_ID() === $this->page_ids['checkout_id'] ) ? true : false;
	}
}

Pages::g();
