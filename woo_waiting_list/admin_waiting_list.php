<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/ju/jq-3.6.0/dt-1.13.1/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/ju/jq-3.6.0/dt-1.13.1/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<?php $currenturl = admin_url(sprintf(basename($_SERVER['REQUEST_URI']))); ?>

<?php global $wpdb; ?>
<?php 
if(isset($_POST['delbutton'])) {
    global $wpdb;
    // $myid = $_POST['data1'];
    $waiting_table_name = $wpdb->prefix . 'woocommerce_order_items';
    $myid = $_POST['myid'];
    $deleted = $wpdb->delete($waiting_table_name, array('order_id'=>$myid ) );
    if($deleted){

        ?>
        <script type="text/javascript">
            $('#example222').DataTable().ajax.reload();
            alert('Record Deleted Successfully!');
        </script>
        <?php
        header("location: $currenturl");
    }
    else{

        ?>
        <script type="text/javascript">alert('Record not Deleted!');</script>
        <?php  
    }
} 
?>
<table id="example" class="display" style="width:100%">

    <thead>

        <tr>

            <!-- <th>Order Id</th> -->

            <th>Customer Name</th>

            <th>Customer Email</th>

            <th>Customer Address</th>

            <th>Customer Phone</th>

            <th>Waiting List Number</th>

            <th>Waiting List Status</th>

            <th>Edit Status</th>

        </tr>

    </thead>

    <tbody>

        <?php 

        echo "<h1>Waiting List Data</h1>";

        global $wpdb;

        $product_id = get_option( 'wlproductId' );

        $orders_statuses = "'wc-completed', 'wc-processing','wc-on-hold','wc-selected'";

        $sql = $wpdb->get_results( "

            SELECT *

            FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 

            {$wpdb->prefix}woocommerce_order_items as woi, 

            {$wpdb->prefix}posts as p

            WHERE  woi.order_item_id = woim.order_item_id

            AND woi.order_id = p.ID

            AND p.post_status IN ( $orders_statuses )

            AND woim.meta_key IN ( '_product_id', '_variation_id' )

            AND woim.meta_value LIKE '$product_id'

            ORDER BY p.ID ASC"

        );
        $count = count($sql);
echo "<pre>";
        print_r($count);
