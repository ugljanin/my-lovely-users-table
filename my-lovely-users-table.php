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

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MY_LOVELY_USERS_TABLE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-my-lovely-users-table-activator.php
 */
function activate_my_lovely_users_table()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-my-lovely-users-table-activator.php';
	My_Lovely_Users_Table_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-my-lovely-users-table-deactivator.php
 */
function deactivate_my_lovely_users_table()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-my-lovely-users-table-deactivator.php';
	My_Lovely_Users_Table_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_my_lovely_users_table');
register_deactivation_hook(__FILE__, 'deactivate_my_lovely_users_table');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-my-lovely-users-table.php';

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

	$plugin = new My_Lovely_Users_Table();
	$plugin->run();
}
run_my_lovely_users_table();

function load_user_details_ajax()
{
	header('Content-type: application/json');
	$nonce = $_POST['nonce'];

	//check nonce
	if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
		$status = array(
			'type' => 'danger',
			'message' => 'Busted'
		);
		echo json_encode($status);
		wp_die();
	}
	// sanitize input
	$user_details = intval($_POST['user_details']);
	$api_url = 'https://jsonplaceholder.typicode.com/users?id=' . $user_details;

	// $resultuser = file_get_contents($url);
	$body = get_transient('my_lovely_users_details_api_request_'. $user_details);
	$caching_time = get_option( 'my_lovely_users_table_caching_time' ,1);

	if (false === $body) {
		$response = wp_remote_get($api_url);
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			echo "Something went wrong: $error_message";
		} else {
			if (200 == wp_remote_retrieve_response_code($response)) {
				$body     = wp_remote_retrieve_body($response);
				set_transient('my_lovely_users_details_api_request_'. $user_details, $body, intval($caching_time) * MINUTE_IN_SECONDS);
			} else {
	?>
				<h1>An error occured while fetching users, please try again</h1>
	<?php
			}
		}
	}
	$array = json_decode($body, true);
	if(is_array($array)) {
		$status = array(
			'type' => 'success',
			'message' => 'Success',
			'content' => $array
		);
		//action that fires on user clicked, could be used for loging purposes
		do_action('my-lovely-users-table-user-clicked',date('Y-m-d H:i:s'),$user_details);
	} else {
		$status = array(
			'type' => 'danger',
			'message' => 'There is a problem with the data source, please contact the administrator'
		);
		//action that fires on user error occurred
		do_action('my-lovely-users-table-user-error',date('Y-m-d H:i:s'),$user_details);
	}
	echo json_encode($status);
	wp_die();
}

add_action('wp_ajax_nopriv_load_user_details_ajax', 'load_user_details_ajax');
add_action('wp_ajax_load_user_details_ajax', 'load_user_details_ajax');
