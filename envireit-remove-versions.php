<?php

/*
Plugin Name: Envire.it Remove Versions Plugin
Plugin URI: https://www.envire.it
Description: Envire.it Remove Versions Plugin
Author: Sergio De Falco
Version: 1.0
Author URI: https://www.envire.it/
Text Domain: envireit-remove-versions
Domain Path: /languages/
License: GPL v3
*/

register_activation_hook( __FILE__ , array( 'Envireit_Remove_Versions_Loader', 'activate_plugin' ) );		// Registering plugin activation hook.
register_deactivation_hook( __FILE__, array( 'Envireit_Remove_Versions_Loader', 'deactivate_plugin' ) );	// Registering plugin deactivation hook.

/**
 * Load the Remove Versions Plugin
 *
 * @since 1.0
 */
class Envireit_Remove_Versions_Loader {
	/**
	 * Uniquely identify plugin version
	 * Bust caches based on this value
	 *
	 * @since 1.0
	 * @var string
	 */
	const VERSION = '1.0';

	/**
	 * Define Simple CDN default settings.
	 *
	 * @since 1.0
	 *
	 * @var array {}
	 */
	public $simple_cdn_settings_defaults = array(
		'version' 	=> '1.0'
	);

	/**
	 * Let's get it started
	 *
	 * @since 1.0
	 */
	public function __construct() {
		// Load the textdomain for translations
		load_plugin_textdomain( 'envireit-remove-versions', true, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Check if is admin and intialize the administration.
		if ( ! is_admin() ) {
			add_action( 'wp', array( &$this, 'public_init' ) );
		}
	}

	/**
	 * Handles actions for the plugin activation
	 *
	 * @since 1.0
	 */
	static function activate_plugin() {
	}

	/**
	 * Handles actions for the plugin deactivation
	 *
	 * @since 1.0
	 */
	static function deactivate_plugin() {
	}

	/**
	 * Intialize the public.
	 *
	 * @since 1.0
	 */
	public function public_init() {
		add_filter( 'style_loader_src',			array( &$this, 'remove_version_querystring' ),	1000 );
		add_filter( 'script_loader_src',		array( &$this, 'remove_version_querystring' ),	1000 );
		add_filter( 'the_generator',			array( &$this, 'remove_wp_version' ),			99);
	}

	/**
	 * Remove Query var version from enqueued styles and scripts
	 *
	 * @since 1.0
	 */
	public function remove_version_querystring( $src ) {
	    if( strpos( $src, '?ver=' ) )
	        $src = remove_query_arg( 'ver', $src );
	    return $src;
	}

	/**
	 * Remove WordPress version number from all different areas
	 *
	 * @since 1.0
	 */
	public function remove_wp_version() {
		return '';
	}
}

/**
 * Load plugin function during the WordPress init action
 *
 * @since 1.0
 */
function remove_versions_plugin_loader_init() {
	global $remove_versions_plugin_loader;

	$remove_versions_plugin_loader = new Envireit_Remove_Versions_Loader();
}
add_action( 'init', 'remove_versions_plugin_loader_init', 0 ); // load before widgets_init at 1
