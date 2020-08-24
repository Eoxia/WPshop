<?php
/**
 * La classe gérant les fonction principales des domunents de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\Post_Class;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Documents Class.
 */
class Doli_Documents extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Documents_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-documents';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'documents';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'documents';

	/**
	 * Le nom du post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'documents';

	/**
	 * Appel la vue "list" du module "doli-order".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$per_page        = get_user_meta( get_current_user_id(), Doli_Order::g()->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Doli_Order::g()->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$route = 'orders?sortfield=t.rowid&sortorder=DESC&limit=' . $per_page . '&page=' . ( $current_page - 1 );

		if ( ! empty( $s ) ) {
			// La route de dolibarr ne fonctionne pas avec des caractères en base10
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_orders = Request_Util::get( $route );
		$orders      = $this->convert_to_wp_order_format( $doli_orders );

		if ( ! empty( $orders ) ) {
			foreach ( $orders as &$element ) {
				$element->data['tier']  = Third_Party::g()->get( array( 'id' => $element->data['parent_id'] ), true );
				$element->data['datec'] = \eoxia\Date_Util::g()->fill_date( $element->data['datec'] );
			}
		}

		View_Util::exec( 'wpshop', 'doli-order', 'list', array(
			'orders'   => $orders,
			'doli_url' => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Appel la vue "item" d'une commande.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $order    Les données d'une commande.
	 * @param string     $doli_url L'url de Dolibarr.
	 */
	public function display_item( $order, $doli_url = '' ) {
		if ( empty( $order->data['tier'] ) ) {
			$order->data['tier'] = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );
		}
		View_Util::exec( 'wpshop', 'doli-order', 'item', array(
			'order'    => $order,
			'doli_url' => $doli_url,
		) );
	}

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
	public function convert_to_wp_documents_format( $doli_documents ) {
		$wp_documents = array();

		if ( ! empty( $doli_documents ) ) {
			foreach ( $doli_documents as $doli_document ) {
				$wp_document    = $this->get( array( 'schema' => true ), true );
				$wp_documents[] = $this->doli_to_wp( $doli_document, $wp_document, true );
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
	public function doli_to_wp( $doli_document, $wp_document, $only_convert = false ) {
		if ( is_object( $wp_document ) ) {
			//$wp_document->data['external_id'] = (int) $doli_document->id;
			$wp_document->data['name']        = $doli_document->name;
			$wp_document->data['path']        = $doli_document->path;
			$wp_document->data['fullpath']        = $doli_document->fullname;
			$time = get_date_from_gmt ( date( 'Y-m-d H:i:s', $doli_document->date ) );
			$wp_document->data['date']        = $time;
			$wp_document->data['size']        = (int) $doli_document->size;
			$wp_document->data['dolibarr_type']        = $doli_document->type;
			//$wp_document->data['parent_id']   = Doli_Products::g()->get_wp_id_by_doli_id( $doli_document->socid );

			$wp_document->data['linked_objects_ids'] = array();

			// @todo: Répéter dans toutes les méthode doli_to_wp. Mettre dans CommonObject.
			if ( ! empty( $doli_document->linkedObjectsIds ) ) {
				foreach ( $doli_document->linkedObjectsIds as $key => $values ) {
					$values = (array) $values;
					$wp_document->data['linked_objects_ids'][ $key ] = array();

					if ( ! empty( $values ) ) {
						foreach ( $values as $value ) {
							$wp_document->data['linked_objects_ids'][ $key ][] = (int) $value;
						}
					}
				}
			}

			if ( ! $only_convert ) {
				$wp_document = $this->update( $wp_document->data );
			}
		}
			return $wp_document;
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

Doli_Documents::g();
