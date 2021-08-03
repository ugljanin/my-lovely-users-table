<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.emirugljanin.com
 * @since      1.0.0
 *
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/includes
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
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/includes
 * @author     Emir Ugljanin <emirugljanin@gmail.com>
 */
namespace MLUT;

use MLUT\AdminArea;
use MLUT\PublicArea;

class MLUT {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'MY_LOVELY_USERS_TABLE_VERSION' ) ) {
			$this->version = MY_LOVELY_USERS_TABLE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'my-lovely-users-table';

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
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - i18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new \MLUT\Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the My_Lovely_Users_Table_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new \MLUT\i18n();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	public function init() {
		/* ... */
	  }
	public function addHooks() {
		// add_action('init', [ $this, 'init' ], 20);

		add_filter('the_title', function($title) {
			return $title;
		 }, 99);
	 }
	 public function fireHooks() {

		do_action('my_admin_action', $this);
 
	 }
	 public $post;

    function setPost() {

        global $post;
        $this->post = $post;

        do_action('my_class_set_post', $this);

        return $post;
    }

	private function define_admin_hooks() {

		$plugin_admin = new \MLUT\AdminArea\My_Lovely_Users_Table_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Lets add a hook to setup our rewrites for the custom plugin URLs
		$this->loader->add_action( 'init', $plugin_admin, 'setup_rewrites' );
	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function define_public_hooks() {

		$plugin_public = new \MLUT\PublicArea\My_Lovely_Users_Table_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Setup action and filter for dynamic pages
		$this->loader->add_filter( 'query_vars', $plugin_public, 'register_query_values' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'register_custom_template_redirect' );

	}

	public function testCall()
	{
		return 1;
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
	 * @return    Loader    Orchestrates the hooks of the plugin.
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