echo "</pre>";

        foreach( $sql as $sqldata ){

            $order_id = $sqldata->order_id;

            $order_item_id = $sqldata->order_item_id;

            $order = new WC_Order($order_id);

            $customer_email = $order->get_billing_email();

            $customer_first_name = $order->get_billing_first_name();

            $customer_last_name = $order->get_billing_last_name();

            $customer_phone_number = $order->get_billing_phone();

            $items = $order->get_items();

            ?>

            <tr>

                <!-- <td><?php echo $order_id; ?></td> -->

                <td><?php echo $fullname = $customer_first_name .' '. $customer_last_name; ?></td>

                <td><?php echo $customer_email; ?></td>

                <td><?php 
                echo $billing_address_1  = $order->get_billing_address_1();
                echo ' ' . $billing_address_2  = $order->get_billing_address_2();
                echo ' ' . $billing_city       = $order->get_billing_city();
                echo ' ' . $billing_state      = $order->get_billing_state();
                echo ' ' . $billing_postcode   = $order->get_billing_postcode();
                echo ' ' . $billing_country    = $order->get_shipping_country();
                ?></td>

                <td><?php echo $customer_phone_number; ?></td>

                <?php 




                foreach ($items as $key => $value) {
                    $waitingno = wc_get_order_item_meta($key, 'Waiting Number', true);
                    $waitingss = wc_get_order_item_meta($key, 'Status', true);
                    if(isset($_POST['submit'])){
                        if($waitingno != 0){
                            $newwaitingnum = $waitingno - 1;
                            $pd = wc_update_order_item_meta($key, 'Waiting Number', $newwaitingnum);
                        }
                    }



                    ?>     
                    <td>
                        <?php echo '<div class="waitingno">'.$waitingno.'</div> '. $order_item_id. '(Order ID '.$order_id.')'; ?>
                        <?php echo '<a href="/wp-admin/post.php?post='.$order_id.'&action=edit" target="_blank">Goto Order <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                        '; ?>
                        <?php //echo ')(Itemid '.$order_item_id; ?>
                        <?php //echo ')(Waiting '.$waitingno .')'; ?>
                    </td>
                    <td>
                        <?php 
                        if($waitingss == 'Not Selected'){ 
                            if($waitingno == 1)
                            {
                                echo "Waiting 1st Position";     
                            }
                            else
                            {
                                echo "Waiting"; 
                            }
                        }
                        else 
                            { echo "Selected"; } 
                    ?></td>
                    <?php update_post_meta( $order_id, 'Status', 'yes' ); 
                } 


                ?>

                <td>

                    <?php
                    if($waitingss == 'Not Selected' && $waitingno == 1){
                        ?>
                        <button id="btnMyTest001" type="button" class="btn btn-danger " data-toggle="modal" data-target="#my_modal" data-oid="<?php echo $order_id; ?>" data-wuser="<?php echo $fullname; ?>" data-wstatus="<?php echo $waitingss; ?>" data-wnumber="<?php echo $waitingno; ?>">Win This <?php //echo  $order_id; ?></button>
                    <?php } 
                    elseif($waitingss == 'Selected' && $waitingno == 0){
                        echo '<div class="btn btn-success" style="color:black; font-weight:bold;"><i class="fa-solid fa-medal"></i> WON!</div>';
                        ?>
                        <form method="post">
                            <input type="hidden" name="myid" value="<?php echo $order_id; ?>">
                            <input type="submit" id="delete" name="delbutton" class="btn btn-sm btn-danger" value="Delete" onclick="return confirm('Are you sure you want to Remove?');">
                        </form>
                        <?php
                    }
                    else { ?>
                        <button id="btnMyTest002" type="button" class="btn btn-primary" data-toggle="modal" data-target="#my_modal2" data-oid2="<?php echo $order_id; ?>" data-wuser2="<?php echo $fullname; ?>" data-wstatus2="<?php echo $waitingss; ?>" data-wnumber2="<?php echo $waitingno; ?>" data-swipe2="yes">Move <?php //echo  $order_id; ?></button>
                    <?php } ?>

                </td>

            </tr>

        <?php } ?>

    </tbody>

    <tfoot>

        <tr>

            <!-- <th>Order Id</th> -->

            <th>Customer Name</th>

            <th>Customer Email</th>

            <th>Customer Address</th>

            <th>Customer Phone</th>

            <th>Waiting List Number</th>

            <th>Waiting List Status</th>

            <th>Edit Status</th>

        </tr>

    </tfoot>

</table>

<div class="modal fade" id="my_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">
        <form method="POST">
          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Waiting List</h4>

        </div>

        <div class="modal-body">
            <style type="text/css">p{font-weight: bold;}</style>
            <div class="form-group">
                <p>Order id: <span id="woid"></span></p> 
                <p>User: <span id="wuserlbl"></span></p>
                <p>Waiting List Number is: <span id="wnumberlbl"></span></p>
                
                <input type="hidden" name="oid" id="oid" class="form-control" />
                <input type="hidden" name="wnumber" id="wnumber" class="form-control" />
                <input type="hidden" name="wstatus" id="wstatus" class="form-control" />
                
            <!-- <input type="radio" name="wstatus" value="true"><label>True</label>
                <input type="radio" name="wstatus" value="false"><label>False</label> -->
            </div>
        </div>

        <div class="modal-footer">
            <!-- onclick="cancelRecord()" -->
            <p style="float:left;padding-top: 5px;">Click Win button to make this User <span style="color:green;font-weight: 600;">Selected/Win</span>.</p>
            <input type="submit" class="btn btn-primary" name="submit" value="Win This User">
            <button type="button"  class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
        <?php 
        if(isset($_POST['submit'])){
            $order_id = $_POST['oid'];
            $r_da = $_POST['wnumber'] - 1;
            if($_POST['wstatus'] == 'Not Selected'){ $t_dy = 'Selected'; } else { die(); }

            $order = wc_get_order($order_id);
            foreach ($order->get_items() as $item_id => $item_obj) {
                $pd = wc_update_order_item_meta($item_id, 'Waiting Number', $r_da);
                $rd = wc_update_order_item_meta($item_id, 'Status', $t_dy);
            }
            $order->update_status( 'wc-selected' );
            clean_post_cache( $order->get_id() );
            wc_delete_shop_order_transients( $order );
            wp_cache_delete( 'order-items-' . $order->get_id(), 'orders' );
            header("Location: $currenturl");
                // die();
        }    
        ?>        
    </form>
