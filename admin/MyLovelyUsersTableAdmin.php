<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.emirugljanin.com
 * @since      1.0.0
 *
 * @package    MyLovelyUsersTableAdmin
 * @subpackage MyLovelyUsersTableAdmin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MyLovelyUsersTableAdmin
 * @subpackage MyLovelyUsersTableAdmin/admin
 * @author     Emir Ugljanin <emirugljanin@gmail.com>
 */

namespace MLUT\AdminArea;

class MyLovelyUsersTableAdmin{

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add an action to setup the admin menu in the left nav
		add_action( 'admin_menu', array($this, 'add_admin_menu') );
		// Add some actions to setup the settings we want on the wp admin page
		add_action('admin_init', array($this, 'setup_sections'));
		add_action('admin_init', array($this, 'setup_fields'));
	}

		/**
	 * Add the menu items to the admin menu
	 *
	 * @since    1.0.0
	 */

	public function add_admin_menu() {

		// Main Menu Item
	  	add_menu_page(
			'My Lovely Plugin',
			'My Lovely Plugin',
			'manage_options',
			'my-lovely-users-table',
			array($this, 'display_my_lovely_users_table_admin_page'),
			'dashicons-store',
			1);

		// Sub Menu Item One
		add_submenu_page(
			'my-lovely-users-table',
			'Settings',
			'Settings',
			'manage_options',
			'my-lovely-users-table',
			array($this, 'display_my_lovely_users_table_admin_page')
		);

	}

	/**
	 * Callback function for displaying the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_my_lovely_users_table_admin_page(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/my-lovely-users-table-admin-display.php';
	}


	/**
	 * Setup sections in the settings
	 *
	 * @since    1.0.0
	 */
	public function setup_sections() {
		add_settings_section( 'settings', 'Settings', array($this, 'section_callback'), 'wordpress-my-lovely-users-table-options' );
	}

	/**
	 * Callback for each section
	 *
	 * @since    1.0.0
	 */
	public function section_callback( $arguments ) {
		switch( $arguments['id'] ){
			case 'settings':
				echo '<p>You can change the configuration of this plugin by changing options below.</p>';
				break;
		}
	}

	/**
	 * Field Configuration, each item in this array is one field/setting we want to capture
	 *
	 * @since    1.0.0
	 */
	public function setup_fields() {
		$fields = array(
			array(
				'uid' => 'my_lovely_users_table_endpoint',
				'label' => 'Endpoint',
				'section' => 'settings',
				'type' => 'text',
				'placeholder' => 'Add the slug that will be used to access the users page',
				'helper' => '',
				'supplemental' => 'Accepting lowercase letters and dashes.',
				'default' => "my-lovely-users-table",
			),
			array(
				'uid' => 'my_lovely_users_table_caching_time',
				'label' => 'Caching time',
				'section' => 'settings',
				'type' => 'select',
				'supplemental' => 'Number in minutes.',
				'options' => array(
					'option1' => '1',
					'option2' => '5',
					'option3' => '10',
					'option4' => '60',
					'option5' => '120',
				),
				'default' => array()
			)
		);
		// Lets go through each field in the array and set it up
		foreach( $fields as $field ){
			add_settings_field( $field['uid'], $field['label'], array($this, 'field_callback'), 'wordpress-my-lovely-users-table-options', $field['section'], $field );
			register_setting( 'wordpress-my-lovely-users-table-options', $field['uid'] );
		}
	}

	/**
	 * This handles all types of fields for the settings
	 *
	 * @since    1.0.0
	 */
	public function field_callback($arguments) {
		// Set our $value to that of whats in the DB
		$value = get_option( $arguments['uid'] );
		// Only set it to default if we get no value from the DB and a default for the field has been set
		if(!$value) {
			$value = $arguments['default'];
		}
		// Lets do some setup based ont he type of element we are trying to display.
		switch( $arguments['type'] ){
			case 'text':
			case 'password':
			case 'number':
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
				break;
			case 'textarea':
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
				break;
			case 'select':
			case 'multiselect':
				if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
					$attributes = '';
					$options_markup = '';
					foreach( $arguments['options'] as $key => $label ){
						$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, true ) ], $key, false ), $label );
					}
					if( $arguments['type'] === 'multiselect' ){
						$attributes = ' multiple="multiple" ';
					}
					printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup );
				}
				break;
			case 'radio':
			case 'checkbox':
				if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
					$options_markup = '';
					$iterator = 0;
					foreach( $arguments['options'] as $key => $label ){
						$iterator++;
						$is_checked = '';
						// This case handles if there is only one checkbox and we don't have anything saved yet.
						if(isset($value[ array_search( $key, $value, true ) ])) {
							$is_checked = checked( $value[ array_search( $key, $value, true ) ], $key, false );
						} else {
							$is_checked = "";
						}
						// Lets build out the checkbox
						$options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, $is_checked, $label, $iterator );
					}
					printf( '<fieldset>%s</fieldset>', $options_markup );
				}
				break;
			case 'image':
				// Some code borrowed from: https://mycyberuniverse.com/integration-wordpress-media-uploader-plugin-options-page.html
				$options_markup = '';
				$image = [];
				$image['id'] = '';
				$image['src'] = '';

				// Setting the width and height of the header iamge here
				$width = '1800';
				$height = '1068';

				// Lets get the image src
				$image_attributes = wp_get_attachment_image_src( $value, array( $width, $height ) );
				// Lets check if we have a valid image
				if ( !empty( $image_attributes ) ) {
					// We have a valid option saved
					$image['id'] = $value;
					$image['src'] = $image_attributes[0];
				} else {
					// Default
					$image['id'] = '';
					$image['src'] = $value;
				}

				// Lets build our html for the image upload option
				$options_markup .= '
				<img data-src="' . $image['src'] . '" src="' . $image['src'] . '" width="180px" height="107px" />
				<div>
					<input type="hidden" name="' . $arguments['uid'] . '" id="' . $arguments['uid'] . '" value="' . $image['id'] . '" />
					<button type="submit" class="upload_image_button button">Upload</button>
					<button type="submit" class="remove_image_button button">&times; Delete</button>
				</div>';
				printf('<div class="upload">%s</div>',$options_markup);
				break;
		}
		// If there is helper text, lets show it.
		if( array_key_exists('helper',$arguments) && $helper = $arguments['helper']) {
			printf( '<span class="helper"> %s</span>', $helper );
		}
		// If there is supplemental text lets show it.
		if( array_key_exists('supplemental',$arguments) && $supplemental = $arguments['supplemental'] ){
			printf( '<p class="description">%s</p>', $supplemental );
		}
	}

	/**
	 * Admin Notice
	 * 
	 * This displays the notice in the admin page for the user
	 *
	 * @since    1.0.0
	 */
	public function admin_notice($message) { ?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo($message); ?></p>
		</div><?php
	}

	/**
	 * This handles setting up the rewrite rules for Past Sales
	 *
	 * @since    1.0.0
	 */
	public function setup_rewrites() {
		//
		// $url_slug = 'my-lovely-users-table';
		$url_slug = get_option( 'my_lovely_users_table_endpoint' ,'my-lovely-users-table');
		// Lets setup our rewrite rules
		add_rewrite_rule( $url_slug . '/?$', 'index.php?my_lovely_users_table=index', 'top' );
	
		// Lets flush rewrite rules on activation
		flush_rewrite_rules();
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-lovely-users-table-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-lovely-users-table-admin.js', array( 'jquery' ), $this->version, false );

	}

}
