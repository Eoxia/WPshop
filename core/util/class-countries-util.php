<?php
/**
 * Les fonctions utilitaires des pays.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Récupère le pays par le code.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param  string $code Le code du pays.
 *
 * @return array        Les données du pays.
 */
function get_from_code( $code ) {
	$countries = get_countries();

	return $countries[ $code ];
}

/**
 * Récupère un pays par rapport à son ID.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param  integer $id L'ID du pays.
 *
 * @return array       Les données du pays.
 */
function get_from_id( $id ) {
	$countries = get_countries();

	if ( ! empty( $countries ) ) {
		foreach ( $countries as $country ) {
			if ( (int) $country['id'] === (int) $id ) {
				return $country;
			}
		}
	}

	return null;
}

/**
 * Définition de tous les pays.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @return array Les données du pays.
 */
function get_countries() {
	return apply_filters( 'wps_countries', array(
		array(
			'id'       => '0',
			'code'     => '',
			'code_iso' => null,
			'label'    => __( 'Country', 'wpshop' ),
			'active'   => '1',
		),
		array(
			'id'       => '34',
			'code'     => 'AD',
			'code_iso' => 'AND',
			'label'    => 'Andorre',
			'active'   => '1',
		),
		array(
			'id'       => '227',
			'code'     => 'AE',
			'code_iso' => 'ARE',
			'label'    => 'United Arab Emirates',
			'active'   => '1',
		),
		array(
			'id'       => '30',
			'code'     => 'AF',
			'code_iso' => 'AFG',
			'label'    => 'Afghanistan',
			'active'   => '1',
		),
		array(
			'id'       => '38',
			'code'     => 'AG',
			'code_iso' => 'ATG',
			'label'    => 'Antigua-et-Barbuda',
			'active'   => '1',
		),
		array(
			'id'       => '36',
			'code'     => 'AI',
			'code_iso' => 'AIA',
			'label'    => 'Anguilla',
			'active'   => '1',
		),
		array(
			'id'       => '32',
			'code'     => 'AL',
			'code_iso' => 'ALB',
			'label'    => 'Albanie',
			'active'   => '1',
		),
		array(
			'id'       => '39',
			'code'     => 'AM',
			'code_iso' => 'ARM',
			'label'    => 'Arménie',
			'active'   => '1',
		),
		array(
			'id'       => '35',
			'code'     => 'AO',
			'code_iso' => 'AGO',
			'label'    => 'Angola',
			'active'   => '1',
		),
		array(
			'id'       => '37',
			'code'     => 'AQ',
			'code_iso' => 'ATA',
			'label'    => 'Antarctique',
			'active'   => '1',
		),
		array(
			'id'       => '23',
			'code'     => 'AR',
			'code_iso' => 'ARG',
			'label'    => 'Argentine',
			'active'   => '1',
		),
		array(
			'id'       => '33',
			'code'     => 'AS',
			'code_iso' => 'ASM',
			'label'    => 'Samoa américaines',
			'active'   => '1',
		),
		array(
			'id'       => '41',
			'code'     => 'AT',
			'code_iso' => 'AUT',
			'label'    => 'Autriche',
			'active'   => '1',
		),
		array(
			'id'       => '28',
			'code'     => 'AU',
			'code_iso' => 'AUS',
			'label'    => 'Australia',
			'active'   => '1',
		),
		array(
			'id'       => '40',
			'code'     => 'AW',
			'code_iso' => 'ABW',
			'label'    => 'Aruba',
			'active'   => '1',
		),
		array(
			'id'       => '31',
			'code'     => 'AX',
			'code_iso' => 'ALA',
			'label'    => 'Iles Aland',
			'active'   => '1',
		),
		array(
			'id'       => '42',
			'code'     => 'AZ',
			'code_iso' => 'AZE',
			'label'    => 'Azerbaïdjan',
			'active'   => '1',
		),
		array(
			'id'       => '53',
			'code'     => 'BA',
			'code_iso' => 'BIH',
			'label'    => 'Bosnie-Herzégovine',
			'active'   => '1',
		),
		array(
			'id'       => '46',
			'code'     => 'BB',
			'code_iso' => 'BRB',
			'label'    => 'Barbade',
			'active'   => '1',
		),
		array(
			'id'       => '45',
			'code'     => 'BD',
			'code_iso' => 'BGD',
			'label'    => 'Bangladesh',
			'active'   => '1',
		),
		array(
			'id'       => '2',
			'code'     => 'BE',
			'code_iso' => 'BEL',
			'label'    => 'Belgium',
			'active'   => '1',
		),
		array(
			'id'       => '60',
			'code'     => 'BF',
			'code_iso' => 'BFA',
			'label'    => 'Burkina Faso',
			'active'   => '1',
		),
		array(
			'id'       => '59',
			'code'     => 'BG',
			'code_iso' => 'BGR',
			'label'    => 'Bulgarie',
			'active'   => '1',
		),
		array(
			'id'       => '44',
			'code'     => 'BH',
			'code_iso' => 'BHR',
			'label'    => 'Bahreïn',
			'active'   => '1',
		),
		array(
			'id'       => '61',
			'code'     => 'BI',
			'code_iso' => 'BDI',
			'label'    => 'Burundi',
			'active'   => '1',
		),
		array(
			'id'       => '49',
			'code'     => 'BJ',
			'code_iso' => 'BEN',
			'label'    => 'Bénin',
			'active'   => '1',
		),
		array(
			'id'       => '245',
			'code'     => 'BL',
			'code_iso' => 'BLM',
			'label'    => 'Saint-Barthélemy',
			'active'   => '1',
		),
		array(
			'id'       => '50',
			'code'     => 'BM',
			'code_iso' => 'BMU',
			'label'    => 'Bermudes',
			'active'   => '1',
		),
		array(
			'id'       => '58',
			'code'     => 'BN',
			'code_iso' => 'BRN',
			'label'    => 'Brunei',
			'active'   => '1',
		),
		array(
			'id'       => '52',
			'code'     => 'BO',
			'code_iso' => 'BOL',
			'label'    => 'Bolivie',
			'active'   => '1',
		),
		array(
			'id'       => '56',
			'code'     => 'BR',
			'code_iso' => 'BRA',
			'label'    => 'Brazil',
			'active'   => '1',
		),
		array(
			'id'       => '43',
			'code'     => 'BS',
			'code_iso' => 'BHS',
			'label'    => 'Bahamas',
			'active'   => '1',
		),
		array(
			'id'       => '51',
			'code'     => 'BT',
			'code_iso' => 'BTN',
			'label'    => 'Bhoutan',
			'active'   => '1',
		),
		array(
			'id'       => '55',
			'code'     => 'BV',
			'code_iso' => 'BVT',
			'label'    => 'Ile Bouvet',
			'active'   => '1',
		),
		array(
			'id'       => '54',
			'code'     => 'BW',
			'code_iso' => 'BWA',
			'label'    => 'Botswana',
			'active'   => '1',
		),
		array(
			'id'       => '47',
			'code'     => 'BY',
			'code_iso' => 'BLR',
			'label'    => 'Biélorussie',
			'active'   => '1',
		),
		array(
			'id'       => '48',
			'code'     => 'BZ',
			'code_iso' => 'BLZ',
			'label'    => 'Belize',
			'active'   => '1',
		),
		array(
			'id'       => '14',
			'code'     => 'CA',
			'code_iso' => 'CAN',
			'label'    => 'Canada',
			'active'   => '1',
		),
		array(
			'id'       => '69',
			'code'     => 'CC',
			'code_iso' => 'CCK',
			'label'    => 'Iles des Cocos (Keeling)',
			'active'   => '1',
		),
		array(
			'id'       => '73',
			'code'     => 'CD',
			'code_iso' => 'COD',
			'label'    => 'République démocratique du Congo',
			'active'   => '1',
		),
		array(
			'id'       => '65',
			'code'     => 'CF',
			'code_iso' => 'CAF',
			'label'    => 'République centrafricaine',
			'active'   => '1',
		),
		array(
			'id'       => '72',
			'code'     => 'CG',
			'code_iso' => 'COG',
			'label'    => 'Congo',
			'active'   => '1',
		),
		array(
			'id'       => '6',
			'code'     => 'CH',
			'code_iso' => 'CHE',
			'label'    => 'Switzerland',
			'active'   => '1',
		),
		array(
			'id'       => '21',
			'code'     => 'CI',
			'code_iso' => 'CIV',
			'label'    => 'Côte d\'Ivoire',
			'active'   => '1',
		),
		array(
			'id'       => '74',
			'code'     => 'CK',
			'code_iso' => 'COK',
			'label'    => 'Iles Cook',
			'active'   => '1',
		),
		array(
			'id'       => '67',
			'code'     => 'CL',
			'code_iso' => 'CHL',
			'label'    => 'Chili',
			'active'   => '1',
		),
		array(
			'id'       => '24',
			'code'     => 'CM',
			'code_iso' => 'CMR',
			'label'    => 'Cameroun',
			'active'   => '1',
		),
		array(
			'id'       => '9',
			'code'     => 'CN',
			'code_iso' => 'CHN',
			'label'    => 'China',
			'active'   => '1',
		),
		array(
			'id'       => '70',
			'code'     => 'CO',
			'code_iso' => 'COL',
			'label'    => 'Colombie',
			'active'   => '1',
		),
		array(
			'id'       => '75',
			'code'     => 'CR',
			'code_iso' => 'CRI',
			'label'    => 'Costa Rica',
			'active'   => '1',
		),
		array(
			'id'       => '77',
			'code'     => 'CU',
			'code_iso' => 'CUB',
			'label'    => 'Cuba',
			'active'   => '1',
		),
		array(
			'id'       => '63',
			'code'     => 'CV',
			'code_iso' => 'CPV',
			'label'    => 'Cap-Vert',
			'active'   => '1',
		),
		array(
			'id'       => '300',
			'code'     => 'CW',
			'code_iso' => 'CUW',
			'label'    => 'Curaçao',
			'active'   => '1',
		),
		array(
			'id'       => '68',
			'code'     => 'CX',
			'code_iso' => 'CXR',
			'label'    => 'Ile Christmas',
			'active'   => '1',
		),
		array(
			'id'       => '78',
			'code'     => 'CY',
			'code_iso' => 'CYP',
			'label'    => 'Cyprus',
			'active'   => '1',
		),
		array(
			'id'       => '79',
			'code'     => 'CZ',
			'code_iso' => 'CZE',
			'label'    => 'République Tchèque',
			'active'   => '1',
		),
		array(
			'id'       => '5',
			'code'     => 'DE',
			'code_iso' => 'DEU',
			'label'    => 'Germany',
			'active'   => '1',
		),
		array(
			'id'       => '81',
			'code'     => 'DJ',
			'code_iso' => 'DJI',
			'label'    => 'Djibouti',
			'active'   => '1',
		),
		array(
			'id'       => '80',
			'code'     => 'DK',
			'code_iso' => 'DNK',
			'label'    => 'Danemark',
			'active'   => '1',
		),
		array(
			'id'       => '82',
			'code'     => 'DM',
			'code_iso' => 'DMA',
			'label'    => 'Dominique',
			'active'   => '1',
		),
		array(
			'id'       => '83',
			'code'     => 'DO',
			'code_iso' => 'DOM',
			'label'    => 'République Dominicaine',
			'active'   => '1',
		),
		array(
			'id'       => '13',
			'code'     => 'DZ',
			'code_iso' => 'DZA',
			'label'    => 'Algeria',
			'active'   => '1',
		),
		array(
			'id'       => '84',
			'code'     => 'EC',
			'code_iso' => 'ECU',
			'label'    => 'Equateur',
			'active'   => '1',
		),
		array(
			'id'       => '89',
			'code'     => 'EE',
			'code_iso' => 'EST',
			'label'    => 'Estonia',
			'active'   => '1',
		),
		array(
			'id'       => '85',
			'code'     => 'EG',
			'code_iso' => 'EGY',
			'label'    => 'Egypte',
			'active'   => '1',
		),
		array(
			'id'       => '237',
			'code'     => 'EH',
			'code_iso' => 'ESH',
			'label'    => 'Sahara occidental',
			'active'   => '1',
		),
		array(
			'id'       => '88',
			'code'     => 'ER',
			'code_iso' => 'ERI',
			'label'    => 'Erythrée',
			'active'   => '1',
		),
		array(
			'id'       => '4',
			'code'     => 'ES',
			'code_iso' => 'ESP',
			'label'    => 'Spain',
			'active'   => '1',
		),
		array(
			'id'       => '90',
			'code'     => 'ET',
			'code_iso' => 'ETH',
			'label'    => 'Ethiopie',
			'active'   => '1',
		),
		array(
			'id'       => '94',
			'code'     => 'FI',
			'code_iso' => 'FIN',
			'label'    => 'Finlande',
			'active'   => '1',
		),
		array(
			'id'       => '93',
			'code'     => 'FJ',
			'code_iso' => 'FJI',
			'label'    => 'Iles Fidji',
			'active'   => '1',
		),
		array(
			'id'       => '91',
			'code'     => 'FK',
			'code_iso' => 'FLK',
			'label'    => 'Iles Falkland',
			'active'   => '1',
		),
		array(
			'id'       => '155',
			'code'     => 'FM',
			'code_iso' => 'FSM',
			'label'    => 'Micronésie',
			'active'   => '1',
		),
		array(
			'id'       => '92',
			'code'     => 'FO',
			'code_iso' => 'FRO',
			'label'    => 'Iles Féroé',
			'active'   => '1',
		),
		array(
			'id'       => '1',
			'code'     => 'FR',
			'code_iso' => 'FRA',
			'label'    => 'France',
			'active'   => '1',
		),
		array(
			'id'       => '16',
			'code'     => 'GA',
			'code_iso' => 'GAB',
			'label'    => 'Gabon',
			'active'   => '1',
		),
		array(
			'id'       => '7',
			'code'     => 'GB',
			'code_iso' => 'GBR',
			'label'    => 'United Kingdom',
			'active'   => '1',
		),
		array(
			'id'       => '104',
			'code'     => 'GD',
			'code_iso' => 'GRD',
			'label'    => 'Grenade',
			'active'   => '1',
		),
		array(
			'id'       => '99',
			'code'     => 'GE',
			'code_iso' => 'GEO',
			'label'    => 'Georgia',
			'active'   => '1',
		),
		array(
			'id'       => '95',
			'code'     => 'GF',
			'code_iso' => 'GUF',
			'label'    => 'Guyane française',
			'active'   => '1',
		),
		array(
			'id'       => '241',
			'code'     => 'GG',
			'code_iso' => 'GGY',
			'label'    => 'Guernesey',
			'active'   => '1',
		),
		array(
			'id'       => '100',
			'code'     => 'GH',
			'code_iso' => 'GHA',
			'label'    => 'Ghana',
			'active'   => '1',
		),
		array(
			'id'       => '101',
			'code'     => 'GI',
			'code_iso' => 'GIB',
			'label'    => 'Gibraltar',
			'active'   => '1',
		),
		array(
			'id'       => '103',
			'code'     => 'GL',
			'code_iso' => 'GRL',
			'label'    => 'Groenland',
			'active'   => '1',
		),
		array(
			'id'       => '98',
			'code'     => 'GM',
			'code_iso' => 'GMB',
			'label'    => 'Gambie',
			'active'   => '1',
		),
		array(
			'id'       => '108',
			'code'     => 'GN',
			'code_iso' => 'GIN',
			'label'    => 'Guinea',
			'active'   => '1',
		),
		array(
			'id'       => '87',
			'code'     => 'GQ',
			'code_iso' => 'GNQ',
			'label'    => 'Guinée Equatoriale',
			'active'   => '1',
		),
		array(
			'id'       => '102',
			'code'     => 'GR',
			'code_iso' => 'GRC',
			'label'    => 'Greece',
			'active'   => '1',
		),
		array(
			'id'       => '206',
			'code'     => 'GS',
			'code_iso' => 'SGS',
			'label'    => 'Iles Géorgie du Sud et Sandwich du Sud',
			'active'   => '1',
		),
		array(
			'id'       => '107',
			'code'     => 'GT',
			'code_iso' => 'GTM',
			'label'    => 'Guatemala',
			'active'   => '1',
		),
		array(
			'id'       => '106',
			'code'     => 'GU',
			'code_iso' => 'GUM',
			'label'    => 'Guam',
			'active'   => '1',
		),
		array(
			'id'       => '109',
			'code'     => 'GW',
			'code_iso' => 'GNB',
			'label'    => 'Guinea-Bissao',
			'active'   => '1',
		),
		array(
			'id'       => '115',
			'code'     => 'HK',
			'code_iso' => 'HKG',
			'label'    => 'Hong Kong',
			'active'   => '1',
		),
		array(
			'id'       => '112',
			'code'     => 'HM',
			'code_iso' => 'HMD',
			'label'    => 'Iles Heard et McDonald',
			'active'   => '1',
		),
		array(
			'id'       => '114',
			'code'     => 'HN',
			'code_iso' => 'HND',
			'label'    => 'Honduras',
			'active'   => '1',
		),
		array(
			'id'       => '76',
			'code'     => 'HR',
			'code_iso' => 'HRV',
			'label'    => 'Croatie',
			'active'   => '1',
		),
		array(
			'id'       => '111',
			'code'     => 'HT',
			'code_iso' => 'HTI',
			'label'    => 'Haiti',
			'active'   => '1',
		),
		array(
			'id'       => '18',
			'code'     => 'HU',
			'code_iso' => 'HUN',
			'label'    => 'Hongrie',
			'active'   => '1',
		),
		array(
			'id'       => '118',
			'code'     => 'ID',
			'code_iso' => 'IDN',
			'label'    => 'Indonésie',
			'active'   => '1',
		),
		array(
			'id'       => '8',
			'code'     => 'IE',
			'code_iso' => 'IRL',
			'label'    => 'Irland',
			'active'   => '1',
		),
	) );
}
