<?php
/**
 * La classe gérant les fonctions principales des catégories de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.5.0
 */

namespace wpshop;

use eoxia\Term_Class;
use eoxia\View_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Category Class.
 */
class Doli_Category extends Term_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Category_Model';

	/**
	 * Le term type.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	protected $type = 'wps-product-cat';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	protected $meta_key = 'doli-categories';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	protected $base = 'doli-categories';

	/**
	 * Le nom du term type.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Doli Category';

	/**
	 * La limite par page.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var integer
	 */
	public $limit = 1000;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	public $option_per_page = 'category_doli_per_page';

	/**
	 * Appel la vue "list" du module "doli-categories".
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	public function display() {
		global $wpdb;
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$per_page        = get_user_meta( get_current_user_id(), Doli_Category::g()->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Doli_Category::g()->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$route = 'categories?sortfield=t.rowid&sortorder=DESC&limit=' . $per_page . '&page=' . ( $current_page - 1 );

		if ( ! empty( $s ) ) {
			// La route de dolibarr ne fonctionne pas avec des caractères en base10
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_categories = Request_Util::get( $route );


		foreach ( $doli_categories as $key => $doli_category ) {
			if ( $doli_category->array_options->options__wps_id == 0 ) {
				unset( $doli_categories[$key] );
			}
		}

		$wp_categories   = $this->convert_to_wp_category_format( $doli_categories );

//		if ( ! empty($wp_categories)) {
//			foreach( $wp_categories as $wp_category) {
//				if (empty($wp_category->data['external_id'] || $wp_category->data['external_id'] == 0)) {
//					wp_delete_term($wp_category->data['id'],'wps-product-cat');
//				}
//			}
//		}

		View_Util::exec( 'wpshop', 'doli-categories', 'list', array(
			'categories' => $wp_categories,
			'doli_url' => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Appel la vue "item" d'une catégorie.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param Doli_Category $category  Les données d'une catégorie.
	 * @param string       $doli_url L'url de Dolibarr.
	 */
	public function display_item( $category, $doli_url = '' ) {
		View_Util::exec( 'wpshop', 'doli-categories', 'item', array(
			'category' => $category,
		) );
	}

	/**
	 * Convertit un tableau Category Object provenant de Dolibarr vers un format Category Object WPshop afin de normisé pour l'affichage.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param  stdClass $doli_categories Le tableau contenant toutes les données des catégories provenant de Dolibarr.
	 *
	 * @return Doli_Category            Le tableau contenant toutes les données des catégories convertis depuis le format de Dolibarr.
	 */
	public function convert_to_wp_category_format( $doli_categories ) {
		$wp_categories = array();

		if ( ! empty( $doli_categories ) ) {
			foreach ( $doli_categories as $doli_category ) {
				$wp_category    = $this->get( array( 'schema' => true ), true );
				$wp_categories[] = $this->doli_to_wp( $doli_category, $wp_category);
			}
		}

		return $wp_categories;
	}

	/**
	 * Synchronise depuis Dolibarr vers WP.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param  stdClass               $doli_category Les données d'une catégorie Dolibarr.
	 * @param  Doli_Category          $wp_category   Les données d'une catégorie WordPress.
	 * @param  boolean  $save         Enregistres les données sinon renvoies l'objet remplit sans l'enregistrer en base de donnée.
	 * @param  array    $notices      Gestion des erreurs et informations de l'évolution de la méthode.	 *
	 * @return Doli_Category          Les données d'une catégorie WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_category, $wp_category, $save = true, &$notices = array(
		'errors'   => array(),
		'messages' => array(),
	) ) {
		$category = null;

		$doli_category = Request_Util::get( 'categories/' . $doli_category->id ); // Charges par la route single des factures pour avoir accès à linkedObjectsIds->commande.

		$wp_category->data['external_id'] = (int) $doli_category->id;

		$wp_category->data['name'] = $doli_category->label;
		if ( ! empty($doli_category->array_options->options__wps_slug) ) {
			$wp_category->data['slug'] = $doli_category->array_options->options__wps_slug;
		}

		$wp_category = Doli_Category::g()->update( $wp_category->data );

		if ( $save ) {
			$data_sha = array();
			$data_sha['doli_id']  = (int) $wp_category->data['external_id'];
			$data_sha['wp_id']    = $wp_category->data['id'];
			$data_sha['name']     = $wp_category->data['name'];
			$data_sha['slug']  	  = $wp_category->data['slug'];

			$wp_category->data['sync_sha_256'] = hash( 'sha256', implode( ',', $data_sha ) );
			//@todo save_post utilisé ?

			update_term_meta( $wp_category->data['id'], '_sync_sha_256', $wp_category->data['sync_sha_256'] );
			update_term_meta( $wp_category->data['id'], '_external_id', (int) $doli_category->id );
			$notices['messages'][] = sprintf( __( 'Erase data for the product <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop' ), $wp_category->data['name'] );
		}

		return $wp_category;
	}

	/**
	 * Fonction de recherche.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre d'éléments trouvés.
	 */
	public function search( $s = '', $default_args = array(), $count = false ) {
		$route = 'categories?sortfield=t.rowid&sortorder=DESC';

		if ( ! empty( $s ) ) {
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_categories = Request_Util::get( $route );

		if ( $count && ! empty( $doli_categories ) ) {
			return count( $doli_categories );
		} else {
			return 0;
		}
	}

	public function get_related_categories( $object ) {
		$related_categories = array();
		$related_terms = get_the_terms( $object->data['id'],'wps-product-cat' );
		if ( ! empty( $related_terms ) ) {
			foreach ( $related_terms as $related_term ) {
				$related_categories[] = $related_term->name;
			}
		}

		return $related_categories;

	}
}

Doli_Category::g();
