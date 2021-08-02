<?php

namespace MLUTPublic;

class Users
{
    public function __construct()
    {
        add_action('wp_ajax_nopriv_load_user_details_ajax',  [&$this, 'load_user_details_ajax']);
        add_action('wp_ajax_load_user_details_ajax', [&$this, 'load_user_details_ajax']);
    }
    public function load_user_details_ajax()
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
        $body = get_transient('my_lovely_users_details_api_request_' . $user_details);
        $caching_time = get_option('my_lovely_users_table_caching_time', 1);

        if (false === $body) {
            $response = wp_remote_get($api_url);
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            } else {
                if (200 == wp_remote_retrieve_response_code($response)) {
                    $body     = wp_remote_retrieve_body($response);
                    set_transient('my_lovely_users_details_api_request_' . $user_details, $body, intval($caching_time) * MINUTE_IN_SECONDS);
                } else {
?>
                    <h1>An error occured while fetching users, please try again</h1>
<?php
                }
            }
        }
        $array = json_decode($body, true);
        if (is_array($array)) {
            $status = array(
                'type' => 'success',
                'message' => 'Success',
                'content' => $array
            );
            //action that fires on user clicked, could be used for loging purposes
            do_action('my-lovely-users-table-user-clicked', date('Y-m-d H:i:s'), $user_details);
        } else {
            $status = array(
                'type' => 'danger',
                'message' => 'There is a problem with the data source, please contact the administrator'
            );
            //action that fires on user error occurred
            do_action('my-lovely-users-table-user-error', date('Y-m-d H:i:s'), $user_details);
        }
        echo json_encode($status);
        wp_die();
    }
}
