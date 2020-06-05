<?php
/**
 * Classe définisant le modèle d'une commande WPshop.
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
 * Orders Model Class.
 */
class Doli_Order_Model extends \eoxia\Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Order  $object     Les données de l'objet.
	 * @param string $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {

		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => '_external_id',
			'since'       => '2.0.0',
			'description' => 'L\'ID du customer (dolibarr). Relation avec dolibarr.',
		);

		$this->schema['external_status'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'external_status',
			'since'       => '2.0.0',
			'description' => 'Le status de la proposition commerciale dans dolibarr. Relation avec dolibarr.',
		);

		$this->schema['datec'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'datec',
			'since'       => '2.0.0',
			'description' => 'Date de création de la commande. Relation avec dolibarr',
			'context'     => array( 'GET' ),
		);

		$this->schema['date_commande'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'date_commande',
			'since'       => '2.0.0',
			'description' => 'Date de la commande. Relation avec dolibarr',
			'context'     => array( 'GET' ),
		);

		$this->schema['total_ht'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ht',
			'since'       => '2.0.0',
			'description' => '',
		);

		$this->schema['tva_amount'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'tva_amount',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['shipping_cost'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'shipping_cost',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['total_price_no_shipping'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_price_no_shipping',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['total_tva'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_tva',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		$this->schema['total_ttc'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ttc',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
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

		$this->schema['billed'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'billed',
			'default'   => 0,
		);

		$this->schema['delivered'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'delivered',
			'default'   => 0,
		);

		$this->schema['payment_failed'] = array(
			'type'      => 'boolean',
			'meta_type' => 'single',
			'field'     => '_payment_failed',
			'default'   => false,
		);

		$this->schema['external_data'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => '_external_data',
			'default'   => array(),
		);

		$this->schema['tracking_link'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_tracking_link',
			'since'       => '2.0.0',
			'description' => 'URL de suivi de colis pour la livraison',
		);

		$this->schema['traitment_in_progress'] = array(
			'type'        => 'boolean',
			'meta_type'   => 'single',
			'field'       => '_traitment_in_progress',
			'default'     => false,
			'since'       => '2.0.0',
			'description' => 'Permet d\'afficher un status "Traiement en cours" lors de l\'attente du retour de Paypal, Stripe ou autre système de paiement.',
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
