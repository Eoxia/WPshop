<?php
/**
 * La classe gérant les actions de synchronisations des entités de dolibarr.
 *
 * @todo: Translate to English.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\LOG_Util;
use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Sync Action Class.
 */
class Doli_Sync_Action {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_modal_sync', array( $this, 'load_modal_sync' ) );

		add_action( 'wp_ajax_sync', array( $this, 'sync' ) );
		add_action( 'wp_ajax_sync_entry', array( $this, 'sync_entry' ) );

		add_action( 'wps_listing_table_header_end', array( $this, 'add_sync_header' ) );
		add_action( 'wps_listing_table_end', array( $this, 'add_sync_item' ), 10, 2 );

		add_action( 'wp_ajax_check_sync_status', array( $this, 'check_sync_status' ) );
		
		add_action( 'wp_ajax_wps_frontend_auto_sync', array( $this, 'frontend_auto_sync' ) );
		add_action( 'wp_ajax_nopriv_wps_frontend_auto_sync', array( $this, 'frontend_auto_sync' ) );

		add_action( 'wp_footer', array( $this, 'frontend_auto_sync_script' ), 100 );
		add_action( 'admin_head', array( $this, 'admin_auto_sync_script' ) );
	}

	/**
	 * Injecte les variables JS pour le comportement des synchronisations automatiques dans l'admin.
	 */
	public function admin_auto_sync_script() {
		$sync_settings  = get_option( 'wps_sync_settings', array() );
		$auto_sync_list = isset( $sync_settings['auto_sync_list'] ) ? (int) $sync_settings['auto_sync_list'] : 1;
		echo '<script>var wps_auto_sync_list = ' . $auto_sync_list . ';</script>';
	}

	/**
	 * Injecte le script asynchrone sur le front-end pour la synchronisation automatique des produits affichés.
	 *
	 * @since   2.4.0
	 */
	public function frontend_auto_sync_script() {
		// Vérifie si on est sur l'administration ou si l'option front-end est désactivée
		if ( is_admin() ) {
			return;
		}

		$sync_settings  = get_option( 'wps_sync_settings', array() );
		$auto_sync_shop = isset( $sync_settings['auto_sync_shop'] ) ? $sync_settings['auto_sync_shop'] : 0;

		if ( empty( $auto_sync_shop ) ) {
			return;
		}
		?>
		<style>
		/* Loader CSS pour la synchronisation */
		.wps-sync-loader {
			display: inline-block;
			width: 20px;
			height: 20px;
			border: 3px solid rgba(0,0,0,0.1);
			border-radius: 50%;
			border-top-color: #2271b1;
			animation: wps-sync-spin 1s ease-in-out infinite;
			vertical-align: middle;
		}
		@keyframes wps-sync-spin {
			to { transform: rotate(360deg); }
		}
		.wps-product-price.wps-sync-loading {
			opacity: 0.5;
			position: relative;
		}
		.wps-product-price.wps-sync-loading:after {
			content: '';
			display: inline-block;
			width: 14px;
			height: 14px;
			border: 2px solid rgba(0,0,0,0.1);
			border-radius: 50%;
			border-top-color: #2271b1;
			animation: wps-sync-spin 1s ease-in-out infinite;
			margin-left: 10px;
			vertical-align: middle;
		}
		</style>
		<script>
		jQuery(document).ready(function($) {
			// Récupère tous les IDs des produits présents sur la page (en utilisant le bouton d'ajout au panier qui possède l'ID)
			var product_ids = [];
			var product_elements = {};

			$('.wps-product').each(function() {
				var container = $(this);
				var btn = container.find('.wps-product-buy[data-id]');
				if ( btn.length ) {
					var id = btn.data('id');
					if ( id && product_ids.indexOf(id) === -1 ) {
						product_ids.push(id);
						product_elements[id] = container;
						// Affiche le loader sur le prix
						container.find('.wps-product-price').addClass('wps-sync-loading');
					}
				}
			});

			if ( product_ids.length > 0 ) {
				$.post(
					'<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					{
						action: 'wps_frontend_auto_sync',
						product_ids: product_ids
					},
					function( response ) {
						if ( response.success && response.data.products ) {
							$.each( response.data.products, function( id, data ) {
								if ( product_elements[id] ) {
									var container = product_elements[id];
									
									// Retire le loader
									container.find('.wps-product-price').removeClass('wps-sync-loading');
									
									// Met à jour le prix
									if ( data.price_display ) {
										var priceContainer = container.find('.wps-product-price');
										if (priceContainer.length) {
											var currency = priceContainer.find('span[itemprop="priceCurrency"]').prop('outerHTML') || '<span>€</span>';
											priceContainer.html('<span itemprop="price" content="'+data.price_ttc+'">' + data.price_display + '</span> ' + currency);
										}
									}

									// Gérer l'état du bouton si le stock est 0 (optionnel, selon le fonctionnement WPShop)
									if ( data.stock <= 0 ) {
										// container.find('.wps-product-buy').removeClass('wps-product-add-to-cart').addClass('out-of-stock');
									}
								}
							});
						}
						
						// Nettoyage de secours au cas où la réponse n'inclut pas tous les produits (ex: pas d'external_id)
						$('.wps-product-price.wps-sync-loading').removeClass('wps-sync-loading');
					}
				).fail(function() {
					// En cas d'erreur de requête, on retire les loaders pour ne pas bloquer l'affichage
					$('.wps-product-price.wps-sync-loading').removeClass('wps-sync-loading');
				});
			}
		});
		</script>
		<?php
