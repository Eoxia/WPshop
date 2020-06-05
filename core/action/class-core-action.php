<?php
/**
 * Classe gérant les actions principales de WPshop.
 *
 * Elle ajoute les styles et scripts JS principaux pour le bon fonctionnement de WPshop.
 * Elle ajoute également les textes de traductions (fichiers .mo)
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
 * Main actions of wpshop.
 */
class Core_Action {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_register_session' ), 1 );
		add_action( 'init', array( $this, 'callback_language' ) );
		add_action( 'init', array( $this, 'callback_install_default' ) );
		add_action( 'init', array( $this, 'callback_init_block' ) );

		add_action( 'wp_head', array( $this, 'define_ajax_url' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_enqueue_scripts' ), 11 );

		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

		add_action( 'wp_ajax_check_erp_statut', array( $this, 'check_erp_statut' ) );

		add_action( 'admin_init', array( $this, 'redirect_to' ) );
	}

	/**
	 * Enregistre la session et enlève l'admin bar.
	 *
	 * @since 2.0.0
	 */
	public function callback_register_session() {
		if ( ! session_id() ) {
			session_start();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			show_admin_bar( false );
		}
	}

	/**
	 * Charge le fichier de traduction.
	 *
	 * @since 2.0.0
	 */
	public function callback_language() {
		load_plugin_textdomain( 'wpshop', false, PLUGIN_WPSHOP_DIR . '/core/asset/language/' );
	}

	/**
	 * Installe les données par défaut.
	 *
	 * @todo: Documenter les données par défaut.
	 *
	 * @since 2.0.0
	 */
	public function callback_install_default() {
		Core::g()->default_install();
	}

	/**
	 * Enregistre les blocks gutenberg.
	 *
	 * @since 2.0.0
	 */
	public function callback_init_block() {
		$asset_file = include( PLUGIN_WPSHOP_PATH . 'build/block.asset.php');

		wp_register_script(
			'wpshop-products',
			PLUGIN_WPSHOP_URL . '/build/block.js',
			$asset_file['dependencies'],
			$asset_file['version']
		);

		wp_localize_script( 'wpshop-products', 'wpshop', array(
				'homeUrl'        => home_url(),
				'addToCartNonce' => wp_create_nonce( 'add_to_cart' ),
			)
		);

		register_block_type( 'wpshop/products', array(
			'editor_script' => 'wpshop-products',
		) );
	}

	/**
	 * Ajoute ajaxurl.
	 *
	 * @since 2.0.0
	 */
	public function define_ajax_url() {
		echo '<script type="text/javascript">
		  var ajaxurl = "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '";
		</script>';
	}

	/**
	 * Init backend style and script
	 *
	 * @since 2.0.0
	 */
	public function callback_admin_enqueue_scripts() {
		wp_enqueue_media();

		wp_dequeue_script( 'wpeo-assets-datepicker-js' );
		wp_dequeue_style( 'wpeo-assets-datepicker' );

		wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
		wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ) );

		wp_enqueue_style( 'wpshop-style', PLUGIN_WPSHOP_URL . 'core/asset/css/style.css', array(), \eoxia\Config_Util::$init['wpshop']->version );
		wp_enqueue_script( 'wpshop-backend-script', PLUGIN_WPSHOP_URL . 'core/asset/js/backend.min.js', array( 'jquery', 'jquery-form' ), \eoxia\Config_Util::$init['wpshop']->version );

		$script_params = array(
			'check_erp_statut_nonce' => wp_create_nonce( 'check_erp_statut' ),
			'url'                    => get_option( 'siteurl' ),
		);

		wp_localize_script( 'wpshop-backend-script', 'scriptParams', $script_params );
	}

	/**
	 * Init backend style and script
	 *
	 * @since 2.0.0
	 */
	public function callback_enqueue_scripts() {
		wp_dequeue_script( 'wpeo-assets-datepicker-js' );
		wp_dequeue_style( 'wpeo-assets-datepicker' );
		wp_enqueue_style( 'wpshop-style', PLUGIN_WPSHOP_URL . 'core/asset/css/style.css', array(), \eoxia\Config_Util::$init['wpshop']->version );
		wp_enqueue_style( 'wpshop-style-frontend', PLUGIN_WPSHOP_URL . 'core/asset/css/style.frontend.min.css', array(), \eoxia\Config_Util::$init['wpshop']->version );
		wp_enqueue_script( 'wpshop-frontend-script', PLUGIN_WPSHOP_URL . 'core/asset/js/frontend.min.js', array(), \eoxia\Config_Util::$init['wpshop']->version );

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$script_params = array(
			'check_erp_statut_nonce' => wp_create_nonce( 'check_erp_statut' ),
			'url'                    => get_option( 'siteurl' ),
			'dolibarr_url'           => $dolibarr_option['dolibarr_url'],
			'dolibarr_public_key'    => ! empty( $dolibarr_option['dolibarr_public_key'] ) ? $dolibarr_option['dolibarr_public_key'] : null,
		);

		wp_localize_script( 'wpshop-frontend-script', 'scriptParams', $script_params );
	}

	/**
	 * Ajoute le menu principal de WPshop.
	 *
	 * @since 2.0.0
	 */
	public function callback_admin_menu() {
		add_menu_page( __( 'WPshop', 'wpshop' ), __( 'WPshop', 'wpshop' ), 'manage_options', 'wpshop', '', 'dashicons-store' );
		add_submenu_page( 'wpshop', __( 'Dashboard', 'wpshop' ), __( 'Dashboard', 'wpshop' ), 'manage_options', 'wpshop', array( Dashboard::g(), 'callback_add_menu_page' ) );
	}

	public function check_erp_statut() {
		check_ajax_referer( 'check_erp_statut' );

		$statut          = false;
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		if ( empty( $dolibarr_option['dolibarr_url'] ) || empty( $dolibarr_option['dolibarr_secret'] ) ) {
			wp_send_json_success( array(
				'connected' => false,
			) );
		}

		$response = Request_Util::get( 'status' );

		if ( ! empty( $response ) && 200 === $response->success->code ) {
			$statut = true;
		}

		ob_start();
		if ( current_user_can( 'administrator') ) {
			require_once( PLUGIN_WPSHOP_PATH . '/core/view/erp-connexion-error.view.php' );
		}
		wp_send_json_success( array(
			'connected' => true,
			'statut'    => $statut,
			'view'      => ob_get_clean(),
		) );
	}

	/**
	 * Permet de rediriger l'utilisateur Client/Abonné vers la page de gestion de compte.
	 *
	 * @since 2.0.0
	 */
	public function redirect_to() {

		$current_user = current_user_can( 'subscriber' );

		if ( $current_user ) {
			$_pos  = strlen($_SERVER['REQUEST_URI']) - strlen('/wp-admin/');
			$_pos2 = strlen($_SERVER['REQUEST_URI']) - strlen('/wp-admin/profile.php');

			if ( ( strpos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false && ( ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) == $_pos ) || ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) == $_pos2 ) ) ) ) {

				wp_redirect( home_url( '/mon-compte' ) );

				die();
			}
		}
	}
}

new Core_Action();
