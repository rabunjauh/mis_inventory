<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />
<style>
.current{
  color: #fff!important;
  background-color: #337ab7!important;
}
.choose {
  font-size: 10px;
  font-style: normal;
  border:none;
  background:url(<?php echo prefix_url; ?>assets/images/browse.png) no-repeat;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  color:#ccc;
}
.datepicker{z-index:1151 !important;}
</style>
<div class="box">
  <div class="box-header">
    <span class="box-title">Borrow</span>
    <?php if ($this->session->userdata('role')): ?>
      <div class="content-nav">
        <div class="btn-group col-sm-8 col-xs-10 col-sm-offset-3 col-xs-offset-2">
          <a href="<?=base_url('borrow/add'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plus-sign"></i> New Borrow</a>
          <!-- <a href="<?=base_url('inventory/stock'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-ok-sign"></i> Stock</a> -->
          <a href="<?=base_url('inventory/item_in'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plus-sign"></i> Inventory In</a>
          <!-- <a href="<?=base_url('inventory/item_out'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-minus-sign"></i> Inventory Out</a> -->
          <a href="<?=base_url('csv/download_borrow'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i><span class="hidden-xs"> Download</span></a>
          <a href="#" data-toggle="modal" data-target="#filterModal" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-search"></i><span class="hidden-xs"> Filter</span></a>
        </div>
      </div>
    <?php endif; ?>
  </div><!-- /.box-header -->
  <div class="box-body table-responsive">
      <div style="overflow-x:auto;">
          <table id="example" class="table table-bordered table-striped">
              <thead>
                  <tr>
                      <th>Borrow ID</th>
                      <th>Borrow By</th>
                      <th>Project Name</th>
                      <th>Warehouse Name</th>
                      <th>Borrow Date</th>
                      <th>Note</th>
                      <th>Status</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (!empty($results)): ?>
                      <?php foreach ($results as $value): ?>
                          <tr>
                              <td><?php echo $value->borrow_id ?> </td>
                              <td><?php echo $value->employee_name ?> </td>
                              <td><?php echo $value->project_name ?> </td>
                              <td><?php echo $value->warehouse_name ?> </td>
                              <td><?php echo $value->borrow_date ?> </td>
                              <td><?php echo $value->note ?> </td>
                              <td><?php if ($value->borrow_status == 0) {
                                  echo "<p style='color:red;'>Borrowed</p>";
                              }else {
                                  echo "<p style='color:green;'>Returned</p>";
                              } ?> </td>
                              <?php if ($this->session->userdata('role')): ?>
                                  <td>
                                      <a href="<?=base_url('borrow/view/'.$value->borrow_id); ?>" data-toggle="tooltip" data-placement="top" title="View"><i class="glyphicon glyphicon-eye-open"></i></a>&nbsp;
                                      <a href="<?=base_url('borrow/print_view/'.$value->borrow_id); ?>" data-toggle="tooltip" data-placement="top" title="Print"><i class="glyphicon glyphicon-print"></i></a>&nbsp;
                                      <a href="<?=base_url('borrow/return_borrow/'.$value->borrow_id); ?>" data-toggle="tooltip" data-placement="top" title="Return"><i class="glyphicon glyphicon-retweet"></i></a>&nbsp;
                                      <a href="<?=base_url('borrow/modify/'.$value->borrow_id); ?>" data-toggle="tooltip" data-placement="top" title="Modify"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                                      <a data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')" href="<?=base_url('borrow/delete/'.$value->borrow_id); ?>" class=""><i class="glyphicon glyphicon-trash"></i></a>
                                  </td>
                              <?php endif; ?>
                          </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="8">
                              No Data
                          </td>
                      <?php endif; ?>
                  </tbody>
                  <tfoot>

                  </tfoot>
              </table>
              <?php if ($borrow_total > 9): ?>
                  <tr>
                      <td colspan="5">
                          <?php echo $this->pagination->create_links(); ?>
                      </td>
                  </tr>
              <?php endif; ?>
      </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->
<form id="form" action="<?php echo base_url() ?>borrow/filter" method="post"  enctype="multipart/form-data" >
<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter</h4>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <label for="lblemployee" class="control-label">Employee: </label>
          <div class="">
            <input type="text" class="form-control" readonly="readonly" value="<?php echo (isset($user)) ? $user : '' ?>" name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
            <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php echo (isset($user_id)) ? $user_id : '' ?>"/>
            <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;"
            onclick="open_popup();" title="Browse Employee" />
            <button type="button" class="btn btn-xs btn-danger" name="button" onclick="clearEmployee()">Clear</button>
          </div>
        </div>

        <div class="form-group">
          <label for="borrow_status" class="control-label">Date:</label>
          <div class="">
            <input id="borrow_date" name="borrow_date" data-date-format="yyyy-mm-dd" value="<?php echo (isset($borrow_date)) ? $borrow_date : '' ?>"  class="form-control datepicker datepicker-date" placeholder="Borrow Date" type="text"  />
          </div>
        </div>

        <div class="form-group">
          <label for="warehouse_id" class="control-label">Warehouse:</label>
          <div class="">
            <select name="warehouse_id" id="warehouse_id" class="form-control">
              <option value="all" <?php echo (isset($warehouse) && $warehouse == 'all') ? 'selected' : ''; ?> >All</option>
              <?php foreach ($warehouses as $value): ?>
                <option value="<?php echo $value->warehouse_id ?>"  <?php echo (isset($warehouse) && $warehouse == $value->warehouse_id) ? 'selected' : ''; ?> ><?php echo $value->warehouse_name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="project_uid" class="control-label">Project: </label>
          <div class="">
            <select name="project_uid" id="project_uid" class="form-control">
              <option value="all" <?php echo (isset($project_filter) && $project_filter == 'all') ? 'selected' : '' ?> >All</option>
              <?php foreach ($project as $value): ?>
                <option value="<?php echo $value->project_uid ?>" <?php echo (isset($project_filter) && $project_filter == $value->project_uid) ? 'selected' : '' ?> ><?php echo $value->project_name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="borrow_status" class="control-label">Status:</label>
          <div class="">
            <select name="borrow_status" id="borrow_status" class="form-control">
              <option value="all"  <?php echo (isset($borrow_status) && $borrow_status == 'all') ? 'selected' : '' ?> >All</option>
              <option value="0"    <?php echo (isset($borrow_status) && $borrow_status == '0') ? 'selected' : '' ?>  >Borrowed</option>
              <option value="1"    <?php echo (isset($borrow_status) && $borrow_status == '1') ? 'selected' : '' ?>  >Returned</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="">
            <input type="submit" class="btn btn-success" value="Search">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</form>
<script>

function open_popup() {
  window.open('<?php echo prefix_url;?>inventory/employee/', 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
}

function clearEmployee() {
  $('#txtemployeename').val('');
  $('#txtempid').val('');
}
$('.datepicker-date').datepicker();

/*
function submitFilter() {
  var borrow_status = $( "#borrow_status" ).val();
  var project_uid = $( "#project_uid" ).val();
  var warehouse_id = $( "#warehouse_id" ).val();
  var url = "<?php echo base_url()."borrow/filter?"; ?>";

  if (borrow_status == "") {
    url += "borrow_status=all&";
  }else {
    url += "borrow_status="+borrow_status+"&";
  }
  if (project_uid == "") {
    url += "project_uid=all&";
  }else {
    url += "project_uid="+project_uid+"&";
  }
  if (warehouse_id == "") {
    url += "warehouse_id=all";
  }else {
    url += "warehouse_id="+warehouse_id;
  }
  location.replace(url);

}*/
</script>
