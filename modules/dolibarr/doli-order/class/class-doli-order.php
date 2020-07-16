<?php
/**
 * Fonction principales pour les commandes avec DolibarR.
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
 * Doli Order Class.
 */
class Doli_Order extends \eoxia\Post_Class {

	/**
	 * Model name @see ../model/*.model.php.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Doli_Order_Model';

	/**
	 * Post type
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-order';

	/**
	 * La clé principale du modèle
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'order';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'order';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	/**
	 * Nom du post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $post_type_name = 'Orders';

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
	public $option_per_page = 'doli_order_per_page';

	/**
	 * Récupères la liste des devis et appel la vue "list" du module "order".
	 *
	 * @since 2.0.0
	 */
	public function display() {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$per_page        = get_user_meta( get_current_user_id(), Doli_Order::g()->option_per_page, true );

		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Doli_Order::g()->limit;
		}

		$current_page = isset( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$route = 'orders?sortfield=t.rowid&sortorder=DESC&limit=' . $per_page . '&page=' . ( $current_page - 1 );

		if ( ! empty( $s ) ) {
			// La route de dolibarr ne fonctionne pas avec des caractères en base10
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_orders = Request_Util::get( $route );
		$orders      = $this->convert_to_wp_order_format( $doli_orders );

		if ( ! empty( $orders ) ) {
			foreach ( $orders as &$element ) {
				$element->data['tier']  = Third_Party::g()->get( array( 'id' => $element->data['parent_id'] ), true );
				$element->data['datec'] = \eoxia\Date_Util::g()->fill_date( $element->data['datec'] );
			}
		}

		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'list', array(
			'orders'   => $orders,
			'doli_url' => $dolibarr_option['dolibarr_url'],
		) );
	}

	public function display_item( $order, $doli_url = '' ) {
		if ( empty( $order->data['tier'] ) ) {
			$order->data['tier'] = Third_Party::g()->get( array( 'id' => $order->data['parent_id'] ), true );
		}
		\eoxia\View_Util::exec( 'wpshop', 'doli-order', 'item', array(
			'order'    => $order,
			'doli_url' => $doli_url,
		) );
	}

	/**
	 * Convertis un tableau Order Object provenant de dolibarr vers un format Order Object WPShop afin de normisé pour l'affichage.
	 *
	 * @since 2.0.0
	 *
	 * @param array $doli_orders Tableau Order Object provenant de Dolibarr.
	 *
	 * @return array $wp_orders Tableau Order Object de WPshop convertis depuis le format de Dolibarr.
	 */
	public function convert_to_wp_order_format( $doli_orders ) {
		$wp_orders = array();

		if ( ! empty( $doli_orders ) ) {
			foreach ( $doli_orders as $doli_order ) {
				$wp_order    = $this->get( array( 'schema' => true ), true );
				$wp_orders[] = $this->doli_to_wp( $doli_order, $wp_order, true );
			}
		}

		return $wp_orders;
	}

	/**
	 * Synchronisation depuis Dolibarr vers WP.
	 *
	 * @since 2.0.0
	 *
	 * @param  stdClass    $doli_order   Les données venant de dolibarr.
	 * @param  Order_Model $wp_order     Les données de WP.
	 * @param  Boolean     $only_convert Only Convert Dolibarr Object to WP. Don't save the WP Object on the database.
	 *
	 * @return Order_Model             Les données de WP avec ceux de dolibarr.
	 */
	public function doli_to_wp( $doli_order, $wp_order, $only_convert = false ) {
		if ( is_object( $wp_order ) ) {
			$wp_order->data['external_id']    = (int) $doli_order->id;
			$wp_order->data['title']          = $doli_order->ref;
			$wp_order->data['total_ht']       = $doli_order->total_ht;
			$wp_order->data['total_ttc']      = $doli_order->total_ttc;
			$wp_order->data['total_tva']      = $doli_order->total_tva;
			$wp_order->data['billed']         = (int) $doli_order->billed;
			$wp_order->data['date_commande']  = date( 'Y-m-d H:i:s', $doli_order->date_commande );
			$time = get_date_from_gmt ( date( 'Y-m-d H:i:s', $doli_order->date_creation ) );
			$wp_order->data['datec']          = $time;
			$wp_order->data['author_id']      = Doli_User::g()->get_wp_id_by_doli_id( $doli_order->user_author_id );
			$wp_order->data['parent_id']      = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $doli_order->socid );
			$wp_order->data['payment_method'] = Doli_Payment::g()->convert_to_wp( $doli_order->mode_reglement_code );

			$wp_order->data['lines'] = null;

			if ( ! empty( $doli_order->lines ) ) {
				$wp_order->data['lines'] = array();
				foreach ( $doli_order->lines as $line ) {
					$line_data = array(
						'fk_commande' => $doli_order->id,
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

					$wp_order->data['lines'][] = $line_data;
				}
			}

			$wp_order->data['linked_objects_ids'] = array();

			// @todo: Répéter dans toutes les méthode doli_to_wp. Mettre dans CommonObject.
			if ( ! empty( $doli_order->linkedObjectsIds ) ) {
				foreach ( $doli_order->linkedObjectsIds as $key => $values ) {
					$values = (array) $values;
					$wp_order->data['linked_objects_ids'][ $key ] = array();

					if ( ! empty( $values ) ) {
						foreach ( $values as $value ) {
							$wp_order->data['linked_objects_ids'][ $key ][] = (int) $value;
						}
					}
				}
			}

			$status = '';

			switch ( $doli_order->statut ) {
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
					break;
				case 3:
					$status                      = 'wps-delivered';
					$wp_order->data['delivered'] = 1;
					break;
				default:
					$status = 'publish';
					break;
			}

			if ( $wp_order->data['billed'] ) {
				$status = 'wps-billed';

				if ( ! $only_convert ) {
					Product_Downloadable::g()->create_from_order( $wp_order );
				}
			}

			$wp_order->data['status'] = $status;

			if ( ! $only_convert ) {
				$wp_order = Doli_Order::g()->update( $wp_order->data );
			}

			return $wp_order;
		}
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
		$route = 'orders?sortfield=t.rowid&sortorder=DESC';

		if ( ! empty( $s ) ) {
			$route .= '&sqlfilters=(t.ref%3Alike%3A\'%25' . $s . '%25\')';
		}

		$doli_orders = Request_Util::get( $route );

		if ( $count && ! empty( $doli_orders ) ) {
			return count( $doli_orders );
		} else {
			return 0;
		}
	}

	public function add_line( $order, $line_data ) {
		$order->data['lines'][] = $line_data;

		Doli_Order::g()->update( $order->data );
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

			Doli_Order::g()->update( $order->data );
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

			Doli_Order::g()->update( $order->data );
		}
	}
}

Doli_Order::g();
