<?php
/**
 * La classe gérant les filtres d'association des entités.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Associate Filter Class.
 */
class Doli_Associate_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_associate_entry', array( $this, 'add_email' ), 10, 2 );
	}

	/**
	 * Ajoute l'adresse email dans l'utilisateur venant de dolibarr dans la modal d'association.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $output Le nom de l'utilisateur.
	 * @param  Object $entry  Les données de l'utilisateur.
	 *
	 * @return string         Le nom de l'utilisateur avec son adresse email.
	 */
	public function add_email( $output, $entry ) {
		if ( '1' === $entry->entity && ! empty( $entry->email ) ) {
			$output .= ' (' . $entry->email . ')';
		}

		return $output;
	}
}

new Doli_Associate_Filter();
