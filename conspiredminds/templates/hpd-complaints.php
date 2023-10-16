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
              $customerTable4 = $table_prefix . 'hpd_complaint';
              $data = $wpdb->get_results( "SELECT * FROM $customerTable4");
              if(!empty($data)) {  ?>
                <table id="pbs" class="display table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th>Problem ID</th>
                            <th>Complaint ID </th>
                            <th>Unit ID</th>
                            <th>Unit Type</th>
                            <th>Spacetype ID</th>
                            <th>Spacetype</th>
                            <th>Type ID</th>
                            <th>Type</th>
                          </tr>
                        </thead>
                    <tbody>
                      <!-- Display Data from DataBase -->
                      <?php
                        foreach($data as $key => $value){ 
                      ?>
                      <tr>
                        <td><?php echo $value->problemid; ?></td>
                        <td><?php echo $value->complaintid; ?></td>
                        <td><?php echo $value->unittypeid; ?></td>
                        <td><?php echo $value->unittype; ?></td>
                        <td><?php echo $value->spacetypeid; ?></td>
                        <td><?php echo $value->spacetype; ?>
                        <td><?php echo $value->typeid; ?>
                        <td><?php echo $value->type; ?>
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