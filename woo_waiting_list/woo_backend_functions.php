<?php 
// waiting list settings Admin menu
function waiting_list_table(){
  add_menu_page(
    'Waiting List Table Data',// page title  
    'Waiting List Table',// menu title  
    'manage_options',// capability  
    'waiting_list_table',// menu slug  
    'print_table_data',  // callback function  
    '', // custom icons code
    '55' // menu position change
);  
// sub menu

add_submenu_page(
          'waiting_list_table',               // parent slug
          'Waiting List',                      // page title
          'Waiting List',                      // menu title
          'manage_options',                   // capability
          'waiting_list_table',               // slug
          'print_table_data' // callback
    );
add_submenu_page(
          'waiting_list_table',               // parent slug
          'Waiting List Settings',                      // page title
          'Settings',                      // menu title
          'manage_options',                   // capability
          'waitinglist-settings',               // slug
          'submenu_waitinglist_setting' // callback
    );

 
}
add_action( 'admin_menu', 'waiting_list_table' );

function print_table_data(){
  require_once('admin_waiting_list.php');
}

function submenu_waitinglist_setting(){
 require_once('admin_waiting_list_settings.php');   
}


// plugin settings link add
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'plugin_page_settings_link');
function plugin_page_settings_link( $links ) {
    $links[] = '<a href="' .
    admin_url( 'admin.php?page=waiting_list_table' ) .
    '">' . __('Settings') . '</a>';
    return $links;
}

// order page edit item hook
add_filter( 'wc_order_is_editable', 'wc_make_processing_orders_editable', 10, 2 );
function wc_make_processing_orders_editable( $is_editable, $order ) {
  $is_editable = true;
    if ( $order->get_status() == 'processing' || $order->get_status() == 'wc-selected' ) {
        $is_editable = true;
    }
    return $is_editable;
}

// create custom woocommerce order status
function register_selected_order_status() {
    register_post_status( 'wc-selected', array(
        'label'                     => 'Selected in Watiting List',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Selected in Watiting List <span class="count">(%s)</span>', 'Selected in Watiting List <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_selected_order_status' );


add_filter( 'wc_order_statuses', 'custom_order_status');
function custom_order_status( $order_statuses ) {
    $order_statuses['wc-selected'] = _x( 'Selected in Watiting List', 'Order status', 'woocommerce' ); 
    return $order_statuses;
}
 ?>