</div> <!-- modal-content -->

</div>

</div>


<!-- another model for swipe -->
<div class="modal fade" id="my_modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">
     <form method="POST">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Change Waiting List Order</h4>
    </div>
    <div class="modal-body">
        <style type="text/css">p{font-weight: bold;}</style>
        <div class="form-group">
            <p>Order id: <span id="woid2"></span></p> 
            <p>User: <span id="wuserlbl2"></span></p>
            <p>Waiting List Number is: <span id="wnumberlbl2"></span></p>
            <input type="hidden" name="oid2" id="oid2" class="form-control" />
            <input type="hidden" name="wnumber2" id="wnumber2" class="form-control" />
            <input type="hidden" name="wstatus2" id="wstatus2" class="form-control" />
            <input type="hidden" name="wswipe2" id="wswipe2" class="form-control" />
            <?php 

            $mv = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = 'Waiting Number' AND meta_value !=0" );
            $totalavailable = count($mv);
            ?>
            <b>Move to any Position: </b> 
            <select name="swipeno2">
                <?php for ($i=1; $i <= $totalavailable; $i++) { 
                 echo '<option value='.$i.'>'.$i.'</option>';
             } 
             ?>
         </select>
     </div>
 </div>
 <div class="modal-footer">
    <p style="float:left;padding-top: 5px;">Rearrange user's waiting list number.</p>
    <input type="submit" class="btn btn-primary" name="submitswipe" value="Move Now">
    <button type="button"  class="btn btn-danger" data-dismiss="modal">Cancel</button>
</div>
</form>
</div> <!-- modal-content -->
</div>
</div>



