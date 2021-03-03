<?php
/**
 * La classe gérant les fonctions principales des réglages.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.1
 */

namespace wpshop;

use eoxia\Singleton_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Settings Class.
 */
class Settings extends Singleton_Util {

	/**
	 * Les options par défauts.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * TVA.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $tva = array( 0, 2.1, 5.5, 10, 20 );

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 */
	protected function construct() {
		$this->default_settings = array(
			'debug_mode'          => false,

			'dolibarr_url'        => 'http://www.votredolibarr.ext',
			'dolibarr_secret'     => '',
			'dolibarr_public_key' => '',
			'shop_email'          => '',
			'error'               => '',
			'thumbnail_size'      => array(
				'width'  => 360,
				'height' => 460,
			),
			'use_quotation' => true,
			'split_product' => true,
			'price_min' => 0,
			'notice' => array(
				'error_erp'    => true,
				'activate_erp' => true,
			),
			//liens Tiers
			'dolibarr_create_tier'      => 'societe/card.php?action=create&leftmenu',
			'dolibarr_tiers_lists'      => 'societe/list.php?leftmenu=thirdparties',
			//@todo 'dolibarr_contacts_create'      => 'contact/card.php?leftmenu=contacts&action=create',
			//@todo 'dolibarr_contacts_lists'      	=> 'contact/list.php?leftmenu=contacts',
			//Liens Produits | Services
			//-Liens Produits
			'dolibarr_create_product'   => 'product/card.php?leftmenu=product&action=create&type=0',
			'dolibarr_products_lists'   => 'product/list.php?leftmenu=product&type=0',
			'dolibarr_product_document' => 'product/document.php?id=',
			/**-Liens Services
			//@todo 'dolibarr_create_service'   => 'product/card.php?leftmenu=service&action=create&type=1',
			//@todo 'dolibarr_services_lists'   => 'product/list.php?leftmenu=service&type=1',
			*/
			/**-Liens Entrepôts @todo faire les liens et les mettre en place
			//'dolibarr_create_entrepot'   => 'product/stock/card.php?action=create&leftmenu=',
			//'dolibarr_entrepots_lists'   => 'product/stock/list.php?leftmenu=',
			 */
			/**-Liens Projet @todo faire les liens et les mettre en place
			//'dolibarr_create_projet'   => 'projet/card.php?leftmenu=projects&action=create',
			//'dolibarr_projets_lists'   => 'projet/list.php?leftmenu=projets&search_status=99',
			 */
			//liens Commerce
			//-liens Propositions commerciales
			'dolibarr_create_proposal'  => 'comm/propal/card.php?action=create&leftmenu=propals',
			'dolibarr_proposals_lists'  => 'comm/propal/list.php?leftmenu=propals',
			//-liens Commandes
			'dolibarr_create_order'     => 'commande/card.php?action=create&leftmenu=orders',
			'dolibarr_orders_lists'     => 'commande/list.php?leftmenu=orders',

			//Liens Facturation | Paiement
			//-Liens Factures clients
			'dolibarr_create_invoices'  => 'compta/facture/card.php?action=create&leftmenu=',
			'dolibarr_invoices_lists'   => 'compta/facture/list.php?leftmenu=customers_bills',
			'dolibarr_payments_lists'   => 'compta/paiement/list.php?leftmenu=customers_bills_payment',
			//-Liens Commandes facturables @todo

		);

		$this->shipping_cost_default_settings = array(
			'from_price_ht'       => null,
			'shipping_product_id' => 0,
		);
	}

	/**
	 * Affiche l'onglet "Général" de la page réglages.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section.
	 */
	public function display_general( $section = '' ) {
		$dolibarr_option = get_option( 'wps_dolibarr', $this->default_settings );
		$debug_mode      = get_option( 'debug_mode', $this->default_settings['debug_mode'] );

		View_Util::exec( 'wpshop', 'settings', 'general', array(
			'debug_mode'      => $debug_mode,
			'dolibarr_option' => $dolibarr_option,
		) );
	}

