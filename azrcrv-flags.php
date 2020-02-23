<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Flags
 * Description: Allows flags to be added to posts and pages using a shortcode.
 * Version: 1.3.0
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/flags
 * Text Domain: flags
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
register_activation_hook(__FILE__, 'azrcrv_create_plugin_menu_f');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('wp_enqueue_scripts', 'azrcrv_f_load_css');
add_action('admin_menu', 'azrcrv_f_create_admin_menu');
add_action('admin_enqueue_scripts', 'azrcrv_f_load_css');
//add_action('the_posts', 'azrcrv_f_check_for_shortcode');
add_action('plugins_loaded', 'azrcrv_f_load_languages');

// add filters
add_filter('plugin_action_links', 'azrcrv_f_add_plugin_action_link', 10, 2);

// add shortcodes
add_shortcode('flag', 'azrcrv_f_flag');
add_shortcode('FLAG', 'azrcrv_f_flag');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('flags', false, $plugin_rel_path);
}

/**
 * Check if shortcode on current page and then load css and jqeury.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_check_for_shortcode($posts){
    if (empty($posts)){
        return $posts;
	}
	
	
	// array of shortcodes to search for
	$shortcodes = array(
						'flag','FLAG'
						);
	
    // loop through posts
    $found = false;
    foreach ($posts as $post){
		// loop through shortcodes
		foreach ($shortcodes as $shortcode){
			// check the post content for the shortcode
			if (has_shortcode($post->post_content, $shortcode)){
				$found = true;
				// break loop as shortcode found in page content
				break 2;
			}
		}
	}
 
    if ($found){
		// as shortcode found call functions to load css and jquery
        azrcrv_f_load_css();
    }
    return $posts;
}
	
/**
 * Load plugin css.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_load_css(){
	wp_enqueue_style('azrcrv-f', plugins_url('assets/css/style.css', __FILE__));
}

/**
 * Add Flags action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=azrcrv-f">'.esc_html__('Settings' ,'flags').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add Flags menu to plugin menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Flag Settings", "flag")
						,esc_html__("Flag", "flag")
						,'manage_options'
						,'azrcrv-f'
						,'azrcrv_f_settings');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_settings(){
	if (!current_user_can('manage_options')){
		$error = new WP_Error('not_found', esc_html__('You do not have sufficient permissions to access this page.' , 'flags'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
	}
	?>
	<div id="azrcrv-f-general" class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

		<label for="explanation">
			<p><?php esc_html_e('Flags allows a 16x16 flag to be displayed in a post of page using a [flag] shortcode.', 'flags'); ?></p>
			<p><?php esc_html_e('Format of shortcode is [flag=gb] to display the flag of the United Kingdom of Great Britain and Northern Ireland; 247 flags are included.', 'flags'); ?></p>
			<p><?php esc_html_e('Defintion of flags can be found at Wikipedia page ISO 3166-1 alpha-2: ', 'flags'); ?><a href='https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2'>https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2</a></p>
		</label>
		
		<p>
		<?php esc_html_e('Available flags are:', 'icons');
			
			$dir = plugin_dir_path(__FILE__).'/images';
			$flags = array();
			if (is_dir($dir)){
				if ($directory = opendir($dir)){
					while (($file = readdir($directory)) !== false){
						if ($file != '.' and $file != '..' and $file != 'Thumbs.db' and $file != 'index.php'){
							$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
							$flags[] = $filewithoutext;
						}
					}
					closedir($directory);
				}
				asort($flags);
				
				if ($directory = opendir($dir)){
					foreach ($flags as $flag){	
						$country_name = azrcrv_f_get_country_name($flag);
						echo "<div style='width: 200px; display: inline-block;'><img src='";
						echo plugin_dir_url(__FILE__)."images/".esc_html($flag).".png' alt='".esc_html($country_name)."' />&nbsp;<em>".esc_html($country_name)." (".esc_html($flag).")</em></div>";
					}
				}
			}
			?>
		</p>
		
		<label for="additional-plugins">
			azurecurve <?php esc_html_e('has the following plugins which allow shortcodes to be used in comments and widgets:', 'flags'); ?>
		</label>
		<ul class='azrcrv-plugin-index'>
			<li>
				<?php
				if (azrcrv_f_is_plugin_active('azrcrv-shortcodes-in-comments/azrcrv-shortcodes-in-comments.php')){
					echo "<a href='admin.php?page=azrcrv-sic' class='azrcrv-plugin-index'>Shortcodes in Comments</a>";
				}else{
					echo "<a href='https://plugins.classicpress.org/shortcodes-in-comments/' class='azrcrv-plugin-index'>Shortcodes in Comments</a>";
				}
				?>
			</li>
			<li>
				<?php
				if (azrcrv_f_is_plugin_active('azrcrv-shortcodes-in-widgets/azrcrv-shortcodes-in-widgets.php')){
					echo "<a href='admin.php?page=azrcrv-siw' class='azrcrv-plugin-index'>Shortcodes in Widgets</a>";
				}else{
					echo "<a href='https://plugins.classicpress.org/shortcodes-in-widgets/' class='azrcrv-plugin-index'>Shortcodes in Widgets</a>";
				}
				?>
			</li>
		</ul>
	</div><?php
}

/**
 * Get country name.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_get_country_name($country_code){

$countries = array(
				'AW' => 'Aruba',
				'AG' => 'Antigua and Barbuda',
				'AE' => 'United Arab Emirates',
				'AF' => 'Afghanistan',
				'DZ' => 'Algeria',
				'AZ' => 'Azerbaijan',
				'AL' => 'Albania',
				'AM' => 'Armenia',
				'AD' => 'Andorra',
				'AO' => 'Angola',
				'AS' => 'American Samoa',
				'AR' => 'Argentina',
				'AU' => 'Australia',
				'AT' => 'Austria',
				'AI' => 'Anguilla',
				'AX' => 'Aland Islands',
				'AQ' => 'Antarctica',
				'BH' => 'Bahrain',
				'BB' => 'Barbados',
				'BW' => 'Botswana',
				'BM' => 'Bermuda',
				'BE' => 'Belgium',
				'BS' => 'Bahamas, The',
				'BD' => 'Bangladesh',
				'BZ' => 'Belize',
				'BA' => 'Bosnia and Herzegovina',
				'BO' => 'Bolivia Plurinational State of',
				'BL' => 'Saint Barthelemy',
				'MM' => 'Myanmar',
				'BJ' => 'Benin',
				'BY' => 'Belarus',
				'SB' => 'Solomon Islands',
				'BR' => 'Brazil',
				'BT' => 'Bhutan',
				'BG' => 'Bulgaria',
				'BV' => 'Bouvet Island',
				'BN' => 'Brunei',
				'BI' => 'Burundi',
				'CA' => 'Canada',
				'KH' => 'Cambodia',
				'TD' => 'Chad',
				'LK' => 'Sri Lanka',
				'CG' => 'Congo, Republic of the',
				'CD' => 'Congo Democratic Republic of the',
				'CN' => 'China',
				'CL' => 'Chile',
				'KY' => 'Cayman Islands',
				'CC' => 'Cocos Keeling Islands',
				'CM' => 'Cameroon',
				'KM' => 'Comoros',
				'CO' => 'Colombia',
				'MP' => 'Northern Mariana Islands',
				'CR' => 'Costa Rica',
				'CF' => 'Central African Republic',
				'CU' => 'Cuba',
				'CV' => 'Cabo Verde',
				'CK' => 'Cook Islands',
				'CY' => 'Cyprus',
				'DK' => 'Denmark',
				'DJ' => 'Djibouti',
				'DM' => 'Dominica',
				'DO' => 'Dominican Republic',
				'EC' => 'Ecuador',
				'EG' => 'Egypt',
				'IE' => 'Ireland',
				'GQ' => 'Equatorial Guinea',
				'EE' => 'Estonia',
				'ER' => 'Eritrea',
				'SV' => 'El Salvador',
				'ET' => 'Ethiopia',
				'CZ' => 'Czechia',
				'GF' => 'French Guiana',
				'FI' => 'Finland',
				'FJ' => 'Fiji',
				'FK' => 'Falkland Islands Islas Malvinas',
				'FM' => 'Micronesia Federated States of',
				'FO' => 'Faroe Islands',
				'PF' => 'French Polynesia',
				'FR' => 'France',
				'TF' => 'French Southern and Antarctic Lands',
				'GM' => 'Gambia, The',
				'GA' => 'Gabon',
				'GE' => 'Georgia',
				'GH' => 'Ghana',
				'GI' => 'Gibraltar',
				'GD' => 'Grenada',
				'GG' => 'Guernsey',
				'GL' => 'Greenland',
				'DE' => 'Germany',
				'GP' => 'Guadeloupe',
				'GU' => 'Guam',
				'GR' => 'Greece',
				'GT' => 'Guatemala',
				'GN' => 'Guinea',
				'GY' => 'Guyana',
				'HT' => 'Haiti',
				'HK' => 'Hong Kong',
				'HM' => 'Heard Island and McDonald Islands',
				'HN' => 'Honduras',
				'HR' => 'Croatia',
				'HU' => 'Hungary',
				'IS' => 'Iceland',
				'ID' => 'Indonesia',
				'IM' => 'Isle of Man',
				'IN' => 'India',
				'IO' => 'British Indian Ocean Territory',
				'IR' => 'Iran Islamic Republic of',
				'IL' => 'Israel',
				'IT' => 'Italy',
				'CI' => 'Cote d\'Ivoire',
				'IQ' => 'Iraq',
				'JP' => 'Japan',
				'JE' => 'Jersey',
				'JM' => 'Jamaica',
				'SJ' => 'Jan Mayen',
				'JO' => 'Jordan',
				'KE' => 'Kenya',
				'KG' => 'Kyrgyzstan',
				'KP' => 'Korea Democratic People\'s Republic of',
				'KI' => 'Kiribati',
				'KR' => 'Korea Republic of',
				'CX' => 'Christmas Island',
				'KW' => 'Kuwait',
				'XK' => 'Kosovo',
				'KZ' => 'Kazakhstan',
				'LA' => 'Laos',
				'LB' => 'Lebanon',
				'LV' => 'Latvia',
				'LT' => 'Lithuania',
				'LR' => 'Liberia',
				'SK' => 'Slovakia',
				'UM' => 'United States Minor Outlying Islands',
				'LI' => 'Liechtenstein',
				'LS' => 'Lesotho',
				'LU' => 'Luxembourg',
				'LY' => 'Libya',
				'MG' => 'Madagascar',
				'MQ' => 'Martinique',
				'MO' => 'Macau',
				'MD' => 'Moldova Republic of',
				'YT' => 'Mayotte',
				'MN' => 'Mongolia',
				'MS' => 'Montserrat',
				'MW' => 'Malawi',
				'ME' => 'Montenegro',
				'MK' => 'North Macedonia',
				'ML' => 'Mali',
				'MC' => 'Monaco',
				'MA' => 'Morocco',
				'MU' => 'Mauritius',
				'MR' => 'Mauritania',
				'MT' => 'Malta',
				'OM' => 'Oman',
				'MV' => 'Maldives',
				'MX' => 'Mexico',
				'MY' => 'Malaysia',
				'MZ' => 'Mozambique',
				'NC' => 'New Caledonia',
				'NU' => 'Niue',
				'NF' => 'Norfolk Island',
				'NE' => 'Niger',
				'VU' => 'Vanuatu',
				'NG' => 'Nigeria',
				'NL' => 'Netherlands',
				'NO' => 'Norway',
				'NP' => 'Nepal',
				'NR' => 'Nauru',
				'SR' => 'Suriname',
				'BQ' => 'Bonaire, Sint Eustatius and Saba',
				'NI' => 'Nicaragua',
				'NZ' => 'New Zealand',
				'PY' => 'Paraguay',
				'PN' => 'Pitcairn Islands',
				'PE' => 'Peru',
				'PK' => 'Pakistan',
				'PL' => 'Poland',
				'PA' => 'Panama',
				'PT' => 'Portugal',
				'PG' => 'Papua New Guinea',
				'PW' => 'Palau',
				'GW' => 'Guinea-Bissau',
				'QA' => 'Qatar',
				'RE' => 'Reunion',
				'RS' => 'Serbia',
				'MH' => 'Marshall Islands',
				'MF' => 'Saint Martin',
				'RO' => 'Romania',
				'PH' => 'Philippines',
				'PR' => 'Puerto Rico',
				'RU' => 'Russia',
				'RW' => 'Rwanda',
				'SA' => 'Saudi Arabia',
				'PM' => 'Saint Pierre and Miquelon',
				'KN' => 'Saint Kitts and Nevis',
				'SC' => 'Seychelles',
				'ZA' => 'South Africa',
				'SN' => 'Senegal',
				'SH' => 'Saint Helena',
				'SI' => 'Slovenia',
				'SL' => 'Sierra Leone',
				'SM' => 'San Marino',
				'SG' => 'Singapore',
				'SO' => 'Somalia',
				'ES' => 'Spain',
				'SS' => 'South Sudan',
				'LC' => 'Saint Lucia',
				'SD' => 'Sudan',
				'SJ' => 'Svalbard',
				'SE' => 'Sweden',
				'GS' => 'South Georgia and the South Sandwich Islands',
				'SX' => 'Sint Maarten',
				'SY' => 'Syrian Arab Republic',
				'CH' => 'Switzerland',
				'TT' => 'Trinidad and Tobago',
				'TH' => 'Thailand',
				'TJ' => 'Tajikistan',
				'TC' => 'Turks and Caicos Islands',
				'TK' => 'Tokelau',
				'TO' => 'Tonga',
				'TG' => 'Togo',
				'ST' => 'Sao Tome and Principe',
				'TN' => 'Tunisia',
				'TL' => 'Timor-Leste',
				'TR' => 'Turkey',
				'TV' => 'Tuvalu',
				'TW' => 'Taiwan',
				'TM' => 'Turkmenistan',
				'TZ' => 'Tanzania, United Republic of',
				'CW' => 'Curacao',
				'UG' => 'Uganda',
				'GB' => 'United Kingdom of Great Britain and Northern Ireland',
				'UA' => 'Ukraine',
				'US' => 'United States of America',
				'BF' => 'Burkina Faso',
				'UY' => 'Uruguay',
				'UZ' => 'Uzbekistan',
				'VC' => 'Saint Vincent and the Grenadines',
				'VE' => 'Venezuela Bolivarian Republic of',
				'VG' => 'Virgin Islands British',
				'VN' => 'Vietnam',
				'VI' => 'Virgin Islands U.S.',
				'VA' => 'Holy See',
				'NA' => 'Namibia',
				'PS' => 'Palestine, State of',
				'WF' => 'Wallis and Futuna',
				'EH' => 'Western Sahara',
				'WS' => 'Samoa',
				'SZ' => 'Eswatini',
				'CS' => 'Serbia and Montenegro',
				'YE' => 'Yemen',
				'ZM' => 'Zambia',
				'ZW' => 'Zimbabwe',
				'ENGLAND' => 'England',
				'WALES' => 'Wales',
				'SCOTLAND' => 'Scotland',
				'NORTHERNIRELAND' => 'Northern Ireland',
				'NORTHUMBERLAND' => 'Northumberland',
				'CURACAO' => 'Curaçao',
				'ULSTER' => 'Ulster Banner',
				'EUROPEANUNION' => 'European Union',
			);
			
	$country_name = $countries[strtoupper($country_code)];
	
	if (strlen($country_name) == 0){
		$country_name = $country_code;
	}
	
	return $country_name;
}

/**
 * Check if function active (included due to standard function failing due to order of load).
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_is_plugin_active($plugin){
    return in_array($plugin, (array) get_option('active_plugins', array()));
}

/**
 * Flag shortcode.
 *
 * @since 1.0.0
 *
 */
function azrcrv_f_flag($atts, $content = null)
{
	if (empty($atts)){
		$flag = 'none';
	}else{
		$attribs = implode('',$atts);
		$flag = trim (trim (trim (trim (trim ($attribs , '=') , '"') , "'") , '&#8217;') , "&#8221;");
	}
	$flag = esc_html($flag);
	$country_name = azrcrv_f_get_country_name($flag);
	return "<img class='azrcrv-f' src='".plugin_dir_url(__FILE__)."images/".esc_html($flag).".png' alt= '".esc_html($country_name)."' />";
}

?>