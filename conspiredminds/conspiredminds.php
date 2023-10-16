<?php
/**
    * @package  PBSPlugin
    */
/*
 * Plugin Name:       PBS
 * Plugin URI:        #
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            PBS
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        #
 * Text Domain:       pbs
 */

if ( !defined( 'ABSPATH' ) ) exit;

// Act on plugin activation
register_activation_hook( __FILE__, "activate_myplugin" );

// Act on plugin de-activation
register_deactivation_hook( __FILE__, "deactivate_myplugin" );

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Activate Plugin
function activate_myplugin() {
         
    dob_violation();
    dob_complaints();
    ecb_violations();
    hpd_complaints();
    hpd_violations();
    landmark_complaints();
    landmark_violations();
    oath_hearings();
    service_requests();
    
}

// De-activate Plugin
function deactivate_myplugin() {
    
    // Do something on Deactivation. 
}

// Initialize DB Tables
function dashboard() {

   
}

register_activation_hook( __FILE__, 'dashboard' );


// Initialize DB Tables
function dob_violation() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable = $table_prefix . 'dob_violation';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable'" ) != $customerTable ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `bin` varchar(500) NOT NULL, ";
        $sql .= " `isn_dob_bis_viol` varchar(500) NOT NULL, ";
        $sql .= " `boro` varchar(500), ";
        $sql .= " `block` varchar(500) NOT NULL, ";
        $sql .= " `lot` varchar(500), ";
        $sql .= " `issue_date` varchar(500), ";
        $sql .= " `violation_type_code` varchar(150) NOT NULL, ";
        $sql .= " `violation_number` varchar(500), ";
        $sql .= " `house_number` varchar(500), ";
        $sql .= " `street` varchar(500) NOT NULL, ";
        $sql .= " `disposition_date` varchar(500) NOT NULL, ";
        $sql .= " `device_number` varchar(500) NOT NULL, ";
        $sql .= " `description` varchar(500) DEFAULT NULL, ";
        $sql .= " `disposition_comments` varchar(500) NOT NULL, ";
        $sql .= " `number` varchar(500) NOT NULL, ";
        $sql .= " `violation_category` varchar(500) NOT NULL, ";
        $sql .= " `violation_type` varchar(500) NOT NULL, ";
        $sql .= " `status` varchar(1) NOT NULL, ";
        $sql .= " PRIMARY KEY (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'dob_violation' );

function insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'dob_violation';    

    // retrive data from api 
    $db = dob_violation_restapi_callback();   
    
    // retrive exist data from database 
    $coldata = $wpdb->get_results( "SELECT * FROM $table_name");    

    $arrExistData = [];
    foreach($coldata as $objTempExistData){
        $arrExistData[$objTempExistData->isn_dob_bis_viol] = $objTempExistData;
    }

    $arrApiData = [];

    ### UPDATE API DATA IN DATABASE OR INSERT NEW DATASET - START ####
    if (  !empty($db) ) {

        foreach ($db as $value) {

            // if ( $value->bin != '1009713' ) continue; 

            // refresh data purpose 
            $arrApiData[$value->isn_dob_bis_viol] = $value;


            if ( isset($arrExistData[$value->isn_dob_bis_viol]) ) {

                // var_dump("case 111");

                // update logic
                $result = $wpdb->update( 
                    $table_name, 
                    [
                        'bin' => $value->bin, 
                        'isn_dob_bis_viol' => $value->isn_dob_bis_viol, 
                        'boro' => $value->boro,
                        'block' => $value->block, 
                        'lot' => $value->lot, 
                        'issue_date' => $value->issue_date, 
                        'violation_type_code' => $value->violation_type_code, 
                        'violation_number' => $value->violation_number, 
                        'house_number' => $value->house_number, 
                        'street' => $value->street, 
                        'disposition_date' => $value->disposition_date,
                        'device_number' => $value->device_number,
                        'description' => $value->description, 
                        'disposition_comments' => $value->disposition_comments, 
                        'number' => $value->number,
                        'violation_category' => $value->violation_category, 
                        'violation_type' => $value->violation_type, 
                        'status' => 1, 
                    ],
                    [
                        'isn_dob_bis_viol' => $value->isn_dob_bis_viol,
                    ]
                );  

                // var_dump($result);
                // die();
            }
            else {
                // insert logic

                // var_dump("case 222");

                $arrTempData = [
                    'bin' => $value->bin, 
                    'isn_dob_bis_viol' => $value->isn_dob_bis_viol, 
                    'boro' => $value->boro,
                    'block' => $value->block, 
                    'lot' => $value->lot, 
                    'issue_date' => $value->issue_date, 
                    'violation_type_code' => $value->violation_type_code, 
                    'violation_number' => $value->violation_number, 
                    'house_number' => $value->house_number, 
                    'street' => $value->street, 
                    'disposition_date' => $value->disposition_date,
                    'device_number' => $value->device_number,
                    'description' => $value->description, 
                    'disposition_comments' => $value->disposition_comments, 
                    'number' => $value->number,
                    'violation_category' => $value->violation_category, 
                    'violation_type' => $value->violation_type, 
                    'status' => 1, 
                ];

                $result = $wpdb->insert( 
                    $table_name, 
                    $arrTempData
                );  


                // if (1) {
                //     print("<pre>wpdb " );   
                //     print_r($wpdb);
                //     print("<pre>arrTempData " );   
                //     print_r($arrTempData);
                //     print("<pre>result " );   
                //     print_r($result);
                //     die();
                // }
                
                // var_dump($result);
                // die();

            }
        }
    }
    ### UPDATE API DATA IN DATABASE OR INSERT NEW DATASET - END ####


    # refrest database exist data 

    ##### CHECK EXIST DATA NEED TO DISABLED OR NOT - START #####
    foreach($arrExistData as $value) {

        if ( !isset($arrApiData[$value->isn_dob_bis_viol]) ) {

            // update status as disabled 
            $data = $wpdb->get_results("UPDATE * FROM $customerTable WHERE isn_dob_bis_viol");

             // update logic
             $result = $wpdb->update( 
                $table_name, 
                [
                    'status' => 0, 
                ],

                [
                    'isn_dob_bis_viol' => $value->isn_dob_bis_viol,
                ]
            ); 
        
        }
    }

     ##### CHECK EXIST DATA NEED TO DISABLED OR NOT - END #####

    // if (!$result) {
    //     print 'There was an error';
    // } 
   
}

add_action( 'init', 'insertData' );

function dob_violation_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/3h2n-5cm9.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}


//Add cron event on data
add_action( 'PBS', 'insertData' );


