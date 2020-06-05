<?php
/**
 * Classe définisant le modèle d'un paiement dolibarr.
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
 * Doli Payement Model.
 */
class Doli_Payment_Model extends \eoxia\Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Product $object     Les données de l'objet.
	 * @param string  $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['payment_type'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => '_payment_type',
			'since'       => '2.0.0',
			'description' => '',
		);

		$this->schema['amount'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => '_amount',
			'since'       => '2.0.0',
			'description' => '',
		);

//		$this->schema['last_sync'] = array(
//			'type'      => 'wpeo_date',
//			'meta_type' => 'single',
//			'field'     => '_last_sync',
//			'context'   => array( 'GET' ),
//		);

		parent::__construct( $object, $req_method );
	}
}
