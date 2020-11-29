<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Flags
 * Description: Allows flags to be added to posts and pages using a shortcode.
 * Version: 1.9.0
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

// include svg-sanitizer
require_once(dirname(__FILE__).'/libraries/svg-sanitizer/autoload.php');

/**
 * Setup actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('wp_enqueue_scripts', 'azrcrv_f_load_css');
add_action('admin_menu', 'azrcrv_f_create_admin_menu');
add_action('admin_enqueue_scripts', 'azrcrv_f_load_admin_style');
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
	
	$upload_dir = wp_upload_dir();
 
	$defaults = array(
						'width' => '16',
						'border' => '',
						'folder' => trailingslashit($upload_dir['basedir']).'flags/',
						'url' => trailingslashit($upload_dir['baseurl']).'flags/',
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
		
		if(isset($_GET['settings-updated'])){
			echo '<div class="notice notice-success is-dismissible"><p><strong>'.esc_html__('Settings have been saved.', 'flags').'</strong></p></div>';
		}else if(isset($_GET['upload-successful'])){
			echo '<div class="notice notice-success is-dismissible"><p><strong>'.esc_html__('Upload successful.', 'flags').'</strong></p></div>';
		}else if (isset($_GET['invalid-upload-request'])){
			echo '<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('Invaluid upload request; upload failed.', 'flags').'</strong></p></div>';
		}else if (isset($_GET['settings-updated'])){
			echo '<div class="notice notice-error is-dismissible"><p><strong>'.esc_html__('Upload failed.', 'flags').'</strong></p></div>';
		}
		
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
			<?php
			if (isset($saved_options)){
				echo '<a class="nav-tab" data-item=".tabs-3" href="#tabs-3">'.__('Upload Flag', 'flags').'</a>';
			}
			?>
		</h2>

		<div>
			<div class="azrcrv_f_tabs <?php if ($showsettings == false){ echo 'invisible'; } ?> tabs-1">
				<p class="azrcrv_f_horiz">
					<form method="post" action="admin-post.php">
						<input type="hidden" name="action" value="azrcrv_f_save_options" />
						<input name="page_options" type="hidden" value="width,border" />
						<table class="form-table">
						
							<tr><th scope="row"><?php esc_html_e('Default width?', 'flags'); ?></th><td>
								<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Default width', 'flags'); ?></span></legend>
									<label for="width"><input type="number" name="width" class="small-text" value="<?php echo $options['width']; ?>" />px</label>
								</fieldset>
							</td></tr>
						
							<tr><th scope="row"><?php esc_html_e('Default border?', 'flags'); ?></th><td>
								<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Default border', 'flags'); ?></span></legend>
									<label for="border"><input type="text" name="border" class="regular-text" value="<?php echo $options['border']; ?>"/></label>
									<p class="description"><?php esc_html_e('Setting a default border is supported, but not recommended; borders are work better if applied only to those flags with a background matching your site background.', 'flags'); ?></p>
								</fieldset>
							</td></tr>
							
							<tr><th scope="row"><label for="folder"><?php esc_html_e('Custom Flag Folder', 'flags'); ?></label></th><td>
								<input name="folder" type="text" id="folder" value="<?php if (strlen($options['folder']) > 0){ echo stripslashes($options['folder']); } ?>" class="large-text" />
								<p class="description" id="folder-description"><?php esc_html_e('Specify the folder where custom flags will be placed; if the folder does not exist, it will be created with 0755 permissions.', 'flags'); ?></p></td>
							</td></tr>
							
							<tr><th scope="row"><label for="url"><?php esc_html_e('Custom Flag URL', 'flags'); ?></label></th><td>
								<input name="url" type="text" id="url" value="<?php if (strlen($options['url']) > 0){ echo stripslashes($options['url']); } ?>" class="large-text" />
								<p class="description" id="url-description"><?php esc_html_e('Specify the URL for the custom flags folder.', 'flags'); ?></p></td>
							</td></tr>
							
						</table>
		
						<?php wp_nonce_field('azrcrv-f', 'azrcrv-f-nonce'); ?>
						<input type="hidden" name="azrcrv_f_data_update" value="yes" />
						<input type="hidden" name="which_button" value="save_settings" class="short-text" />
						<input type="submit" value="Save Changes" class="button-primary"/>
					</form>
				</p>
			</div>
			
			<div class="azrcrv_f_tabs <?php if ($showsettings == true){ echo 'invisible'; } ?> tabs-2">
				<p class="azrcrv_f_horiz">
				<?php
					$flags = azrcrv_f_get_flags();
					
					foreach ($flags as $flag_id => $flag){	
						
						if ($flag['type'] == 'standard'){
							$folder = plugin_dir_url(__FILE__).'assets/images/';
						}else{
							$folder = $options['url'];
						}
						
						echo '<div style="width: 350px; display: inline-block;">';
							echo '<img style="width: 20px;" src="'.esc_attr($folder).esc_attr($flag_id).'.svg'.'" class="azrcrv-f" alt="'.esc_attr($flag['name']).'" /> '.esc_attr($flag_id).' ('.esc_attr($flag['name']).')';
						echo '</div>';
					}
					?>
				</p>
			</div>
			
			<div class="azrcrv_f_tabs invisible tabs-3">
				<p class="azrcrv_f_horiz">
					<form method="post" action="admin-post.php" enctype="multipart/form-data">
					<input type="hidden" name="action" value="azrcrv_f_save_options" />
						<table class="form-table">
						
							<tr><th scope="row" colspan="2"><?php printf(esc_html__('Upload files must have an extension of %s', 'flags'), '<strong>svg</strong>'); ?></th></tr>
							
							<tr><th scope="row"><?php esc_html_e('Select image to upload:', 'flags'); ?></th><td>
								<input type="file" name="fileToUpload" id="fileToUpload">
							</td></tr>
							
						</table>
						
						<input type="hidden" name="which_button" value="upload_image" class="short-text" />
						<?php wp_nonce_field('azrcrv-f', 'azrcrv-f-nonce'); ?>
						<input type="hidden" name="azrcrv_f_data_update" value="yes" />
						<input type="submit" value="Upload Image" class="button-primary">
						
					</form>
				</p>
			</div>
		</div>
	</div>
	
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
						echo "<a href='https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/' class='azrcrv-plugin-index'>Shortcodes in Comments</a>";
					}
					?>
				</li>
				<li>
					<?php
					if (azrcrv_f_is_plugin_active('azrcrv-shortcodes-in-widgets/azrcrv-shortcodes-in-widgets.php')){
						echo "<a href='admin.php?page=azrcrv-siw' class='azrcrv-plugin-index'>Shortcodes in Widgets</a>";
					}else{
						echo "<a href='https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/' class='azrcrv-plugin-index'>Shortcodes in Widgets</a>";
					}
					?>
				</li>
			</ul>
		</p>
	</div>

	<?php
}

/**
 * Get all flags (both custom and standard).
 *
 * @since 1.9.0
 *
 */
