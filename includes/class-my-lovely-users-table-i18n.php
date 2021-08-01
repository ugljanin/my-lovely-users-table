<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.emirugljanin.com
 * @since      1.0.0
 *
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/includes
 * @author     Emir Ugljanin <emirugljanin@gmail.com>
 */
class My_Lovely_Users_Table_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'my-lovely-users-table',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
