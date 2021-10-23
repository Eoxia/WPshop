<?php
/**
 * La classe gérant Les filtres des utilisateurs de Dolibarr.
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
 * Doli User Filter Class.
 */
class Doli_User_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wp_footer_end', array( $this, 'check_user' ) );
	}

	/**
	 * Vérifie si l'utilisateur est connecté à l'ERP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function check_user() {
		if ( Settings::g()->dolibarr_is_active() ) {
			$response = Doli_User::g()->check_connected_to_erp();

			if ( ! $response['status'] ) {
				$link = Template_Util::get_template_part( 'dolibarr/doli-user', 'user-alert' );
				include $link;
			}
		}
	}
}

new Doli_User_Filter();
