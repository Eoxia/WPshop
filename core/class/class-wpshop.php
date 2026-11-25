<?php
/**
 * La classe gérant les fonctions principales de WPshop.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * WPshop Class.
 */
class WPshop extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * La méthode appelée à l'activation du plugin.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function default_install() {
		$db_version = get_option( 'wpshop2_database_version', false );

		if ( ! $db_version ) {
			Pages::g()->create_default_page();

			// New install: the shop uses the page templates ("Nouveaux modèles").
			add_option( Display_Mode::OPTION, Display_Mode::TEMPLATES );

			$db_version = '1.0.0';
			update_option( 'wpshop2_database_version', $db_version );
		}

		if ( version_compare( $db_version, '1.0.1', '<' ) ) {
			Pages::g()->create_connection_page();

			$db_version = '1.0.1';
			update_option( 'wpshop2_database_version', $db_version );
		}

		if ( version_compare( $db_version, '1.0.2', '<' ) ) {
			// Existing site: keep the legacy display until the admin switches (no-op on new installs).
			add_option( Display_Mode::OPTION, Display_Mode::CLASSIC );

			if ( Display_Mode::g()->is_templates_mode() ) {
				Display_Mode::g()->apply_page_templates();
			}

			$db_version = '1.0.2';
			update_option( 'wpshop2_database_version', $db_version );
		}

		if ( version_compare( $db_version, '1.0.3', '<' ) ) {
			// Shop page template (product catalogue) added: assign it on sites already in "Nouveaux modèles".
			if ( Display_Mode::g()->is_templates_mode() ) {
				Display_Mode::g()->apply_page_templates();
			}

			$db_version = '1.0.3';
			update_option( 'wpshop2_database_version', $db_version );
		}

		if ( version_compare( $db_version, '1.0.4', '<' ) ) {
			// Email templates were private pages: move them to their own post type (same IDs and content).
			Emails::g()->migrate_email_posts();

			$db_version = '1.0.4';
			update_option( 'wpshop2_database_version', $db_version );
		}
	}
}

WPshop::g();
