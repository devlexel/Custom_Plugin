<style type="text/css">
/*  section 1   */
     .adminbox{ border:1px solid #f2f2f2; padding:20px; text-align:center; margin:auto; }
     .waitingno {
          background: green;
          color: white;
          font-size: 30px;
          padding: 10px 40px;
          display: inline-flex;
          justify-content: center;
          border-radius: 50%;
          font-weight: 600;
          border: 4px solid #104a10;
          margin-bottom: 70px;
     }
/*  section 2   */
     .twrap {
          width: 100%;
          overflow-x: auto;
     }
/*  section 3   */
     .mybigrow {
          border-bottom: 3px solid #f2f2f2;
          display: flow-root;
          margin-bottom: 8px;
     }
     .myrow {
          border-bottom: 2px solid #f2f2f2;
          display: flow-root;
          margin-bottom: 8px;
     }
     span.leftlbl {
          float: left;
     }
     span.rightlbl {
          float: right;
     } 
</style>
<?php 
// Get Order ID and Key
// global $wpdb, $woocommerce, $post, $order;
$user_id = get_current_user_id();
$orders  = get_posts( array(
  'meta_key'    => '_customer_user',
  'meta_value'  => $user_id,
  'post_type'   => 'shop_order',
  'post_status' => array_keys( wc_get_order_statuses() ),
  'numberposts' => -1
));
$arrorderid =[];
foreach($orders as $orderid)
{
     $arrorderid[] = $orderid->ID;
}
$myoderid = $arrorderid[0];
$order = wc_get_order( $myoderid );

$data = $order->get_data();

// echo "<pre>";
// print_r($data );

$oid = $data['id'];
$status = $data['status'];
$currency = $data['currency'];
$discount = $data['discount_total'];
$product_total = $data['total'];

// $od = new DateTime($order->get_date_created());
$od = new DateTime($order->get_date_modified());
$order_date = $od->format('Y-m-d');
$endsubcription = date('Y-m-d', strtotime("+364 days $order_date"));
$subscription_mail = date('Y-m-d', strtotime("+350 days $order_date"));
$current_date = date('Y-m-d');
if($subscription_mail == $current_date){
   echo "mail";
}
$items = $order->get_items();
// echo '<pre>';
// print_r( $items );
// echo '</pre>';
foreach ( $items as $item ) {
    $product_name = $item['name'];
    $product_id = $item['product_id'];
    $product_variation_id = $item['variation_id'];
    $product_wl = $item['Waiting Number'];
    $product_status = $item['Status'];
   // $product_total = $item['total'];
    $product_subtotal = $item['subtotal'];
}

?>
<h2>Venteliste</h2>
<!-- section 1 -->
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
<p>labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
<div class="adminbox">
     <p>labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
     <div class="waitingno"><?php echo $product_wl; ?></div>
     <p><b>labore et dolore magna aliqua. Ut enim ad minim veniam.</b></p>
     <p>labore et dolore magna aliqua. Ut enim ad minim veniam.</p>



     <!-- section 2 table -->
     <div class="twrap">
        <table>
           <tr>
              <th>ID</th>
              <th>Ordre titel</th>
              <th>Maengde</th>
              <th>Ordre Status</th>
              <th>Handlinger</th>
         </tr>
         <tr>
              <td><?php echo $oid; ?></td>
              <td><?php echo $product_name; ?> - <?php echo $order_date; ?></td>
              <td><?php echo $product_total. ' '.$currency; ?></td>
              <td><?php echo $status; ?></td>
              <td><a href="/min-konto/orders/">Se Ordre</a></td>
         </tr>
    </table>
</div>


<!-- section 3 status and date -->
<div class="myrow">
     <span class="leftlbl">Status</span>
     <span class="rightlbl"><?php echo $product_status; ?></span>
</div>
<div class="myrow">
     <span class="leftlbl">Start Dato</span>
     <span class="rightlbl"><?php echo $order_date; ?></span>
</div>
<div class="myrow">
     <span class="leftlbl">Sidste ordre dato</span>
     <span class="rightlbl"><?php echo $order_date; ?></span>
</div>
<div class="myrow">
     <span class="leftlbl">Naeste betailing dato</span>
     <span class="rightlbl"><?php echo $endsubcription; ?></span>
</div>
<div class="myrow">
     <span class="leftlbl">Payment</span>
     <span class="rightlbl"> ???? </span>
</div>
<div class="myrow">
     <span class="leftlbl">Handlinger</span>
     <span class="rightlbl"> ???? </span>
</div>


<!-- step 4 - total -->
<h4>Abonnement</h4>
<div class="mybigrow">
     <span class="leftlbl">PRODUCT</span>
     <span class="rightlbl">TOTAL</span>
</div>
<div class="myrow">
     <span class="leftlbl"><?php echo '<a href="'.get_permalink($product_id).'">'.get_the_title($product_id).'</a>'; ?> x 1</span>
     <span class="rightlbl"><?php echo $product_total. ' '.$currency; ?></span>
</div>
<div class="myrow">
     <span class="leftlbl">Subtotal: </span>
     <span class="rightlbl"><?php echo $product_subtotal. '.00 '.$currency; ?></span>
</div>
<div class="myrow">
     <span class="leftlbl">Total: </span>
     <span class="rightlbl"><?php echo $product_total. ' '.$currency; ?></span>
</div>
</div> <!-- <div class="adminbox">  -->