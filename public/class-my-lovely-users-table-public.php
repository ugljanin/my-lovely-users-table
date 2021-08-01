<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.emirugljanin.com
 * @since      1.0.0
 *
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/public
 * @author     Emir Ugljanin <emirugljanin@gmail.com>
 */
class My_Lovely_Users_Table_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
     * Custom Plugin Rewrites
     *
     * @since    1.0.0
     */
    public function register_custom_plugin_redirect()
    {
        // Show all users
        if (get_query_var('my_lovely_users_table')) {
            add_filter('template_include', function () {
				$plugin_path='my-lovely-users-table/';
				$file_name='my-lovely-users-table-public-display.php';
				//search for template override in themes directory
				if ( locate_template( $plugin_path.$file_name ) ) {
					$template = locate_template( $plugin_path.$file_name );
				} else {
					// Template not found in theme's folder, use plugin's template as a fallback
					$template = plugin_dir_path(__FILE__) . 'partials/'.$file_name;
				}
				return $template;
            });
		}
		
	}

	/**
     * Register Query Values for Custom Plugin
     *
     * Filters that are needed for rendering the custom plugin page
     *
     * @since    1.0.0
     */
    public function register_query_values($vars)
    {
		$vars[] = 'my_lovely_users_table';

        return $vars;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in My_Lovely_Users_Table_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Lovely_Users_Table_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-lovely-users-table-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in My_Lovely_Users_Table_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Lovely_Users_Table_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-lovely-users-table-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'the_ajax_script', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce')
		));
	}

}
