<?php
/**
 * La classe gérant les filtres des synchronisations des entités de dolibarr.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
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
		$data_sha['label']                = $response->label;
		$data_sha['description']          = $response->description;
		$data_sha['price']                = $response->price;
		$data_sha['price_ttc']            = $response->price_ttc;
		$data_sha['tva_tx']               = $response->tva_tx;
		$data_sha['stock']                = $response->stock_reel;
		$data_sha['status']               = $response->array_options->options__wps_status;
		$data_sha['multilangs']           = (array) $response->multilangs;

		if ( $response->array_options->options__wps_status == 1  || $response->array_options->options__wps_status == 'publish' ) {
			$data_sha['status'] = 'publish';
		} else {
			$data_sha['status'] = 'draft';
		}

		$response->sha = hash( 'sha256', implode( ',', $data_sha ) );

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

		$data_sha['doli_id']  = $response->id;
		$data_sha['wp_id']    = $wp_id;
		$data_sha['title']    = $response->name;
		$data_sha['town']     = $response->town;
		$data_sha['zip']      = $response->zip;
		$data_sha['state']    = $response->state;
		$data_sha['country']  = $response->country;
		$data_sha['address']  = $response->address;
		$data_sha['phone']    = $response->phone;
		$data_sha['email']    = $response->email;

		$response->sha = hash( 'sha256', implode( ',', $data_sha ) );

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

		$data_sha['doli_id']  = (int) $response->id;
		$data_sha['wp_id']    = $wp_id;
		$data_sha['name']    = $response->label;
		$data_sha['slug']  	  = $response->array_options->options__wps_slug;

		$response->sha = hash( 'sha256', implode( ',', $data_sha ) );

		return $response;
	}
}

Doli_Sync_Filter::g();
