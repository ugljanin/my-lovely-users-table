<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.emirugljanin.com
 * @since             1.0.0
 * @package           My_Lovely_Users_Table
 *
 * @wordpress-plugin
 * Plugin Name:       My lovely users table
 * Plugin URI:        my-lovely-users-table
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Emir Ugljanin
 * Author URI:        https://www.emirugljanin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-lovely-users-table
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

//to be able to load namespaces
if(file_exists(dirname(__FILE__).'/vendor/autoload.php'))
{
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

use MLUT\Activate;
use MLUT\Deactivate;
use MLUT\MLUT;
use MLUTPublic\Users;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MY_LOVELY_USERS_TABLE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 */
function activate_my_lovely_users_table()
{
	Activate::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-my-lovely-users-table-deactivator.php
 */
function deactivate_my_lovely_users_table()
{
	Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_my_lovely_users_table');
register_deactivation_hook(__FILE__, 'deactivate_my_lovely_users_table');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_my_lovely_users_table()
{
	$plugin = new MLUT();
	$users= new Users;
	$plugin->run();
}
run_my_lovely_users_table();



