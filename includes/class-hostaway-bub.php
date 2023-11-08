<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://www.buildupbookings.com/
 * @since      1.0.0
 *
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/includes
 * @author     Braudy Pedrosa <braudy@buildupbookings.com>
 */
class Hostaway_Bub {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hostaway_Bub_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'HOSTAWAY_BUB_VERSION' ) ) {
			$this->version = HOSTAWAY_BUB_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'hostaway-bub';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hostaway_Bub_Loader. Orchestrates the hooks of the plugin.
	 * - Hostaway_Bub_i18n. Defines internationalization functionality.
	 * - Hostaway_Bub_Admin. Defines all hooks for the admin area.
	 * - Hostaway_Bub_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		define( 'HOSTAWAY_ACF_PATH', plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/advanced-custom-fields/' );
		define( 'HOSTAWAY_ACF_URL', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/library/advanced-custom-fields/' );

		include_once( HOSTAWAY_ACF_PATH . 'acf.php' );

		add_filter('acf/settings/url', 'plugin_name_settings_url');
		function plugin_name_settings_url( $url ) {
			return HOSTAWAY_ACF_URL;
		}

		add_filter('acf/settings/save_json', 'hostaway_json_save_point');
		function hostaway_json_save_point( $path ) {
			$path = plugin_dir_path( dirname( __FILE__ ) ) . '/acf-json';
			return $path;
		}

		add_filter('acf/settings/load_json', 'hostaway_json_load_point');
		function hostaway_json_load_point( $paths ) {        
			unset($paths[0]);
			$paths[] = plugin_dir_path( dirname( __FILE__ ) ) . '/acf-json';
			return $paths;
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostaway-bub-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostaway-bub-i18n.php';
		require_once plugin_dir_path( dirname (__FILE__ ) ) . 'includes/class-hostaway-api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostaway-bub-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hostaway-bub-public.php';

		$this->loader = new Hostaway_Bub_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hostaway_Bub_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hostaway_Bub_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Hostaway_Bub_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_admin, 'post_type_init' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'admin_menu', $plugin_admin, 'menu_init' );
		$this->loader->add_action( 'admin_post_save_hostaway_settings', $plugin_admin, 'save_menu' );
		$this->loader->add_filter( 'plugin_action_links_hostaway-bub/hostaway-bub.php', $plugin_admin, 'add_plugin_links' );
		
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hostaway_Bub_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hostaway_Bub_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