function azrcrv_f_get_flags(){
	
	$options = azrcrv_f_get_option('azrcrv-f');
	
	$dir = $options['folder'];
	$flags = array();
	if (is_dir($dir)){
		if ($directory = opendir($dir)){
			while (($file = readdir($directory)) !== false){
				//echo $file;
				if (substr($file, -3) == 'svg'){
					$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
					$flags[$filewithoutext] = array(
														'type' => 'custom',
														'name' => azrcrv_f_get_country_name($filewithoutext),
													);
				}
			}
			closedir($directory);
		}
	}
	
	$dir = plugin_dir_path(__FILE__).'assets/images';
	if (is_dir($dir)){
		if ($directory = opendir($dir)){
			while (($file = readdir($directory)) !== false){
				//echo $file;
				if (substr($file, -3) == 'svg'){
					$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
					if (!array_key_exists($filewithoutext, $flags)){
						$flags[$filewithoutext] = array(
															'type' => 'standard',
															'name' => azrcrv_f_get_country_name($filewithoutext),
														);
					}
				}
			}
			closedir($directory);
		}
	}
					
	// sort flags by name
	//ksort($flags ,2);
	
	// sort alphabetically by name
	uasort($flags, 'azrcrv_f_sort_flags_by_country_name');
	
	return $flags;
}

/**
 * Sort flags by name.
 *
 * @since 1.9.0
 *
 */
