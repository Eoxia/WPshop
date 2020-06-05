<?php
/**
 * Classe définisant le modèle d'un produit WPshop.
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
 * Class product model.
 */
class Doli_Invoice_Model extends \eoxia\Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Doli_Invoice $object     Les données de l'objet.
	 * @param string       $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.0.0',
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['datec'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'datec',
			'since'       => '2.0.0',
			'description' => 'Date de création de la fature. Relation avec dolibarr',
			'context'     => array( 'GET' ),
		);

		$this->schema['date_invoice'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'date_invoice',
			'since'       => '2.0.0',
			'description' => 'Date de la fature. Relation avec dolibarr',
			'context'     => array( 'GET' ),
		);

		$this->schema['total_ht'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ht',
			'since'       => '2.0.0',
			'description' => '',
		);

		$this->schema['total_ttc'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ttc',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la facture, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['resteapayer'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'resteapayer',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la facture, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['totalpaye'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'totalpaye',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la facture, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['lines'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => '_lines',
			'default'   => null,
		);

		$this->schema['payment_method'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'payment_method',
			'default'   => '',
		);

		$this->schema['paye'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'paye',
			'default'   => 0,
		);

		$this->schema['third_party_id'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_third_party_id',
		);

		$this->schema['avoir'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_avoir',
			'default'   => 0,
		);

		$this->schema['date_last_synchro'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => '_date_last_synchro',
			'since'       => '2.0.0',
			'description' => 'La date de la dernière synchronisation.',
			'context'     => array( 'GET' ),
		);

		$this->schema['linked_objects_ids'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => '_linked_objects_ids',
			'since'     => '2.0.0',
			'default'   => null,
		);

		parent::__construct( $object, $req_method );
	}
}
