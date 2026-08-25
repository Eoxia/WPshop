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

		add_filter( 'parent_file', function( $parent_file ) {
			global $current_screen;
			if ( isset( $current_screen->id ) && 'edit-wps-product-cat' === $current_screen->id ) {
				return 'wpshop';
			}
			return $parent_file;
		} );

		add_filter( 'submenu_file', function( $submenu_file ) {
			global $current_screen;
			if ( isset( $current_screen->id ) && 'edit-wps-product-cat' === $current_screen->id ) {
				return 'edit-tags.php?taxonomy=wps-product-cat&post_type=wps-product';
			}
			return $submenu_file;
		} );

		add_action( 'wps-product-cat_edit_form_fields', array( $this, 'add_dolibarr_info_field' ), 10, 2 );
		add_action( 'edited_wps-product-cat', array( $this, 'save_dolibarr_info_field' ), 10, 2 );
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
			
			$link = $doli_url . '/categories/viewcat.php?id=' . $external_id . '&type=product';
			
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label>Dolibarr</label></th>
				<td>
					<p class="description">
						Id de la catégorie : <strong><?php echo esc_html( $external_id ); ?></strong> - <a href="<?php echo esc_url( $link ); ?>" target="_blank" style="text-decoration:none;"><strong><?php echo esc_html( $name ); ?></strong></a>
					</p>
				</td>
			</tr>
			<script>jQuery(document).ready(function($){ 
				$("#description").prop("readonly", true);
				$("#description").after("<p class=\'description\' style=\'color:#d63638;\'>Ce champ est en lecture seule car cette catégorie est synchronisée avec Dolibarr.</p>");
			});</script>
			<?php
		} else {
			// Find already linked dolibarr categories
			$linked_terms = get_terms( array(
				'taxonomy'   => 'wps-product-cat',
				'hide_empty' => false,
				'meta_query' => array(
					array(
						'key'     => '_external_id',
						'compare' => 'EXISTS'
					)
				)
			) );
			$linked_doli_ids = array();
			if ( ! is_wp_error( $linked_terms ) ) {
				foreach ( $linked_terms as $t ) {
					$ext_id = get_term_meta( $t->term_id, '_external_id', true );
					if ( ! empty( $ext_id ) ) {
						$linked_doli_ids[] = (int) $ext_id;
					}
				}
			}

			// Fetch Dolibarr categories
			$doli_categories = \wpshop\Request_Util::get( 'categories?type=product' );
			$options = '<option value="">-- Sélectionnez une catégorie --</option>';
			if ( ! empty( $doli_categories ) && ! isset( $doli_categories->error ) ) {
				// We can sort them by name for convenience
				$sorted_doli_cats = (array) $doli_categories;
				usort($sorted_doli_cats, function($a, $b) {
					return strcasecmp($a->label, $b->label);
				});
				foreach ( $sorted_doli_cats as $doli_cat ) {
					if ( ! in_array( (int) $doli_cat->id, $linked_doli_ids ) ) {
						$options .= '<option value="' . esc_attr( $doli_cat->id ) . '">' . esc_html( $doli_cat->id . ' - ' . $doli_cat->label ) . '</option>';
					}
				}
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label>Dolibarr</label></th>
				<td>
					<p class="description" style="color:#d63638;">
						Catégorie non synchronisée avec Dolibarr. Vous pouvez l'associer en sélectionnant une catégorie Dolibarr libre :<br><br>
						<select name="doli_external_id" style="min-width: 300px; max-width: 100%;">
							<?php echo $options; ?>
						</select>
					</p>
					<p class="description" style="color:#d63638;">Dans ce cas la description sera écrasée par celle de Dolibarr.</p>
				</td>
			</tr>
			<script>jQuery(document).ready(function($){ 
				$("#description").after("<p class=\'description\' style=\'color:#d63638;\'>Ce champ est en lecture seule uniquement s\'il est associé à une catégorie Dolibarr, ce qui n\'est pas le cas ici.</p>");
				if ($.fn.select2) {
					$("select[name='doli_external_id']").select2({ width: 'resolve' });
				} else if ($.fn.selectWoo) {
					$("select[name='doli_external_id']").selectWoo({ width: 'resolve' });
				}
			});</script>
			<?php
		}
	}

	/**
	 * Sauvegarde l'association manuelle de la catégorie
	 */
	public function save_dolibarr_info_field( $term_id ) {
		$external_id = 0;
		if ( isset( $_POST['doli_external_id'] ) ) {
			$external_id = intval( $_POST['doli_external_id'] );
			if ( $external_id > 0 ) {
				update_term_meta( $term_id, '_external_id', $external_id );
				
				// Assigner l'ID WordPress à la catégorie côté Dolibarr pour que l'association soit bidirectionnelle
				\wpshop\Request_Util::get( 'doliwpshop/associatecategory?wp_id=' . (int) $term_id . '&doli_id=' . (int) $external_id );
			}
		} else {
			$existing_external_id = get_term_meta( $term_id, '_external_id', true );
			if ( ! empty( $existing_external_id ) ) {
				$external_id = intval( $existing_external_id );
			}
		}

		if ( $external_id > 0 ) {
			// Re-synchronize category from Dolibarr immediately to get the description and name
			$doli_cat = \wpshop\Request_Util::get( 'categories/' . $external_id );
			if ( ! empty( $doli_cat ) && isset( $doli_cat->label ) ) {
				$wp_cat = \wpshop\Doli_Category::g()->get( array( 'id' => $term_id ), true );
				\wpshop\Doli_Category::g()->doli_to_wp( $doli_cat, $wp_cat );
			}
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
									  'edit-tags.php?taxonomy=wps-product-cat&post_type=wps-product'
									  );
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
