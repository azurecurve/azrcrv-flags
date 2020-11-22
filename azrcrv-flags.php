<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Flags
 * Description: Allows flags to be added to posts and pages using a shortcode.
 * Version: 1.6.0
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/flags/
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
add_action('admin_init', 'azrcrv_create_plugin_menu_f');

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
add_action('plugins_loaded', 'azrcrv_f_load_languages');
add_action('admin_post_azrcrv_f_save_options', 'azrcrv_f_save_options');

// add filters
add_filter('plugin_action_links', 'azrcrv_f_add_plugin_action_link', 10, 2);
add_filter('the_posts', 'azrcrv_f_check_for_shortcode', 10, 2);
add_filter('codepotent_update_manager_image_path', 'azrcrv_f_custom_image_path');
add_filter('codepotent_update_manager_image_url', 'azrcrv_f_custom_image_url');

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
 * Get options including defaults.
 *
 * @since 1.6.0
 *
 */
function azrcrv_f_get_option($option_name){
 
	$defaults = array(
						'width' => '16',
						'border' => '',
					);

	$options = get_option($option_name, $defaults);

	$options = wp_parse_args($options, $defaults);

	return $options;

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
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-f').'"><img src="'.plugins_url('/pluginmenu/images/Favicon-16x16.png', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'flags').'</a>';
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
						,esc_html__('Flags Settings', 'flags')
						,esc_html__('Flags', 'flags')
						,'manage_options'
						,'azrcrv-f'
						,'azrcrv_f_settings');
   
}

/**
 * Load css and jquery for flags.
 *
 * @since 1.6.0
 *
 */
function azrcrv_f_load_admin_style(){
    wp_register_style('flags-css', plugins_url('assets/css/admin.css', __FILE__), false, '1.0.0');
    wp_enqueue_style( 'flags-css' );
	
	wp_enqueue_script("flags-admin-js", plugins_url('assets/jquery/jquery.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs'));
}
add_action('admin_enqueue_scripts', 'azrcrv_f_load_admin_style');

/**
 * Custom plugin image path.
 *
 * @since 1.4.0
 *
 */