>>>>>>> Stashed changes
	}

	/**
	 * Charge la modal de synchronisation.
	 *
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function load_modal_sync() {
		check_ajax_referer( 'load_modal_sync' );

		$sync_action = ! empty( $_POST['sync'] ) ? sanitize_text_field( $_POST['sync'] ) : '';
		$sync_infos  = Doli_Sync::g()->sync_infos;
		$sync_action = explode( ',', $sync_action );

		if ( ! empty( $sync_infos ) ) {
			foreach ( $sync_infos as $key => &$sync_info ) {
				if ( ! in_array( $key, $sync_action, true ) ) {
					unset ( $sync_infos[ $key ] );
					continue;
				}

				$sync_info['last']         = false;
				$sync_info['total_number'] = 0;
				$sync_info['page']         = 0;
				$sync_info                 = Doli_Sync::g()->count_entries( $sync_info );

				if ( end( $sync_action ) == $key ) {
					$sync_info['last'] = true;
				}
			}
		}

		ob_start();
		View_Util::exec( 'wpshop', 'doli-sync', 'main', array(
			'sync_infos' => $sync_infos,
		) );
		$view = ob_get_clean();

		ob_start();
		View_Util::exec( 'wpshop', 'doli-sync', 'modal-sync-button' );
		$buttons_view = ob_get_clean();
		wp_send_json_success( array(
			'view'         => $view,
			'buttons_view' => $buttons_view,
		) );
	}

	/**
	 * Fait la synchronisation.
	 *
	 * @todo: Refactoring
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function sync() {
		check_ajax_referer( 'sync' );

		$done           = false;
		$updateComplete = false;

		$type = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$done_number  = ! empty( $_POST['done_number'] ) ? (int) $_POST['done_number'] : 0;
		$total_number = ! empty( $_POST['total_number'] ) ? (int) $_POST['total_number'] : 0;
		$last         = ( ! empty( $_POST['last'] ) && '1' == $_POST['last'] ) ? true : false;

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );

		// @todo: Do Array http_build_query.
		$doli_entries = Request_Util::get( $sync_info['endpoint'] . '?sortfield=t.rowid&sortorder=ASC&limit=' . Doli_Sync::g()->limit_entries_by_request . '&page=' . $done_number / Doli_Sync::g()->limit_entries_by_request );

		if ( ! empty( $doli_entries ) ) {
			foreach ( $doli_entries as $doli_entry ) {

				// translators: Try to sync %s.
				LOG_Util::log( sprintf( 'Try to sync %s', json_encode( $doli_entry ) ), 'wpshop2' );
				$wp_entry = $sync_info['wp_class']::g()->get( array(
					'meta_key'   => '_external_id',
					'meta_value' => (int) $doli_entry->id,
				), true );

				// Repli anti-doublons : si le lien _external_id a été perdu, on retrouve le produit
				// existant par sa référence (_ref) au lieu d'en recréer un nouveau à chaque synchro.
				if ( empty( $wp_entry ) && 'wps-product' === $type && ! empty( $doli_entry->ref ) ) {
					$wp_entry = $sync_info['wp_class']::g()->get( array(
						'meta_key'   => '_ref',
						'meta_value' => $doli_entry->ref,
					), true );

					// get() renvoie un tableau si plusieurs doublons partagent la même référence :
					// on conserve alors le premier (les autres pourront être nettoyés séparément).
					if ( is_array( $wp_entry ) ) {
						$wp_entry = ! empty( $wp_entry ) ? reset( $wp_entry ) : null;
					}
				}

				if ( empty( $wp_entry ) ) {
					$wp_entry = $sync_info['wp_class']::g()->get( array( 'schema' => true ), true );
				}

				do_action( 'wps_sync_' . $type . '_before', $doli_entry, $wp_entry );
				$sync_info['doli_class']::g()->doli_to_wp( $doli_entry, $wp_entry );
				do_action( 'wps_sync_' . $type . '_after', $doli_entry, $wp_entry );

				// translators: Sync done for the entry {json_data}.
				LOG_Util::log( sprintf( 'Sync done for the entry %s', json_encode( $doli_entry ) ), 'wpshop2' );

				$done_number++;
			}
		}

		if ( $done_number >= $total_number ) {
			$done_number = $total_number;
			$done        = true;

			if ( $last ) {
				$updateComplete = true;
			}
		}

		wp_send_json_success( array(
			'updateComplete'     => $updateComplete,
			'done'               => $done,
			'progression'        => $done_number . '/' . $total_number,
			'progressionPerCent' => 0 !== $total_number ? ( ( $done_number * 100 ) / $total_number ) : 0,
			'doneDescription'    => $done_number . '/' . $total_number,
			'doneElementNumber'  => $done_number,
			'errors'             => null,
		) );
	}

	/**
	 * Synchronise une entrée.
	 *
	 * @todo: Translate to english and comment.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function sync_entry() {
		check_ajax_referer( 'sync_entry' );

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		$wp_id   		 = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$entry_id		 = ! empty( $_POST['entry_id'] ) ? (int) $_POST['entry_id'] : 0;
		$type    		 = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		$sync_status = Doli_Sync::g()->sync( $wp_id, $entry_id, $type );
		$sync_info   = Doli_Sync::g()->get_sync_infos( $type );

		ob_start();
		// @todo: Add display_item for contact.
		if ( $type !== 'wps-user' || $type !== 'wps-product-cat' ) {
			$sync_info['wp_class']::g()->display_item( $sync_status['wp_object'], true, $dolibarr_option['dolibarr_url'] );
		}

		$item_view = ob_get_clean();

		ob_start();
		$status_check = Doli_Sync::g()->display_sync_status( $sync_status['wp_object'], $type, true );
		$sync_view = ob_get_clean();

		$js_scripts = '';

		if ( $type === 'wps-product' ) {
			$terms = get_the_terms( $wp_id, 'wps-product-cat' );
			$categories_html = '';
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$links = array();
				foreach ( $terms as $term ) {
					$link = admin_url( 'edit.php?wps-product-cat=' . $term->slug . '&post_type=wps-product' );
					$links[] = '<a href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a>';
				}
				$categories_html = implode( ', ', $links );
			} else {
				$categories_html = '—';
			}
			$js_scripts .= 'var row = $("#post-' . (int) $wp_id . '"); if(row.length){ row.find(".column-taxonomy-wps-product-cat").html("' . addslashes( $categories_html ) . '"); }';
		}

		if ( ! empty( $sync_status['messages'] ) ) {
			$is_error = ! empty( $sync_status['wp_error'] ) && $sync_status['wp_error']->has_errors();
			$bg_color = $is_error ? '#dc3232' : '#46b450';
			
			$messages_html = '';
			foreach ( $sync_status['messages'] as $message ) {
				$messages_html .= '<div style="margin-bottom:4px;">' . wp_kses_post( $message ) . '</div>';
			}

			$js_scripts .= '
				var notice = $("<div class=\'notice is-dismissible\' style=\'border-left: 4px solid ' . $bg_color . '; background: #fff; padding: 10px 15px; margin: 15px 0; box-shadow: 0 1px 1px rgba(0,0,0,.04);\'><p style=\'margin:0; color: #3c434a; font-weight: 500;\'>' . addslashes($messages_html) . '</p><button type=\'button\' class=\'notice-dismiss\'><span class=\'screen-reader-text\'>Cacher</span></button></div>");
				
				notice.find(".notice-dismiss").on("click", function() {
					notice.fadeTo(100, 0, function() { notice.slideUp(100, function() { notice.remove(); }); });
				});

				$(".wp-header-end").after(notice);
				
				setTimeout(function() {
					notice.fadeTo(100, 0, function() { notice.slideUp(100, function() { notice.remove(); }); });
				}, 7000);
			';
		}

		if ( ! empty( $js_scripts ) ) {
			$sync_view .= '<script>(function($) { ' . $js_scripts . ' })(jQuery);</script>';
		}

		wp_send_json_success( array(
			'id'               => $wp_id,
			'namespace'        => 'wpshop',
			'module'           => 'doliSync',
			'callback_success' => 'syncEntrySuccess',
			'item_view'        => $item_view,
			'sync_view'        => $sync_view,
			'status_code'      => is_array( $status_check ) && isset( $status_check['status_code'] ) ? $status_check['status_code'] : null,
			'debug'            => is_array( $status_check ) && isset( $status_check['debug'] ) ? $status_check['debug'] : null,
		) );
	}

	/**
	 * Appel la vue pour ajouter "Synchro" dans le header du listing.
	 *
	 * @todo: Translate to English.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function add_sync_header( $type ) {
		if ( in_array( $type, array( 'products', 'thirdparties', 'proposals' ) ) && Settings::g()->dolibarr_is_active() ) {
			View_Util::exec( 'wpshop', 'doli-sync', 'sync-header' );
		}
	}

	/**
	 * Prépare les données pour l'état de synchronisation de l'entité.
	 * et appel la vue sync-item.
	 *
	 * @todo: Translate to English.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param mixed   $object      Les données d'une entité.
	 * @param boolean $sync_status Le statut de la synchronisation.
	 */
	public function add_sync_item( $object, $sync_status ) {
		if ( Settings::g()->dolibarr_is_active() && in_array( $object->data['type'], array( 'wps-product', 'wps-third-party', 'wps-proposal','wps-product-cat' ) ) ) {
			Doli_Sync::g()->display_sync_status( $object, $object->data['type'], $sync_status );
		}
	}

	/**
	 * Vérifie le statut de la synchronisation.
	 *
	 * @todo: nonce
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function check_sync_status() {
		$wp_id = ! empty( $_POST['wp_id'] ) ? (int) $_POST['wp_id'] : 0;
		$type  = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

		if ( empty( $wp_id ) && ! in_array( $type, array( 'wps-product', 'wps-third-party', 'wps-proposals', 'wps-user', 'wps-product-cat' ) ) ) {
			wp_send_json_error();
		}

		$sync_info = Doli_Sync::g()->get_sync_infos( $type );

		$object = $sync_info['wp_class']::g()->get( array( 'id' => $wp_id ), true );

		ob_start();
		$status = Doli_Sync::g()->display_sync_status( $object, $type );
		$view = ob_get_clean();
		wp_send_json_success( array(
			'view'   => $view,
			'id'     => $wp_id,
			'status' => $status,
		) );
	}
	/**
	 * Déclenche une synchronisation légère (Prix/Stock) pour le front-end avec prise en charge du TTL.
	 *
	 * @since   2.4.0
	 */
	public function frontend_auto_sync() {
		// Vérification du nonce (facultatif pour du front-end public, mais recommandé si on le passe)
		// On va s'en passer car c'est une action publique en lecture/mise à jour légère.
		
		$product_ids = ! empty( $_POST['product_ids'] ) ? array_map( 'intval', (array) $_POST['product_ids'] ) : array();
		if ( empty( $product_ids ) ) {
			wp_send_json_error( array( 'message' => 'No products' ) );
		}

		// Limite stricte pour préserver Dolibarr
		$product_ids = array_slice( $product_ids, 0, 10 );

		$sync_settings = get_option( 'wps_sync_settings', array() );
		$ttl_hours     = isset( $sync_settings['auto_sync_ttl'] ) ? (int) $sync_settings['auto_sync_ttl'] : 4;
		$ttl_seconds   = $ttl_hours * 3600;

		$results = array();

		foreach ( $product_ids as $wp_id ) {
			if ( empty( $wp_id ) ) continue;

			$product = Product::g()->get( array( 'id' => $wp_id ), true );
			if ( empty( $product->data['external_id'] ) ) {
				continue;
			}

			// Vérification du TTL (Péremption)
			$last_sync_date = get_post_meta( $wp_id, '_date_last_synchro', true );
			$needs_sync     = true;

			if ( ! empty( $last_sync_date ) ) {
				$last_time = strtotime( $last_sync_date );
				if ( ( time() - $last_time ) < $ttl_seconds ) {
					$needs_sync = false;
				}
			}

			if ( $needs_sync ) {
				// Interrogation de Dolibarr pour ce produit
				$doli_product = Request_Util::get( 'products/' . $product->data['external_id'] );
				if ( ! is_wp_error( $doli_product ) && ! empty( $doli_product->id ) ) {
					$product = Doli_Products::g()->light_sync( $product, $doli_product );
				}
			}

			// Renvoi des données nécessaires pour mettre à jour l'affichage
			$results[ $wp_id ] = array(
				'price_ttc'     => number_format( $product->data['price_ttc'], 2, '.', '' ),
				'price_display' => number_format( $product->data['price_ttc'], 2, ',', '' ),
				'stock'         => (int) $product->data['stock'],
			);
		}

		wp_send_json_success( array( 'products' => $results ) );
	}
}

new Doli_Sync_Action();