// DOB Complaints Data

    //Initialize DB Tables
    function dob_complaints() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable2 = $table_prefix . 'dob_complaint';

     // Create Customer Table if not exist
     if( $wpdb->get_var( "show tables like '$customerTable2'" ) != $customerTable2 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable2` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `complaintid` varchar(500) NOT NULL, ";
        $sql .= " `buildingid` varchar(500) NOT NULL, ";
        $sql .= " `boroughid` varchar(500), ";
        $sql .= " `borough` varchar(500) NOT NULL, ";
        $sql .= " `housenumber` varchar(500), ";
        $sql .= " `streetname` varchar(500), ";
        $sql .= " `zip` varchar(150) NOT NULL, ";
        $sql .= " `block` varchar(500), ";
        $sql .= " `lot` varchar(500), ";
        $sql .= " `apartment` varchar(500) NOT NULL, ";
        $sql .= " `communityboard` varchar(500) NOT NULL, ";
        $sql .= " `receiveddate` varchar(500) NOT NULL, ";
        $sql .= " `statusid` varchar(500) NOT NULL, ";
        $sql .= " `status` varchar(500) NOT NULL, ";
        $sql .= " `statusdate` varchar(500) NOT NULL, ";
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'dob_complaints' );

function dob_complaints_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'dob_complaint';     

    $db = dob_complaints_restapi_callback();

    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else {    

        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                'complaintid' => $value->complaintid, 
                'buildingid' => $value->buildingid, 
                'boroughid' => $value->boroughid,
                'borough' => $value->borough, 
                'housenumber' => $value->housenumber, 
                'streetname' => $value->streetname, 
                'zip' => $value->zip, 
                'block' => $value->block, 
                'lot' => $value->lot, 
                'apartment' => $value->apartment, 
                'communityboard' => $value->communityboard,
                'receiveddate' => $value->receiveddate,
                'statusid' => $value->statusid, 
                'status' => $value->status, 
                'statusdate' => $value->statusdate,
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
  
}

add_action( 'init', 'dob_complaints_insertData' );

function dob_complaints_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/uwyv-629c.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'dob_complaints_insertData' );


// ECB Violations Data

//Initialize DB Tables

function ecb_violations() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable3 = $table_prefix . 'ecb_violation';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable3'" ) != $customerTable3 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable3` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `isn_dob_bis_extract` varchar(500) NOT NULL, ";
        $sql .= " `ecb_violation_number` varchar(500) NOT NULL, ";
        $sql .= " `ecb_violation_status` varchar(500), ";
        $sql .= " `dob_violation_number` varchar(500) NOT NULL, ";
        $sql .= " `bin` varchar(500), ";
        $sql .= " `boro` varchar(500), ";
        $sql .= " `block` varchar(150) NOT NULL, ";
        $sql .= " `lot` varchar(500), ";
        $sql .= " `hearing_date` varchar(500), ";
        $sql .= " `hearing_time` varchar(500) NOT NULL, ";
        $sql .= " `served_date` varchar(500) NOT NULL, ";
        $sql .= " `issue_date` varchar(500) NOT NULL, ";
        $sql .= " `severity` varchar(500) NOT NULL, ";
        $sql .= " `violation_type` varchar(500) NOT NULL, ";
        $sql .= " `respondent_name` varchar(500) NOT NULL, ";
        $sql .= " `respondent_house_number` varchar(500) NOT NULL, ";
        $sql .= " `respondent_street` varchar(500) NOT NULL, ";
        $sql .= " `respondent_city` varchar(500) NOT NULL, ";
        $sql .= " `respondent_zip` varchar(500) NOT NULL, ";
        $sql .= " `violation_description` varchar(500) NOT NULL, ";
        $sql .= " `penality_imposed` varchar(500) NOT NULL, ";
        $sql .= " `amount_paid` varchar(500) NOT NULL, ";
        $sql .= " `balance_due` varchar(500) NOT NULL, ";
        $sql .= " `infraction_code1` varchar(500) NOT NULL, ";
        $sql .= " `section_law_description1` varchar(500) NOT NULL, ";
        $sql .= " `aggravated_level` varchar(500) NOT NULL, ";
        $sql .= " `hearing_status` varchar(500) NOT NULL, ";
        $sql .= " `certification_status` varchar(500) NOT NULL, ";
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'ecb_violations' );

function ecb_violations_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'ecb_violation';     

    $db = ecb_violations_restapi_callback();

    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else {    
        
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                'isn_dob_bis_extract' => $value->isn_dob_bis_extract, 
                'ecb_violation_number' => $value->ecb_violation_number, 
                'ecb_violation_status' => $value->ecb_violation_status,
                'dob_violation_number' => $value->dob_violation_number, 
                'bin' => $value->bin, 
                'boro' => $value->boro, 
                'block' => $value->block, 
                'lot' => $value->lot, 
                'hearing_date' => $value->hearing_date, 
                'hearing_time' => $value->hearing_time, 
                'served_date' => $value->served_date,
                'issue_date' => $value->issue_date,
                'severity' => $value->severity, 
                'violation_type' => $value->violation_type, 
                'respondent_name' => $value->respondent_name,
                'respondent_house_number' => $value->respondent_house_number,
                'respondent_street' => $value->respondent_street,
                'respondent_city' => $value->respondent_city,
                'respondent_zip' => $value->respondent_zip,
                'violation_description' => $value->violation_description,
                'penality_imposed' => $value->penality_imposed,
                'amount_paid' => $value->amount_paid,
                'balance_due' => $value->balance_due,
                'infraction_code1' => $value->infraction_code1,
                'section_law_description1' => $value->section_law_description1,
                'aggravated_level' => $value->aggravated_level,
                'hearing_status' => $value->hearing_status,
                'certification_status' => $value->certification_status,
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'ecb_violations_insertData' );

function ecb_violations_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/6bgk-3dad.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'ecb_violations_insertData' );


// HPD Complaints Data

//Initialize DB Tables

