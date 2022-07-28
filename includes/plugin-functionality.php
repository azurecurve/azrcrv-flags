<?php
/*
	plugin functionality
*/

/**
 * Declare the Namespace.
 *
 * @since 1.0.0
 */
namespace azurecurve\Flags;

/**
 * Get all flags (both custom and standard).
 *
 * @since 1.9.0
 */
function get_flags() {

	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	$dir   = $options['folder'];
	$flags = array();
	if ( is_dir( $dir ) ) {
		if ( $directory = opendir( $dir ) ) {
			// phpcs:ignore.
			while ( ( $file = readdir( $directory ) ) !== false ) {
				// echo $file;
				if ( substr( $file, -3 ) == 'svg' ) {
					$filewithoutext           = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $file );
					$flags[ $filewithoutext ] = array(
						'type' => 'custom',
						'name' => get_country_name( $filewithoutext ),
					);
				}
			}
			closedir( $directory );
		}
	}

	$dir = plugin_dir_path( __FILE__ ) . '../assets/flags';
	if ( is_dir( $dir ) ) {
		if ( $directory = opendir( $dir ) ) {
			// phpcs:ignore.
			while ( ( $file = readdir( $directory ) ) !== false ) {
				// echo $file;
				if ( substr( $file, -3 ) == 'svg' && $file <> 'logo.png' ) {
					$filewithoutext = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $file );
					if ( ! array_key_exists( $filewithoutext, $flags ) ) {
						$flags[ $filewithoutext ] = array(
							'type' => 'standard',
							'name' => get_country_name( $filewithoutext ),
						);
					}
				}
			}
			closedir( $directory );
		}
	}

	// sort flags by name
	// ksort($flags ,2);

	// sort alphabetically by name
	uasort( $flags, __NAMESPACE__ . '\\sort_flags_by_country_name' );

	return $flags;
}

/**
 * Sort flags by name.
 *
 * @since 1.9.0
 */
function sort_flags_by_country_name( $a, $b ) {

	return strnatcasecmp( $a['name'], $b['name'] );

}

/**
 * Get country name.
 *
 * @since 1.0.0
 */
