<?php
/**
 * La classe définisant le modèle d'une facture.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Invoice Model Class.
 */
class Doli_Invoice_Model extends Post_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
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
			'version'     => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'ID provenant de dolibarr',
		);

		$this->schema['datec'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'datec',
			'context'     => array( 'GET' ),
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Date de création de la fature. Relation avec dolibarr',
		);

		$this->schema['date_invoice'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'date_invoice',
			'context'     => array( 'GET' ),
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Date de la fature. Relation avec dolibarr',
		);

		$this->schema['total_ht'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ht',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix total HT de la facture.',
		);

		$this->schema['total_ttc'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ttc',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix total de la facture, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['resteapayer'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'resteapayer',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix restant a payer de la facture.',
			'default'     => 0.00000000,
		);

		$this->schema['totalpaye'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'totalpaye',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix total payé de la facture.',
			'default'     => 0.00000000,
		);

		$this->schema['lines'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => '_lines',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Les lignes d\'information de la facture.',
			'default'   => null,
		);

		$this->schema['payment_method'] = array(
			'type'      => 'string',
			'meta_type' => 'single',
			'field'     => 'payment_method',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La méthode de paiement utilisé pour la facture.',
			'default'   => '',
		);

		$this->schema['paye'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'paye',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix payé de la facture.',
			'default'   => 0,
		);

		$this->schema['third_party_id'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_third_party_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'id du tier.',
		);

		$this->schema['avoir'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_avoir',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'avoir sur une facture.',
			'default'   => 0,
		);

		$this->schema['date_last_synchro'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => '_date_last_synchro',
			'context'     => array( 'GET' ),
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La date de la dernière synchronisation.',
		);

		$this->schema['linked_objects_ids'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => '_linked_objects_ids',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'id des objets liés.',
			'default'   => null,
		);

		parent::__construct( $object, $req_method );
	}
}
