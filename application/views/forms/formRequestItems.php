<script src="<?php echo prefix_url; ?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url; ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url; ?>assets/css/datepicker.css" />

<style>
  .choose {
    font-size: 10px;
    font-style: normal;
    border: none;
    background: url(<?php echo prefix_url; ?>assets/images/browse.png) no-repeat;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    color: #ccc;
  }
</style>

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
      <?php
      if (!empty($request)) {
        echo form_open_multipart(base_url() . 'borrow/updateRequest', 'role="form" class="form-horizontal"');
        $textRequestID = array(
          'name' => 'textRequestID',
          'id' => 'textRequestID',
          'type' => 'hidden',
          'value' => $request->requestID
        );

        echo form_input($textRequestID);
      } else {
        echo form_open_multipart(base_url() . 'borrow/formRequestItems', 'role="form" class="form-horizontal"');
      }
      // create array for dropdown list value      
      if (!empty($request)) {
        if (!empty($request->department)) {
          $departmentSelected = $request->department;
        } elseif (!empty($request->idDepartmentExisting)) {
          $departmentSelected = $request->idDepartmentExisting;
        }

        if (!empty($request->designation)) {
          $designationSelected = $request->designation;
        } elseif (!empty($request->idPositionExisting)) {
          $designationSelected = $request->idPositionExisting;
        }
      } else {
        $selected = '';
      }

      if (!empty($request->uid)) {
        $employeeName = $request->employeename;
        $dateOfJoin = $request->join_date;
        $position = $request->positionExisting;
        $department = $request->departmentExisting;
        $company = $request->companyExisting;
      } else {
        $employeeName = $request->employeeName;
        $dateOfJoin = $request->dateOfJoin;
        $position = $request->positiondesc;
        $department = $request->deptdesc;
        $company = $request->company;
      }

      $optionDepartment = array();
      $optionDepartment[''] = 'Select Group / Department';
      foreach ($listDepartments as $listDepartment) {
        $optionDepartment[$listDepartment->iddept] = $listDepartment->deptdesc;
      }

      $optionDesignation = array();
      $optionDesignation[''] = 'Select Designation';
      foreach ($listPositions as $listPosition) {
        $optionDesignation[$listPosition->idposition] = $listPosition->positiondesc;
      }

      $optionRequestItems = array();
      $optionRequestItems[''] = 'Select Items';
      foreach ($listRequestItemsSuggestion as $listRequestItemSuggestion) {
        $optionRequestItems[$listRequestItemSuggestion->suggestionID] = $listRequestItemSuggestion->suggestion;
      }
      ?>


      <div class="form-group">
        <label for="lblEmployeeStatus" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee Status: *</label>
        <div class="col-sm-6 col-xs-12">
        
          <?php
            foreach ($listEmployeeStatuses as $value) :
              if (!empty($request->employeeStatus)) {
                ($request->employeeStatus == $value->statusID) ? $checked = TRUE : $checked = FALSE;
              } else {
                ($value->statusID == 1) ? $checked = TRUE : $checked = FALSE;
              }
          ?>

            <label class="radio-inline"><?= form_radio('radioEmployeeStatus', '$value->statusID', $checked) . $value->statusDesc ?></label>
          
          <?php
            endforeach
          ?>

        </div>
      </div>

      <div class="form-group empStatus">
        <label for="lblemployee" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee: *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" readonly="readonly" value="<?php echo $employeeName ?>" name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
          <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php if (!empty($request) && $request->uid) {
                                                                          echo $request->uid;
                                                                        } ?>" />
          <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" onclick="open_popup('inventory/employee/');" title="Browse Employee" />
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="textCompany" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Company: *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control newEmpStatus" name="textCompany" value="<?php echo $company; ?>" id="textCompany" placeholder="Company Name" required>
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDepartment" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Group / Department: *</label>
        <div class="col-sm-6 col-xs-12">
          <?= form_dropdown('dropdownDepartment', $optionDepartment, $departmentSelected, 'id="dropdownDepartment" class="form-control newEmpStatus" required') ?>
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDateOfJoin" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Join: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfJoin" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker newEmpStatus" type="text" name="txtDateOfJoin" value="<?php echo $dateOfJoin;  ?>" />
        </div>
      </div>

      <div class="form-group">
        <label for="labelDateOfRequest" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Request: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfRequest" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfRequest" value="<?php if (!empty($request) && $request->dateOfRequest) {
                                                                                                                                                            echo $request->dateOfRequest;
                                                                                                                                                          } ?>" required />
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDesignation " class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Designation: *</label>
        <div class="col-sm-6 col-xs-12">
          <?= form_dropdown('dropdownDesignation', $optionDesignation, $designationSelected, 'id="dropdownDesignation" class="form-control newEmpStatus" required') ?>
        </div>
      </div>

      <div class="form-group">
        <label for="labelPhone" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Office Direct Line / Mobile No. : *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" name="txtPhone" value="<?php if (!empty($request) && $request->phone) {
                                                      echo $request->phone;
                                                    } ?>" id="txtPhone" class="form-control">
        </div>
      </div>

      <table class="table table-bordered table-striped" id="requestItems">
        <thead>
          <tr>
            <th>No.</th>
            <th>Items</th>
            <th>Remark</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="item_area">
          <?php if (!empty($requestDetails)) : ?>
            <?php foreach ($requestDetails as $requestDetail) : ?>
              <tr class="tr_clone">
                <td class="numberRow-requestItems"> </td>
                <td class="itemsColumn"><?= form_dropdown('optionRequestItems[]', $optionRequestItems, $requestDetail->items, 'id="optionRequestItems" class="form-control optionRequestItems" required') ?></td>
                <td class="remarkColumn"><input type="text" name="textRemarks[]" class="form-control textRemark" placeholder="Items Remark" value="<?php echo $requestDetail->remarks; ?>"></td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="deleteClone(this,'requestItems')"> Delete </button>
                </td>
              </tr>
            <?php endforeach ?>
          <?php else : ?>
            <tr class="tr_clone">
              <td class="numberRow-requestItems"> </td>
              <td class="itemsColumn"><?= form_dropdown('optionRequestItems[]', $optionRequestItems, '', 'id="optionRequestItems" class="form-control optionRequestItems" required') ?></td>
              <td class="remarkColumn"><input type="text" name="textRemarks[]" class="form-control textRemark" placeholder="Items Remark"></td>
              <td>
                <button type="button" class="btn btn-danger" onclick="deleteClone(this,'requestItems')"> Delete </button>
              </td>
            </tr>
          <?php endif ?>
        </tbody>
      </table>
      <a class="btn btn-default btn-flat" type="button" onclick="addRow('requestItems');"><i class="glyphicon glyphicon-plus-sign"></i> Add More Row </a>
      <!--  -->
      <div class="form-group">
        <label class="col-xs-offset-1 col-sm-offset-3">
          <p class="text-muted">* Required fields.</p>
        </label>
      </div>
      <div>
        <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3" id="saveRequest"><i class="glyphicon glyphicon-ok"></i> Save </button>&nbsp;
        <button type="reset" class="btn btn-default btn-flat"> Reset </button>
      </div>
      <?= form_close(); ?>
      <br />
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div><!-- /.row -->

