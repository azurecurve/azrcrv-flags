<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Flags
 * Description: Allows flags to be added to posts and pages using a shortcode.
 * Version: 1.0.1
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

// add filters
add_filter('plugin_action_links', 'azrcrv_f_add_plugin_action_link', 10, 2);

// add shortcodes
add_shortcode('flag', 'azrcrv_f_flag');
add_shortcode('FLAG', 'azrcrv_f_flag');

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
		<h2><?php echo esc_html(get_admin_page_title()); ?></h2>

		<label for="explanation">
			<p><?php esc_html_e('Flags allows a 16x16 flag to be displayed in a post of page using a [flag] shortcode.', 'flags'); ?></p>
			<p><?php esc_html_e('Format of shortcode is [flag=gb] to display the flag of the United Kingdom of Great Britain and Northern Ireland; 247 flags are included.', 'flags'); ?></p>
			<p><?php esc_html_e('Defintion of flags can be found at Wikipedia page ISO 3166-1 alpha-2: ', 'flags'); ?><a href='https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2'>https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2</a></p>
		</label>
		
		<p>
		<?php esc_html_e('Available flags are:', 'icons');
			
			$dir = plugin_dir_path(__FILE__).'/images';
			if (is_dir($dir)){
				if ($directory = opendir($dir)){
					while (($file = readdir($directory)) !== false){
						if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
							$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
							echo "<div style='width: 180px; display: inline-block;'><img src='";
							echo plugin_dir_url(__FILE__)."images/".esc_html($filewithoutext).".png;' alt='".esc_html($filewithoutext)."' />&nbsp;<em>".esc_html($filewithoutext)."</em></div>";
						}
					}
					closedir($directory);
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
	return "<img class='azrcrv-f' src='".plugin_dir_url(__FILE__)."images/".esc_html($flag).".png' alt= '".esc_html($flag)."' />";
}

?>