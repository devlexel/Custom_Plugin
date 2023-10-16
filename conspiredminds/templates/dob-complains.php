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
              $customerTable2 = $table_prefix . 'dob_complaint';
              $data = $wpdb->get_results( "SELECT * FROM $customerTable2");
              if(!empty($data)) {  ?>
                <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th>Complaint ID</th>
                            <th>Building ID</th>
                            <th>Borough ID</th>
                            <th>Borough</th>
                            <th>House Number</th>
                            <th>Street Name</th>
                            <th>ZIP</th>
                          </tr>
                        </thead>
                    <tbody>
                      <!-- Display Data from DataBase -->
                      <?php
                        foreach($data as $key => $value){ 
                      ?>
                      <tr>
                        <td><?php echo $value->complaintid; ?></td>
                        <td><?php echo $value->buildingid; ?></td>
                        <td><?php echo $value->boroughid; ?></td>
                        <td><?php echo $value->borough; ?></td>
                        <td><?php echo $value->housenumber; ?></td>
                        <td><?php echo $value->streetname; ?>
                        <td><?php echo $value->zip; ?>
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