function azrcrv_f_custom_image_path($path){
    if (strpos($path, 'azrcrv-flags') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.4.0
 *
 */
function azrcrv_f_custom_image_url($url){
    if (strpos($url, 'azrcrv-flags') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
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
	
	$options = azrcrv_f_get_option('azrcrv-f');
	$saved_options = get_option('azrcrv-f');
	
	echo '<div id="azrcrv-f-general" class="wrap">';
	
		echo '<h1>'.esc_html(get_admin_page_title()).'</h1>';
		
		if(isset($_GET['settings-updated'])){ ?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php esc_html_e('Settings have been saved.', 'flags'); ?></strong></p>
			</div>
		<?php }
		
		echo '<form method="post" action="admin-post.php">';
		echo '<input type="hidden" name="action" value="azrcrv_f_save_options" />';
		echo '<input name="page_options" type="hidden" value="width,border" />';
		
		wp_nonce_field('azrcrv-f', 'azrcrv-f-nonce');
		
		echo '<input type="hidden" name="azrcrv_f_data_update" value="yes" />';
		
		if (isset($saved_options['width'])){
			$showsettings = false;
		}else{
			$showsettings = true;
		}
		
		?>
		
		<label for="explanation">
			<p><?php printf(esc_html__('%s allows a scalable SVG flag to be displayed in a post or page using the %s shortcode.', 'flags'), 'Flags', '<strong>[flag]</strong>'); ?></p>
			<p><?php printf(esc_html__('The shortcode usage is %s where the %s is the country code shown below; width and border are optional paramaters and can be defaulted from the settings. Shortcode usage of %s where default parameters are to be used is also supported.', 'flags'), '<strong>[flag id="gb" width="20px" border="1px solid black"]</strong>', '<strong>id</strong>', '<strong>[flag="gb"]</strong>'); ?></p>
			<p><?php printf(esc_html__('Defintion of most flags can be found at %s (although some additional flags have been included).', 'flags'), '<a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">Wikipedia page ISO 3166-1 alpha-2</a>'); ?></p>
		</label>
		
		<h2 class="nav-tab-wrapper nav-tab-wrapper-azrcrv-f">
			<a class="nav-tab <?php if ($showsettings == true){ echo 'nav-tab-active'; } ?>" data-item=".tabs-1" href="#tabs-1"><?php _e('Default Settings', 'flags') ?></a>
			<a class="nav-tab <?php if ($showsettings == false){ echo 'nav-tab-active'; } ?>" data-item=".tabs-2" href="#tabs-2"><?php _e('Available Flags', 'flags') ?></a>
		</h2>

		<div>
			<div class="azrcrv_f_tabs <?php if ($showsettings == false){ echo 'invisible'; } ?> tabs-1">
				<p class="azrcrv_f_horiz">
				<table class="form-table">
				
					<tr><th scope="row"><?php esc_html_e('Default width?', 'flags'); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Default width?', 'flags'); ?></span></legend>
							<label for="width"><input type="number" name="width" class="small-text" value="<?php echo $options['width']; ?>" />px</label>
						</fieldset>
					</td></tr>
				
					<tr><th scope="row"><?php esc_html_e('Default border?', 'flags'); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Default border?', 'flags'); ?></span></legend>
							<label for="border"><input type="text" name="border" class="regular-text" value="<?php echo $options['border']; ?>"/></label>
							<p class="description"><?php esc_html_e('Setting a default border is supported, but not recommended; borders are work better if applied only to those flags with a background matching your site background.', 'flags'); ?></p>
						</fieldset>
					</td></tr>
					
				</table>
				</p>
			</div>
			
			<div class="azrcrv_f_tabs <?php if ($showsettings == true){ echo 'invisible'; } ?> tabs-2">
				
				<div style='padding-left: 6px; '>
				<?php
					$dir = plugin_dir_path(__FILE__).'assets/images';
					$flags = array();
					if (is_dir($dir)){
						if ($directory = opendir($dir)){
							while (($file = readdir($directory)) !== false){
								//echo $file;
								if (substr($file, -3) == 'svg'){
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
								echo '<div style="width: 350px; display: inline-block;">';
								echo '<object style="width: 20px;" type="image/svg+xml" data="'.plugin_dir_url(__FILE__).'assets/images/'.esc_html($flag).'.svg'.'" class="azrcrv-f" alt="'.esc_html($country_name).'">'.__('[Unknown Flag]', 'flags').'</object> '.esc_html($flag).' ('.esc_html($country_name).')';
								echo '</div>';
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
	</form>
				
	<div>
		<p>
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
		</p>
	</div>

	<?php
}

/**
 * Save settings.
 *
 * @since 1.6.0
 *
 */
function azrcrv_f_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'flags'));
	}
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-f', 'azrcrv-f-nonce')){
	
		// Retrieve original plugin options array
		$options = get_option('azrcrv-f');
		
		$option_name = 'width';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field(intval($_POST[$option_name]));
		}
		
		$option_name = 'border';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		// Store updated options array to database
		update_option('azrcrv-f', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-f&settings-updated', admin_url('admin.php')));
		exit;
	}
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
					'EU' => 'European Union',
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
					'IT-ABRUZZO' => 'Italy - Abruzzo',
					'IT-APULIA' => 'Italy - Apulia',
					'IT-BASILICATA' => 'Italy - Basilicata',
					'IT-CALABRIA' => 'Italy - Calabria',
					'IT-CAMPANIA' => 'Italy - Campania',
					'IT-EMILIA_ROMAGNA' => 'Italy - Elilia-Romagna',
					'IT-FRIULI_VENEZIAGIULIA' => 'Italy - Friuli-Venezia Giulia',
					'IT-LAZIO' => 'Italy - Lazio',
					'IT-LIGURIA' => 'Italy - Liguria',
					'IT-LONBARDY' => 'Italy - Lombardy',
					'IT-MARCHE' => 'Italy - Marche',
					'IT-MOLISE' => 'Italy - Molise',
					'IT-PIEDMONT' => 'Italy - Piedmont',
					'IT-SARDINIA' => 'Italy - Sardinia',
					'IT-SICILY' => 'Italy - Sicily',
					'IT-TRENTINO_SOUTHTYROL' => 'Italy - Trentino-South Tyrol',
					'IT-TUSCANY' => 'Italy - Tuscany',
					'IT-UMBRIA' => 'Italy - Umbria',
					'IT-VALLEAOSTA' => 'Italy - Aosta Valley',
					'IT-VENETO' => 'Italy - Veneto',
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
					'AN' => 'Netherlands Antilles',
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
					'ES-CT' => 'Spain - Catalonia',
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
					'GB-ENG' => 'UK of GB & NI - England',
					'GB-ENG-NORTHUMBERLAND' => 'UK of GB & NI - England - Northumberland',
					'GB-NIR' => 'UK of GB & NI - Northern ireland',
					'GB-SCT' => 'UK of GB & NI - Scotland',
					'GB-ULSTER' => 'UK of GB & NI - Ulster',
					'GB-WLS' => 'UK of GB & NI - Wales',
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
					'VA' => 'Vatican City',
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
				);
	
	if (isset($countries[strtoupper($country_code)])){
		$country_name = $countries[strtoupper($country_code)];
	}else{
		$country_name = '';
	}
	
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
function azrcrv_f_flag($atts, $content = null){
	
	$options = azrcrv_f_get_option('azrcrv-f');
	
	if (empty($atts)){
		$flag = 'none';
	}else{
		$args = shortcode_atts(array(
			'id' => '',
			'width' => $options['width'].'px',
			'border' => $options['border'],
		), $atts);
		$id = $args['id'];
		$width = $args['width'];
		$border = $args['border'];
		if ($id == ''){
			$attribs = implode('',$atts);
			$flag = trim (trim (trim (trim (trim ($attribs , '=') , '"') , "'") , '&#8217;') , "&#8221;");
			$width = $options['width'].'px';
			$border = $options['border'];
		}else{
			$flag = $id;
		}
	}
	
	if (strtoupper($flag) == 'ENGLAND'){
		$flag = 'gb-eng';
	}elseif (strtoupper($flag) == 'NORTHUMBERLAND'){
		$flag = 'gb-eng-northumberland';
	}elseif (strtoupper($flag) == 'ULSTER'){
		$flag = 'gb-eng-ulster';
	}elseif (strtoupper($flag) == 'ULSTERBANNER'){
		$flag = 'gb-eng-ulster';
	}elseif (strtoupper($flag) == 'SCOTLAND'){
		$flag = 'gb-sct';
	}elseif (strtoupper($flag) == 'WALES'){
		$flag = 'gb-wls';
	}elseif (strtoupper($flag) == 'EUROPEANUNION'){
		$flag = 'gb-eu';
	}elseif (strtoupper($flag) == 'CURACAO'){
		$flag = 'gb-cw';
	}elseif (strtoupper($flag) == 'NORTHERNIRELAND'){
		$flag = 'gb-nir';
	}elseif (strtoupper($flag) == 'CATALONIA'){
		$flag = 'es-ct';
	}
	
	if ($width != ''){
		$width = 'width: '.esc_html($width).'; ';
	}
	if ($border != ''){
		$border = 'border: '.esc_html($border).'; ';
	}
	
	$country_name = azrcrv_f_get_country_name($flag);
	
	$image = '<object style="'.$width.' '.$border.' " type="image/svg+xml" data="'.plugin_dir_url(__FILE__).'assets/images/'.esc_html($flag).'.svg'.'" class="azrcrv-f" alt="'.esc_html($country_name).'">'.__('[Unknown Flag]', 'flags').'</object>';
	
	return $image;
}

?>