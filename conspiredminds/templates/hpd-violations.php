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
              $customerTable5 = $table_prefix . 'hpd_violation';
              $data = $wpdb->get_results( "SELECT * FROM $customerTable5");
              if(!empty($data)) {  ?>
                <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th>Isn Dob Bis Viol</th>
                            <th>Boro</th>
                            <th>BIN</th>
                            <th>Block</th>
                            <th>Lot</th>
                            <th>Issue Date</th>
                            <th>Violation Type Code</th>
                            <th>Violation Number</th>
                            <th>House Number</th>
                          </tr>
                        </thead>
                    <tbody>
                      <!-- Display Data from DataBase -->
                      <?php
                        foreach($data as $key => $value){ 
                      ?>
                      <tr>
                        <td><?php echo $value->isn_dob_bis_viol; ?></td>
                        <td><?php echo $value->boro; ?></td>
                        <td><?php echo $value->bin; ?></td>
                        <td><?php echo $value->block; ?></td>
                        <td><?php echo $value->lot; ?></td>
                        <td><?php echo $value->issue_date; ?>
                        <td><?php echo $value->violation_type_code; ?>
                        <td><?php echo $value->violation_number; ?>
                        <td><?php echo $value->house_number; ?>
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