function hpd_complaints() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable4 = $table_prefix . 'hpd_complaint';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable4'" ) != $customerTable4 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable4` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `problemid` varchar(500) NOT NULL, ";
        $sql .= " `complaintid` varchar(500) NOT NULL, ";
        $sql .= " `unittypeid` varchar(500), ";
        $sql .= " `unittype` varchar(500) NOT NULL, ";
        $sql .= " `spacetypeid` varchar(500), ";
        $sql .= " `spacetype` varchar(500), ";
        $sql .= " `typeid` varchar(150) NOT NULL, ";
        $sql .= " `type` varchar(500), ";
        $sql .= " `majorcategoryid` varchar(500), ";
        $sql .= " `majorcategory` varchar(500) NOT NULL, ";
        $sql .= " `minorcategoryid` varchar(500) NOT NULL, ";
        $sql .= " `minorcategory` varchar(500) NOT NULL, ";
        $sql .= " `codeid` varchar(500) NOT NULL, ";
        $sql .= " `code` varchar(500) NOT NULL, ";
        $sql .= " `statusid` varchar(500) NOT NULL, ";
        $sql .= " `status` varchar(500) NOT NULL, ";
        $sql .= " `statusdate` varchar(500) NOT NULL, ";
        $sql .= " `statusdescription` varchar(500) NOT NULL, ";
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'hpd_complaints' );

function hpd_complaints_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'hpd_complaint';     

    $db = hpd_complaints_restapi_callback();

    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else {    
        
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                'problemid' => $value->problemid, 
                'complaintid' => $value->complaintid, 
                'unittypeid' => $value->unittypeid,
                'unittype' => $value->unittype, 
                'spacetypeid' => $value->spacetypeid, 
                'spacetype' => $value->spacetype, 
                'typeid' => $value->typeid, 
                'type' => $value->type, 
                'majorcategoryid' => $value->majorcategoryid, 
                'majorcategory' => $value->majorcategory, 
                'minorcategoryid' => $value->minorcategoryid,
                'minorcategory' => $value->minorcategory,
                'codeid' => $value->codeid, 
                'code' => $value->code, 
                'statusid' => $value->statusid,
                'status' => $value->status,
                'statusdate' => $value->statusdate,
                'statusdescription' => $value->statusdescription,
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'hpd_complaints_insertData' );

function hpd_complaints_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/a2nx-4u46.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'hpd_complaints_insertData' );


// HPD Violations Data

//Initialize DB Tables

function hpd_violations() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable5 = $table_prefix . 'hpd_violation';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable5'" ) != $customerTable5 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable5` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `isn_dob_bis_viol` varchar(500) NOT NULL, ";
        $sql .= " `boro` varchar(500) NOT NULL, ";
        $sql .= " `bin` varchar(500), ";
        $sql .= " `block` varchar(500) NOT NULL, ";
        $sql .= " `lot` varchar(500), ";
        $sql .= " `issue_date` varchar(500), ";
        $sql .= " `violation_type_code` varchar(150) NOT NULL, ";
        $sql .= " `violation_number` varchar(500), ";
        $sql .= " `house_number` varchar(500), ";
        $sql .= " `street` varchar(500) NOT NULL, ";
        $sql .= " `disposition_date` varchar(500) NOT NULL, ";
        $sql .= " `disposition_comments` varchar(500) NOT NULL, ";
        $sql .= " `device_number` varchar(500) NOT NULL, ";
        $sql .= " `number` varchar(500) NOT NULL, ";
        $sql .= " `violation_category` varchar(500) NOT NULL, ";
        $sql .= " `violation_type` varchar(500) NOT NULL, ";
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'hpd_violations' );

function hpd_violations_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'hpd_violation';     

    $db = hpd_violations_restapi_callback();

    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else {   
        
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array( 
                'isn_dob_bis_viol' => $value->isn_dob_bis_viol, 
                'boro' => $value->boro, 
                'bin' => $value->bin,
                'block' => $value->block, 
                'lot' => $value->lot, 
                'issue_date' => $value->issue_date, 
                'violation_type_code' => $value->violation_type_code, 
                'violation_number' => $value->violation_number, 
                'house_number' => $value->house_number, 
                'street' => $value->street, 
                'disposition_date' => $value->disposition_date,
                'disposition_comments' => $value->disposition_comments,
                'device_number' => $value->device_number, 
                'number' => $value->number, 
                'violation_category' => $value->violation_category,
                'violation_type' => $value->violation_type,
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'hpd_violations_insertData' );

function hpd_violations_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/3h2n-5cm9.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'hpd_violations_insertData' );


