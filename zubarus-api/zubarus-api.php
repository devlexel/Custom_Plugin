<?php
/**
 * Plugin Name: Zubarus API Integration
 * Plugin URI:  Your Plugin URL
 * Description: Get Zubarus Data to WordPress Users
 * Version:     0.1
 * Author:      Developer
 * Author URI:  Your Author URL
 **/

if (!defined('ABSPATH')) exit;

// Register activation hook
register_activation_hook(__FILE__, 'zubarus_api_integration_activate');

// Register deactivation hook
register_deactivation_hook(__FILE__, 'zubarus_api_integration_deactivate');

// Hook to execute the code when the plugin is activated
function zubarus_api_integration_activate() {
    // Insert DB Tables
    init_db_myplugin();
    // Schedule the cron job
    schedule_memberpress_cron_job();
}

// Hook to execute the code when the plugin is deactivated
function zubarus_api_integration_deactivate() {
    // Remove scheduled cron job
    unschedule_memberpress_cron_job();
}

// Initialize DB Tables
function init_db_myplugin() {
    global $wpdb;
    $customerTable = $wpdb->prefix . 'zubarus';
    if ($wpdb->get_var("SHOW TABLES LIKE '$customerTable'") != $customerTable) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE `$customerTable` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `mem_number` VARCHAR(100) NOT NULL,
            `mem_fname` VARCHAR(100) NOT NULL,
            `mem_lname` VARCHAR(100),
            `mem_email` VARCHAR(100) NOT NULL,
            `mem_pass` VARCHAR(100) NOT NULL,
            `membership` VARCHAR(200) NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Hook to add custom menu page
add_action('admin_menu', 'wpdocs_register_my_custom_menu_page');

function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        'Zubaru API',
        'Zubaru API',
        'manage_options',
        'wp-zubaru',
        'actions_recent_zubaru'
    );
}

// Hook to execute actions_recent_zubaru function
function actions_recent_zubaru() {
    // Token retrieval code (unchanged)
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://zubarus.com/api/v2/access-token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            "userName" => "henrikb@finalizeit.no",
            "password" => "rqQi3aVodr#x4Wr7Taah"
        ]),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    ]);
    $response = curl_exec($curl);
    $result = json_decode($response);

    if ($result && isset($result->status) && $result->status === 'success') {
        $thetoken = 'Authorization: Bearer ' . $result->data->token;

        // Fetch data from the API
        $api_data = fetch_data_from_api($thetoken);

        if (!empty($api_data)) {
            global $wpdb;
            $customerTable = $wpdb->prefix . 'zubarus';

            foreach ($api_data as $api_entry) {
                // Check if the required array keys exist before accessing them
                if (
                    isset($api_entry['mem_number'], $api_entry['mem_fname'], $api_entry['mem_lname'], $api_entry['mem_email'], $api_entry['membership'])
                ) {
                    // Check if the entry with the same mem_number already exists in the database
                    $existing_entry = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM $customerTable WHERE mem_number = %s",
                        $api_entry['mem_number']
                    ));

                    if (
                        $api_entry['membership'] === 'Gratis medlem' ||
                        $api_entry['membership'] === 'Institusjonsmedlem' ||
                        $api_entry['membership'] === 'Skolemedlem +'
                    ) {
                        continue;
                    }

                    $data_to_insert = [
                        'mem_number' => $api_entry['mem_number'],
                        'mem_fname' => $api_entry['mem_fname'],
                        'mem_lname' => $api_entry['mem_lname'],
                        'mem_email' => $api_entry['mem_email'],
                        'mem_pass' => '', // Empty password field as it's not provided in the API
                        'membership' => $api_entry['membership'],
                    ];

                    if (!$existing_entry) {
                        // Insert the data into the database
                        $wpdb->insert($customerTable, $data_to_insert);

                        // Create a WordPress user
                        $user_id = create_wordpress_user($api_entry['mem_email'], '', $api_entry['mem_fname'], $api_entry['mem_lname'], $api_entry['membership']);

                        if ($user_id) {
                            // Send email notification to the imported user
                            send_email_notification($api_entry['mem_email'], 'imported');
                        }
                    } else {
                        // Compare the data with the existing entry
                        if (!isset($existing_entry) && !isset($data_to_insert)) {
                            // Update the data in the database
                            $wpdb->update(
                                $customerTable,
                                $data_to_insert,
                                ['mem_number' => $api_entry['mem_number']]
                            );

                            // Create a WordPress user
                            $user_id = create_wordpress_user($api_entry['mem_email'], '', $api_entry['mem_fname'], $api_entry['mem_lname'], $api_entry['membership']);

                            if ($user_id) {
                                // Send email notification to the updated user
                                send_email_notification($api_entry['mem_email'], 'updated');
                            }
                        }
                    }

                    $user_id = create_wordpress_user($api_entry['mem_email'], '', $api_entry['mem_fname'], $api_entry['mem_lname'], $api_entry['membership']);

                    // Assign the membership to the user
                    assign_membership_to_user($user_id, $api_entry['membership']);
                }
            }
        }
    }
}

