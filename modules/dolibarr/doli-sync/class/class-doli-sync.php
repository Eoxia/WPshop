<?php
/**
 * La classe gérant les fonctions principales des synchronisations des entités de dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.3.1
 */

namespace wpshop;

use eoxia\Singleton_Util;
use eoxia\View_Util;
use stdClass;

defined( 'ABSPATH' ) || exit;

/**
 *  Doli Sync Class.
 */
class Doli_Sync extends Singleton_Util {

	/**
	 * Le tableau contenant toutes les données des synchronisations à effectuer.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $sync_infos = array();

	/**
	 * Limite de synchronisation par requête.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var integer
	 */
	public $limit_entries_by_request = 50;

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {
		$this->sync_infos = array(
			'wps-third-party' => array(
				'title'              => 'Third parties',
				'action'             => 'sync_third_parties',
				'nonce'              => 'sync_third_parties',
				'endpoint'           => 'thirdparties',
				'associate_endpoint' => 'Thirdparty',
				'wp_class'           => '\wpshop\\Third_Party', // @todo: Plural and not plural.
				'doli_class'         => '\wpshop\\Doli_Third_Parties',
				'doli_type'          => 'third_party',
			),
			'wps-product'      => array(
				'title'              => 'Products',
				'action'             => 'sync_products',
				'nonce'              => 'sync_products',
				'endpoint'           => 'Products',
				'associate_endpoint' => 'Product',
				'wp_class'           => '\wpshop\\Product',
				'doli_class'         => '\wpshop\\Doli_Products',
				'doli_type'          => 'product',
			),
			'wps-proposal'     => array(
				'title'              => 'Proposals',
				'action'             => 'sync_proposals',
				'nonce'              => 'sync_proposals',
				'endpoint'           => 'proposals',
				'associate_endpoint' => 'Proposal',
				'wp_class'           => '\wpshop\\Proposals',
				'doli_class'         => '\wpshop\\Doli_Proposals',
				'doli_type'          => 'propal',
			),
			'wps-product-cat'     => array(
				'title'              => 'Categories',
				'action'             => 'sync_categories',
				'nonce'              => 'sync_categories',
				'endpoint'           => 'categories',
				'associate_endpoint' => 'Category',
				'wp_class'           => '\wpshop\\Doli_Category',
				'doli_class'         => '\wpshop\\Doli_Category',
				'doli_type'          => 'category',
			),
		);
	}

	/**
	 * Normalise un montant pour la comparaison de synchronisation.
	 *
	 * Dolibarr renvoie les montants en double(24,8) sous forme de chaîne
	 * ("10.00000000"). On les ramène à une forme décimale canonique, sans
	 * zéros de fin superflus, pour que "10", "10.0" et "10.00000000" soient
	 * considérés identiques.
	 *
	 * @since   2.6.2
	 * @version 2.6.2
	 *
	 * @param  mixed $value Le montant brut.
	 *
	 * @return string       Le montant canonicalisé.
	 */
	public static function format_price( $value ) {
		$value = number_format( (float) $value, 8, '.', '' );
		$value = rtrim( rtrim( $value, '0' ), '.' );

		return ( '' === $value || '-0' === $value ) ? '0' : $value;
	}

	/**
	 * Normalise une valeur entière pour la comparaison de synchronisation.
	 *
	 * @since   2.6.2
	 * @version 2.6.2
	 *
	 * @param  mixed $value La valeur brute.
	 *
	 * @return integer      La valeur en entier.
	 */
	public static function format_int( $value ) {
		return (int) $value;
	}

	/**
	 * Normalise un texte pour la comparaison de synchronisation.
	 *
	 * Neutralise les différences invisibles qui font échouer la comparaison
	 * alors que le contenu affiché est identique : entités HTML, accents non
	 * normalisés (NFC/NFD), espaces insécables, retours à la ligne et espaces
	 * multiples.
	 *
	 * @since   2.6.2
	 * @version 2.6.2
	 *
	 * @param  mixed $value Le texte brut.
	 *
	 * @return string       Le texte canonicalisé.
	 */
	public static function format_text( $value ) {
		$value = (string) $value;
		$value = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' );

		if ( class_exists( '\Normalizer' ) ) {
			$normalized = \Normalizer::normalize( $value, \Normalizer::FORM_C );

			if ( is_string( $normalized ) ) {
				$value = $normalized;
			}
		}

		// Espace insécable (U+00A0) -> espace normale.
		$value = str_replace( "\xC2\xA0", ' ', $value );

		// Fusionne les blancs (retours à la ligne, tabulations, espaces multiples).
		$collapsed = preg_replace( '/\s+/u', ' ', $value );

		if ( is_string( $collapsed ) ) {
			$value = $collapsed;
		}

		return trim( $value );
	}