	/**
	 * Affiche l'onglet "Pages" de la page réglages.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section.
	 */
	public function display_pages( $section = '' ) {
		$pages = get_pages();

		array_unshift( $pages, (object) array(
			'ID'         => 0,
			'post_title' => __( 'No page', 'wpshop' ),
		) );

		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );
		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );
		View_Util::exec( 'wpshop', 'settings', 'pages', array(
			'pages'            => $pages,
			'page_ids_options' => $page_ids_options,
		) );
	}

	/**
	 * Affiche l'onglet "Emails" de la page réglages.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section.
	 */
	public function display_emails( $section = '' ) {
		if ( ! empty( $section ) ) {
			$email            = Emails::g()->emails[ $section ];
			$path_to_template = Emails::g()->get_path( $email['filename_template'] );
			$content          = file_get_contents( $path_to_template );

			View_Util::exec( 'wpshop', 'settings', 'email-single', array(
				'section'     => $section,
				'email'       => $email,
				'content'     => $content,
			) );
		} else {
			$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );
			$emails = Emails::g()->emails;

			if ( ! empty( $emails ) ) {
				foreach ( $emails as $key => $email ) {
					$email[$key] = $page_ids_options[$key];
					$emails[$key] = $email;
				}
			}

			View_Util::exec( 'wpshop', 'settings', 'emails', array(
				'emails' => $emails,
			) );
		}
	}

	/**
	 * Affiche l'onglet "Méthode de paiement" de la page options.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section.
	 */
	public function display_payment_method( $section = '' ) {
		$payment_methods = get_option( 'wps_payment_methods', Payment::g()->default_options );

		if ( ! empty( $section ) ) {
			$payment_data = Payment::g()->get_payment_option( $section );

			View_Util::exec( 'wpshop', 'settings', 'payment-method-single', array(
				'section'      => $section,
				'payment_data' => $payment_data,
			) );
		} else {
			View_Util::exec( 'wpshop', 'settings', 'payment-method', array(
				'payment_methods' => $payment_methods,
			) );
		}
	}

	/**
	 * Affiche l'onglet "Frais de port" de la page options.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section.
	 */
	public function display_shipping_cost( $section = '' ) {
		$shipping_cost_option = get_option( 'wps_shipping_cost', Settings::g()->shipping_cost_default_settings );

		$products = Product::g()->get();

		$no_product = (object) array(
			'data' => array(
				'id'    => 0,
				'title' => __( 'No product', 'wpshop' ),
			),
		);

		array_unshift( $products, $no_product );

		View_Util::exec( 'wpshop', 'settings', 'shipping-cost', array(
			'shipping_cost_option' => $shipping_cost_option,
			'products'             => $products,
		) );
	}

	/**
	 * Affiche l'onglet "ERP" de la page options.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $section La section.
	 */
	public function display_erp( $section = '' ) {
		$dolibarr_option = get_option( 'wps_dolibarr', $this->default_settings );

		View_Util::exec( 'wpshop', 'settings', 'erp', array(
			'dolibarr_option' => $dolibarr_option,
		) );
	}

	/**
	 * Vérifie si dolibarr est actif.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return boolean true or false.
	 */
	public function dolibarr_is_active() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		if ( ! empty( $dolibarr_option['dolibarr_url'] ) && ! empty( $dolibarr_option['dolibarr_secret'] ) && empty( $dolibarr_option['error'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Vérifie si la liste d'envie est activé.
	 *
	 * @todo a revoir
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @return boolean true ou false.
	 */
	public function use_quotation() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		return $dolibarr_option['use_quotation'];
	}

	/**
	 * Vérifie si la séparation des produits est activé.
	 *
	 * @todo a revoir
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @return boolean true ou false.
	 */
	public function split_product() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		return $dolibarr_option['split_product'];
	}

	/**
	 * Vérifie si le mode debug est actif.
	 *
	 * @since   2.3.1
	 * @version 2.3.1
	 *
	 * @return boolean true or false.
	 */
	public function debug_mode() {
		$debug_mode = get_option( 'debug_mode', Settings::g()->default_settings );

		return $debug_mode;
	}
}

Settings::g();