function get_country_name( $country_code ) {

	$countries = array(
		'AW'                              => 'Aruba',
		'AG'                              => 'Antigua and Barbuda',
		'AE'                              => 'United Arab Emirates',
		'AF'                              => 'Afghanistan',
		'DZ'                              => 'Algeria',
		'AZ'                              => 'Azerbaijan',
		'AL'                              => 'Albania',
		'AM'                              => 'Armenia',
		'AD'                              => 'Andorra',
		'AO'                              => 'Angola',
		'AS'                              => 'American Samoa',
		'AR'                              => 'Argentina',
		'AU'                              => 'Australia',
		'AT'                              => 'Austria',
		'AI'                              => 'Anguilla',
		'AX'                              => 'Aland Islands',
		'AQ'                              => 'Antarctica',
		'BH'                              => 'Bahrain',
		'BB'                              => 'Barbados',
		'BW'                              => 'Botswana',
		'BM'                              => 'Bermuda',
		'BE'                              => 'Belgium',
		'BS'                              => 'Bahamas, The',
		'BD'                              => 'Bangladesh',
		'BZ'                              => 'Belize',
		'BA'                              => 'Bosnia and Herzegovina',
		'BO'                              => 'Bolivia Plurinational State of',
		'BL'                              => 'Saint Barthelemy',
		'MM'                              => 'Myanmar',
		'BJ'                              => 'Benin',
		'BY'                              => 'Belarus',
		'SB'                              => 'Solomon Islands',
		'BR'                              => 'Brazil',
		'BT'                              => 'Bhutan',
		'BG'                              => 'Bulgaria',
		'BV'                              => 'Bouvet Island',
		'BN'                              => 'Brunei',
		'BI'                              => 'Burundi',
		'CA'                              => 'Canada',
		'KH'                              => 'Cambodia',
		'TD'                              => 'Chad',
		'LK'                              => 'Sri Lanka',
		'CG'                              => 'Congo, Republic of the',
		'CD'                              => 'Congo Democratic Republic of the',
		'CN'                              => 'China',
		'CL'                              => 'Chile',
		'KY'                              => 'Cayman Islands',
		'CC'                              => 'Cocos Keeling Islands',
		'CM'                              => 'Cameroon',
		'KM'                              => 'Comoros',
		'CO'                              => 'Colombia',
		'MP'                              => 'Northern Mariana Islands',
		'CR'                              => 'Costa Rica',
		'CF'                              => 'Central African Republic',
		'CU'                              => 'Cuba',
		'CV'                              => 'Cabo Verde',
		'CK'                              => 'Cook Islands',
		'CY'                              => 'Cyprus',
		'DK'                              => 'Denmark',
		'DJ'                              => 'Djibouti',
		'DM'                              => 'Dominica',
		'DO'                              => 'Dominican Republic',
		'EC'                              => 'Ecuador',
		'EG'                              => 'Egypt',
		'IE'                              => 'Ireland',
		'GQ'                              => 'Equatorial Guinea',
		'EE'                              => 'Estonia',
		'ER'                              => 'Eritrea',
		'SV'                              => 'El Salvador',
		'ET'                              => 'Ethiopia',
		'EU'                              => 'European Union',
		'CZ'                              => 'Czechia',
		'GF'                              => 'French Guiana',
		'FI'                              => 'Finland',
		'FJ'                              => 'Fiji',
		'FK'                              => 'Falkland Islands Islas Malvinas',
		'FM'                              => 'Micronesia Federated States of',
		'FO'                              => 'Faroe Islands',
		'PF'                              => 'French Polynesia',
		'FR'                              => 'France',
		'TF'                              => 'French Southern and Antarctic Lands',
		'GM'                              => 'Gambia, The',
		'GA'                              => 'Gabon',
		'GE'                              => 'Georgia',
		'GH'                              => 'Ghana',
		'GI'                              => 'Gibraltar',
		'GD'                              => 'Grenada',
		'GG'                              => 'Guernsey',
		'GL'                              => 'Greenland',
		'DE'                              => 'Germany',
		'DE-BADEN_WURTTEMBERG'            => 'Germany - Baden-Wuttemberg',
		'DE-BAVARIA_LOZENGE'              => 'Germany - Bavaria (Lozanges)',
		'DE-BAVARIA_STRIPE'               => 'Germany - Bavara (Stripes)',
		'DE-BERLIN'                       => 'Germany - Berlin',
		'DE-BRANDENBURG'                  => 'Germany - Brandenberg',
		'DE-BREMEN'                       => 'Germany - Bremen',
		'DE-HESSE'                        => 'Germany - Hesse',
		'DE-LOWERSAXONY'                  => 'Germany - Lower Saxony',
		'DE-MECKLENBURG_WESTERNPOMERANIA' => 'Germany - Mecklenberg-Western Pomerania',
		'DE-NORTHRHINE_WESTPHALIA'        => 'Germany - North Rhine-Westphalia',
		'DE-RHINELAND_PALATINATE'         => 'Germany - Rhineland-Palatinate',
		'DE-SAARLAND'                     => 'Germany - Saarland',
		'DE-SAXONY'                       => 'Germany - Saxony',
		'DE-SAXONY_ANHALT'                => 'Germany - Saxony-Anhalt',
		'DE-SCHLESWIG_HOLSTEIN'           => 'Germany - Schleswig-Holstien',
		'DE-THURINGIA'                    => 'Germany - Thuringia',
		'GP'                              => 'Guadeloupe',
		'GU'                              => 'Guam',
		'GR'                              => 'Greece',
		'GT'                              => 'Guatemala',
		'GN'                              => 'Guinea',
		'GY'                              => 'Guyana',
		'HT'                              => 'Haiti',
		'HK'                              => 'Hong Kong',
		'HM'                              => 'Heard Island and McDonald Islands',
		'HN'                              => 'Honduras',
		'HR'                              => 'Croatia',
		'HU'                              => 'Hungary',
		'IS'                              => 'Iceland',
		'ID'                              => 'Indonesia',
		'IM'                              => 'Isle of Man',
		'IN'                              => 'India',
		'IO'                              => 'British Indian Ocean Territory',
		'IR'                              => 'Iran Islamic Republic of',
		'IL'                              => 'Israel',
		'IT'                              => 'Italy',
		'IT-ABRUZZO'                      => 'Italy - Abruzzo',
		'IT-APULIA'                       => 'Italy - Apulia',
		'IT-BASILICATA'                   => 'Italy - Basilicata',
		'IT-CALABRIA'                     => 'Italy - Calabria',
		'IT-CAMPANIA'                     => 'Italy - Campania',
		'IT-EMILIA_ROMAGNA'               => 'Italy - Elilia-Romagna',
		'IT-FRIULI_VENEZIAGIULIA'         => 'Italy - Friuli-Venezia Giulia',
		'IT-LAZIO'                        => 'Italy - Lazio',
		'IT-LIGURIA'                      => 'Italy - Liguria',
		'IT-LOMBARDY'                     => 'Italy - Lombardy',
		'IT-MARCHE'                       => 'Italy - Marche',
		'IT-MOLISE'                       => 'Italy - Molise',
		'IT-PIEDMONT'                     => 'Italy - Piedmont',
		'IT-ROME'                         => 'Italy - Rome',
		'IT-SARDINIA'                     => 'Italy - Sardinia',
		'IT-SICILY'                       => 'Italy - Sicily',
		'IT-TRENTINO_SOUTHTYROL'          => 'Italy - Trentino-South Tyrol',
		'IT-TUSCANY'                      => 'Italy - Tuscany',
		'IT-UMBRIA'                       => 'Italy - Umbria',
		'IT-AOSTAVALLEY'                  => 'Italy - Aosta Valley',
		'IT-VENETO'                       => 'Italy - Veneto',
		'CI'                              => 'Cote d\'Ivoire',
		'IQ'                              => 'Iraq',
		'JP'                              => 'Japan',
		'JE'                              => 'Jersey',
		'JM'                              => 'Jamaica',
		'SJ'                              => 'Jan Mayen',
		'JO'                              => 'Jordan',
		'KE'                              => 'Kenya',
		'KG'                              => 'Kyrgyzstan',
		'KP'                              => 'Korea Democratic People\'s Republic of',
		'KI'                              => 'Kiribati',
		'KR'                              => 'Korea Republic of',
		'CX'                              => 'Christmas Island',
		'KW'                              => 'Kuwait',
		'XK'                              => 'Kosovo',
		'KZ'                              => 'Kazakhstan',
		'LA'                              => 'Laos',
		'LB'                              => 'Lebanon',
		'LV'                              => 'Latvia',
		'LT'                              => 'Lithuania',
		'LR'                              => 'Liberia',
		'SK'                              => 'Slovakia',
		'UM'                              => 'United States Minor Outlying Islands',
		'LI'                              => 'Liechtenstein',
		'LS'                              => 'Lesotho',
		'LU'                              => 'Luxembourg',
		'LY'                              => 'Libya',
		'MG'                              => 'Madagascar',
		'MQ'                              => 'Martinique',
		'MO'                              => 'Macau',
		'MD'                              => 'Moldova Republic of',
		'YT'                              => 'Mayotte',
		'MN'                              => 'Mongolia',
		'MS'                              => 'Montserrat',
		'MW'                              => 'Malawi',
		'ME'                              => 'Montenegro',
		'MK'                              => 'North Macedonia',
		'ML'                              => 'Mali',
		'MC'                              => 'Monaco',
		'MA'                              => 'Morocco',
		'MU'                              => 'Mauritius',
		'MR'                              => 'Mauritania',
		'MT'                              => 'Malta',
		'OM'                              => 'Oman',
		'MV'                              => 'Maldives',
		'MX'                              => 'Mexico',
		'MY'                              => 'Malaysia',
		'MZ'                              => 'Mozambique',
		'AN'                              => 'Netherlands Antilles',
		'NC'                              => 'New Caledonia',
		'NU'                              => 'Niue',
		'NF'                              => 'Norfolk Island',
		'NE'                              => 'Niger',
		'VU'                              => 'Vanuatu',
		'NG'                              => 'Nigeria',
		'NL'                              => 'Netherlands',
		'NO'                              => 'Norway',
		'NP'                              => 'Nepal',
		'NR'                              => 'Nauru',
		'SR'                              => 'Suriname',
		'BQ'                              => 'Bonaire, Sint Eustatius and Saba',
		'NI'                              => 'Nicaragua',
		'NZ'                              => 'New Zealand',
		'PY'                              => 'Paraguay',
		'PN'                              => 'Pitcairn Islands',
		'PE'                              => 'Peru',
		'PK'                              => 'Pakistan',
		'PL'                              => 'Poland',
		'PL-DS'                           => 'Poland - Lower Silesian Voivodeship',
		'PL-KP'                           => 'Poland - Kuyavian-Pomeranian Voivodeship',
		'PL-LU'                           => 'Poland - Lublin Voivodeship',
		'PL-LB'                           => 'Poland - Lubusz Voivodeship',
		'PL-LD'                           => 'Poland - Łódź Voivodeship',
		'PL-MA'                           => 'Poland - Lesser Poland Voivodeship',
		'PL-MZ'                           => 'Poland - Masovian Voivodeship',
		'PL-OP'                           => 'Poland - Opole Voivodeship',
		'PL-PK'                           => 'Poland - Subcarpathian Voivodeship',
		'PL-PD'                           => 'Poland - Podlaskie Voivodeship',
		'PL-PM'                           => 'Poland - Pomeranian Voivodeship',
		'PL-SL'                           => 'Poland - Silesian Voivodeship',
		'PL-SK'                           => 'Poland - Holy Cross Voivodeship',
		'PL-WN'                           => 'Poland - Warmian-Masurian Voivodeship',
		'PL-WP'                           => 'Poland - Greater Poland Voivodeship',
		'PL-ZP'                           => 'Poland - West Pomeranian Voivodeship',
		'PA'                              => 'Panama',
		'PT'                              => 'Portugal',
		'PG'                              => 'Papua New Guinea',
		'PW'                              => 'Palau',
		'GW'                              => 'Guinea-Bissau',
		'QA'                              => 'Qatar',
		'RE'                              => 'Reunion',
		'RS'                              => 'Serbia',
		'MH'                              => 'Marshall Islands',
		'MF'                              => 'Saint Martin',
		'RO'                              => 'Romania',
		'PH'                              => 'Philippines',
		'PR'                              => 'Puerto Rico',
		'RU'                              => 'Russia',
		'RW'                              => 'Rwanda',
		'SA'                              => 'Saudi Arabia',
		'PM'                              => 'Saint Pierre and Miquelon',
		'KN'                              => 'Saint Kitts and Nevis',
		'SC'                              => 'Seychelles',
		'ZA'                              => 'South Africa',
		'SN'                              => 'Senegal',
		'SH'                              => 'Saint Helena',
		'SI'                              => 'Slovenia',
		'SL'                              => 'Sierra Leone',
		'SM'                              => 'San Marino',
		'SG'                              => 'Singapore',
		'SO'                              => 'Somalia',
		'ES'                              => 'Spain',
		'ES-CT'                           => 'Spain - Catalonia',
		'SS'                              => 'South Sudan',
		'LC'                              => 'Saint Lucia',
		'SD'                              => 'Sudan',
		'SJ'                              => 'Svalbard',
		'SE'                              => 'Sweden',
		'GS'                              => 'South Georgia and the South Sandwich Islands',
		'SX'                              => 'Sint Maarten',
		'SY'                              => 'Syrian Arab Republic',
		'CH'                              => 'Switzerland',
		'TT'                              => 'Trinidad and Tobago',
		'TH'                              => 'Thailand',
		'TJ'                              => 'Tajikistan',
		'TC'                              => 'Turks and Caicos Islands',
		'TK'                              => 'Tokelau',
		'TO'                              => 'Tonga',
		'TG'                              => 'Togo',
		'ST'                              => 'Sao Tome and Principe',
		'TN'                              => 'Tunisia',
		'TL'                              => 'Timor-Leste',
		'TR'                              => 'Turkey',
		'TV'                              => 'Tuvalu',
		'TW'                              => 'Taiwan',
		'TM'                              => 'Turkmenistan',
		'TZ'                              => 'Tanzania, United Republic of',
		'CW'                              => 'Curacao',
		'UG'                              => 'Uganda',
		'GB'                              => 'United Kingdom of Great Britain and Northern Ireland',
		'GB-ENG'                          => 'UK of GB & NI - England',
		'GB-ENG-NORTHUMBERLAND'           => 'UK of GB & NI - England - Northumberland',
		'GB-ENG-CORNWALL'                 => 'UK of GB & NI - England - Cornwall',
		'GB-NIR'                          => 'UK of GB & NI - Northern ireland',
		'GB-SCT'                          => 'UK of GB & NI - Scotland',
		'GB-SCT-ISLEOFSKYE'               => 'UK of GB & NI - Scotland - Isle of Skye',
		'GB-SCT-ORKNEY'                   => 'UK of GB & NI - Scotland - Orkney',
		'GB-SCT-SHETLAND'                 => 'UK of GB & NI - Scotland - Shetland',
		'GB-SCT-ROYALBANNER'              => 'UK of GB & NI - Scotland - Royal Banner',
		'GB-ULSTER'                       => 'UK of GB & NI - Ulster',
		'GB-WLS'                          => 'UK of GB & NI - Wales',
		'GB-CORNWALL'                     => 'UK of GB & NI - England - Cornwall',
		'UA'                              => 'Ukraine',
		'US'                              => 'United States of America',
		'US-AL'                           => 'USA - Alabama',
		'US-AK'                           => 'USA - Alaska',
		'US-AZ'                           => 'USA - Arizona',
		'US-AR'                           => 'USA - Arkansas',
		'US-CA'                           => 'USA - California',
		'US-CO'                           => 'USA - Colorado',
		'US-CT'                           => 'USA - Connecticut',
		'US-DE'                           => 'USA - Delaware',
		'US-FL'                           => 'USA - Florida',
		'US-GA'                           => 'USA - Georgia',
		'US-HI'                           => 'USA - Hawaii',
		'US-ID'                           => 'USA - Idaho',
		'US-IL'                           => 'USA - Illinois',
		'US-IN'                           => 'USA - Indiana',
		'US-IA'                           => 'USA - Iowa',
		'US-KS'                           => 'USA - Kansas',
		'US-KY'                           => 'USA - Kentucky',
		'US-LA'                           => 'USA - Louisiana',
		'US-ME'                           => 'USA - Maine',
		'US-MD'                           => 'USA - Maryland',
		'US-MA'                           => 'USA - Massachusetts',
		'US-MI'                           => 'USA - Michigan',
		'US-MN'                           => 'USA - Minnesota',
		'US-MS'                           => 'USA - Mississippi',
		'US-MO'                           => 'USA - Missouri',
		'US-MT'                           => 'USA - Montana',
		'US-NE'                           => 'USA - Nebraska',
		'US-NV'                           => 'USA - Nevada',
		'US-NH'                           => 'USA - New Hampshire',
		'US-NJ'                           => 'USA - New Jersey',
		'US-NM'                           => 'USA - New Mexico',
		'US-NY'                           => 'USA - New York',
		'US-NC'                           => 'USA - North Carolina',
		'US-ND'                           => 'USA - North Dakota',
		'US-OH'                           => 'USA - Ohio',
		'US-OK'                           => 'USA - Oklahoma',
		'US-OR'                           => 'USA - Oregon',
		'US-OR-REVERSE'                   => 'USA - Oregon (Reverse)',
		'US-PA'                           => 'USA - Pennsylvania',
		'US-RI'                           => 'USA - Rhode Island',
		'US-SC'                           => 'USA - South Carolina',
		'US-SD'                           => 'USA - South Dakota',
		'US-TN'                           => 'USA - Tennessee',
		'US-TX'                           => 'USA - Texas',
		'US-UT'                           => 'USA - Utah',
		'US-VT'                           => 'USA - Vermont',
		'US-VA'                           => 'USA - Virginia',
		'US-WA'                           => 'USA - Washington',
		'US-WV'                           => 'USA - West Virginia',
		'US-WI'                           => 'USA - Wisconsin',
		'US-WY'                           => 'USA - Wyoming',
		'US-AS'                           => 'USA - American Samoa',
		'US-DC'                           => 'USA - District of Columbia',
		'US-GU'                           => 'USA - Guam',
		'US-MP'                           => 'USA - Northern Mariana Islands',
		'US-PR'                           => 'USA - Puerto Rico',
		'US-VI'                           => 'USA - Virgin Islands',
		'BF'                              => 'Burkina Faso',
		'UY'                              => 'Uruguay',
		'UZ'                              => 'Uzbekistan',
		'VC'                              => 'Saint Vincent and the Grenadines',
		'VE'                              => 'Venezuela Bolivarian Republic of',
		'VG'                              => 'Virgin Islands British',
		'VN'                              => 'Vietnam',
		'VI'                              => 'Virgin Islands U.S.',
		'VA'                              => 'Vatican City',
		'NA'                              => 'Namibia',
		'PS'                              => 'Palestine, State of',
		'WF'                              => 'Wallis and Futuna',
		'EH'                              => 'Western Sahara',
		'WS'                              => 'Samoa',
		'SZ'                              => 'Eswatini',
		'CS'                              => 'Serbia and Montenegro',
		'YE'                              => 'Yemen',
		'ZM'                              => 'Zambia',
		'ZW'                              => 'Zimbabwe',
		'PIRATE'                          => 'Skull and Crossbones',
		'PIRATE2'                         => 'Skull and Cross Cutlasses',
		'CONFEDERATE'                     => 'Confederate Battle Flag',
		'NAVAJONATION'                    => 'Navajo Nation',
		'TEXAS'                           => 'Republic of Texas',
	);

	if ( isset( $countries[ strtoupper( $country_code ) ] ) ) {
		$country_name = $countries[ strtoupper( $country_code ) ];
	} else {
		$country_name = '';
	}

	if ( strlen( $country_name ) == 0 ) {
		$country_name = $country_code;
	}

	return $country_name;
}


