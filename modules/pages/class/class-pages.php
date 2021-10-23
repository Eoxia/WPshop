<?php
/**
 * La classe gérant les fonctions principales des pages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Pages Class.
 */
class Pages extends Singleton_Util {
	/**
	 * Le tableau contenant toutes les pages personnalisables par défaut.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $default_options;

	/**
	 * Le tableau contenant toutes les pages emails par défaut.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $mail_page;

	/**
	 * Les titres des pages lisible.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $page_state_titles;

	/**
	 * Les titres des pages non visible.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $page_state_titles_private;

	/**
	 * Le tableau contenant toutes les id des pages.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $page_ids;

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
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

		$this->page_content_default = array(
			'customer_new_account'     => __( 'Welcome <br> This email confirms that your account has been created. <br> Thank you for your trust and see you soon on our shop.', 'wpshop' ),
			'customer_current_order'   => __( 'Hello <br> We have just recorded your order, thank you to send us your payment. <br> We thank you for your confidence and see you soon on our shop.', 'wpshop' ),
			'customer_completed_order' => __( 'Hello <br> This email confirms that your payment for your recent order has just been validated. <br> See you soon on our shop.', 'wpshop' ),
			'customer_paid_order'      => __( 'Paid order', 'wpshop' ),
			'customer_invoice'         => __( 'Hello <br> You can access your invoices by logging in to your account.', 'wpshop' ),
			'customer_delivered_order' => __( 'Delivered order', 'wpshop' ),
		);

			$this->page_ids = get_option( 'wps_page_ids', $this->default_options );
	}

	/**
	 * Créer et associer les pages par défaut nécessaires pour le fonctionnement de WPShop.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
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

					LOG_Util::log( sprintf( 'Create the page %s when activate plugin success', $page_title ), 'wpshop' );
				} else {
					LOG_Util::log( sprintf( 'Error for create the page %s when activate plugin', $page_title ), 'wpshop' );
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
					'post_content' => $this->page_content_default[$key],
				) );

				if ( ! empty( $page_id ) ) {
					$this->page_ids[ $key ] = $page_id;

					LOG_Util::log( sprintf( 'Create the private page %s when activate plugin success', $page_title ), 'wpshop' );
				} else {
					LOG_Util::log( sprintf( 'Error create the private page %s when activate plugin', $page_title ), 'wpshop' );
				}
			}

			update_option( 'wps_page_ids', $this->page_ids );
		}
	}

	/**
	 * Récupère le slug de la page "Boutique".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le slug de la page "Boutique".
	 */
	public function get_slug_shop_page() {
		$page = get_page( $this->page_ids['shop_id'] );

		if ( ! $page ) {
			return false;
		}

		return $page->post_name;
	}

	/**
	 * Récupère le slug de la page "Mon compte".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le slug de la page "Mon compte".
	 */
	public function get_slug_my_account_page() {
		$page = get_page( $this->page_ids['my_account_id'] );

		if ( ! $page ) {
			return false;
		}

		return $page->post_name;
	}

	/**
	 * Récupère le slug de la page "Tunnel de vente".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le slug de la page "Tunnel de vente".
	 */
	public function get_slug_checkout_page() {
		$page = get_page( $this->page_ids['checkout_id'] );

		if ( ! $page ) {
			return false;
		}

		return $page->post_name;
	}

	/**
	 * Récupère le lien vers la page "Mon compte".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Mon compte".
	 */
	public function get_account_link() {
		return get_permalink( $this->page_ids['my_account_id'] );
	}

	/**
	 * Récupère le lien vers la page "Panier".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Panier".
	 */
	public function get_cart_link() {
		return get_permalink( $this->page_ids['cart_id'] );
	}

	/**
	 * Récupère le lien vers la page "Paiement".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Paiement".
	 */
	public function get_checkout_link() {
		return get_permalink( $this->page_ids['checkout_id'] );
	}

	/**
	 * Récupère le lien vers la page "Condition générales de vente".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Condition générales de vente".
	 */
	public function get_general_conditions_of_sale_link() {
		return get_permalink( $this->page_ids['general_conditions_of_sale'] );
	}

	/**
	 * Récupère le lien vers la page "Commande payée".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Commande payée".
	 */
	public function get_customer_paid_order_link() {
		return get_permalink( $this->page_ids['customer_paid_order'] );
	}

	/**
	 * Récupère le lien vers la page "Commande en cours".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Commande en cours".
	 */
	public function get_customer_current_order_link() {
		return get_permalink( $this->page_ids['customer_current_order'] );
	}

	/**
	 * Récupère le lien vers la page "Commande complétée".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Commande complétée".
	 */
	public function get_customer_completed_order_link() {
		return get_permalink( $this->page_ids['customer_completed_order'] );
	}

	/**
	 * Récupère le lien vers la page "Commande livrée".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Commande livrée".
	 */
	public function get_customer_delivered_order_link() {
		return get_permalink( $this->page_ids['customer_delivered_order'] );
	}

	/**
	 * Récupère le lien vers la page "Facture".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Facture".
	 */
	public function get_customer_invoice_link() {
		return get_permalink( $this->page_ids['customer_invoice'] );
	}

	/**
	 * Récupère le lien vers la page "Nouveau compte".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return string Le lien vers la page "Nouveau compte".
	 */
	public function get_customer_new_account_link() {
		return get_permalink( $this->page_ids['customer_new_account'] );
	}

	/**
	 * Est-ce la page "checkout" ?
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return boolean True si oui, sinon false.
	 */
	public function is_checkout_page() {
		$this->page_ids = get_option( 'wps_page_ids', $this->default_options );

		return ( get_the_ID() === $this->page_ids['checkout_id'] ) ? true : false;
	}
}

Pages::g();
