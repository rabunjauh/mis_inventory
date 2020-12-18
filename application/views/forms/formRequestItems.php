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
        if(isset($request)){
          echo form_open_multipart(base_url() . 'borrow/updateRequest', 'role="form" class="form-horizontal"'); 
        }else{
          echo form_open_multipart(base_url() . 'borrow/formRequestItems', 'role="form" class="form-horizontal"'); 
        }  
      // create array for dropdown list value
      $optionDepartment = array();
      // if (isset($request) && ($request->department || $request->idDepartmentExisting)) {
      //   if (isset($request->department) ){
      //     $departmentID = $request->department;
      //     $departmentDesc = $request->deptdesc;
      //   }elseif (isset($request->idDepartmentExisting)) {
      //     $departmentID = $request->idDepartmentExisting;
      //     $departmentDesc = $request->departmentExisting;
      //   } 
      //   $optionDepartment['departmentID'] = $departmentDesc;
      //   foreach ($listDepartments as $listDepartment) {
      //     $optionDepartment[$listDepartment->iddept] = $listDepartment->deptdesc;
      //   }
      // }else{
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

          <?php foreach ($listEmployeeStatuses as $listEmployeeStatus) : ?>
            <label class="radio-inline"><?= form_radio('radioEmployeeStatus', $listEmployeeStatus->statusID, $checked = $listEmployeeStatus->statusID == '1' ? TRUE : FALSE) . $listEmployeeStatus->statusDesc; ?></label>
          <?php endforeach ?>
        </div>
      </div>

      <div class="form-group empStatus">
        <label for="lblemployee" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee: *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" readonly="readonly" value="<?php if (isset($request) && $request->employeeName) {
                                                                                echo $request->employeeName;
                                                                              } ?>" name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
          <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php if (isset($request) && $request->uid) {
                                                                          echo $request->uid;
                                                                        } ?>" />
          <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" onclick="open_popup('inventory/employee/');" title="Browse Employee" />
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="textCompany" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Company: *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" name="textCompany" value="<?php if(isset($request) && $request->company){ echo $request->company; } ?>" id="textCompany" placeholder="Company Name">
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDepartment" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Group / Department: *</label>
        <div class="col-sm-6 col-xs-12">
         <?= form_dropdown('dropdownDepartment', $optionDepartment, $selected, 'id="dropdownDepartment" class="form-control" required') ?>
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDateOfJoin" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Join: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfJoin" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfJoin" />
        </div>
      </div>

      <div class="form-group">
        <label for="labelDateOfRequest" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Request: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfRequest" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfRequest" />
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDesignation " class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Designation: *</label>
        <div class="col-sm-6 col-xs-12">
          <?= form_dropdown('dropdownDesignation', $optionDesignation, '', 'id="dropdownDesignation" class="form-control" required') ?>
        </div>
      </div>

      <div class="form-group">
        <label for="labelPhone" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Office Direct Line / Mobile No. : *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" name="txtPhone" id="txtPhone" class="form-control">
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
          <tr class="tr_clone">
            <td class="numberRow-requestItems"> </td>
            <!-- <td><input type="text" name="textItems[]" class="form-control textItems" placeholder="Request Items"></td> -->
            <td class="itemsColumn"><?= form_dropdown('optionRequestItems[]', $optionRequestItems, '', 'id="optionRequestItems" class="form-control optionRequestItems" required') ?></td>
            <td class="remarkColumn"><input type="text" name="textRemarks[]" class="form-control textRemark" placeholder="Items Remark"></td>
            <td>
              <button type="button" class="btn btn-danger" onclick="deleteClone(this,'requestItems')"> Delete </button>
            </td>
          </tr>
        </tbody>
      </table>
      <a class="btn btn-default btn-flat" type="button" onclick="addRow('requestItems');"><i class="glyphicon glyphicon-plus-sign"></i> Add More Row</a>
      <!--  -->
      <div class="form-group">
        <label class="col-xs-offset-1 col-sm-offset-3">
          <p class="text-muted">* Required fields.</p>
        </label>
      </div>
      <div>
        <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3" id="saveRequest"><i class="glyphicon glyphicon-ok"></i> Save</button>&nbsp;
        <button type="reset" class="btn btn-default btn-flat">Reset</button>
      </div>
      <?= form_close(); ?>
      <br />
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div><!-- /.row -->

<script>
  $(document).ready(function() {
    $('.datepicker').datepicker({
      autoclose: true
    });

    reNumber('requestItems');

    $('#txtemployeename').attr("readonly", false);
    $('.choose').hide();
    $('input[type=radio][name=radioEmployeeStatus]').change(function() {
      if (this.value == 1 || this.value == 4) {
        $('.choose').hide();
        $('.newEmpStatus').show();
        $('#txtemployeename').attr("readonly", false);
        $('#txtemployeename').val('');
      } else {
        $('#txtemployeename').attr("readonly", true);
        $('.choose').show();
        $('.newEmpStatus').hide();
        $('.employee').show();
      }
    });

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
          let html = '';
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
        } else if (optionRequestItems[i].value === '2') {
          validationPlaceholder += 'Email Address';
        } else {
          validationPlaceholder += 'Items Remark';
          let validationRequired = itemRemarkValidation[i].setAttribute('required', false);
        }

        itemRemarkValidation[i].setAttribute('Placeholder', validationPlaceholder);
        validationRequired;
      });
    }
  }

  itemsValidation();
</script>