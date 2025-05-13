<?php
/**
 * La classe gérant les actions principales de WPshop.
 *
 * Elle ajoute les styles et scripts JS principaux pour le bon fonctionnement de WPshop.
 * Elle ajoute également les textes de traductions (fichiers .mo)
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;
use \eoxia\Custom_Menu_Handler as CMH;

/**
 * WPshop Action.
 */
class WPshop_Action {

	/**
	 * Le Constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_register_session' ), 1 );
		add_action( 'init', array( $this, 'callback_language' ) );
		add_action( 'init', array( $this, 'callback_install_default' ) );

		add_action( 'init', array( $this, 'callback_init_block' ) );
		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_filter( 'block_categories_all', [$this, 'call_back_block_categories'] );
		} else {
			add_filter( 'block_categories', [$this, 'call_back_block_categories'] );
		}

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
	 * @since   2.0.0
	 * @version 2.0.0
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
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_language() {
		load_plugin_textdomain( 'wpshop', false, PLUGIN_WPSHOP_DIR . '/core/asset/language/' );
	}

	/**
	 * Installe les données par défaut.
	 *
	 * @todo: Documenter les données par défaut.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_install_default() {
		WPshop::g()->default_install();
	}

	/**
	 * Enregistre les blocks gutenberg.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_init_block() {
		$block_build_dir = PLUGIN_WPSHOP_PATH . '/blocks/build/';
		
		// Vérifier si le répertoire existe
		if (!file_exists($block_build_dir) || !is_dir($block_build_dir)) {
			// Log pour débogage
			error_log('WPShop: Le répertoire des blocs n\'existe pas: ' . $block_build_dir);
			return;
		}
		
		$block_dir_urls = scandir($block_build_dir);

		foreach ($block_dir_urls as $url) {
			// Skip directory navigation entries
			if ($url === '.' || $url === '..') {
				continue;
			}
			
			$block_dir = $block_build_dir . $url;
			$block_json_path = $block_dir . '/block.json';
		
			if (file_exists($block_json_path)) {
				// Read block.json to get the block name
				$block_json = json_decode(file_get_contents($block_json_path), true);
				
				if (!empty($block_json['name'])) {
					// Check if block is already registered
					if (!\WP_Block_Type_Registry::get_instance()->is_registered($block_json['name'])) {
						register_block_type($block_dir);
					} else {
						error_log('WPShop: Block already registered: ' . $block_json['name']);
					}
				} else {
					register_block_type($block_dir);
				}
			} else {
				error_log('WPShop: Fichier block.json non trouvé dans: ' . $block_dir);
			}
		
			if (file_exists($block_dir . '/inner-blocks')) {
				$inner_block_dir = $block_dir . '/inner-blocks/';
				$inner_block_dir_urls = scandir($inner_block_dir);
		
				foreach ($inner_block_dir_urls as $inner_url) {
					// Skip directory navigation entries
					if ($inner_url === '.' || $inner_url === '..') {
						continue;
					}
					
					$inner_block_path = $inner_block_dir . $inner_url;
					$inner_block_json_path = $inner_block_path . '/block.json';
		
					if (file_exists($inner_block_json_path)) {
						// Read block.json to get the block name
						$inner_block_json = json_decode(file_get_contents($inner_block_json_path), true);
						
						if (!empty($inner_block_json['name'])) {
							// Check if block is already registered
							if (!\WP_Block_Type_Registry::get_instance()->is_registered($inner_block_json['name'])) {
								register_block_type($inner_block_path);
							} else {
								error_log('WPShop: Inner block already registered: ' . $inner_block_json['name']);
							}
						} else {
							register_block_type($inner_block_path);
						}
					} else {
						error_log('WPShop: Fichier block.json non trouvé dans: ' . $inner_block_path);
					}
				}
			}
		}
	}

	public function call_back_block_categories( $block_categories  ) {

		array_unshift(
			$block_categories,
			[
				'slug'  => 'wpshop',
				'title' => __( 'WPshop', 'wpshop' )
			]
		);
		return $block_categories;
	}

	/**
	 * Ajoute ajaxurl.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function define_ajax_url() {
		echo '<script type="text/javascript">
		  var ajaxurl = "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '";
		</script>';
	}

	/**
	 * Init backend style and script.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
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
	 * Init backend style and script.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_enqueue_scripts() {
		wp_dequeue_script( 'wpeo-assets-datepicker-js' );
		wp_dequeue_style( 'wpeo-assets-datepicker' );
		wp_enqueue_style( 'wpshop-style', PLUGIN_WPSHOP_URL . 'core/asset/css/style.css', array(), \eoxia\Config_Util::$init['wpshop']->version );
		wp_enqueue_style( 'wpshop-style-frontend', PLUGIN_WPSHOP_URL . 'core/asset/css/style.frontend.min.css', array(), \eoxia\Config_Util::$init['wpshop']->version );
		
		add_editor_style( PLUGIN_WPSHOP_URL . 'core/asset/css/style.min.css' );
		add_editor_style( PLUGIN_WPSHOP_URL . 'core/asset/css/style.frontend.min.css' );
		add_editor_style( PLUGIN_WPSHOP_URL . 'core/external/eo-framework/core/assets/css/style.min.css' );
		
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
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function callback_admin_menu() {
		// CMH::register_container( 'WPshop', 'WPshop', 'manage_options', 'wpshop', '', PLUGIN_WPSHOP_URL . 'core/asset/image/wpshop-16x16.png', null );
		// CMH::add_logo( 'wpshop', PLUGIN_WPSHOP_URL . 'core/asset/image/wpshop.png', admin_url( 'admin.php?page=wpshop' ) );
		// CMH::register_menu( 'wpshop', __( 'Dashboard', 'wpshop' ), __( 'Dashboard', 'wpshop' ), 'manage_options', 'wpshop', array( Dashboard::g(), 'callback_add_menu_page' ), 'fa fa-home', 'bottom' );

//		add_menu_page( __( 'WPshop', 'wpshop' ), __( 'WPshop', 'wpshop' ), 'manage_options', 'wpshop', '', 'dashicons-store' );
//		add_submenu_page( 'wpshop', __( 'Dashboard', 'wpshop' ), __( 'Dashboard', 'wpshop' ), 'manage_options', 'wpshop', array( Dashboard::g(), 'callback_add_menu_page' ) );
	


		add_menu_page(
			'WPshop', // Titre de la page
			'WPshop', // Nom du menu
			'manage_options', // Capacité requise
			'wpshop', // Slug du menu
			[$this, 'display_menu_page'], // Fonction qui affiche la page
			PLUGIN_WPSHOP_URL . 'core/asset/image/wpshop-16x16.png',
		);

	}

	public function display_menu_page() {
		// Code pour afficher le contenu de la page de menu
		echo '<h1>Bienvenue sur la page de menu WPshop</h1>';
	}

	/**
	 * Vérifie le statut de l'ERP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
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
	 * @since   2.0.0
	 * @version 2.0.0
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

new WPshop_Action();
