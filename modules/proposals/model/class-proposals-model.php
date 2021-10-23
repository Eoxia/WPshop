<?php
/**
 * Classe définisant le modèle d'un proposal WPSHOP.
 *
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <technique@eoxia.com>.
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
 * Class proposal model.
 */
class Proposals_Model extends \eoxia\Post_Model {

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

		$this->schema['prefix'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'prefix',
			'since'       => '2.0.0',
			'description' => 'Le prefix du devis',
		);

		$this->schema['number'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'number',
			'since'       => '2.0.0',
			'default'     => 1,
			'description' => 'La numérotation du devis',
		);

		$this->schema['datec'] = array(
			'type'        => 'wpeo_date',
			'meta_type'   => 'single',
			'field'       => 'datec',
			'since'       => '2.0.0',
			'description' => 'Date de création de la commande. Relation avec dolibarr',
			'context'     => array( 'GET' ),
		);

		$this->schema['total_ht'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_ht',
			'since'       => '2.0.0',
			'description' => '',
		);

		$this->schema['total_tva'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'total_tva',
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

		$this->schema['mode_reglement_id'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => 'mode_reglement_id',
			'default'   => 0,
		);

		$this->schema['sync_sha_256'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_sync_sha_256',
			'since'       => '2.0.0',
			'description' => 'La cohérence de la synchronisation avec un ERP',
		);

		parent::__construct( $object, $req_method );
	}
}
