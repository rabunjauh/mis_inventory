<script src="<?php echo prefix_url; ?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url; ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url; ?>assets/css/datepicker.css" />

<script>
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
    var allTr = $("#" + table).find('.tr_clone');
    var $clone = $tr.clone();

    $clone.find(':text').val('');
    var number = parseInt($('.numberRow-' + table).last().text());
    $tr.after($clone);
    //$clone.find(':text').attr('required',true);
    // $clone.find('input').val('');
    $clone.find('select').val('');
    reNumber(table);
    $clone.show();
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

  // function ajaxForm(element, targetUrl, ajaxMethod, elementEvent) {
  //   $(element).on(elementEvent, function() {
  //     var id = $(this).val();
  //     // ajax request
  //     $.ajax({
  //       url: targetUrl,
  //       method: ajaxMethod,
  //       if (ajaxMethod = "POST") {
  //         data: {
  //           id: id
  //         }
  //       },
  //       async: true,
  //       dataType: 'json',
  //       success: function(data) {
  //         console.log(data);
  //           // $('#dropdownDesignation').html(data);
  //           // let html = '';
  //           // for (let i = 0; i < data.length; i++) {
  //           //   html += '<option value=' + data[i].idposition + '>' + data[i].positiondesc + '</option>';
  //           // }
  //           // $('#dropdownDesignation').html(html);
  //       }
  //     });
  //     return false;
  //   });
  // }

  // function ajaxForm(element, targetUrl, ajaxMethod, elementEvent) {

  // }
</script>

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

      <?= form_open_multipart(base_url() . 'borrow/formRequestItems', 'role="form" class="form-horizontal"'); ?>
      <?php
      // create array for dropdown list value
      $optionDepartment = array();
      $optionDepartment[0] = 'Select Group / Department';
      foreach ($listDepartments as $listDepartment) {
        $optionDepartment[$listDepartment->iddept] = $listDepartment->deptdesc;
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
          <label class="radio-inline"><?= form_radio('radioEmployeeStatus', '1', TRUE); ?>New Staff</label>
          <label class="radio-inline"><?= form_radio('radioEmployeeStatus', '2', FALSE); ?>Existing Staff</label>
          <label class="radio-inline"><?= form_radio('radioEmployeeStatus', '3', FALSE); ?>Resignation</label>
          <label class="radio-inline"><?= form_radio('radioEmployeeStatus', '4', FALSE); ?>Transfer</label>
          <?php foreach ($listEmployeeStatuses as $listEmployeeStatus): ?>

          <?php endforeach ?>  
        </div>
      </div>

      <div class="form-group empStatus">
        <label for="lblemployee" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee: </label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" readonly="readonly" value="<?php if (isset($borrow) && $borrow->employeename) {
                                                                                echo $borrow->employeename;
                                                                              } ?>" name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
          <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php if (isset($borrow) && $borrow->taken_by_uid) {
                                                                          echo $borrow->taken_by_uid;
                                                                        } ?>" />
          <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" onclick="open_popup('inventory/employee/');" title="Browse Employee" />
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="textCompany" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Company: *</label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" name="textCompany" id="textCompany" placeholder="Company Name">
        </div>
      </div>

      <div class="form-group newEmpStatus">
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
        <label for="labelDateOfRequest" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Request: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="txtDateOfRequest" readonly data-date-format="yyyy-mm-dd" class="form-control datepicker" type="text" name="txtDateOfRequest" />
        </div>
      </div>

      <div class="form-group newEmpStatus">
        <label for="labelDesignation " class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Designation: *</label>
        <div class="col-sm-6 col-xs-12">
          <?= form_dropdown('dropdownDesignation', $optionDesignation, '', 'id="dropdownDesignation" class="form-control"') ?>
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
            <td><input type="text" name="textItems[]" class="form-control textItems " placeholder="Request Items"></td>
            <td><input type="text" name="textRemarks[]" class="form-control" placeholder="Items Remark"></td>
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
            console.log(html);
          }
          console.log(html)
          $('#dropdownDesignation').html(html);
        }
      });
      return false;
    });
    // tes
    // document.querySelector('input[list]').addEventListener('input', function(e) {
    //   var input = e.target,
    //     list = input.getAttribute('list'),
    //     options = document.querySelectorAll('#' + list + ' option'),
    //     hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
    //     inputValue = input.value;
    //   hiddenInput.value = inputValue;

    //   for (var i = 0; i < options.length; i++) {
    //     var option = options[i];

    //     if (option.innerText === inputValue) {
    //       hiddenInput.value = option.getAttribute('data-value');
    //       console.log(hiddenInput.value);
    //       break;
    //     }
    //   }
    // });
    // // 
  });
</script>