<?php
/**
 * La classe gérant les fonction principales des propositions commerciales de Dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.3
 */

namespace wpshop;

use eoxia\Singleton_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Proposals Class.
 */
class Doli_Proposals extends Singleton_Util {

	/**
	 * La limite par page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var integer
	 */
	public $limit = 10;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	public $option_per_page = 'proposal_doli_per_page';

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {}

	/**
	 * Synchronise Dolibarr vers WPshop.
	 *
	 * @since   2.0.0
	 * @version 2.3.3
	 *
	 * @param  stdClass $doli_proposal Les données d'une proposition commerciale Dolibarr.
	 * @param  Proposals $wp_proposal  Les données d'une proposition commerciale WordPress.
	 *
	 * @return Proposals               Les données d'une proposition commerciale WordPress avec ceux de Dolibarr.
	 */
	public function doli_to_wp( $doli_proposal, $wp_proposal ) {
		if ( is_object( $wp_proposal ) ) {
			$wp_proposal->data['external_id']    = (int) $doli_proposal->id;
			$wp_proposal->data['title']          = $doli_proposal->ref;
			$wp_proposal->data['total_ht']       = $doli_proposal->total_ht;
			$wp_proposal->data['total_tva']      = $doli_proposal->total_tva;
			$wp_proposal->data['total_ttc']      = $doli_proposal->total_ttc;
			$wp_proposal->data['billed']         = 0;

			// @todo: Les trois valeurs ci-dessous ne corresponde pas au valeur de Dolibarr elle sont adapté pour répondre au besoin de WPshop.
			// @todo: Détailler les besoins de WPshop et enlever ce fonctionnement pour le coup.
			$time = get_date_from_gmt ( date( 'Y-m-d H:i:s', $doli_proposal->datec ) );
			$wp_proposal->data['datec']          = $time;
			$wp_proposal->data['parent_id']      = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $doli_proposal->socid );
			$wp_proposal->data['payment_method'] = ( null === $doli_proposal->mode_reglement_code ) ? $wp_proposal->data['payment_method'] : Doli_Payment::g()->convert_to_wp( $doli_proposal->mode_reglement_code );

			$wp_proposal->data['lines'] = null;

			if ( ! empty( $doli_proposal->lines ) ) {
				$wp_proposal->data['lines'] = array();
				foreach ( $doli_proposal->lines as $line ) {
					$line_data = array(
						'fk_proposal' => $doli_proposal->id,
						'fk_product'  => $line->fk_product,
						'qty'         => $line->qty,
						'total_tva'   => $line->total_tva,
						'total_ht'    => $line->total_ht,
						'total_ttc'   => $line->total_ttc,
						'libelle'     => ! empty( $line->libelle ) ? $line->libelle : $line->desc,
						'tva_tx'      => $line->tva_tx,
						'subprice'    => $line->price,
						'rowid'       => $line->rowid,
					);

					$wp_proposal->data['lines'][] = $line_data;
				}
			}

			// @todo: Ajouter une ENUMERATION pour mieux comprendre les chiffres dans ce switch.
			switch ( $doli_proposal->statut ) {
				case -1:
					$status = 'wps-canceled';
					break;
				case 0:
					$status = 'draft';
					break;
				case 1:
					$status = 'publish';
					break;
				case 2:
					$status = 'wps-accepted';
					break;
				case 3:
					$status = 'wps-refused';
					break;
				case 4:
					$status                      = 'wps-billed';
					$wp_proposal->data['billed'] = 1;
					break;
				default:
					$status = 'publish';
					break;
			}

			$wp_proposal->data['status'] = $status;

			return $wp_proposal;
		}
	}

	/**
	 * Appel la vue "list" du module "doli-proposals".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$per_page        = get_user_meta( get_current_user_id(), Doli_Order::g()->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Doli_Proposals::g()->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$route = 'proposals?sortfield=t.rowid&sortorder=DESC&limit=' . $per_page . '&page=' . ( $current_page - 1 );

		if ( ! empty( $s ) ) {
			// La route de dolibarr ne fonctionne pas avec des caractères en base10
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_proposals = Request_Util::get( $route );
		$proposals      = $this->convert_to_wp_proposal_format( $doli_proposals );

		if ( ! empty( $proposals ) ) {
			foreach ( $proposals as &$element ) {
				$element->data['tier']  = Third_Party::g()->get( array( 'id' => $element->data['parent_id'] ), true );
				$element->data['datec'] = \eoxia\Date_Util::g()->fill_date( $element->data['datec'] );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'doli-proposals', 'list', array(
			'proposals' => $proposals,
			'doli_url'  => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Appel la vue "item" d'une proposition commerciale.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Proposals  $proposal    Les données d'une proposition commerciale.
	 * @param boolean    $sync_status Le statut de la synchronisation.
	 * @param string     $doli_url    L'url de Dolibarr.
	 */
	public function display_item( $proposal, $sync_status, $doli_url = '' ) {
		\eoxia\View_Util::exec( 'wpshop', 'doli-proposals', 'item', array(
			'proposal'    => $proposal,
			'sync_status' => $sync_status,
			'doli_url'    => $doli_url,
		) );
	}

	/**
	 * Fonction de recherche.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre d'éléments trouvés.
	 */
	public function search( $s = '', $default_args = array(), $count = false ) {
		$route = 'proposals?sortfield=t.rowid&sortorder=DESC';

		if ( ! empty( $s ) ) {
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_proposals = Request_Util::get( $route );

		if ( $count && ! empty( $doli_proposals ) ) {
			return count( $doli_proposals );
		} else {
			return 0;
		}
	}

	/**
	 * Convertit un tableau Proposal Object provenant de dolibarr vers un format Porposal Object WPShop afin de normisé pour l'affichage.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $doli_proposals Le tableau contenant toutes les données des propositions commerciales provenant de Dolibarr.
	 *
	 * @return array                 Le tableau contenant toutes les données des propositions commerciales convertis depuis le format de Dolibarr.
	 */
	public function convert_to_wp_proposal_format( $doli_proposals ) {
		$wp_proposals = array();

		if ( ! empty( $doli_proposals ) ) {
			foreach ( $doli_proposals as $doli_proposal ) {
				$wp_proposal    = Proposals::g()->get( array( 'schema' => true ), true );
				$wp_proposals[] = $this->doli_to_wp( $doli_proposal, $wp_proposal );
			}
		}

		return $wp_proposals;
	}
}

Doli_Proposals::g();
