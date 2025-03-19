<?php
/**
 * La classe gérant les fonctions principales des réglages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2025 Eoxia <technique@eoxia.com>.
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
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 */
	protected function construct() {
		$this->default_settings = array(
			'dolibarr_url'        => 'http://www.votredolibarr.ext',
			'dolibarr_secret'     => '',
			'shop_email'          => '',
			'error'               => '',
			'thumbnail_size'      => array(
				'width'  => 360,
				'height' => 460,
			),
			'split_product' => true,
			'price_min' => 0,
			'notice' => array(
				'error_erp'    => true,
				'activate_erp' => true,
			),
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

		View_Util::exec( 'wpshop', 'settings', 'general', array(
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
}

Settings::g();