// Landmark Complaints Data

//Initialize DB Tables

function landmark_complaints() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable6 = $table_prefix . 'landmark_complain';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable6'" ) != $customerTable6 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable6` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        //Fields to be add
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'landmark_complaints' );



function landmark_complaints_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'landmark_complain';     

    $db = landmark_complaints_restapi_callback();

    
    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else { 
        
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                    // Data to be insert
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'landmark_complaints_insertData' );

function landmark_complaints_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/dvzp-gez7.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'landmark_complaints_insertData' );



// Landmark Violations Data

//Initialize DB Tables

function landmark_violations() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable7 = $table_prefix . 'landmark_violation';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable7'" ) != $customerTable7 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable7` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        //Fields to be add
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'landmark_violations' );

function landmark_violations_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'landmark_violation';     

    $db = landmark_violations_restapi_callback();
    
    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else {        
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                    // Data to be insert
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'landmark_violations_insertData' );

function landmark_violations_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/4khw-k4vc.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'landmark_violations_insertData' );



//  OATH Hearings Data

//Initialize DB Tables

function oath_hearings() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable8 = $table_prefix . 'oath_hearing';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable8'" ) != $customerTable8 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable8` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        //Fields to be add
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'oath_hearings' );

function oath_hearings_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'oath_hearing';     

    $db = oath_hearings_restapi_callback();
    
    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else { 
        
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                    // Data to be insert
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'oath_hearings_insertData' );

function oath_hearings_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/6ky6-6i9a.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'oath_hearings_insertData' );


//  Service Requests 311 Data

//Initialize DB Tables

