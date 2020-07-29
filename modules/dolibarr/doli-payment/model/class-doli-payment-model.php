<?php
/**
 * La Classe définisant le modèle d'un paiement de Dolibarr.
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
 * Doli Payement Model.
 */
class Doli_Payment_Model extends Post_Model {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Doli_Payment $object     Les données de l'objet.
	 * @param string       $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['payment_type'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_payment_type',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le type de paiement',
		);

		$this->schema['amount'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_amount',
			'since'       => '2.0.0',
			'version'     => '2.0.0',
			'description' => 'Le montant du paiement',
		);

		parent::__construct( $object, $req_method );
	}
}
