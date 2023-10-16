<!-- DataBase Configuration File -->
<div class="table">
    <div class="table-data">
        <div class="cons-data">
            <div class="cd-title">
                <h2>Dashboard</h2>
            </div>
            <div class="cd-content">
                <div class="cdc-tab-wrap">
                    <div class="cdc-tab-title">
                        <ul>
                            <li><a href="#property-list">Property List</a></li>
                            <li><a href="#manage-property">Manage Properties</a></li>
                            <li><a href="#property-summary">Property Summary</a></li>
                            <li><a href="#setting">Settings</a></li>
                        </ul>
                    </div>
                    <div class="cdc-tab-content">
                        <div class="cdct-content-box" id="property-list">
                            <h2>Property List</h2>
                            <!-- Get Data from Table -->
                            <?php 
                            global $table_prefix, $wpdb;
                            $customerTable = $table_prefix . 'dob_violation';
                            $user_id = get_current_user_id();
                            $bin = ( get_user_meta( $user_id, 'bin', true ) ) ? get_user_meta( $user_id, 'bin', true ) : '';
                            $data = $wpdb->get_results("SELECT * FROM $customerTable WHERE bin = $bin ");
                            if(!empty($data)) {  ?>
                            <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Address</th>
                                        <th>Sync Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Display Data from DataBase -->
                                    <?php
                                      foreach($data as $key => $value){ 
                                    ?>
                                    <tr>    
                                        <td><?php echo $value->house_number ."','". $value->street; ?></td>
                                        <td><?php echo $value->status; ?></td>
                                        <td><a class="view-icon dashboard" href="#property-summary"></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                        <div class="cdct-content-box" id="manage-property">
                            <h2>Manage Properties</h2>
                            <div class="mp-tab-title">
                                <ul>
                                    <li><a href="#add-property">Add Property</a></li>
                                    <li><a href="#dlt-property">Delete Property</a></li>
                                </ul>
                            </div>
                            <div class="mange-prop-content">
                                <div id="add-property" class="mange-prop-box">
                                    <h2>Add Property</h2>
                                    <?php
                                        $user_id = get_current_user_id();
                                        $bin = ( get_user_meta( $user_id, 'bin', true ) ) ? get_user_meta( $user_id, 'bin', true ) : '';
                                    ?>
                                    <form id="property-mng" action="" method="post">
                                        <label for="bin">BIN Number:
                                            <input id="bin" type="text" name="bin" value="<?php echo $bin; ?> ">
                                        </label>
                                        <input type="submit" id="submit" name="submit" value="Submit">
                                    </form>
                                    <?php 
                                        if ( ! function_exists( 'insert_update_user_meta' ) ) {
                                            /**
                                             * Creates a meta key and inserts the meta value.
                                             * If the passed meta key already exists then updates the meta value.
                                             */
                                            function insert_update_user_meta( $user_id, $meta_key, $meta_value ) {
                                        
                                            // Add data in the user meta field.
                                            $meta_key_not_exists = add_user_meta( $user_id, $meta_key, $meta_value, true );
                                        
                                            // If meta key already exists then just update the meta value for and return true
                                            if ( ! $meta_key_not_exists ) {
                                                update_user_meta( $user_id, $meta_key, $meta_value );
                                                return true;
                                            }
                                            }
                                        }   
                                        // If the form is submitted
                                        if ( isset( $_POST['submit'] ) ) {
                                            
                                            // Get form values.
                                            $bin = ( ! empty( $_POST['bin'] ) ) ? sanitize_text_field( $_POST['bin'] ) : '';
                                            
                                            // Insert/Update the form values to user_meta table.
                                            insert_update_user_meta( $user_id, 'bin', $bin );

                                            // Once everything is done redirect the user back to the same page
                                            $location = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                                            wp_safe_redirect( $location );
                                            exit;
                                        } 
                                    ?>  
                                </div>
                                <form method="post" action="<?php echo admin_url('admin.php?page=sample-page'); ?>">
                                    <div id="dlt-property" class="mange-prop-box">
                                        <h2>Delete Property</h2>
                                        <?php
                                            global $table_prefix, $wpdb;
                                            $customerTable = $table_prefix . 'dob_violation';
                                            $user_id = get_current_user_id();
                                            $bin = ( get_user_meta( $user_id, 'bin', true ) ) ? get_user_meta( $user_id, 'bin', true ) : '';
                                            $data = $wpdb->get_results("SELECT * FROM $customerTable WHERE bin = $bin");
                                            if(!empty($data)) { 

                                            ### DELETE SINGLE ROW DATA FROM TABLE  ####

                                            
                                            if (isset($_GET['id'])){
                                                $id = $_GET['id'];
                                                $wpdb->get_results("DELETE FROM $customerTable WHERE id = $id");
                                            } ?>

                                            <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Address</th>
                                                        <th>Sync Status</th>
                                                        <th>View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Display Data from DataBase -->
                                                <?php
                                                    foreach($data as $key => $value){ 
                                                ?>
                                                <tr> 
                                                    <td><?php echo $value->id; ?></td>  
                                                    <td><?php echo $value->house_number ."','". $value->street; ?></td>
                                                    <td><?php echo $value->status; ?></td>
                                                    <td><a class="btn btn-danger" href="admin.php?page=sample-page&id=<?php echo $value->id; ?>"> DELETE </a></td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>              
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="cdct-content-box" id="property-summary">
                            <h2>Property Summary</h2>
                            <?php
                            global $table_prefix, $wpdb;
                            $customerTable = $table_prefix . 'dob_violation';
                            $user_id = get_current_user_id();
                            $bin = ( get_user_meta( $user_id, 'bin', true ) ) ? get_user_meta( $user_id, 'bin', true ) : '';
                            $data = $wpdb->get_results( "SELECT * FROM $customerTable WHERE bin = $bin");
                            $count = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable" );                            
                            if(!empty($data)) {  ?>
                            <table id="pbs" class="display table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Display Data from DataBase -->
                                    <?php
                                        foreach($data as $key => $value){ 
                                    ?>
                                    <tr>
                                        <td>Address</td>
                                        <td><?php echo $value->house_number ."','". $value->street; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>DOB COMP.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable2 = $table_prefix . 'dob_complaint'; 
                                               $count2 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable2" );       
                                            ?>
                                            <a class="view-icon" href="<?php echo get_home_url();?>/wp-admin/admin.php?page=dob-complains" target="_blank"><?php echo $count2; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>DOB VIOL.</td>
                                        <td><a class="view-icon" href="<?php echo get_home_url();?>/wp-admin/admin.php?page=dob-violations" target="_blank"><?php echo $count; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ECB VIOL.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable3 = $table_prefix . 'ecb_violation'; 
                                               $count3 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable3" );       
                                            ?>
                                            <a class="view-icon" href="<?php echo get_home_url();?>/wp-admin/admin.php?page=ecb-violations" target="_blank"><?php echo $count3; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>HPD COMP.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable4 = $table_prefix . 'hpd_complaint'; 
                                               $count4 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable4" );       
                                            ?>
                                            <a class="view-icon" href="<?php echo get_home_url();?>/wp-admin/admin.php?page=hpd-complaints" target="_blank"><?php echo $count4; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>HPD VIOL.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable5 = $table_prefix . 'hpd_violation'; 
                                               $count5 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable5" );       
                                            ?>
                                            <a class="view-icon" href="<?php echo get_home_url();?>/wp-admin/admin.php?page=hpd-violations" target="_blank"><?php echo $count5; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LANDMARK COMP.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable6 = $table_prefix . 'landmark_complain'; 
                                               $count6 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable6" );       
                                            ?>
                                            <a class="view-icon" href="#" target="_blank"><?php echo $count6; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LANDMARK VIOL.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable7 = $table_prefix . 'landmark_violation'; 
                                               $count7 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable7" );       
                                            ?>
                                            <a class="view-icon" href="#" target="_blank"><?php echo $count7; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>OATH HEAR.</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable8 = $table_prefix . 'oath_hearing'; 
                                               $count8 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable8" );       
                                            ?>
                                            <a class="view-icon" href="#" target="_blank"><?php echo $count8; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SER.311</td>
                                        <td>
                                            <?php 
                                               global $table_prefix, $wpdb;
                                               $customerTable9 = $table_prefix . 'service_request'; 
                                               $count9 = $wpdb->get_var( "SELECT COUNT(*) FROM $customerTable9" );       
                                            ?>
                                            <a class="view-icon" href="<?php echo get_home_url();?>/wp-admin/admin.php?page=service-requests" target="_blank"><?php echo $count9; ?></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                        <div class="cdct-content-box" id="setting">
                            <h2>Setting</h2>
                            <p> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quas consectetur totam ullam
                                accusantium, hic architecto quibusdam. Deleniti magni soluta placeat!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="current-loggedin-user">
    <div class="user-data">
        <?php global $current_user;
        $current_user = wp_get_current_user();
        // echo "UserID:".$current_user->ID;
        echo "Username :".$current_user->display_name;
        echo get_avatar( $current_user->ID);

        ?>
        <div class="user-profile-button">
            <a href="https://pbsnyc.conspiredminds.com/wp-admin/profile.php?wp_http_referer=%2Fwp-admin%2Fusers.php">Change
                Profile Photo</a>
        </div>
        <div class="user-properties">
            <div class="property-data">
                <h4>Total Properties</h4>
                <h4>Balance</h4>
                <h4>Hearings</h4>
            </div>
            <div class="user-edit-profile">
                <a
                    href="https://pbsnyc.conspiredminds.com/wp-admin/profile.php?wp_http_referer=%2Fwp-admin%2Fusers.php">Edit
                    Profile</a>
            </div>
        </div>
    </div>
</div>