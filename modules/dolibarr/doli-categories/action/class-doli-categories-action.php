<?php
/**
 * La classe gérant les actions des catégories de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\View_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

// Include the Category_List_Table class
require_once( WP_PLUGIN_DIR . '/wpshop/modules/dolibarr/doli-categories/class/class-category-list-table.php' );

/**
 * Doli Category Action Class.
 */
class Doli_Category_Action {

	/**
	 * Définition des metaboxes sur la page.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var array
	 */
	public $metaboxes = null;

	/**
	 * Le constructeur.
	 *
	 * @since   2.1.0
	 * @version   2.1.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 20 );

		add_action( 'wps_checkout_create_category', array( $this, 'create_category' ), 10, 1 );
		add_action( 'admin_post_wps_download_category', array( $this, 'download_category' ) );

		add_action( 'admin_init', function () {
			if ( isset( $_GET['page'] ) && $_GET['page'] === 'wps-doli-categories' ) {
				wp_redirect( admin_url( 'edit-tags.php?taxonomy=wps-product-cat' ) );
				exit;
			}
		});

		add_action( 'admin_footer', function() {
			$screen = get_current_screen();
			if ( $screen && $screen->id === 'edit-wps-product-cat' ) {
				echo '<script>jQuery(document).ready(function($){ $("#description").prop("readonly", true); });</script>';
			}
		});

		add_action( 'wps-product-cat_edit_form_fields', array( $this, 'add_dolibarr_info_field' ), 10, 2 );
	}

	/**
	 * Ajoute un champ d'information Dolibarr sur l'édition de la catégorie.
	 *
	 * @param \WP_Term $term     Term object.
	 * @param string   $taxonomy Taxonomy slug.
	 */
	public function add_dolibarr_info_field( $term, $taxonomy ) {
		$external_id = get_term_meta( $term->term_id, '_external_id', true );
		if ( ! empty( $external_id ) ) {
			$dolibarr_option = get_option( 'wps_dolibarr', \wpshop\Settings::g()->default_settings );
			$doli_url = rtrim( $dolibarr_option['dolibarr_url'], '/' );
			
			$doli_cat = \wpshop\Request_Util::get( 'categories/' . $external_id );
			$name = ( ! empty( $doli_cat ) && isset( $doli_cat->label ) ) ? $doli_cat->label : 'ID ' . $external_id;
			
			$link = $doli_url . '/categories/card.php?id=' . $external_id . '&type=product';
			
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label>Dolibarr</label></th>
				<td>
					<p class="description">
						Id de la catégorie : <strong><?php echo esc_html( $external_id ); ?></strong> - <a href="<?php echo esc_url( $link ); ?>" target="_blank" style="text-decoration:none;"><strong><?php echo esc_html( $name ); ?></strong></a>
					</p>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Initialise la page "Catégories".
	 *
	 * @since     2.1.0
	 * @version   2.1.0
	 */
	public function callback_admin_menu() {
		if ( Settings::g()->dolibarr_is_active() ) {
			$hook = add_submenu_page( 'wpshop', 
									  __( 'Categories', 'wpshop' ), 
									  __( 'Categories', 'wpshop' ), 
									  'manage_options',
									  'wps-doli-categories',
									  '__return_null',
									  2 );
		}
	}

	/**
	 * Ajoute le menu "Options de l'écran".
	 *
	 * @since     2.1.0
	 * @version   2.1.0
	 */
	public function callback_add_screen_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => _x( 'Categories', 'Category per page', 'wpshop' ),
				'default' => Doli_Category::g()->limit,
				'option'  => Doli_Category::g()->option_per_page,
			)
		);
	}
}

new Doli_Category_Action();