function send_email_notification($email, $status) {
    $subject = 'Email Notification';
    $message = 'Email Notification';

    if ($status === 'imported') {
        $subject = 'Welcome to Our Website';
        $message = 'Thank you for joining our website!';
    } elseif ($status === 'updated') {
        $subject = 'Account Update';
        $message = 'Your account information has been updated.';
    }

    if ($subject && $message) {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Send email
        $result = wp_mail($email, $subject, $message, $headers);

        if ($result) {
            // Email sent successfully
            return true;
        } else {
            // Email sending failed
            return false;
        }
    }

    // Return false if subject or message is empty
    return false;
}

// Function to create a WordPress user
function create_wordpress_user($email, $password, $first_name, $last_name, $membership) {
    $user_data = array(
        'user_login'    => $email,
        'user_pass'     => $password,
        'user_email'    => $email,
        'first_name'    => $first_name,
        'last_name'     => $last_name,
        'role'          => 'subscriber', // Change the role as needed
        'membership'    =>  $membership,
    );

    $user_id = wp_insert_user($user_data);

    return $user_id;
}

// 'fetch_data_from_api' function to fetch data from the API
function fetch_data_from_api($token) {
    // Construct the API endpoint URL with the current date and additional parameters
    $api_url = 'https://zubarus.com/api/v2/members?dateRegistered=2023-10-03';

    // Initialize cURL session
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET", // Use GET to retrieve data
        CURLOPT_HTTPHEADER => [$token, "Content-Type: application/json"],
    ]);

    // Execute the cURL request
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Close the cURL session
    curl_close($curl);

    // Handle the API response
    if ($err) {
        return [];
    } else {
        $result = json_decode($response, true);

        if ($result && isset($result['status']) && $result['status'] === 'success') {
            // Extract member data with registered date and status
            $members = $result['data']['members'];

            $detailedMembers = [];

            foreach ($members as $member) {
                $memNumber = $member['memberNumber'];
                $detailedMemberInfo = fetch_data_from_api_with_memnumber($token, $memNumber);

                if ($detailedMemberInfo && isset($detailedMemberInfo['status']) && $detailedMemberInfo['status'] === 'success') {
                    $detailedMembers[] = $detailedMemberInfo['data'];
                }
            }
            return $detailedMembers; // Return the array of detailed members
        } else {
            return [];
        }
    }
}

// 'fetch_data_from_api_with_memnumber' function to fetch data for a specific member number
function fetch_data_from_api_with_memnumber($token, $memNumber) {
    $api_url = 'https://zubarus.com/api/v2/members/' . $memNumber . '?dateRegistered=today&include=firstName,lastName,email,membership';

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [$token, "Content-Type: application/json"],
    ]);

    $response = curl_exec($curl);
    // echo '<pre>';
    // print_r($response);
    // die;
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code
    curl_close($curl);

    if ($http_status == 404) {
        // Handle 404 Not Found error
        return ['status' => 'error', 'message' => 'Member not found'];
    }

    if ($response === false) {
        // Handle cURL error
        return ['status' => 'error', 'message' => 'cURL error: ' . curl_error($curl)];
    }

    $result = json_decode($response, true);

    if ($result && isset($result['status']) && $result['status'] === 'success') {
        $data = $result['data'];

        // Check if 'memberNumber' key exists in the 'data' array
        if (isset($data['memberNumber'])) {
            // Ensure the required keys exist in the data array
            if (
                isset($data['firstName']) &&
                isset($data['lastName']) &&
                isset($data['email']) &&
                isset($data['membership'])
            ) {
                // Extract the required fields
                $extractedData = [
                    'mem_number' => $data['memberNumber'],
                    'mem_fname' => $data['firstName'],
                    'mem_lname' => $data['lastName'],
                    'mem_email' => $data['email'],
                    'membership' => $data['membership']['name'],
                ];
                echo '<pre>';
                print_r( $extractedData);
                die;

                return [
                    'status' => 'success',
                    'data' => $extractedData,
                ];
            } else {
                return ['status' => 'error', 'message' => 'Required keys not found in the API response'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Key "memberNumber" not found in the API response'];
        }
    } else {
        return ['status' => 'error', 'message' => 'API request failed'];
    }
}

// Function to assign a membership to a user
function assign_membership_to_user($user_id, $membership) {
    // Example: Assuming user memberships are stored as user meta
    $current_memberships = get_user_meta($user_id, 'user_memberships', true) ?: [];

    global $wpdb;
    $customerTable = $wpdb->prefix . 'zubarus';
    $membership_data = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $customerTable WHERE membership = %s",
        $membership
    ));

    // echo '<pre>';
    // print_r($membership_data);
    // die;


    // Check if the membership already exists
    $membership_exists = false;
    foreach ($current_memberships as $existing_membership) {
        if ($existing_membership['name'] === $membership_data->membership) {
            $membership_exists = true;
            break;
        }
    }

    if (!$membership_exists) {
        // Add the new membership to the user
        $current_memberships[] = $membership_data;

        // Save the updated memberships for the user
        update_user_meta($user_id, 'user_memberships', $current_memberships);
    }
}

add_action('mepr_member_data_updater_worker', 'actions_recent_zubaru');