function service_requests() {

    // WP Globals
    global $table_prefix, $wpdb;

    // Customer Table
    $customerTable9 = $table_prefix . 'service_request';

    // Create Customer Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable9'" ) != $customerTable9 ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable9` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `unique_key` varchar(500) NOT NULL, ";
        $sql .= " `created_date` varchar(500) NOT NULL, ";
        $sql .= " `agency` varchar(500), ";
        $sql .= " `agency_name` varchar(500) NOT NULL, ";
        $sql .= " `complaint_type` varchar(500), ";
        $sql .= " `descriptor` varchar(500), ";
        $sql .= " `location_type` varchar(150) NOT NULL, ";
        $sql .= " `incident_zip` varchar(150) NOT NULL, ";
        $sql .= " `incident_address` varchar(150) NOT NULL, ";
        $sql .= " `street_name` varchar(150) NOT NULL, ";
        $sql .= " `cross_street_1` varchar(150) NOT NULL, ";
        $sql .= " `cross_street_2` varchar(150) NOT NULL, ";
        $sql .= " `address_type` varchar(150) NOT NULL, ";
        $sql .= " `city` varchar(150) NOT NULL, ";
        $sql .= " `status` varchar(150) NOT NULL, ";
        $sql .= " `resolution_description` varchar(150) NOT NULL, ";
        $sql .= " `resolution_action_updated_date` varchar(150) NOT NULL, ";
        $sql .= " `community_board` varchar(150) NOT NULL, ";
        $sql .= " `bbl` varchar(150) NOT NULL, ";
        $sql .= " `borough` varchar(150) NOT NULL, ";
        $sql .= " `x_coordinate_state_plane` varchar(150) NOT NULL, ";
        $sql .= " `y_coordinate_state_plane` varchar(150) NOT NULL, ";
        $sql .= " `open_data_channel_type` varchar(150) NOT NULL, ";
        $sql .= " `park_facility_name` varchar(150) NOT NULL, ";
        $sql .= " `park_borough` varchar(150) NOT NULL, ";
        $sql .= " `latitude` varchar(150) NOT NULL, ";
        $sql .= " `longitude` varchar(150) NOT NULL, ";
        $sql .= " `location` varchar(150) NOT NULL, ";
        $sql .= " `type` varchar(150) NOT NULL, ";
        $sql .= " `coordinates` varchar(150) NOT NULL, ";
        $sql .= " `location_address` varchar(150) NOT NULL, ";
        $sql .= " `location_city` varchar(150) NOT NULL, ";
        $sql .= " `location_state` varchar(150) NOT NULL, ";
        $sql .= " `location_zip` varchar(150) NOT NULL, ";
        $sql .= " `:@computed_region_efsh_h5xi` varchar(150) NOT NULL, ";
        $sql .= " `:@computed_region_f5dn_yrer` varchar(150) NOT NULL, ";
        $sql .= " `:@computed_region_yeji_bk3q` varchar(150) NOT NULL, ";
        $sql .= " `:@computed_region_92fq_4b7q` varchar(150) NOT NULL, ";
        $sql .= " `:@computed_region_sbqj_enih` varchar(150) NOT NULL, ";
        $sql .= " PRIMARY KEY `customer_id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

register_activation_hook( __FILE__, 'service_requests' );

function service_requests_insertData() {     
    global $wpdb; 
    $table_name = $wpdb->prefix . 'service_request';     

    $db = service_requests_restapi_callback();

    $alreadyGot = $wpdb->get_results(
        "SELECT
        COUNT(*) AS TOTALCOUNT
        FROM {$table_name}"
    );

    $count = $alreadyGot[0]->TOTALCOUNT;

    if( $count > 0 ) {
    //do nothing
    }
    else {      
        foreach ($db as $key => $value) {
            $result = $wpdb->insert( 
                $table_name, 
                array(  
                    'unique_key' => $value->unique_key, 
                    'created_date' => $value->created_date, 
                    'agency' => $value->agency,
                    'agency_name' => $value->agency_name, 
                    'complaint_type' => $value->complaint_type, 
                    'descriptor' => $value->descriptor, 
                    'location_type' => $value->location_type, 
                    'incident_zip' => $value->incident_zip, 
                    'incident_address' => $value->incident_address, 
                    'street_name' => $value->street_name, 
                    'cross_street_1' => $value->cross_street_1,
                    'cross_street_2' => $value->cross_street_2,
                    'address_type' => $value->address_type, 
                    'city' => $value->city, 
                    'status' => $value->status,
                    'resolution_description' => $value->resolution_description,
                    'resolution_action_updated_date' => $value->resolution_action_updated_date, 
                    'community_board' => $value->community_board, 
                    'bbl' => $value->bbl,
                    'borough' => $value->borough, 
                    'x_coordinate_state_plane' => $value->x_coordinate_state_plane, 
                    'y_coordinate_state_plane' => $value->y_coordinate_state_plane, 
                    'open_data_channel_type' => $value->open_data_channel_type, 
                    'park_facility_name' => $value->park_facility_name, 
                    'park_borough' => $value->park_borough, 
                    'latitude' => $value->latitude, 
                    'longitude' => $value->longitude,
                    'location' => $value->location,
                    'type' => $value->type, 
                    'coordinates' => $value->coordinates, 
                    'location_address' => $value->location_address,
                    'location_city' => $value->location_city,
                    'location_state' => $value->location_state, 
                    'location_zip' => $value->location_zip, 
                    // ':@computed_region_efsh_h5xi' => $value->:@computed_region_efsh_h5xi,
                    // ':@computed_region_f5dn_yrer' => $value->:@computed_region_f5dn_yrer,
                    // ':@computed_region_yeji_bk3q' => $value->:@computed_region_yeji_bk3q, 
                    // ':@computed_region_92fq_4b7q' => $value->:@computed_region_92fq_4b7q, 
                    // ':@computed_region_sbqj_enih' => $value->:@computed_region_sbqj_enih,  
                ),
            );        
            // if (!$result) {
            //     print 'There was an error';
            // }
        }
    }
}

add_action( 'init', 'service_requests_insertData' );

function service_requests_restapi_callback(){
    $url = 'https://data.cityofnewyork.us/resource/fhrw-4uyv.json';   
        $arguments = array(
         'method' => 'GET'
        );
        $response = wp_remote_get( $url, $arguments );
        if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
        } 
        else { 
        $data = json_decode(wp_remote_retrieve_body($response));
        } 
    return $data;
}

