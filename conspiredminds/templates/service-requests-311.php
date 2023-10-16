<!-- DataBase Configuration File -->
<div class="cons-data">
  <div class="cd-title">
    <h2>Dashboard</h2>
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
              $customerTable9 = $table_prefix . 'service_request';
              $data = $wpdb->get_results( "SELECT * FROM $customerTable9");
              if(!empty($data)) {  ?>
                <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th>Unique Key</th>
                            <th>Created Date</th>
                            <th>Agency</th>
                            <th>Agency Name</th>
                            <th>Complaint Type</th>
                            <th>Descriptor</th>
                            <th>Location Type</th>
                            <th>Incident Zip</th>
                            <th>Incident Address</th>
                          </tr>
                        </thead>
                    <tbody>
                      <!-- Display Data from DataBase -->
                      <?php
                        foreach($data as $key => $value){ 
                      ?>
                      <tr>
                        <td><?php echo $value->unique_key; ?></td>
                        <td><?php echo $value->created_date; ?></td>
                        <td><?php echo $value->agency; ?></td>
                        <td><?php echo $value->agency_name; ?></td>
                        <td><?php echo $value->complaint_type; ?></td>
                        <td><?php echo $value->descriptor; ?>
                        <td><?php echo $value->location_type; ?>
                        <td><?php echo $value->incident_zip; ?>
                        <td><?php echo $value->incident_address; ?>
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
              <p> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quas consectetur totam ullam accusantium, hic architecto quibusdam. Deleniti magni soluta placeat!</p>
          </div>
      </div>
    </div>
  </div>
</div> 