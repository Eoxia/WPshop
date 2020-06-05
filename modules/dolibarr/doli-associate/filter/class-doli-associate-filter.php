<?php
/**
 * Les filtres relatives aux association.
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
 * Doli Associate Filter Class.
 */
class Doli_Associate_Filter {

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_associate_entry', array( $this, 'add_email' ), 10, 2 );
	}

	/**
	 * Ajoutes l'adresse email dans l'utilisateur venant de dolibarr dans la
	 * modal d'association.
	 *
	 * @since 2.0.0
	 *
	 * @param string $output Le nom de l'utilisateur.
	 * @param Object $entry  Les données de l'utilisateur.
	 *
	 * @return string        Le nom de l'utilisateur avec son adresse email.
	 */
	public function add_email( $output, $entry ) {
		if ( '1' === $entry->entity && ! empty( $entry->email ) ) {
			$output .= ' (' . $entry->email . ')';
		}

		return $output;
	}
}

new Doli_Associate_Filter();
