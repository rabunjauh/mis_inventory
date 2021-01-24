<script src="<?php echo prefix_url; ?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url; ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url; ?>assets/css/datepicker.css" />
<style>
  .current {
    color: #fff !important;
    background-color: #337ab7 !important;
  }

  .choose {
    font-size: 10px;
    font-style: normal;
    border: none;
    background: url(<?php echo prefix_url; ?>assets/images/browse.png) no-repeat;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    color: #ccc;
  }

  .datepicker {
    z-index: 1151 !important;
  }
</style>
<div class="box">
  <div class="box-header">
    <span class="box-title">Request</span>
    <?php if ($this->session->userdata('role')) : ?>
      <div class="content-nav">
        <div class="btn-group col-sm-8 col-xs-10 col-sm-offset-4 col-xs-offset-2">
          <a href="<?= base_url('borrow/formRequestItems'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plus-sign"></i> New Request</a>
          <a href="<?= base_url('csv/downloadRequest'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i><span class="hidden-xs"> Download</span></a>
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
            <th>Request ID</th>
            <th>Employee Status</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Company</th>
            <th>Date Of Join</th>
            <th>Date of Request</th>
            <th>Request Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($results)) : ?>
            <?php foreach ($results as $value) : ?>
              <tr>
                <?php
                if ($value->uid) {
                  $employeeName = $value->employeename;
                  $dateOfJoin = $value->join_date;
                  $position = $value->positionExisting;
                  $department = $value->departmentExisting;
                  $company = $value->companyExisting;
                } else {
                  $employeeName = $value->employeeName;
                  $dateOfJoin = $value->dateOfJoin;
                  $position = $value->positiondesc;
                  $department = $value->deptdesc;
                  $company = $value->company;
                }
                ?>
                <td><?= $value->requestID ?></td>
                <td><?= $value->statusDesc ?></td>
                <td><?= $employeeName ?></td>
                <td><?= $position ?></td>
                <td><?= $department ?></td>
                <td><?= $company ?></td>
                <td><?= $dateOfJoin ?></td>
                <td><?= $value->dateOfRequest ?></td>
                <td><?= $value->approvalDesc ?></td>
                <?php if ($this->session->userdata('role')) : ?>
                  <td>
                    <a href="<?= base_url('borrow/requestDetails/' . $value->requestID); ?>" data-toggle="tooltip" data-placement="top" title="View"><i class="glyphicon glyphicon-eye-open"></i></a>&nbsp;
                    <a href="<?= base_url('borrow/modifyRequest/' . $value->requestID); ?>" data-toggle="tooltip" data-placement="top" title="Modify"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                    <a data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')" href="<?= base_url('borrow/deleteRequest/' . $value->requestID); ?>" class=""><i class="glyphicon glyphicon-trash"></i></a>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="10">
                No Data
              </td>
            </tr>
          <?php endif; ?>
        </tbody>

        <tfoot>

        </tfoot>
      </table>
      <?php if ($request_total > 9) : ?>
        <tr>
          <td colspan="5">
            <?php echo $this->pagination->create_links(); ?>
          </td>
        </tr>
      <?php endif; ?>
    </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->
<form id="form" action="<?php echo base_url() ?>borrow/requesItemsfilter" method="post" enctype="multipart/form-data">
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
            <label for="lblEmployeeStatus" class="control-label">Employee Status:</label>
            <div class="">
              <?php
                foreach ($listEmployeeStatuses as $value) :
              ?>

                <label class="radio-inline"><?= form_radio('radioEmployeeStatus', $value->statusID) . $value->statusDesc ?></label>

              <?php
                endforeach
              ?>
            </div>
          </div>

          <div class="form-group">
            <label for="lblemployee" class="control-label">Employee: </label>
            <div class="">
              <input type="text" class="form-control" readonly="readonly" value="<?php echo (isset($user)) ? $user : '' ?>" name="txtemployeename" id="txtemployeename" placeholder="Employee Name" />
              <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php echo (isset($user_id)) ? $user_id : '' ?>" />
              <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" onclick="open_popup('inventory/employee/');" title="Browse Employee" />
              <button type="button" class="btn btn-xs btn-danger" name="button" id="btnClear" onclick="clearEmployee()">Clear</button>
            </div>
          </div>

          <div class="form-group">
            <label for="borrow_status" class="control-label newEmpStatus">Company:</label>
            <div class="">
              <input type="text" class="form-control newEmpStatus" name="textCompany" id="textCompany" placeholder="Company Name">
            </div>
          </div>

          <div class="form-group">
            <label for="borrow_status" class="control-label">Date Of Join:</label>
            <div class="">
              <input id="txtDateOfJoin" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfJoin" />
            </div>
          </div>

          <div class="form-group">
            <label for="borrow_status" class="control-label">Date Of Request:</label>
            <div class="">
              <input id="txtDateOfRequest" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfRequest" />
            </div>
          </div>

          <div class="form-group">
            <label for="borrow_status" class="control-label">Status:</label>
            <div class="">
              <?php
              $optionApproval = array();
              $optionApproval[''] = 'All';
              foreach ($listApprovals as $listApproval) {
                $optionApproval[$listApproval->approvalID] = $listApproval->approvalDesc;
              }
              ?>
              <?= form_dropdown('dropdownApproval', $optionApproval, '', 'id="dropdownApproval" class="form-control"') ?>
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
  function open_popup(url, value) {
    $('#type_input').val(value);
    window.open('<?php echo prefix_url; ?>' + url, 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
  }

  function clearEmployee() {
    $('#txtemployeename').val('');
    $('#txtempid').val('');
  }

  $('.datepicker').datepicker({
    format: "dd/mm/yyyy"
  });

  $(document).ready(function() {
    function newEmployeeFormValidation() {
      $('.choose').hide();
      $('#btnClear').hide();
      $('.newEmpStatus').show();
      $('#txtemployeename').attr("readonly", false);
    }

    function existingEmployeeFormValidation() {
      $('#txtemployeename').attr("readonly", true);
      $('.choose').show();
      $('#btnClear').show();
      $('.newEmpStatus').hide().prop('required', false);
      $('.employee').show();
    }

    $('#txtemployeename').attr("readonly", false);
    $('.choose').hide();
    $('#btnClear').hide();
    $('input[type=radio][name=radioEmployeeStatus]').change(function() {
      if (this.value == 1) {
        newEmployeeFormValidation();
      } else {
        existingEmployeeFormValidation();
      }
    });
  });
</script>