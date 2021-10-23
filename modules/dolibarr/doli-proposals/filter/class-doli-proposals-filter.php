<?php
/**
 * La classe gérant les filtres des propositions commerciales de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.3.3
 * @version   2.3.3
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Proposals Filter Class.
 */
class Doli_Proposals_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.3.3
	 * @version 2.3.3
	 */
	public function __construct() {
		add_filter( 'wps_doli_status', array( $this, 'wps_doli_status' ), 10, 2 );
	}

	/**
	 * Change le statut d'une proposition commerciale.
	 *
	 * @since   2.3.3
	 * @version 2.3.3
	 *
	 * @param  string    $status Le statut d'une proposition commerciale.
	 * @param  Proposals $object Les données d'une proposition commerciale.
	 *
	 * @return string            Le nouveau statut d'une proposition commerciale.
	 */
	public function wps_doli_status( $status, $object ) {
		if ( $object->data['type'] == Proposals::g()->get_type() ) {
			switch ($object->data['status']) {
				case 'publish':
					$status .= __(' (open proposal)', 'wpshop');
					break;
				case 'wps-accepted':
					$status .= __(' (to be invoiced)', 'wpshop');
					break;
				case 'wps-refused':
					$status .= __(' (closed)', 'wpshop');
					break;
			}
		}
		return $status;
	}
}

new Doli_Proposals_Filter();