	/**
	 * Get sync info by type.
	 *
	 * @todo: Mal nommé
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $post_type Le type de l'entité.
	 *
	 * @return array             Les données d'une synchronisation.
	 */
	public function get_sync_infos( $post_type ) {
		return $this->sync_infos[ $post_type ];
	}

	/**
	 * Compte le nombre d'entrée.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $sync_info Les informations de synchro.
	 *
	 * @return array            Les informations de synchro avec le nombre total d'élement en plus.
	 */
	public function count_entries( $sync_info ) {
		if ( ! empty( $sync_info['endpoint'] ) ) {
			$args = http_build_query( [
				'limit' => $this->limit_entries_by_request,
				'page'  => $sync_info['page'],
			] );

			$tmp = Request_Util::get( $sync_info['endpoint'] . '?' . $args );

			if ( $tmp ) {
				$count                      = count( $tmp );
				$sync_info['total_number'] += count( $tmp );

				if ( $count >= $this->limit_entries_by_request ) {
					$sync_info['page']++;
					$sync_info = $this->count_entries( $sync_info );
				}
			}
		}

		return $sync_info;
	}

	/**
	 * Associe et synchronise les données d'une entité.
	 *
	 * @todo: Translate to english.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 *
	 * @param  integer $wp_id    L'id de l'entitée sur WordPress.
	 * @param  integer $entry_id L'id de l'entitée sur Dolibarr.
	 * @param  string  $type     Le type de l'entitée.
	 *
	 * @return array             Les données d'une synchronisation.
	 *
	 * // @todo: Handle Error sync.
	 */
	public function sync( $wp_id, $entry_id, $type ) {
		global $wpdb;
		$wp_error  = new \WP_Error();
		$wp_object = null;
		$messages  = array();

		switch ( $type ) {
			case 'wps-third-party':
				$doli_third_party = Request_Util::get( 'thirdparties/' . $entry_id );
				$wp_third_party   = Third_Party::g()->get( array( 'id' => $wp_id ), true );

				$wp_third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_third_party, $wp_third_party );

				// translators: Erase date for the third party <strong>Eoxia</strong> with the <strong>dolibarr</strong> data.
				$messages[] = sprintf( __( 'Erase data for the third party <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop' ), $wp_third_party->data['title'] );

				$wp_object = $wp_third_party;
				break;
			case 'wps-product':
				$doli_product = Request_Util::get( 'products/' . $entry_id );
				
				$wp_product   = Product::g()->get( array( 'id' => $wp_id ), true );
				$wp_product   = Doli_Products::g()->doli_to_wp( $doli_product, $wp_product );
				Doli_Products::g()->update_post_image( $wp_product->data['id'], $entry_id );

				$messages[] = sprintf( __( 'Erase data for the product <strong>%s</strong> with the <strong>dolibarr</strong> data', 'wpshop' ), $wp_product->data['title'] );

				// Rattachement des catégories par identifiant Dolibarr (_external_id) et non par nom :
				// robuste aux accents/encodage et aux doublons. La catégorie absente est créée puis liée,
				// et l'ensemble est remplacé d'un seul appel wp_set_object_terms (compteurs + caches à jour).
				$doli_categories = Request_Util::get( 'categories/object/product/' . $entry_id . '?' );
				$term_ids        = array();
				if ( ! empty( $doli_categories ) ) {
					foreach ( $doli_categories as $doli_category ) {
						$term_id = $this->resolve_category_term_id( $doli_category, true );
						if ( $term_id ) {
							$term_ids[] = $term_id;
						}
					}
				}
				wp_set_object_terms( $wp_product->data['id'], $term_ids, 'wps-product-cat', false );

				$wp_object = $wp_product;
				break;
//@todo à supprimer **********************************************************
			case 'wps-proposal':
				$doli_proposal = Request_Util::get( 'proposals/' . $entry_id );
				$wp_proposal   = Proposals::g()->get( array( 'id' => $wp_id ), true );

				Doli_Proposals::g()->doli_to_wp( $doli_proposal, $wp_proposal );

				$wp_object = $wp_proposal;
				break;
//@todo à supprimer **********************************************************
			case 'wps-product-cat':
				$doli_category = Request_Util::get( 'categories/' . $entry_id );
				$wp_category   = Doli_Category::g()->get( array( 'id' => $wp_id ), true );

				$wp_category   = Doli_Category::g()->doli_to_wp( $doli_category, $wp_category);

				$wp_object = $wp_category;
				break;
			default:
				break;
		}

