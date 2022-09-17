<?php
/*
	setup
*/

/**
 * Declare the Namespace.
 *
 * @since 1.0.0
 */
namespace azurecurve\Flags;

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 */

// add actions.
add_action( 'admin_menu', __NAMESPACE__ . '\\create_admin_menu' );
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_languages' );
add_action( 'admin_init', __NAMESPACE__ . '\\register_admin_styles' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_styles' );
add_action( 'admin_init', __NAMESPACE__ . '\\register_admin_scripts' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_scripts' );
add_action( 'init', __NAMESPACE__ . '\\register_frontend_styles' );
add_filter( 'the_posts', __NAMESPACE__ . '\\check_for_shortcode' );
add_action( 'admin_post_' . PLUGIN_UNDERSCORE . '_save_options', __NAMESPACE__ . '\\save_options' );

// add filters.
add_filter( 'plugin_action_links', __NAMESPACE__ . '\\add_plugin_action_link', 10, 2 );

$plugin_slug_for_um = plugin_basename( trim( PLUGIN_FILE ) );
add_filter( 'codepotent_update_manager_' . $plugin_slug_for_um . '_image_path', __NAMESPACE__ . '\\custom_image_path' );
add_filter( 'codepotent_update_manager_' . $plugin_slug_for_um . '_image_url', __NAMESPACE__ . '\\custom_image_url' );

// add shortcodes.
add_shortcode( 'flag', __NAMESPACE__ . '\\shortcode_display_flag' );
add_shortcode( 'FLAG', __NAMESPACE__ . '\\shortcode_display_flag' );
