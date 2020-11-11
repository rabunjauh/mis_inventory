<script src="<?php echo prefix_url; ?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url; ?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url; ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url; ?>assets/css/datepicker.css" />

<div class="row">
  <div class="col-xs-12">
    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
  </div>
</div>
<div class="row">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">MIS Request Form</h3>
    </div><!-- /.box-header -->
    <div class="box-body">

      <?= form_open_multipart(base_url() . 'items/add_item', 'role="form" class="form-horizontal"'); ?>
      <?php
      // create array for dropdown list value
      $optionDepartment = array();
      $optionDepartment[0] = 'Select Group / Department';
      foreach ($listDepartments as $listDepartment) {
        $optionDepartment[$listDepartment->iddept] = $listDepartment->deptdesc;
      }

      $optionCompany = array();
      $optionCompany[0] = 'Select Company';
      foreach ($Company as $value) {
        $optionCompany[$value->Company_id] = $value->Company;
      }

      $optionDesignation = array();
      $optionDesignation[0] = 'Select Designation';
      foreach ($listPositions as $listPosition) {
        $optionDesignation[$listPosition->idposition] = $listPosition->positiondesc;
      }

      ?>
      <div class="form-group">
        <label for="lblEmployeeStatus" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee Status: *</label>
        <div class="col-sm-6 col-xs-12">
        <label class="radio-inline"><input type="radio" id="radioEmployeeStatus" name="radioEmployeeStatus" value="1">New Staff</label>
        <label class="radio-inline"><input type="radio" id="radioEmployeeStatus" name="radioEmployeeStatus" value="2">Existing Staff</label>
        <label class="radio-inline"><input type="radio" id="radioEmployeeStatus" name="radioEmployeeStatus" value="3">Resignation</label>
        <label class="radio-inline"><input type="radio" id="radioEmployeeStatus" name="radioEmployeeStatus" value="4">Transfer</label>
        </div>
      </div>

      <div class="form-group">
        <label for="lbl_employee" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee Name: *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" readonly="readonly" value="
          <?php 
            if (isset($borrow) && $borrow->employeename) {
              echo $borrow->employeename;
            }
          ?>" 
          name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
          <input type="hidden" name="taken_by_uid" id="txtempid" value="
          <?php 
            if (isset($borrow) && $borrow->taken_by_uid) {
              echo $borrow->taken_by_uid;
            } 
          ?>" />
          <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" onclick="open_popup('inventory/employee/');" title="Browse Employee" />
        </div>
      </div>

      <div class="form-group">
        <label for="labelDepartment" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Group / Department: *</label>
        <div class="col-sm-6 col-xs-12">
          <?= form_dropdown('dropdownDepartment', $optionDepartment, '', 'id="dropdownDepartment" class="form-control"') ?>
        </div>
      </div>

      <div class="form-group">
        <label for="labelDateOfJoin" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Join: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfJoin" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfJoin" />
        </div>
      </div>

      <div class="form-group">
        <label for="lbl_date_of_request" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Request: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfRequest" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfRequest" />
        </div>
      </div>

      <div class="form-group">
        <label for="lblDesignation" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Designation: *</label>
        <div class="col-sm-6 col-xs-12">
          <?= form_dropdown('dropdownDesignation', $optionDesignation, '', 'id="dropdownDesignation" class="form-control"') ?>
        </div>
      </div>

      <div class="form-group stock">
        <label for="lbl_phone" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Office Direct Line/Mobile No. : *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" name="txt_phone" id="txt_phone" class="form-control">
        </div>
      </div>
      <table class="table table-bordered table-striped" id="softwareTable">
        <thead>
          <tr>
            <th>No.</th>
            <th>Items</th>
            <th>Remark</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody id="item_area">
          <tr>
            <td class="numberRow-requestDetails"> </td>
            <td> <?= form_dropdown('dropdownItems', $optionItems, '', 'id="dropdownItems" class="form-control"') ?> </td>
            <td>
              <input type="text" name="textComputerRemark" class="form-control">
            </td>
            <td>
              <button type="button" class="btn btn-danger" onclick="deleteClone(this,'itemsTable')"> Delete </button>
            </td>
          </tr>
        </tbody>
      </table>
      <!--  -->
      <div class="form-group">
        <label class="col-xs-offset-1 col-sm-offset-3">
          <p class="text-muted">* Required fields.</p>
        </label>
      </div>
      <div>
        <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3"><i class="glyphicon glyphicon-ok"></i> Save</button>&nbsp;
        <button type="reset" class="btn btn-default btn-flat">Reset</button>
      </div>
      <?= form_close(); ?>
      <br />
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div><!-- /.row -->
<!-- Modal -->
<div class="modal fade" id="catModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?= form_open_multipart(base_url('items/save_category'), 'role="form" class="form-horizontal"'); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Category</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="col-sm-10 col-xs-12 col-sm-offset-1">
            <?= form_input('cat_name', '', 'id="cat_name" placeholder="Category Name" class="form-control" required') ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary col-xs-6 col-sm-offset-2 btn-flat"><i class="glyphicon glyphicon-ok"></i> Save</button>
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // $('.stock').hide();
    // $('.calibration').hide();
    // $('.maintanance').hide();
    // $('.warranty').hide();
    reNumber('requestDetails');

    function reNumber(table) {
      var i = 1;
      $(".numberRow-" + table).each(function() {
        $(this).text(i);
        i++
      });
    }
    $('.datepicker').datepicker({
      autoclose: true
    });

    function changeMaterial(e) {
      var value = $(e).val();
      if (value == 0) {
        $('.stock').hide();
        $('.calibration').hide();
        $('.maintanance').hide();
        $('.warranty').hide();
        // var clearValue = $('.stock').find(':text');
        // clearValue.each(function(index,data) {
        //   $(data).val('');
        // })
      } else {
        $('.stock').show();
        $('.calibration').show();
        $('.maintanance').show();
        $('.warranty').show();

      }
    }

    function changeStatus(e, type) {
      var value = $(e).val();
      if (value == 0) {
        $('.' + type).hide();
        $('#' + type + '_start').val('');
        $('#' + type + '_end').val('');
      } else {
        $('.' + type).show();
      }
    }

    $()
  });
</script>