<script>
  $(document).ready(function() {

    $('#txtDateOfJoin').datepicker({
      format: "dd/mm/yyyy"
    });


    $('#txtDateOfRequest').datepicker({
      format: "dd/mm/yyyy"
    });

    reNumber('requestItems');

    $('#txtemployeename').attr("readonly", false);
    $('.choose').hide();
    $('input[type=radio][name=radioEmployeeStatus]').change(function() {
      if (this.value == 1) {
        $('.choose').hide();
        $('.newEmpStatus').show();
        $('#txtemployeename').attr("readonly", false);
      } else {
        $('#txtemployeename').attr("readonly", true);
        $('.choose').show();
        $('.newEmpStatus').hide().prop('required', false);
        $('.employee').show();
      }
    });

    var radioValChecked = $('input[type=radio][name=radioEmployeeStatus]:checked').val();
    if (radioValChecked == 1) {
      $('.choose').hide();
      $('.newEmpStatus').show();
      $('#txtemployeename').attr("readonly", false);
    } else {
      $('#txtemployeename').attr("readonly", true);
      $('.choose').show();
      $('.newEmpStatus').hide().prop('required', false);
      $('.employee').show();
    }

    $('#dropdownDepartment').change(function() {
      var id = $(this).val();
      // ajax request
      $.ajax({
        url: "<?= base_url('borrow/getDesignationByID'); ?>",
        method: "POST",
        data: {
          id: id
        },
        async: true,
        dataType: 'json',
        success: function(data) {
          $('#dropdownDesignation').html(data);
          let optVal = '';
          let optText = 'Select Designation';
          let html = '<option value=' + optVal + '>' + optText + '</option>';
          for (let i = 0; i < data.length; i++) {
            html += '<option value=' + data[i].idposition + '>' + data[i].positiondesc + '</option>';
          }
          $('#dropdownDesignation').html(html);
        }
      });
      return false;
    });
  });

  function open_popup(url, value) {
    $('#type_input').val(value);
    window.open('<?php echo prefix_url; ?>' + url, 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
  }

  function reNumber(table) {
    var i = 1;
    $(".numberRow-" + table).each(function() {
      $(this).text(i);
      i++
    });
  }

  function addRow(table) {
    var $tr = $("#" + table).find('.tr_clone').last();
    // var allTr = $("#" + table).find('.tr_clone');
    var $clone = $tr.clone();

    $clone.find(':text').val('');
    // var number = parseInt($('.numberRow-' + table).last().text());
    $tr.after($clone);
    $clone.find('.textRemark').attr('placeholder', 'Items Remark');
    //$clone.find(':text').attr('required',true);
    // $clone.find('input').val('');
    // $clone.find('select').val('');
    reNumber(table);
    $clone.show();

    itemsValidation();
    // $('.optionRequestItems').change(function(e) {
    //   console.log(e.value);
    // });
  }

  function get_arr_obj() {
    $('.master_clone').each(function(index, obj) {
      var target = $(obj);
      var code = target.find('.item_code').val();
      var qty = target.find('.quantities').val();
      arrObj[code] = [];
      arrObj[code]['qty'] = qty;
      arrObj[code]['target'] = target;
    })
  }

  var arrObj = [];
  get_arr_obj();

  function deleteClone(e, table) {
    var allTr = $("#" + table).find('.tr_clone');
    var $tr = $(e).closest(".tr_clone");
    var value_code = $tr.find('.item_code').val();
    if (arrObj[value_code]) {
      arrObj.splice(value_code, 1)
    }
    if (allTr.length > 1) {
      var $remove = $tr.remove();
      reNumber(table);
    } else {
      check_master = $("#" + table).find('.master_clone');
      if (check_master.length == 1) {
        check_master.find(':input').val('');
        check_master.hide();
      }
    }
  }


  // let saveBtn = document.getElementById('saveRequest');



  // $(saveBtn).click(function(e) {

  //   e.preventDefault();
  // });

  function itemsValidation() {
    let optionRequestItems = document.querySelectorAll('.optionRequestItems');
    let itemRemarkValidation = document.querySelectorAll('.textRemark');
    for (let i = 0; i < optionRequestItems.length; i++) {
      optionRequestItems[i].addEventListener('change', function() {
        let validationPlaceholder = '';
        if (optionRequestItems[i].value === '1') {
          let validationRequired = itemRemarkValidation[i].setAttribute('required', true);
          validationPlaceholder += 'Desktop / Laptop';
          itemRemarkValidation[i].value = '';
        } else if (optionRequestItems[i].value === '2') {
          validationPlaceholder += 'Email Address';
          itemRemarkValidation[i].value = '';
        } else {
          validationPlaceholder += 'Items Remark';
          let validationRequired = itemRemarkValidation[i].setAttribute('required', false);
          itemRemarkValidation[i].value = '';
        }

        itemRemarkValidation[i].setAttribute('Placeholder', validationPlaceholder);
        // validationRequired;
      });
    }
  }

  // function datepicker(dateFormID) {
  //    $('#' + dateFormID).datepicker({
  //     format: "dd/mm/yyyy"
  //   });
  // }

  itemsValidation();
</script>