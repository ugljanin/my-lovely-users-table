<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.emirugljanin.com
 * @since      1.0.0
 *
 * @package    My_Lovely_Users_Table
 * @subpackage My_Lovely_Users_Table/public/partials
 */
get_header();
$api_url = 'https://jsonplaceholder.typicode.com/users';

//if transient exists load the list from it
$body = get_transient('my_lovely_users_table_api_request');
$caching_time = get_option( 'my_lovely_users_table_caching_time' ,1);

if (false === $body) {
    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    } else {
        if (200 == wp_remote_retrieve_response_code($response)) {
            //if transient does not exist, load from url
            $body = wp_remote_retrieve_body($response);
            set_transient('my_lovely_users_table_api_request', $body, intval($caching_time) * MINUTE_IN_SECONDS);
        } else {
?>
            <h1>An error occured while fetching users, please try again</h1>
<?php
        }
    }
}

$array = json_decode($body, true);
if(is_array($array))
{
    ?>
    <div id="my-lovely-users-list">
        <div class="plugin-container">
            <div class="users-list">
                <h1>My lovely users list</h1>
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Name</td>
                            <td>Username</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($array as $user) {
                            echo '<tr data-id="' . $user['id'] . '">';
                            echo '<td>';
                            echo '<a href="#">' . $user['id'] . '</a>';
                            echo '</td>';
                            echo '<td>';
                            echo '<a href="#">' . $user['name'] . '</a>';
                            echo '</td>';
                            echo '<td>';
                            echo '<a href="#">' . $user['username'] . '</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
    
            <div class="user-details">
                <div class="title">Selected user details</div>
                <div id="status">
                    Please click on a user from the list to obtain user's details.
                </div>
                <div id="my-lovely-user-details">
                    <ul>
                        <li>
                            <strong>ID:</strong> <span id="user-id"></span>
                        </li>
                        <li>
                            <strong>Name:</strong>
                            <span id="user-name"></span>
                        </li>
                        <li>
                            <strong>Username:</strong>
                            <span id="user-username"></span>
                        </li>
                        <li>
                            <strong>Email:</strong>
                            <span id="user-email"></span>
                        </li>
                        <li>
                            <strong>Street:</strong>
                            <span id="user-street"></span>
                        </li>
                        <li>
                            <strong>Suite:</strong>
                            <span id="user-suite"></span>
                        </li>
                        <li>
                            <strong>City:</strong>
                            <span id="user-city"></span>
                        </li>
                        <li>
                            <strong>Phone:</strong>
                            <span id="user-phone"></span>
                        </li>
                        <li>
                            <strong>Company:</strong>
                            <span id="user-company"></span>
                        </li>
                    </ul>
                </div>
    
            </div>
        </div>
    </div>
    <?php
}


get_footer();
?>