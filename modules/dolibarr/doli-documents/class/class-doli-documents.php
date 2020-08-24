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

use eoxia\Attachment_Class;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Documents Class.
 */
class Doli_Documents extends Attachment_Class {

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
}

Doli_Documents::g();
