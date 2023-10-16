<?php 
/**
* @package WaitingListPlugin
*/
/*
Plugin Name: Woo Waiting List
Plugin URI: https://www.haveforeningenrugholm.seoconsult.dk/
Description: Waiting List Plugin
Version: 1.0.0
Author: Developer
Author URI: https://www.haveforeningenrugholm.seoconsult.dk/
License: GPLv2 or later
Text Domain: woowaitinglist
*/
defined('ABSPATH') or die('Not Allowed');
ob_start();
class WaitingListPlugin
{   
    protected $pluginPath;
    protected $pluginUrl;
    function __construct()
    {
        // Set Plugin Path 
        $this->pluginPath = dirname(__FILE__);
        
        // Set Plugin URL 
        $this->pluginUrl = WP_PLUGIN_URL . '/woo_waiting_list';
        
        register_activation_hook(__FILE__, array($this, 'checkDependency'));
        add_filter( 'woocommerce_product_single_add_to_cart_text', 'wcwl_change_addtocart_button' ); 
        add_filter( 'woocommerce_is_sold_individually', 'wcwl_remove_all_quantity_fields', 10, 2 );
        // add_filter( 'theme_page_templates', array($this, 'custom_template_create', 10, 4) );
        // add_filter( 'page_template', array($this, 'custom_template_file_check') );
    }

    function register_script(){
        add_action('wp_enqueue_scripts', array($this, 'enqueue_script'));
    }
    function register_admin_script(){
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_script'));
    }

    // product out of change outof stock text
    function changelblstock($text, $product){
        add_filter('woocommerce_get_availability_text', 'themeprefix_change_soldout', 10, 2 );
    }

    function activate()
    {
        $this->checkDependency();
        // $this->custom_post_type(); //cpt call
        flush_rewrite_rules(); // at the end
    }

    function deactivate()
    {
        flush_rewrite_rules(); // at the end
    }

    function checkDependency()
    {
        if( !class_exists( 'WooCommerce' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'Please install and Activate WooCommerce.', 'woocommerce-addon-slug' ), 'Plugin dependency check', array( 'back_link' => true ) );
        }

        if( !class_exists( 'WC_Stripe' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'Please install and Activate WooCommerce Stripe Gateway.', 'woocommerce-addon-slug' ), 'Plugin dependency check', array( 'back_link' => true ) );
        }


    }

    // enque script
    function enqueue_script()
    {
        ?>
        <script src='https://www.haveforeningenrugholm.seoconsult.dk/wp-includes/js/jquery/jquery.min.js?ver=3.6.1' id='jquery-core-js'></script>
        <?php

        wp_enqueue_style('WaitingListStyle',plugins_url('/assets/waitinglist.css',__FILE__));
        wp_enqueue_script('WaitingListScript',plugins_url('/assets/waitinglist.js',__FILE__));
        $ajax = array(
            'ajaxurl' => admin_url('admin-ajax.php')
        );
        wp_localize_script('WaitingListScript','obj',$ajax );
    }

    function enqueue_admin_script()
    {
        ?>
 <script src='https://www.haveforeningenrugholm.seoconsult.dk/wp-includes/js/jquery/jquery.min.js?ver=3.6.1' id='jquery-core-js'></script>
        <?php
        wp_enqueue_style('WaitingListStyle',plugins_url('/assets/admin_waitinglist.css',__FILE__));
        wp_enqueue_script('WaitingListScript',plugins_url('/assets/admin_waitinglist.js',__FILE__));

    }



} 

// hide if already purchased
add_filter( 'woocommerce_is_purchasable', 'wwl_hide_add_cart_if_already_purchased', 9999, 2 );
function wwl_hide_add_cart_if_already_purchased( $is_purchasable, $product ) {
   if ( wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
      $is_purchasable = false;
   }
   return $is_purchasable;

}


require_once('woo_functions.php');
require_once('woo_backend_functions.php');

if ( class_exists('WaitingListPlugin') ){
    global $product;
    $wlPlugin = new WaitingListPlugin();
    $wlPlugin->register_script();
    $wlPlugin->register_admin_script();
    $wlPlugin->changelblstock('stockslug', $product);
}
register_activation_hook(__FILE__, array( $wlPlugin,'activate' ) );
register_deactivation_hook(__FILE__, array( $wlPlugin,'deactivate' ) );