// Add cron event on data for dob_complaints
// add_action( 'conspiredmind', 'service_requests_insertData' );

 
//plugin menu page 
function admin_menu()
{
    add_menu_page(
        __('PBS NYC - PBS NYC', 'my-textdomain'),
        __('PBS NYC', 'my-textdomain'),
        'manage_options',
        'sample-page',
        'dashboard_contents',
        'dashicons-book',
        3
    );

        add_submenu_page('sample-page',
        __('DOB Violations - DOB Violations', 'my-textdomain'),
        __('DOB Violations', 'my-textdomain'),
        'manage_options',
        'dob-violations',
        'dob_violations_contents');
    
        add_submenu_page('sample-page',
            __('DOB Complaints - DOB Complaints', 'my-textdomain'),
            __('DOB Complaints', 'my-textdomain'),
            'manage_options',
            'dob-complains',
            'dob_complains_contents');

        add_submenu_page('sample-page',
        __('ECB Violations - ECB Violations', 'my-textdomain'),
        __('ECB Violations', 'my-textdomain'),
        'manage_options',
        'ecb-violations',
        'ecb_violations_contents'); 

        add_submenu_page('sample-page',
        __('HPD Complaints - HPD Complaints', 'my-textdomain'),
        __('HPD Complaints', 'my-textdomain'),
        'manage_options',
        'hpd-complaints',
        'hpd_complaints_contents');

        add_submenu_page('sample-page',
        __('HPD Violations - HPD Violations', 'my-textdomain'),
        __('HPD Violations', 'my-textdomain'),
        'manage_options',
        'hpd-violations',
        'hpd_violations_contents');

        add_submenu_page('sample-page',
        __('Landmark Complaints - Landmark Complaints', 'my-textdomain'),
        __('Landmark Complaints', 'my-textdomain'),
        'manage_options',
        'landmark-complaints',
        'landmark_complaints_contents');

        add_submenu_page('sample-page',
        __('Landmark Violations - Landmark Violations', 'my-textdomain'),
        __('Landmark Violations', 'my-textdomain'),
        'manage_options',
        'landmark-violations',
        'landmark_violations_contents');

        add_submenu_page('sample-page',
        __('OATH Hearings -  OATH Hearings', 'my-textdomain'),
        __('OATH Hearings', 'my-textdomain'),
        'manage_options',
        'oath-hearings',
        'oath_hearings_contents');

        add_submenu_page('sample-page',
        __('Service Requests 311 -  Service Requests 311', 'my-textdomain'),
        __('Service Requests 311', 'my-textdomain'),
        'manage_options',
        'service-requests',
        'service_requests_contents');
}

add_action('admin_menu', 'admin_menu');

// Shortcode for Front view
function rest_front_data()
{
?>
<?php require_once __DIR__ . '/templates/dashboard.php'; ?>
<?php
}

add_shortcode('api-data', 'rest_front_data');


//Display data into Admin Panel For Dashboard
function dashboard_contents()
{
?>
<?php require_once __DIR__ . '/templates/dashboard.php'; ?>
<?php
}

//Display data into Admin Panel For DOB Violations
function dob_violations_contents()
{
?>
<?php require_once __DIR__ . '/templates/dob-violations.php'; ?>
<?php
}

//Display data into Admin Panel for DOB Complains
function dob_complains_contents()
{
?>
<?php require_once __DIR__ . '/templates/dob-complains.php'; ?>
<?php
}

//Display data into Admin Panel for ECB Violations
function ecb_violations_contents()
{
?>
<?php require_once __DIR__ . '/templates/ecb-violations.php'; ?>
<?php
}

//Display data into Admin Panel for HPD Complaints
function hpd_complaints_contents()
{
?>
<?php require_once __DIR__ . '/templates/hpd-complaints.php'; ?>
<?php
}

//Display data into Admin Panel for HPD Violations
function hpd_violations_contents()
{
?>
<?php require_once __DIR__ . '/templates/hpd-violations.php'; ?>
<?php
}

//Display data into Admin Panel for Landmark Complaints
function landmark_complaints_contents()
{
?>
<?php require_once __DIR__ . '/templates/landmark-complaints.php'; ?>
<?php
}

//Display data into Admin Panel for Landmark Violations
function landmark_violations_contents()
{
?>
<?php require_once __DIR__ . '/templates/landmark-violations.php'; ?>
<?php
}

