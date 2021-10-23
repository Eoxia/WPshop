<?php
/**
 * La classe gérant les fonctions principales des tiers.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Post_Class;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Third Party class.
 */
class Third_Party extends Post_Class {

	/**
	 * Le nom du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Third_Party_Model';

	/**
	 * Le post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-third-party';

	/**
	 * La clé principale du modèle.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'third-party';

	/**
	 * La route pour accéder à l'objet dans la rest API.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'third-party';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

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
	public $option_per_page = 'third_party_per_page';

	/**
	 * Récupère la liste des produits et appel la vue "list" du module "Product".
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function display() {
		$per_page = get_user_meta( get_current_user_id(), $this->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = $this->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$third_parties_ids = Third_Party::g()->search( $s, array(
			'orderby'        => 'ID',
			'offset'         => ( $current_page - 1 ) * $per_page,
			'posts_per_page' => $per_page,
		) );

		$third_parties = array();

		if ( ! empty( $third_parties_ids ) ) {
			$third_parties = $this->get( array(
				'orderby'  => 'post__in',
				'post__in' => $third_parties_ids,
			) );
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		View_Util::exec( 'wpshop', 'third-parties', 'list', array(
			'third_parties' => $third_parties,
			'doli_url'      => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Appel la vue "item" du tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param Third_Party $third_party Les données d'un produit.
	 * @param string      $sync_status Le statut de la synchronisation.
	 * @param string      $doli_url    L'url de Dolibarr.
	 */
	public function display_item( $third_party, $sync_status, $doli_url = '' ) {
		View_Util::exec( 'wpshop', 'third-parties', 'item', array(
			'third_party' => $third_party,
			'sync_status' => $sync_status,
			'doli_url'    => $doli_url,
		) );
	}

	/**
	 * Affiche les trois dernières actions commerciales du tier.
	 *
	 * @since   2.0.0
	 * @version 2.3.0
	 *
	 * @param Third_Party $third_party Les données du tier.
	 */
	public function display_commercial( $third_party ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_active = Settings::g()->dolibarr_is_active() ? true : false;

		// Move to doli module.
		$order   = array();
		$invoice = array();
		$proposal = array();

		$propal = Proposals::g()->get( array(
			'post_parent'    => $third_party['id'],
			'posts_per_page' => 1,
		), true );

		if ( $dolibarr_active && ! empty( $third_party['external_id'] ) ) {

			$doli_proposal = Request_Util::get( 'proposals?sortfield=t.rowid&sortorder=DESC&limit=1&thirdparty_ids=' . $third_party['external_id'] );

			if ( isset( $doli_proposal[0] ) ) {
				$wp_proposal = Proposals::g()->get( array( 'schema' => true ), true );
				$proposal = Doli_Proposals::g()->doli_to_wp( $doli_proposal[0], $wp_proposal, true );
			}

			$doli_order = Request_Util::get( 'orders?sortfield=t.rowid&sortorder=DESC&limit=1&thirdparty_ids=' . $third_party['external_id'] );

			if ( isset($doli_order[0] ) ) {
				$wp_order = Doli_Order::g()->get( array( 'schema' => true ), true );
				$order = Doli_Order::g()->doli_to_wp( $doli_order[0], $wp_order, true );
			}

			$doli_invoice = Request_Util::get( 'invoices?sortfield=t.rowid&sortorder=ASC&limit=100&thirdparty_ids=' . $third_party['external_id'] );

			if ( isset( $doli_invoice[0] ) ) {
				$wp_invoice = Doli_Invoice::g()->get( array( 'schema' => true ), true );
				$invoice    = Doli_Invoice::g()->doli_to_wp( $doli_invoice[0], $wp_invoice, true );
			}
		}

		View_Util::exec( 'wpshop', 'third-parties', 'commercial', array(
			'doli_url'    => $dolibarr_option['dolibarr_url'],
			'order'       => $order,
			//'propal'    => $propal,
			'proposal'    => $proposal,
			'invoice'     => $invoice,
			'doli_active' => $dolibarr_active,
		) );
	}

	/**
	 * La fonction de recherche.
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
		$args = array(
			'post_type'      => 'wps-third-party',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		$args = wp_parse_args( $default_args, $args );

		if ( ! empty( $s ) ) {
			// Search in contact.
			$users_id = get_users( array(
				'search' => '*' . $s . '*',
				'fields' => 'ID',
				'number' => 20,
			) );

			$users_id = array_merge( $users_id, get_users( array(
				'meta_key'     => '_phone',
				'meta_value'   => implode( '[. ]*', \str_split( str_replace( ' ', '', $s ) ) ) . '[. ]*',
				'meta_compare' => 'REGEXP',
				'fields'       => 'ID',
				'number'       => 20,
			) ) );

			$third_parties_id = array_map( function( $id ) {
				return get_user_meta( $id, '_third_party_id', true );
			}, $users_id );

			$third_parties_id_from_contact = array_filter( $third_parties_id, 'is_numeric' );

			$third_parties_id = array_merge( $third_parties_id_from_contact, get_posts( array(
				's'              => $s,
				'fields'         => 'ids',
				'post_type'      => 'wps-third-party',
				'posts_per_page' => -1,
			) ) );

			array_unique( $third_parties_id );

			if ( empty( $third_parties_id ) ) {
				if ( $count ) {
					return 0;
				} else {
					return array();
				}
			} else {
				$args['post__in'] = $third_parties_id;

				if ( $count ) {
					return count( get_posts( $args ) );
				} else {
					return $third_parties_id;
				}
			}
		}

		if ( $count ) {
			return count( get_posts( $args ) );
		} else {
			return get_posts( $args );
		}

		return $result;
	}

	/**
	 * Dissocie tous les contact d'un tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  Third_Party $third_party Les données du tier.
	 *
	 * @return array                    Messages informatifs.
	 */
	public function dessociate_contact( $third_party ) {
		$messages = array();

		if ( ! empty( $third_party->data['contact_ids'] ) ) {
			$contacts = Contact::g()->get( array( 'include' => $third_party->data['contact_ids'] ) );

			if ( ! empty( $contacts ) ) {
				foreach ( $contacts as $contact ) {
					$contact->data['third_party_id'] = 0;

					Contact::g()->update( $contact->data );

					// translators: Dissociate contact <strong>test@wpshop.fr</strong> from <strong>eoxia</strong>.
					$messages[] = sprintf( __( 'Dissociate contact <strong>%1$s</strong> from <strong>%2$s</strong>', 'wpshop' ), $contact->data['email'], $third_party->data['title'] );
				}
			}

			$third_party->data['contact_ids'] = array();

			$this->update( $third_party->data );
		}

		return $messages;
	}
}

Third_Party::g();