		return array(
			'messages'  => $messages,
			'wp_error'  => $wp_error,
			'wp_object' => $wp_object,
		);
	}

	/**
	 * Retrouve (ou crée) le terme WP "wps-product-cat" correspondant à une catégorie Dolibarr,
	 * en s'appuyant sur l'identifiant Dolibarr (_external_id) plutôt que sur le nom (insensible
	 * aux accents/encodage et aux doublons). Si le terme existe par son nom mais sans lien, on
	 * le réutilise et on le lie (backfill de _external_id).
	 *
	 * @since   2.5.1
	 *
	 * @param  stdClass $doli_category Catégorie Dolibarr (au moins ->id et ->label).
	 * @param  boolean  $create        Crée le terme s'il est introuvable.
	 *
	 * @return integer                 L'ID du terme WP, ou 0 si introuvable et non créé.
	 */
	private function resolve_category_term_id( $doli_category, $create = false ) {
		if ( empty( $doli_category->id ) ) {
			return 0;
		}
		
		static $auto_sync = null;
		if ( $auto_sync === null ) {
			$setup = Request_Util::get( 'setup/conf' );
			$auto_sync = ( isset($setup->WPSHOP_AUTO_SYNC_PRODUCT_CATEGORIES) && $setup->WPSHOP_AUTO_SYNC_PRODUCT_CATEGORIES == 1 );
		}

		if ( ! $auto_sync && empty( $doli_category->array_options->options__wps_id ) ) {
			return 0;
		}

		$found = get_terms( array(
			'taxonomy'   => 'wps-product-cat',
			'hide_empty' => false,
			'meta_key'   => '_external_id',
			'meta_value' => (int) $doli_category->id,
			'number'     => 1,
			'fields'     => 'ids',
		) );
		if ( ! empty( $found ) ) {
			return (int) $found[0];
		}

		// Repli : un terme du même nom existe déjà mais sans lien -> on le réutilise et on le lie.
		$by_name = ! empty( $doli_category->label ) ? get_term_by( 'name', $doli_category->label, 'wps-product-cat' ) : false;
		if ( $by_name ) {
			update_term_meta( $by_name->term_id, '_external_id', (int) $doli_category->id );
			return (int) $by_name->term_id;
		}

		if ( ! $create ) {
			return 0;
		}

		$created = wp_insert_term( $doli_category->label, 'wps-product-cat', array(
			'description' => isset( $doli_category->description ) ? $doli_category->description : '',
		) );
		if ( is_wp_error( $created ) || empty( $created['term_id'] ) ) {
			return 0;
		}
		update_term_meta( $created['term_id'], '_external_id', (int) $doli_category->id );

		return (int) $created['term_id'];
	}

	/**
	 * Récupère l'instantané des champs enregistrés au dernier sync (méta _sync_debug),
	 * pour permettre une comparaison champ par champ lors du diagnostic.
	 *
	 * @since   2.6.2
	 *
	 * @param  integer $id   L'id de l'entité WordPress.
	 * @param  string  $type Le type de l'entité.
	 *
	 * @return array         Le tableau des champs enregistrés, ou vide.
	 */
	private function get_stored_sha_data( $id, $type ) {
		if ( 'wps-user' === $type ) {
			$raw = get_user_meta( $id, '_sync_debug', true );
		} elseif ( 'wps-product-cat' === $type ) {
			$raw = get_term_meta( $id, '_sync_debug', true );
		} else {
			$raw = get_post_meta( $id, '_sync_debug', true );
		}

		$data = ! empty( $raw ) ? json_decode( $raw, true ) : array();

		return is_array( $data ) ? $data : array();
	}

	/**
	 * Compare le jeu de champs enregistré et celui recalculé, et renvoie uniquement
	 * les champs qui diffèrent (avec la valeur stockée et la valeur recalculée).
	 *
	 * @since   2.6.2
	 *
	 * @param  array $stored  Les champs enregistrés au dernier sync.
	 * @param  array $current Les champs recalculés depuis Dolibarr.
	 *
	 * @return array          Les écarts : [champ => ['stored' => ..., 'current' => ...]].
	 */
	private function diff_sha_data( $stored, $current ) {
		$diff = array();
		$keys = array_unique( array_merge( array_keys( $stored ), array_keys( $current ) ) );

		foreach ( $keys as $key ) {
			$s = isset( $stored[ $key ] ) ? $stored[ $key ] : null;
			$c = isset( $current[ $key ] ) ? $current[ $key ] : null;

			if ( (string) $s !== (string) $c ) {
				$diff[ $key ] = array( 'stored' => $s, 'current' => $c );
			}
		}

		return $diff;
	}

	/**
	 * Vérifie la SHA256 entre une entité WPShop et une entité Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 *
	 * @param   integer $id   L'id de l'entité WordPress.
	 * @param   string  $type Le type de l'entité.
	 *
	 * @return array          Le statut de la synchronisation.
	 */
	public function check_status( $id, $type ) {
		$external_id = 0;
		$sha_256 = 0;
		global $wpdb;

		if ( $type == 'wps-user' ) {
			$external_id = get_user_meta($id, '_external_id', true);
			$sha_256 = get_user_meta($id, '_sync_sha_256', true);
		} elseif  ( $type == 'wps-product-cat' ) {
			$external_id = get_term_meta( $id, '_external_id', true );
			$sha_256     = get_term_meta( $id, '_sync_sha_256', true );
		} elseif ( $type == 'wps-product' ) {
			$external_id = get_post_meta( $id, '_external_id', true );
			$sha_256     = get_post_meta( $id, '_sync_sha_256', true );
		} else {
			$external_id = get_post_meta( $id, '_external_id' , true );
			$sha_256 = get_post_meta( $id, '_sync_sha_256', true );
		}

		$debug = array(
			'wp_id'       => (int) $id,
			'type'        => $type,
			'external_id' => $external_id,
			'sha_stored'  => $sha_256,
		);

		$sync_info = $this->sync_infos[ $type ];

		$response = Request_Util::get( $sync_info['endpoint'] . '/' . $external_id );

		// Request_Util::get() renvoie false aussi bien quand l'objet est introuvable que lors d'une
		// erreur API transitoire (timeout, 401, 500...). On ne supprime donc PLUS le lien automatiquement :
		// une erreur passagère ne doit pas casser la liaison et provoquer des doublons au sync suivant.
		if ( ! $response ) {
			$debug['dolibarr_found'] = false;

			return array(
				'status' => true,
				'status_code' => '0x1',
				'status_message' => 'Dolibarr Object: #' . $external_id . ' injoignable (lien conservé).',
				'debug' => $debug,
			);
		}

		$debug['dolibarr_found'] = true;

		// Le lien retour _wps_id côté Dolibarr ne pointe pas vers ce post WP.
		if ( $response->array_options->options__wps_id != $id ) {
			if ( empty( $response->array_options->options__wps_id ) ) {
				// Cas normal après un import Doli -> WP : _wps_id n'a jamais été renseigné côté Dolibarr.
				// On RÉPARE le lien (au lieu de détruire _external_id, ce qui générait des doublons).
				if ( $type == 'wps-product' ) {
					Request_Util::get( 'doliwpshop/associateProduct?wp_id=' . (int) $id . '&doli_id=' . (int) $external_id );
				} elseif ( $type == 'wps-third-party' ) {
					Request_Util::get( 'doliwpshop/associateThirdparty?wp_id=' . (int) $id . '&doli_id=' . (int) $external_id );
				} elseif ( $type == 'wps-product-cat' ) {
					Request_Util::get( 'doliwpshop/associatecategory?wp_id=' . (int) $id . '&doli_id=' . (int) $external_id );
				}
				// Le lien est désormais cohérent : on reflète la valeur en mémoire et on poursuit la vérification.
				$response->array_options->options__wps_id = $id;
			} else {
				// _wps_id pointe vers un AUTRE post WP : vrai conflit. On le signale sans rien supprimer.
				$debug['doli_wps_id'] = $response->array_options->options__wps_id;

				return array(
					'status' => true,
					'status_code' => '0x2',
					'status_message' => 'Dolibarr Object lié à un autre post WP (#' . $response->array_options->options__wps_id . '). Lien conservé.',
					'debug' => $debug,
				);
			}
		}

		// Comparaison des catégories par identifiant Dolibarr (_external_id), insensible à l'ordre.
		// (Auparavant : appariement par NOM, qui échouait dès qu'un accent/une entité différait.)
		$doli_categories = Request_Util::get('categories/object/product/' . $response->id . '?');

		$doli_category_labels = array();
		if ( ! empty( $doli_categories ) ) {
			foreach ( $doli_categories as $doli_category ) {
				$term_id = $this->resolve_category_term_id( $doli_category, false );
				if ( $term_id ) {
					$doli_category_labels[] = $term_id;
				}
			}
		}

		$wp_category_labels = wp_get_object_terms( $id, 'wps-product-cat', array( 'fields' => 'ids' ) );
		$wp_category_labels = is_wp_error( $wp_category_labels ) ? array() : array_map( 'intval', $wp_category_labels );

		sort( $doli_category_labels );
		sort( $wp_category_labels );

		$response = apply_filters( 'doli_build_sha_' . $type, $response, $id );

		// Diagnostic : quel test échoue, et pour le hash, quel champ diffère (stocké vs recalculé).
		$stored_data               = $this->get_stored_sha_data( $id, $type );
		$debug['sha_computed']     = $response->sha;
		$debug['sha_match']        = ( $response->sha === $sha_256 );
		$debug['field_diff']       = $this->diff_sha_data( $stored_data, isset( $response->sha_data ) ? $response->sha_data : array() );
		$debug['wp_categories']    = $wp_category_labels;
		$debug['doli_categories']  = $doli_category_labels;
		$debug['categories_match'] = ( $wp_category_labels == $doli_category_labels );

		// Noms des champs qui divergent, affichés dans la tooltip pour diagnostiquer sans accès BDD.
		// field_diff est vide si l'entité n'a jamais été resynchronisée depuis l'ajout de _sync_debug :
		// dans ce cas seul le hash global peut parler -> on indique qu'une resynchro est requise.
		$sha_diff_fields = array_keys( $debug['field_diff'] );
		if ( ! $debug['sha_match'] && empty( $sha_diff_fields ) ) {
			$sha_diff_fields[] = 'sha (resync requise)';
		}

		// WP Object is not equal Dolibarr Object.
		if ( $type == 'wps-product-cat' || $type == 'wps-third-party' ) {
			if ( $response->sha !== $sha_256 ) {
				return array(
					'status' => true,
					'status_code' => '0x3',
					'status_message' => __('WP Object is not equal Dolibarr Object', 'wpshop') . ' (' . implode( ', ', $sha_diff_fields ) . ')',
					'response->sha' => $response->sha,
					'sha_256' => $sha_256,
					'debug' => $debug,
				);
			}
		} else if ( $type == 'wps-product' ) {
			$data_ok = ( $response->sha === $sha_256 );
			$cat_ok  = ( $wp_category_labels == $doli_category_labels );
			
			// Image check
			$img_ok = true;
			$current_thumbnail_id = get_post_thumbnail_id($id);
			$files = Request_Util::get('documents?modulepart=product&id=' . $external_id);
			
			$doli_filename = '';
			if ( ! empty( $files ) && is_array( $files ) ) {
				foreach ( $files as $f ) {
					if ( isset( $f['filename'] ) && preg_match( '/\.(jpg|jpeg|png|gif|webp)$/i', $f['filename'] ) ) {
						$doli_filename = $f['filename'];
						break;
					}
				}
			}
			
			if ( ! empty( $doli_filename ) ) {
				$existing_attachment = get_posts( array(
					'post_type'      => 'attachment',
					'posts_per_page' => 1,
					'post_parent'    => $id,
					'title'          => sanitize_file_name( $doli_filename ),
				) );
				$existing_id = ! empty( $existing_attachment ) ? (int) $existing_attachment[0]->ID : 0;
				$debug['image'] = array(
					'wp_thumbnail_id' => (int) $current_thumbnail_id,
					'doli_filename'   => $doli_filename,
					'existing_id'     => $existing_id,
				);
				if ( 0 === $existing_id || (int) $current_thumbnail_id !== $existing_id ) {
					$img_ok = false;
				}
			}

			if ( ! $data_ok || ! $cat_ok || ! $img_ok ) {
				$status_message  = "Statut de synchronisation : Échec\n";
				$status_message .= ( $data_ok ? "✅" : "❌" ) . " Données produit : " . ( $data_ok ? "OK" : "HS" ) . "\n";
				$status_message .= ( $cat_ok ? "✅" : "❌" ) . " Tags / Catégories : " . ( $cat_ok ? "OK" : "HS" ) . "\n";
				$status_message .= ( $img_ok ? "✅" : "❌" ) . " Médias : " . ( $img_ok ? "OK" : "HS" );

				$status_code = '0x3';
				if ( $data_ok && ( ! $cat_ok || ! $img_ok ) ) {
					$status_code = '0x4';
				}

				return array(
					'status' => true,
					'status_code' => $status_code,
					'status_message' => $status_message,
					'response->sha' => $response->sha,
					'sha_256' => $sha_256,
					'wp_category_labels' => $wp_category_labels,
					'doli_category_labels' => $doli_category_labels,
					'debug' => $debug,
				);
			}
		} else {
			if ( $response->sha !== $sha_256 || $wp_category_labels != $doli_category_labels ) {
				$detail_fields = ( $response->sha !== $sha_256 ) ? $sha_diff_fields : array();
				if ( $wp_category_labels != $doli_category_labels ) {
					$detail_fields[] = 'categories';
				}

				return array(
					'status' => true,
					'status_code' => '0x3',
					'status_message' => __('WP Object is not equal Dolibarr Object', 'wpshop') . ' (' . implode( ', ', $detail_fields ) . ')',
					'response->sha' => $response->sha,
					'sha_256' => $sha_256,
					'wp_category_labels' => $wp_category_labels,
					'doli_category_labels' => $doli_category_labels,
					'debug' => $debug,
				);
			}
		}

		if ( $type == 'wps-product' ) {
			$status_message  = "Statut de synchronisation : Succès\n";
			$status_message .= "✅ Données produit : OK\n";
			$status_message .= "✅ Tags / Catégories : OK\n";
			$status_message .= "✅ Médias : OK";
		} else {
			$status_message = __('Sync OK', 'wpshop');
		}

		return array(
			'status' => true,
			'status_code' => '0x0',
			'status_message' => $status_message,
			'debug' => $debug,
		);
	}

	/**
	 * Affiche le statut de synchronisation d'une entité.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  mixed   $object          Les données de l'entité.
	 * @param  string  $type            Le type de l'entité.
	 * @param  boolean $load_erp_status Le statut de l'ERP.
	 *
	 * @return string                   Le statut de la synchronisation.
	 */
	public function display_sync_status( $object, $type, $load_erp_status = true )
	{
		$data_view = array(
			'object' => $object,
			'type' => $type,
			'status_color' => 'grey',
			'title' => '',
			'message_tooltip' => __( 'Looking for sync status', 'wpshop' ),
			'can_sync' => false,
		);

		if ( ! $load_erp_status ) {
			View_Util::exec('wpshop', 'doli-sync', 'sync-item', $data_view);
			return;
		}

		if ( empty($object->data['external_id'] ) ) {
			$data_view['status_color'] = 'none';
			$data_view['message_tooltip'] = __('No associated to an ERP Entity', 'wpshop');

			View_Util::exec('wpshop', 'doli-sync', 'sync-item', $data_view);
			return;
		}

		$response = Doli_Sync::g()->check_status($object->data['id'], $type);

		if ( ! $response || ! $response['status'] ) {
			$data_view['status_color'] = 'none';
		} else {
			// @todo: Do Const for status_code.
			switch ($response['status_code']) {
				case '0x0':
					$data_view['status_color'] = 'green';
					$data_view['can_sync'] = true;
					break;
//				case '0x1':
//					$data_view['status_color'] = 'none';
//					break;
//				case '0x2':
//					$data_view['status_color'] = 'none';
//					$object->data['external_id'] =  '';
//					break;
				case '0x3':
					$data_view['status_color'] = 'red';
					$data_view['can_sync'] = true;
					break;
				case '0x4':
					$data_view['status_color'] = 'orange';
					$data_view['can_sync'] = true;
					break;
			}
		}

		$data_view['message_tooltip'] = isset ( $response['status_message'] ) ? $response['status_message'] : __( 'Error not defined', 'wpshop' );
		View_Util::exec( 'wpshop', 'doli-sync', 'sync-item', $data_view );

		return $response;
	}
}

Doli_Sync::g();