//Display data into Admin Panel for  OATH Hearings
function oath_hearings_contents()
{
?>
<?php require_once __DIR__ . '/templates/oath-hearings.php'; ?>
<?php
}

//Display data into Admin Panel for Service Requests 311
function service_requests_contents()
{
?>
<?php require_once __DIR__ . '/templates/service-requests-311.php'; ?>
<?php
}

// Register scripts and css 
function load_custom_wp_admin_style()
{
    $date = date('h : i : s A');
   
    wp_register_style( 'custom_wp_admin_css',  plugin_dir_url( __FILE__ ) . 'assets/main.css?var='.$date.'');
    wp_enqueue_style( 'custom_wp_admin_css' );

    wp_register_script( 'custom_external_admin_js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js' );
    wp_enqueue_script( 'custom_external_admin_js' );

    wp_register_script( 'custom_wp_admin_js',  plugin_dir_url( __FILE__ ) . '/assets/main.js?var='.$date.'');
    wp_enqueue_script( 'custom_wp_admin_js' );

    wp_register_style( 'custom_bootstrap_admin_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
    wp_enqueue_style('custom_bootstrap_admin_css');

    wp_register_style( 'custom_bootstrap_admin_style_2', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css');
    wp_enqueue_style('custom_bootstrap_admin_style_2');

    wp_register_script( 'custom_bootstrap_admin_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js');
    wp_enqueue_script('custom_bootstrap_admin_js');

    wp_register_script( 'custom_bootstrap_admin_js_2', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js');
    wp_enqueue_script('custom_bootstrap_admin_js_2'); 

    wp_register_style( 'bs_datatable_css', 'https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css');
    wp_enqueue_style('bs_datatable_css');  

    wp_register_style( 'd_datatable_css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css');
    wp_enqueue_style('d_datatable_css');  

    wp_register_style( 'd_datatable_css2','https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css');
    wp_enqueue_style('d_datatable_css2');  

    wp_register_style( 'd_datatable_btn_css','https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css');
    wp_enqueue_style('d_datatable_btn_css'); 

    wp_register_script( 'bs_datatable_js','https://code.jquery.com/jquery-3.5.1.js');
    wp_enqueue_script('bs_datatable_js'); 

    wp_register_script( 'bs_datatable_j2','https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js');
    wp_enqueue_script('bs_datatable_j2'); 

    wp_register_script( 'bs_datatable_j3','https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js');
    wp_enqueue_script('bs_datatable_j3');  

    wp_register_script( 'bs_datatable_btn','https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js');
    wp_enqueue_script('bs_datatable_btn'); 
    
    wp_register_script( 'bs_datatable_file','https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js');
    wp_enqueue_script('bs_datatable_file');

    wp_register_script( 'bs_datatable_pdf','https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js');
    wp_enqueue_script('bs_datatable_pdf');

    wp_register_script( 'bs_datatable_pdf2','https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js');
    wp_enqueue_script('bs_datatable_pdf2');

    wp_register_script( 'bs_datatable_html_btn','https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js');
    wp_enqueue_script('bs_datatable_html_btn');

    wp_register_script( 'bs_datatable_print','https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js');
    wp_enqueue_script('bs_datatable_print');
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

// Register scripts and css for Front View
function front_table(){

    wp_register_style( 'front_table_css1','https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css');
    wp_enqueue_style('front_table_css1'); 

    wp_register_style( 'front_table_css2', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css');
    wp_enqueue_style('front_table_css2'); 

    wp_register_style( 'front_table_css3','https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css');
    wp_enqueue_style('front_table_css3'); 


    wp_register_script( 'front_front_js','https://code.jquery.com/jquery-3.5.1.js');
    wp_enqueue_script('front_front_js'); 

    wp_register_script( 'front_front_js2','https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js');
    wp_enqueue_script('front_front_js2'); 

    wp_register_script( 'front_front_js3','https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js');
    wp_enqueue_script('front_front_js3'); 

    
    wp_register_script( 'custom_wp_front_js',  plugin_dir_url( __FILE__ ) . '/assets/main.js');
    wp_enqueue_script( 'custom_wp_front_js' ); 

}
add_action('wp_enqueue_scripts','front_table');
?>