/**
 * Shortcode display flag.
 *
 * @since 1.0.0
 */
function shortcode_display_flag( $atts, $content = null ) {

	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	if ( empty( $atts ) ) {
		$flag = 'none';
	} else {
		$args   = shortcode_atts(
			array(
				'id'     => '',
				'width'  => $options['width'] . 'px',
				'border' => $options['border'],
			),
			$atts
		);
		$id     = $args['id'];
		$width  = $args['width'];
		$border = $args['border'];
		if ( $id == '' ) {
			$attribs = implode( '', $atts );
			$flag    = trim( trim( trim( trim( trim( $attribs, '=' ), '"' ), "'" ), '&#8217;' ), '&#8221;' );
			$width   = $options['width'] . 'px';
			$border  = $options['border'];
		} else {
			$flag = $id;
		}
	}

	if ( strtoupper( $flag ) == 'ENGLAND' ) {
		$flag = 'gb-eng';
	} elseif ( strtoupper( $flag ) == 'NORTHUMBERLAND' ) {
		$flag = 'gb-eng-northumberland';
	} elseif ( strtoupper( $flag ) == 'ULSTER' ) {
		$flag = 'gb-eng-ulster';
	} elseif ( strtoupper( $flag ) == 'ULSTERBANNER' ) {
		$flag = 'gb-eng-ulster';
	} elseif ( strtoupper( $flag ) == 'SCOTLAND' ) {
		$flag = 'gb-sct';
	} elseif ( strtoupper( $flag ) == 'WALES' ) {
		$flag = 'gb-wls';
	} elseif ( strtoupper( $flag ) == 'EUROPEANUNION' ) {
		$flag = 'gb-eu';
	} elseif ( strtoupper( $flag ) == 'CURACAO' ) {
		$flag = 'gb-cw';
	} elseif ( strtoupper( $flag ) == 'NORTHERNIRELAND' ) {
		$flag = 'gb-nir';
	} elseif ( strtoupper( $flag ) == 'CATALONIA' ) {
		$flag = 'es-ct';
	}

	if ( $width != '' ) {
		$width = 'width: ' . esc_html( $width ) . '; ';
	}
	if ( $border != '' ) {
		$border = 'border: ' . esc_html( $border ) . '; ';
	}

	if ( file_exists( trailingslashit( esc_attr( $options['folder'] ) ) . esc_attr( $flag ) . '.svg' ) ) {
		$url = esc_attr( $options['url'] ) . esc_attr( $flag ) . '.svg';
	} else {
		$url = plugin_dir_url( __FILE__ ) . '../assets/flags/' . esc_attr( $flag ) . '.svg';
	}

	$country_name = get_country_name( $flag );

	$image = '<img style="' . $width . ' ' . $border . ' " src="' . $url . '" class="azrcrv-f" alt="' . esc_attr( $country_name ) . '" title="' . esc_attr( $country_name ) . '" />';

	return $image;

}
