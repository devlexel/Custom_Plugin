<?php 
/* change button name from add to cart to Enroll */
function wcwl_change_addtocart_button() {
if(get_option( 'wlproductButton' )){ $swlProductButton = get_option( 'wlproductButton' ); } else { $swlProductButton = 'Waiting List Number'; }    
    return __( $swlProductButton, 'woocommerce' ); 
}
// product out of change outof stock text
function themeprefix_change_soldout ( $text, $product) {
    if ( !$product->is_in_stock() ) {
        $text = 'Waiting List Full';
    }
    return $text;
}
/* disable cart item quantity field */
function wcwl_remove_all_quantity_fields( $return, $product ) 
{
    return( true );
}

/**
 * Add "Custom" template to page attirbute template section.
 */
add_filter( 'theme_page_templates', 'custom_template_create', 10, 4 );
function custom_template_create( $post_templates, $wp_theme, $post, $post_type ) {

    // Add custom template named product-template.php to select dropdown 
    $post_templates['product-template.php'] = __('Waiting List');
    return $post_templates;
}

//Load template from specific page
add_filter( 'page_template', 'custom_template_file_check' );
function custom_template_file_check( $page_template ){

    if ( get_page_template_slug() == 'product-template.php' ) {
        $page_template = plugin_dir_path( __FILE__ ) . 'template/product-template.php';
    }
    return $page_template;
}

// get waiting list number on product page
// ajax
function get_ajax_posts() {
    // Query Arguments
    global $wpdb;
    $results = $wpdb->get_results( "
        SELECT DISTINCT woim.meta_value as id, COUNT(woi.order_id) as count, woi.order_item_name as name
        FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim
        INNER JOIN {$wpdb->prefix}woocommerce_order_items as woi ON woi.order_item_id = woim.order_item_id
        INNER JOIN {$wpdb->prefix}posts as p ON p.ID = woi.order_id
        WHERE p.post_status IN ('wc-processing','wc-on-hold')
        AND (woim.meta_key LIKE 'Status' AND woim.meta_value = 'Not Selected')
        GROUP BY woim.meta_value
        " );

    foreach( $results as $result ){
        $response = $result->count;
    }
    echo $response;
    exit; // leave ajax call

}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_ajax_posts', 'get_ajax_posts');
add_action('wp_ajax_nopriv_get_ajax_posts', 'get_ajax_posts');
// ajax end

// add custom field in product page and send it to order, email
// display it on product page        01
add_action( 'woocommerce_before_add_to_cart_button', 'njengah_fields_before_add_to_cart' );
function njengah_fields_before_add_to_cart( ) {
if(get_option( 'wlproductLabel' )){ $swlProductLabel = get_option( 'wlproductLabel' ); } else { $swlProductLabel = 'Waiting List Number'; }
    echo '<div class="waiting-data"><label>'.$swlProductLabel.': <span id="waitingnumber"></span></label>';
    echo '<input type = "hidden" name = "waiting_number" id = "waiting_number">';
    echo '<input type = "hidden" name = "waiting_status" id = "waiting_status" value="Not Selected">
    </div>';

}
  // Add data to cart item     02
add_filter( 'woocommerce_add_cart_item_data', 'njengah_cart_item_data', 25, 2 );
function njengah_cart_item_data( $cart_item_meta, $product_id ) {
 if ( isset( $_POST ['waiting_number'] ) && isset( $_POST ['waiting_status'] ) ) {
     $custom_data  = array() ;
     $custom_data [ 'waiting_number' ]    = isset( $_POST ['waiting_number'] ) ?  sanitize_text_field ( $_POST ['waiting_number'] ) : "" ;
     $custom_data [ 'waiting_status' ] = isset( $_POST ['waiting_status'] ) ? sanitize_text_field ( $_POST ['waiting_status'] ): "" ;
     $cart_item_meta ['custom_data']     = $custom_data ;
 }
 return $cart_item_meta;
}
  // Display the custom data on cart and checkout page         03
add_filter( 'woocommerce_get_item_data', 'njengah_item_data' , 25, 2 );
function njengah_item_data ( $other_data, $cart_item ) {
 if ( isset( $cart_item [ 'custom_data' ] ) ) {
     $custom_data  = $cart_item [ 'custom_data' ];
     $other_data[] =   array( 'name' => 'Waiting Number',
      'display'  => $custom_data['waiting_number'] );
     $other_data[] =   array( 'name' => 'Status',
      'display'  => $custom_data['waiting_status'] );
 }
 return $other_data;
}
  // Add order item meta       04
add_action( 'woocommerce_add_order_item_meta', 'njengah_order_item_meta' , 10, 2);
function njengah_order_item_meta ( $item_id, $values ) {
 if ( isset( $values [ 'custom_data' ] ) ) {
     $custom_data  = $values [ 'custom_data' ];
     wc_add_order_item_meta( $item_id, 'Waiting Number', $custom_data['waiting_number'] );
     wc_add_order_item_meta( $item_id, 'Status', $custom_data['waiting_status'] );
 }
}

// remove add to cart button from the shop page
add_filter('woocommerce_is_purchasable', 'set_catalog_mode_on_for_category', 10, 2 );
function set_catalog_mode_on_for_category( $is_purchasable, $product ) {
  if( is_shop() ) {
    return false;
  }
  return $is_purchasable;
}

// message for already in waitinglist
add_action( 'woocommerce_after_shop_loop_item', 'wwl_product_already_bought', 30 );
add_action( 'woocommerce_simple_add_to_cart', 'wwl_product_already_bought', 5);
function wwl_product_already_bought() {
   global $product;
   if ( ! is_user_logged_in() ) return;
   if ( wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
     
      ?>
<div class="wll-warning">Already in Waiting List! <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ).'venteliste'; ?>" title="<?php _e('Min Konto',''); ?>"><?php _e('Min Konto',''); ?></a></div>
      <?php

   }
}

