<!-- DataBase Configuration File -->
<div class="cons-data">
  <div class="cd-title">
    <h2>DOB Violations</h2>
  </div>
  <div class="cd-content">
    <div class="cdc-tab-wrap">
      <div class="cdc-tab-title">
          <ul>
              <li><a href="#all">All</a></li>
              <li><a href="#open">Open</a></li>
              <li><a href="#closed">Closed</a></li>
          </ul>
      </div>
      <div class="cdc-tab-content">
          <div class="cdct-content-box" id="all">
            <!-- Get Data from conspiremind Table -->
            <?php 
              global $table_prefix, $wpdb;
              $customerTable = $table_prefix . 'dob_violation';              
              $data = $wpdb->get_results( "SELECT * FROM $customerTable ");
              if(!empty($data)) {  ?>
                <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th class="bin">BIN</th>
                            <th>Address</th>
                            <th>Issue Date</th>
                            <th>Device#</th>
                            <th>Description</th>
                            <th>Number</th>
                            <th>Violation Category</th>
                            <th>Violation Type</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                    <tbody>
                      <!-- Display Data from DataBase -->
                      <?php
                        foreach($data as $key => $value){ 
                      ?>
                      <tr>
                        <td class="bin"><?php echo $value->bin; ?></td>
                        <td><?php echo $value->house_number ."','". $value->street; ?></td>
                        <td><?php echo $value->issue_date; ?></td>
                        <td><?php echo $value->device_number; ?></td>
                        <td><?php echo $value->description; ?></td>
                        <td><?php echo $value->number; ?>
                        <td><?php echo $value->violation_category; ?>
                        <td><?php echo $value->violation_type; ?>
                        <td><?php echo $value->status; ?>
                      </tr>
                      <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
          </div>
          <div class="cdct-content-box" id="open">
              <h2>Tab 2</h2>
              <p> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quas consectetur totam ullam accusantium, hic architecto quibusdam. Deleniti magni soluta placeat!</p>
          </div>
          <div class="cdct-content-box" id="closed">
              <h2>Tab 3</h2>
              <?php 
              global $table_prefix, $wpdb;
              $customerTable = $table_prefix . 'dob_violation';              
              $data = $wpdb->get_results( "SELECT * FROM $customerTable WHERE status = 0 ");
              if(!empty($data)) {  ?>
                <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th class="bin">BIN</th>
                            <th>Address</th>
                            <th>Issue Date</th>
                            <th>Device#</th>
                            <th>Description</th>
                            <th>Number</th>
                            <th>Violation Category</th>
                            <th>Violation Type</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                    <tbody>
                      <!-- Display Data from DataBase -->
                      <?php
                        foreach($data as $key => $value){ 
                      ?>
                      <tr>
                        <td class="bin"><?php echo $value->bin; ?></td>
                        <td><?php echo $value->house_number ."','". $value->street; ?></td>
                        <td><?php echo $value->issue_date; ?></td>
                        <td><?php echo $value->device_number; ?></td>
                        <td><?php echo $value->description; ?></td>
                        <td><?php echo $value->number; ?>
                        <td><?php echo $value->violation_category; ?>
                        <td><?php echo $value->violation_type; ?>
                        <td><?php echo $value->status; ?>
                      </tr>
                      <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
          </div>
      </div>
    </div>
  </div>
</div> 