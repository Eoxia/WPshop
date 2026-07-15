<?php
/**
 * La classe gérant les filtres des synchronisations des entités de dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.1.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Sync Filter Class.
 */
class Doli_Sync_Filter extends Singleton_Util {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 */
	protected function construct() {
		add_filter( 'wps_countries', array( $this, 'doli_countries' ) );

		add_filter( 'doli_build_sha_wps-product', array( $this, 'build_sha_product' ), 10, 2 );
		add_filter( 'doli_build_sha_wps-third-party', array( $this, 'build_sha_third_party' ), 10, 2 );
		add_filter( 'doli_build_sha_wps-product-cat', array( $this, 'build_sha_categories' ), 10, 2 );
	}

	/**
	 * Récupère tous les pays depuis Dolibarr.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $countries Les données des pays venant de WPshop.
	 *
	 * @return array            Les données des pays modifié de WPshop avec ceux de Dolibarr.
	 */
	public function doli_countries( $countries ) {
		if ( Settings::g()->dolibarr_is_active() ) {
			$countries        = Request_Util::get( 'setup/dictionary/countries?sortfield=code&sortorder=ASC&limit=500' );
			$countries_for_wp = array();

			if ( ! empty( $countries ) ) {
				foreach ( $countries as $country ) {
					$country = (array) $country;

					if ( '-' === $country['label'] ) {
						$country['label'] = __( 'Country', 'wpshop' );
					}

					$countries_for_wp[ $country['id'] ] = $country;
				}
			}

			usort( $countries_for_wp, function( $a, $b ) {
				if ( $a['label'] === $b['label'] ) {
					return 0;
				}

				return ( $a['label'] > $b['label'] ) ? 1 : -1;
			} );

			return $countries_for_wp;
		} else {
			return $countries;
		}
	}

	/**
	 * La construction du SHA256 d'une synchronisation d'un produit.
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 *
	 * @param  Product $response Les données d'un produit.
	 * @param  integer $wp_id    L'id d'un produit WordPress.
	 *
	 * @return Product           Les données d'un produit avec le SHA256.
	 */
	public function build_sha_product( $response, $wp_id ) {
		$data_sha = array();

		//@todo doli_id en id_dolibarr
		//@todo wp_id en id_wordpress
		$data_sha['doli_id']              = $response->id;
		$data_sha['wp_id']                = $wp_id;
		// Même normalisation que côté écriture (Doli_Products::doli_to_wp) : on décode les entités
		// HTML pour comparer le contenu réel et non sa représentation encodée.
		$data_sha['label']                = html_entity_decode( (string) $response->label, ENT_QUOTES, 'UTF-8' );
		$data_sha['description']          = html_entity_decode( (string) $response->description, ENT_QUOTES, 'UTF-8' );
		$data_sha['price']                = Doli_Sync::format_price( $response->price );
		$data_sha['price_ttc']            = Doli_Sync::format_price( $response->price_ttc );
		$data_sha['tva_tx']               = Doli_Sync::format_price( $response->tva_tx );
		$data_sha['stock']                = $response->stock_reel ?? 0;
		$data_sha['status']               = $response->array_options->options__wps_status;

		if ( empty( $response->array_options->options__wps_status ) || $response->array_options->options__wps_status == 1 || $response->array_options->options__wps_status == 'publish' ) {
			$data_sha['status'] = 'publish';
		} else {
			$data_sha['status'] = 'draft';
		}

		$response->sha_data = $data_sha;
		$response->sha      = hash( 'sha256', implode( ',', $data_sha ) );

		return $response;
	}

	/**
	 * La construction du SHA256 d'une synchronisation d'un tier.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  Third_Party $response Les données d'un tier.
	 * @param  integer     $wp_id    L'id d'un tier WordPress.
	 *
	 * @return Third_Party           Les données d'un tier avec le SHA256.
	 */
	public function build_sha_third_party( $response, $wp_id ) {
		$data_sha = array();

		$data_sha['doli_id']  = Doli_Sync::format_int( $response->id );
		$data_sha['wp_id']    = Doli_Sync::format_int( $wp_id );
		$data_sha['title']    = Doli_Sync::format_text( $response->name );
		$data_sha['town']     = Doli_Sync::format_text( $response->town );
		$data_sha['zip']      = Doli_Sync::format_text( $response->zip );
		$data_sha['state']    = Doli_Sync::format_int( $response->state_id );
		$data_sha['country']  = Doli_Sync::format_int( $response->country_id );
		$data_sha['address']  = Doli_Sync::format_text( $response->address );
		$data_sha['phone']    = Doli_Sync::format_text( $response->phone );
		$data_sha['email']    = Doli_Sync::format_text( $response->email );

		$response->sha_data = $data_sha;
		$response->sha      = hash( 'sha256', implode( ',', $data_sha ) );

		return $response;
	}

	/**
	 * La construction du SHA256 d'une synchronisation d'une catégorie.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 *
	 * @param  Doli_Category $response Les données d'une catégorie.
	 * @param  integer       $wp_id    L'id d'un tier WordPress.
	 *
	 * @return Doli_Category           Les données d'un catégorie avec le SHA256.
	 */
	public function build_sha_categories( $response, $wp_id ) {
		$data_sha = array();

		// Le slug stocké côté WP est celui du terme (assaini). On compare donc
		// au slug réel du terme : sinon une catégorie sans extrafield wps_slug
		// ressortirait en permanence comme désynchronisée.
		$wp_term = get_term( $wp_id );
		$slug    = ( $wp_term && ! is_wp_error( $wp_term ) ) ? $wp_term->slug : '';

		$data_sha['doli_id'] = Doli_Sync::format_int( $response->id );
		$data_sha['wp_id']   = Doli_Sync::format_int( $wp_id );
		$data_sha['name']    = Doli_Sync::format_text( $response->label );
		$data_sha['slug']    = Doli_Sync::format_text( $slug );

		$response->sha_data = $data_sha;
		$response->sha      = hash( 'sha256', implode( ',', $data_sha ) );

		return $response;
	}
}

Doli_Sync_Filter::g();
