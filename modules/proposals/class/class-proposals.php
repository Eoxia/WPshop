<?php
/**
 * Classe gérant les devis.
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
 * Proposals Class.
 */
class Proposals extends \eoxia\Post_Class {

	/**
	 * Model name @see ../model/*.model.php.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Proposals_Model';

	/**
	 * Post type
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-proposal';

	/**
	 * La clé principale du modèle
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'proposal';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'proposal';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	/**
	 * Le nom du post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Proposals';

	/**
	 * La limite par page.
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $limit = 10;

	/**
	 * Le nom de l'option pour la limite par page.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $option_per_page = 'proposal_per_page';

	/**
	 * Récupères la liste des devis et appel la vue "list" du module "order".
	 *
	 * @since 2.0.0
	 */
	public function display() {
		$per_page = get_user_meta( get_current_user_id(), $this->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = $this->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$proposal_ids = Proposals::g()->search( $s, array(
			'offset'         => ( $current_page - 1 ) * $per_page,
			'posts_per_page' => $per_page,
			'post_status'    => 'any',
		) );

		$proposals = array();

		if ( ! empty( $proposal_ids ) ) {
			$proposals = $this->get( array(
				'post__in' => $proposal_ids,
			) );
		}

		if ( ! empty( $proposals ) ) {
			foreach ( $proposals as &$element ) {
				$element->data['tier'] = null;

				if ( ! empty( $element->data['parent_id'] ) ) {
					$element->data['tier'] = Third_Party::g()->get( array( 'id' => $element->data['parent_id'] ), true );
				}
			}
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		\eoxia\View_Util::exec( 'wpshop', 'proposals', 'list', array(
			'proposals' => $proposals,
			'doli_url'  => $dolibarr_option['dolibarr_url'],
		) );
	}

	public function display_item( $proposal, $sync_status, $doli_url = '' ) {
		\eoxia\View_Util::exec( 'wpshop', 'proposals', 'item', array(
			'proposal'    => $proposal,
			'sync_status' => $sync_status,
			'doli_url'    => $doli_url,
		) );
	}

	/**
	 * Récupères la dernière ref des devis.
	 *
	 * @since 2.0.0
	 *
	 * @return string La référence.
	 */
	public function get_last_ref() {

		//$number = get_post_meta( 'number' );
		global $wpdb;

		$last_ref = $wpdb->get_var( "
			SELECT meta_value FROM $wpdb->postmeta AS PM
				JOIN $wpdb->posts AS P ON PM.post_id=P.ID

			WHERE PM.meta_key='number'
				AND P.post_type='wps-proposal'

			ORDER BY PM.meta_id DESC
			LIMIT 0,1
		" );
		if ( empty( $last_ref ) ) {
			$proposal = Proposals::g()->get( array( 'schema' => true ), true );
			$last_ref = $proposal->data['number'];
		}

		return (int) $last_ref;
	}

	/**
	 * Fonctions de recherche
	 *
	 * @since 2.0.0
	 *
	 * @param  string  $s            Le terme de la recherche.
	 * @param  array   $default_args Les arguments par défaut.
	 * @param  boolean $count        Si true compte le nombre d'élement, sinon
	 * renvoies l'ID des éléments trouvés.
	 *
	 * @return array|integer         Les ID des éléments trouvés ou le nombre
	 * d'éléments trouvés.
	 */
	public function search( $s = '', $default_args = array(), $count = false ) {
		$args = array(
			'post_type'      => 'wps-proposal',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post_status'    => 'any',
		);

		$args = wp_parse_args( $default_args, $args );

		if ( ! empty( $s ) ) {
			$proposals_id = get_posts( array(
				's'              => $s,
				'fields'         => 'ids',
				'post_type'      => 'wps-proposal',
				'posts_per_page' => -1,
				'post_status'    => 'any',
			) );

			if ( empty( $proposals_id ) ) {
				if ( $count ) {
					return 0;
				} else {
					return array();
				}
			} else {
				$args['post__in'] = $proposals_id;

				if ( $count ) {
					return count( get_posts( $args ) );
				} else {
					return $proposals_id;
				}
			}
		}

		if ( $count ) {
			return count( get_posts( $args ) );
		} else {
			return get_posts( $args );
		}
	}

	public function add_line( $order, $line_data ) {
		$order->data['lines'][] = $line_data;

		$this->update( $order->data );
	}

	public function update_line( $order, $line_data ) {
		$founded_line = null;
		$key_line     = null;
		// Search line by rowid.
		if ( ! empty( $order->data['lines'] ) ) {
			foreach ( $order->data['lines'] as $key => $line ) {
				if ( $line['rowid'] == $line_data['rowid'] ) {
					$founded_line = $line;
					$key_line     = $key;
					break;
				}
			}
		}

		if ( $founded_line != null ) {
			array_splice( $order->data['lines'], $key_line, 1 );

			$order->data['lines'][] = $line_data;

			$this->update( $order->data );
		}
	}

	public function delete_line( $order, $row_id ) {
		$founded_line = null;
		$key_line     = null;
		// Search line by rowid.
		if ( ! empty( $order->data['lines'] ) ) {
			foreach ( $order->data['lines'] as $key => $line ) {
				if ( $line['rowid'] == $row_id ) {
					$founded_line = $line;
					$key_line     = $key;
					break;
				}
			}
		}

		if ( $founded_line != null ) {
			array_splice( $order->data['lines'], $key_line, 1 );

			$this->update( $order->data );
		}
	}
}

Proposals::g();