// change name of add to cart button on shop page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text() {
    return __( 'Visit', 'woocommerce' );
}



// New tab in My Account - Start

/** Register New Endpoint. **/
add_action( 'init', 'wwl_new_tab_myaccount');
function wwl_new_tab_myaccount() {
    add_rewrite_endpoint( 'venteliste', EP_ROOT | EP_PAGES );
}

/** Add new query var. **/
add_filter( 'query_vars', 'wwl_venteliste_query_vars' );
function wwl_venteliste_query_vars( $vars ) {
    $vars[] = 'venteliste';
    return $vars;
}


/** Add New tab in my account page. */
add_filter( 'woocommerce_account_menu_items', 'wwl_add_venteliste_tab' );
function wwl_add_venteliste_tab( $items ) {
    $items['venteliste'] = 'Venteliste';
    return $items;
}


/** Add content to the new tab. */
add_action( 'woocommerce_account_venteliste_endpoint', 'wwl_add_venteliste_content' );
function wwl_add_venteliste_content() {
    require('venteliste.php');
}

// Reorder the place of new tab
add_filter ( 'woocommerce_account_menu_items', 'wwl_reorder_account_menu' );
function wwl_reorder_account_menu( $items ) {
    return array(
            'dashboard'          => __( 'Dashboard', 'woocommerce' ),
            // 'orders'             => __( 'Orders', 'woocommerce' ),
            // 'downloads'          => __( 'Downloads', 'woocommerce' ),
            'edit-account'       => __( 'Edit Account', 'woocommerce' ),
            'edit-address'       => __( 'Addresses', 'woocommerce' ),
            'venteliste'       => __( 'Venteliste', 'woocommerce' ),
            'customer-logout'    => __( 'Logout', 'woocommerce' ),
    );

}

/** Remove tab. */
add_filter ( 'woocommerce_account_menu_items', 'wwl_remove_edit_account_tab' );
function wwl_remove_edit_account_tab( $items ) {

    unset( $items['subscriptions'] );
    return $items;
}


// New tab in My Account - End