function azrcrv_f_sort_flags_by_country_name($a, $b){

	return strnatcasecmp($a['name'], $b['name']);

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
		
		if ($_POST['which_button'] == 'save_settings'){
			$option_name = 'width';
			if (isset($_POST[$option_name])){
				$options[$option_name] = sanitize_text_field(intval($_POST[$option_name]));
			}
			
			$option_name = 'border';
			if (isset($_POST[$option_name])){
				$options[$option_name] = sanitize_text_field($_POST[$option_name]);
			}
			
			$option_name = 'folder';
			if (isset($_POST[$option_name])){
				$options[$option_name] = sanitize_text_field($_POST[$option_name]);
			}
			if (!file_exists(sanitize_text_field($_POST[$option_name]))){
				mkdir(sanitize_text_field($_POST[$option_name]), 0755, true);
			}
			
			// Store updated options array to database
			update_option('azrcrv-f', $options);
			
			// set response
			$response = 'settings-updated';
		}else if ($_POST['which_button'] == 'upload_image'){
			$target_dir = $options['folder'];
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$valid = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			// check file size
			if ($_FILES["fileToUpload"]["size"] > 500000){
				$valid = 0;
			}

			// only svg allowed
			if ($imageFileType != "svg"){
			  $valid = 0;
			}
			
			// create new sanitizer
            $svg_sanitizer = new enshrined\svgSanitize\Sanitizer();
			
			// load the dirty svg
			$dirtySVG = file_get_contents($target_file);
			
			// pass dirty svg to the sanitizer and get it back clean
			$cleanSVG = $svg_sanitizer->sanitize($dirtySVG);
			if ($cleanSVG == false){
				$valid = 0;
			}
			
			// check if upload valid
			if ($valid == 0){
				$response = "invalid-upload-request";
			}else{
				// upload file
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$response = "upload-successful";
				} else {
					$response = "upload-failed";
				}
			}
		}else{
			wp_die(esc_html__('Invalid action.', 'flags'));
		}
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-f&'.$response, admin_url('admin.php')));
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
					'DE-BADEN_WURTTEMBERG' => 'Germany - Baden-Wuttemberg',
					'DE-BAVARIA_LOZENGE' => 'Germany - Bavaria (Lozanges)',
					'DE-BAVARIA_STRIPE' => 'Germany - Bavara (Stripes)',
					'DE-BERLIN' => 'Germany - Berlin',
					'DE-BRANDENBURG' => 'Germany - Brandenberg',
					'DE-BREMEN' => 'Germany - Bremen',
					'DE-HESSE' => 'Germany - Hesse',
					'DE-LOWERSAXONY' => 'Germany - Lower Saxony',
					'DE-MECKLENBURG_WESTERNPOMERANIA' => 'Germany - Mecklenberg-Western Pomerania',
					'DE-NORTHRHINE_WESTPHALIA' => 'Germany - North Rhine-Westphalia',
					'DE-RHINELAND_PALATINATE' => 'Germany - Rhineland-Palatinate',
					'DE-SAARLAND' => 'Germany - Saarland',
					'DE-SAXONY' => 'Germany - Saxony',
					'DE-SAXONY_ANHALT' => 'Germany - Saxony-Anhalt',
					'DE-SCHLESWIG_HOLSTEIN' => 'Germany - Schleswig-Holstien',
					'DE-THURINGIA' => 'Germany - Thuringia',
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
					'IT-LOMBARDY' => 'Italy - Lombardy',
					'IT-MARCHE' => 'Italy - Marche',
					'IT-MOLISE' => 'Italy - Molise',
					'IT-PIEDMONT' => 'Italy - Piedmont',
					'IT-ROME' => 'Italy - Rome',
					'IT-SARDINIA' => 'Italy - Sardinia',
					'IT-SICILY' => 'Italy - Sicily',
					'IT-TRENTINO_SOUTHTYROL' => 'Italy - Trentino-South Tyrol',
					'IT-TUSCANY' => 'Italy - Tuscany',
					'IT-UMBRIA' => 'Italy - Umbria',
					'IT-AOSTAVALLEY' => 'Italy - Aosta Valley',
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
					'PL-DS' => 'Poland - Lower Silesian Voivodeship',
					'PL-KP' => 'Poland - Kuyavian-Pomeranian Voivodeship',
					'PL-LU' => 'Poland - Lublin Voivodeship',
					'PL-LB' => 'Poland - Lubusz Voivodeship',
					'PL-LD' => 'Poland - Łódź Voivodeship',
					'PL-MA' => 'Poland - Lesser Poland Voivodeship',
					'PL-MZ' => 'Poland - Masovian Voivodeship',
					'PL-OP' => 'Poland - Opole Voivodeship',
					'PL-PK' => 'Poland - Subcarpathian Voivodeship',
					'PL-PD' => 'Poland - Podlaskie Voivodeship',
					'PL-PM' => 'Poland - Pomeranian Voivodeship',
					'PL-SL' => 'Poland - Silesian Voivodeship',
					'PL-SK' => 'Poland - Holy Cross Voivodeship',
					'PL-WN' => 'Poland - Warmian-Masurian Voivodeship',
					'PL-WP' => 'Poland - Greater Poland Voivodeship',
					'PL-ZP' => 'Poland - West Pomeranian Voivodeship',
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
					'GB-ENG-CORNWALL' => 'UK of GB & NI - England - Cornwall',
					'GB-NIR' => 'UK of GB & NI - Northern ireland',
					'GB-SCT' => 'UK of GB & NI - Scotland',
					'GB-SCT-ISLEOFSKYE' => 'UK of GB & NI - Scotland - Isle of Skye',
					'GB-SCT-ORKNEY' => 'UK of GB & NI - Scotland - Orkney',
					'GB-SCT-SHETLAND' => 'UK of GB & NI - Scotland - Shetland',
					'GB-SCT-ROYALBANNER' => 'UK of GB & NI - Scotland - Royal Banner',
					'GB-ULSTER' => 'UK of GB & NI - Ulster',
					'GB-WLS' => 'UK of GB & NI - Wales',
					'GB-CORNWALL' => 'UK of GB & NI - England - Cornwall',
					'UA' => 'Ukraine',
					'US' => 'United States of America',
					'US-AL' => 'USA - Alabama',
					'US-AK' => 'USA - Alaska',
					'US-AZ' => 'USA - Arizona',
					'US-AR' => 'USA - Arkansas',
					'US-CA' => 'USA - California',
					'US-CO' => 'USA - Colorado',
					'US-CT' => 'USA - Connecticut',
					'US-DE' => 'USA - Delaware',
					'US-FL' => 'USA - Florida',
					'US-GA' => 'USA - Georgia',
					'US-HI' => 'USA - Hawaii',
					'US-ID' => 'USA - Idaho',
					'US-IL' => 'USA - Illinois',
					'US-IN' => 'USA - Indiana',
					'US-IA' => 'USA - Iowa',
					'US-KS' => 'USA - Kansas',
					'US-KY' => 'USA - Kentucky',
					'US-LA' => 'USA - Louisiana',
					'US-ME' => 'USA - Maine',
					'US-MD' => 'USA - Maryland',
					'US-MA' => 'USA - Massachusetts',
					'US-MI' => 'USA - Michigan',
					'US-MN' => 'USA - Minnesota',
					'US-MS' => 'USA - Mississippi',
					'US-MO' => 'USA - Missouri',
					'US-MT' => 'USA - Montana',
					'US-NE' => 'USA - Nebraska',
					'US-NV' => 'USA - Nevada',
					'US-NH' => 'USA - New Hampshire',
					'US-NJ' => 'USA - New Jersey',
					'US-NM' => 'USA - New Mexico',
					'US-NY' => 'USA - New York',
					'US-NC' => 'USA - North Carolina',
					'US-ND' => 'USA - North Dakota',
					'US-OH' => 'USA - Ohio',
					'US-OK' => 'USA - Oklahoma',
					'US-OR' => 'USA - Oregon',
					'US-OR-REVERSE' => 'USA - Oregon (Reverse)',
					'US-PA' => 'USA - Pennsylvania',
					'US-RI' => 'USA - Rhode Island',
					'US-SC' => 'USA - South Carolina',
					'US-SD' => 'USA - South Dakota',
					'US-TN' => 'USA - Tennessee',
					'US-TX' => 'USA - Texas',
					'US-UT' => 'USA - Utah',
					'US-VT' => 'USA - Vermont',
					'US-VA' => 'USA - Virginia',
					'US-WA' => 'USA - Washington',
					'US-WV' => 'USA - West Virginia',
					'US-WI' => 'USA - Wisconsin',
					'US-WY' => 'USA - Wyoming',
					'US-AS' => 'USA - American Samoa',
					'US-DC' => 'USA - District of Columbia',
					'US-GU' => 'USA - Guam',
					'US-MP' => 'USA - Northern Mariana Islands',
					'US-PR' => 'USA - Puerto Rico',
					'US-VI' => 'USA - Virgin Islands',
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
					'PIRATE' => 'Skull and Crossbones',
					'PIRATE2' => 'Skull and Cross Cutlasses',
					'CONFEDERATE' => 'Confederate Battle Flag',
					'NAVAJONATION' => 'Navajo Nation',
					'TEXAS' => 'Republic of Texas',
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
	
	if (file_exists(esc_attr($options['folder']).esc_attr($flag).'.svg')){
		$url = esc_attr($options['url']).esc_attr($flag).'.svg';
	}else{
		$url = plugin_dir_url(__FILE__).'assets/images/'.esc_attr($flag).'.svg';
	}
	
	$country_name = azrcrv_f_get_country_name($flag);
	
	$image = '<img style="'.$width.' '.$border.' " src="'.$url.'" class="azrcrv-f" alt="'.esc_attr($country_name).'" title="'.esc_attr($country_name).'" />';
	
	return $image;
}

?>