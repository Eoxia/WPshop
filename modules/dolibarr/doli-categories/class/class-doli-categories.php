<?php
/**
 * La classe gérant les fonction principales des catégories de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\Term_Class;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Documents Class.
 */
class Doli_Categories extends Term_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Category_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-product-cat';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'categories';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @var string
	 */
	protected $base = 'wps-product-cat';

	/**
	 * Le nom du post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'wps-product-cat';

	/**
	 * Convertit un tableau Documents Object provenant de Dolibarr vers un format Documents Object WPshop afin de normisé pour l'affichage.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  stdClass $doli_documents Le tableau contenant toutes les données des documents provenant de Dolibarr.
	 *
	 * @return Doli_Documents           Le tableau contenant toutes les données des documents convertis depuis le format de Dolibarr.
	 */
	public function convert_to_wp_categories_format( $doli_categories ) {
		$wp_categories = array();

		if ( ! empty( $doli_categories ) ) {
			foreach ( $doli_categories as $doli_category ) {
				$wp_category    = $this->get( array( 'schema' => true ), true );
				$wp_categories[] = $this->doli_to_wp( $doli_category, $wp_category, true );
			}
		}

		return $wp_documents;
	}

	/**
	 * Synchronisation depuis Dolibarr vers WP.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  stdClass       $doli_document Les données d'un document Dolibarr.
	 * @param  Doli_Documents $wp_document   Les données d'un document WordPress.
	 * @param  boolean        $only_convert  Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Doli_Documents                Les données d'un document WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_category, $wp_category, $only_convert = false ) {
		if ( is_object( $wp_category ) ) {
			//$wp_document->data['external_id'] = (int) $doli_document->id;
			$wp_category->data['id']        = $doli_category->id;
			$wp_category->data['type']        = $doli_category->type;
			$wp_category->data['term_taxonomy_id']    = $doli_category->term_taxonomy_id;
//			$time = get_date_from_gmt ( date( 'Y-m-d H:i:s', $doli_category->date ) );
//			$wp_category->data['date']        = $time;
//			$wp_category->data['size']        = (int) $doli_category->size;
//			$wp_category->data['dolibarr_type']        = $doli_category->type;
//			//$wp_document->data['parent_id']   = Doli_Products::g()->get_wp_id_by_doli_id( $doli_document->socid );
//
//			$wp_category->data['linked_objects_ids'] = array();

			// @todo: Répéter dans toutes les méthode doli_to_wp. Mettre dans CommonObject.
			if ( ! empty( $doli_category->linkedObjectsIds ) ) {
				foreach ( $doli_category->linkedObjectsIds as $key => $values ) {
					$values = (array) $values;
					$wp_category->data['linked_objects_ids'][ $key ] = array();

					if ( ! empty( $values ) ) {
						foreach ( $values as $value ) {
							$wp_category->data['linked_objects_ids'][ $key ][] = (int) $value;
						}
					}
				}
			}

			if ( ! $only_convert ) {
				$wp_category = $this->update( $wp_category->data );
			}
		}
		return $wp_category;
	}

	/**
	 * Fonction de recherche.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre d'éléments trouvés.
	 */
	public function search( $s = '', $default_args = array(), $count = false ) {
		$route = 'orders?sortfield=t.rowid&sortorder=DESC';

		if ( ! empty( $s ) ) {
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_orders = Request_Util::get( $route );

		if ( $count && ! empty( $doli_orders ) ) {
			return count( $doli_orders );
		} else {
			return 0;
		}
	}
}

Doli_Categories::g();
