<?php
/**
 * La classe définisant le modèle d'une commande.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Order Model Class.
 */
class Doli_Order_Model extends Post_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Order $object     Les données de l'objet.
	 * @param string     $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {

		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'ID du customer (dolibarr). Relation avec dolibarr.',
		);

		$this->schema['external_status'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'external_status',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le status de la proposition commerciale dans dolibarr. Relation avec dolibarr.',
		);

		$this->schema['datec'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'datec',
			'context'     => array( 'GET' ),
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Date de création de la commande. Relation avec dolibarr',
		);

		$this->schema['date_commande'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'date_commande',
			'context'     => array( 'GET' ),
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Date de la commande. Relation avec dolibarr',
		);

		$this->schema['total_ht'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ht',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le prix total HT de la commande.',
		);

		$this->schema['tva_amount'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'tva_amount',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['shipping_cost'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'shipping_cost',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['total_price_no_shipping'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_price_no_shipping',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['total_tva'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_tva',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['total_ttc'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ttc',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
			'default'     => 0.00000000,
		);

		$this->schema['lines'] = array(
			'type'        => 'array',
			'meta_type'   => 'single',
			'field'       => '_lines',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Les lignes d\'information de la commande.',
			'default'     => null,
		);

		$this->schema['payment_method'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'payment_method',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La méthode de paiement utilisé pour la commande.',
			'default'     => '',
		);

		$this->schema['billed'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'billed',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La commande est payée.',
			'default'     => 0,
		);

		$this->schema['delivered'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'delivered',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'La commande est livrée.',
			'default'     => 0,
		);

		$this->schema['payment_failed'] = array(
			'type'        => 'boolean',
			'meta_type'   => 'single',
			'field'       => '_payment_failed',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'échec du paiement de la commande.',
			'default'     => false,
		);

		$this->schema['external_data'] = array(
			'type'        => 'array',
			'meta_type'   => 'single',
			'field'       => '_external_data',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Les données externes à la commande',
			'default'     => array(),
		);

		$this->schema['tracking_link'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_tracking_link',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'URL de suivi de colis pour la livraison',
		);

		$this->schema['traitment_in_progress'] = array(
			'type'        => 'boolean',
			'meta_type'   => 'single',
			'field'       => '_traitment_in_progress',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Permet d\'afficher un status "Traiement en cours" lors de l\'attente du retour de Paypal, Stripe ou autre système de paiement.',
			'default'     => false,
		);

		$this->schema['linked_objects_ids'] = array(
			'type'        => 'array',
			'meta_type'   => 'single',
			'field'       => '_linked_objects_ids',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'L\'id des objets liés.',
			'default'     => null,
		);

		parent::__construct( $object, $req_method );
	}
}