<!-- winner list -->
<?php 
if(isset($_POST['submitswipe'])){
    $order_id = $_POST['oid2'];
    $r_da2 = $_POST['swipeno2'];
    if($_POST['wstatus2'] == 'Not Selected'){ $t_dy = 'Selected'; } else { die(); }
    $order = wc_get_order($order_id);
    foreach ($order->get_items() as $item_id => $item_obj) {
        $pd = wc_update_order_item_meta($item_id, 'Waiting Number', $r_da2);
    }
    // clean_post_cache( $order->get_id() );
    // wc_delete_shop_order_transients( $order );
    // wp_cache_delete( 'order-items-' . $order->get_id(), 'orders' );

    $oldnumber = $_POST['wnumber2'];
    $newnumber = $_POST['swipeno2'];
    $oid2 = $_POST['oid2'];
    
    // echo '<p>oldnum: '.$oldnumber.'</p>';
    // echo '<p>newnumber: '.$newnumber.'</p>';
    // echo '<p>oid2: '.$oid2.'</p>';

    $sqloitmid = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = 'Waiting Number' AND meta_value = $newnumber");
    if(count($sqloitmid) == 1){
       $fitstoitid = $sqloitmid[0]->order_item_id;
       $newval = $newnumber - 1;
       wc_update_order_item_meta($fitstoitid, 'Waiting Number', $newval);
   }
   else
   {
    $fitstoitid = $sqloitmid[0]->order_item_id;
    $secondoitid = $sqloitmid[1]->order_item_id;

    if($oldnumber > $newnumber){
        if($newnumber != 1){
            $sqloitmid = $wpdb->get_results("SELECT oim.meta_value, oim.order_item_id, oi.order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta as oim LEFT JOIN {$wpdb->prefix}woocommerce_order_items as oi ON oim.order_item_id = oi.order_item_id  WHERE oim.meta_key = 'Waiting Number' AND oim.meta_value BETWEEN $newnumber AND $oldnumber AND oi.order_id !=$oid2");
            foreach($sqloitmid as $newkey){
                $mykey = $newkey->order_item_id;
                $oldval = $newkey->meta_value;
                $newval = $newkey->meta_value + 1;
                wc_update_order_item_meta($mykey, 'Waiting Number', $newval);
            }

        }
        if($newnumber == 1){
            $sqloitmid = $wpdb->get_results("SELECT oim.meta_value, oim.order_item_id, oi.order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta as oim LEFT JOIN {$wpdb->prefix}woocommerce_order_items as oi ON oim.order_item_id = oi.order_item_id  WHERE oim.meta_key = 'Waiting Number' AND oim.meta_value BETWEEN 1 AND $oldnumber AND oi.order_id != $oid2");
            foreach($sqloitmid as $newkey){
                $mykey = $newkey->order_item_id;
                $oldval = $newkey->meta_value;  
                $newval = $newkey->meta_value + 1;
                wc_update_order_item_meta($mykey, 'Waiting Number', $newval);
            }   
        }

    }

    elseif($oldnumber < $newnumber){
        $sqloitmid = $wpdb->get_results("SELECT oim.meta_value, oim.order_item_id, oi.order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta as oim LEFT JOIN {$wpdb->prefix}woocommerce_order_items as oi ON oim.order_item_id = oi.order_item_id  WHERE oim.meta_key = 'Waiting Number' AND oim.meta_value BETWEEN $oldnumber AND $newnumber AND oi.order_id !=$oid2");
        foreach($sqloitmid as $newkey){
            $mykey = $newkey->order_item_id;
            $oldval = $newkey->meta_value;
            $newval = $newkey->meta_value - 1;
            wc_update_order_item_meta($mykey, 'Waiting Number', $newval);
        }
    }

    else{
        die();
    }
}

header("Location: $currenturl");
} 
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable({
            order: [[4, 'asc']],
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ],

            rowCallback: function(row, data, index){

                $(row).find('td:eq(3)').css('background-color', 'rgb(235 233 230)');
                $(row).find('td:eq(4)').css('background-color', 'rgb(235 233 230)');
                $(row).find('td:eq(5)').css('background-color', 'rgb(235 233 230)');

            }

        });
    });

    $(document).on("click", "#btnMyTest001", function (e) {
       $('#my_modal #oid').attr("value", $(this).attr("data-oid"));
       $('#my_modal #wstatus').attr("value", $(this).attr("data-wstatus"));
       $('#my_modal #wnumber').attr("value", $(this).attr("data-wnumber"));
       $("#my_modal #woid").html($(this).attr("data-oid"));
       $("#my_modal #wuserlbl").html($(this).attr("data-wuser"));
       $("#my_modal #wnumberlbl").html($(this).attr("data-wnumber"));
   });
    $(document).on("click", "#btnMyTest002", function (e) {
        var select = $("#swipeno2");
        $('#my_modal2 #oid2').attr("value", $(this).attr("data-oid2"));
        $('#my_modal2 #wstatus2').attr("value", $(this).attr("data-wstatus2"));
        $('#my_modal2 #wnumber2').attr("value", $(this).attr("data-wnumber2"));
        $("#my_modal2 #woid2").html($(this).attr("data-oid2"));
        $("#my_modal2 #wuserlbl2").html($(this).attr("data-wuser2"));
        $("#my_modal2 #wnumberlbl2").html($(this).attr("data-wnumber2"));
        $('#my_modal2 #wswipe2').attr("value", $(this).attr("data-swipe2"));
        var x = $("#wnumber2").val();
        for (i=0;i<=x;i++){
            select.append($('<option></option>').val(i).html(i))
        }
    });


    $('#my_modal2').on('hidden.bs.modal', function () {
        $('#my_modal2 form')[0].reset();
        $("#swipeno2 option").remove(); 
    });





</script>