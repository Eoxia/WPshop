<?php
/**
 * Les filtres pour la synchronisation.
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
 * Doli Synchro Filter Class.
 */
class Doli_Sync_Filter extends \eoxia\Singleton_Util {

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		add_filter( 'wps_countries', array( $this, 'doli_countries' ) );

		add_filter( 'doli_build_sha_wps-product', array( $this, 'build_sha_product' ), 10, 2 );
		add_filter( 'doli_build_sha_wps-third-party', array( $this, 'build_sha_third_party' ), 10, 2 );
	}

	/**
	 * Récupères tous les pays depuis Dolibarr.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $countries Les pays venant de WPshop.
	 *
	 * @return array            Les pays modifié de WPshop avec les données de
	 * dolibarr.
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
	 * @todo: comments
	 *
	 * @param $response
	 * @return
	 */
	public function build_sha_product( $response, $wp_id ) {
		$data_sha = array();

		$data_sha['doli_id']   = $response->id;
		$data_sha['wp_id']     = $wp_id;
		$data_sha['label']     = $response->label;
		$data_sha['description'] = $response->description;
		$data_sha['price']     = $response->price;
		$data_sha['price_ttc'] = $response->price_ttc;
		$data_sha['tva_tx']    = $response->tva_tx;
		$data_sha['status']    = $response->array_options->options__wps_status;

		if ( $response->array_options->options__wps_status == 1  || $response->array_options->options__wps_status == 'publish' ) {
			$data_sha['status'] = 'publish';
		} else {
			$data_sha['status'] = 'draft';
		}

		$response->sha = hash( 'sha256', implode( ',', $data_sha ) );

		return $response;
	}

	/**
	 * @todo: comments
	 *
	 * @param $response
	 * @return
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
}

Doli_Sync_Filter::g();
