<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />

<script>

function open_popup(url,value) {
    $('#type_input').val(value);
    window.open('<?php echo prefix_url;?>'+url, 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
}

</script>
<style>
.choose {
  font-size: 10px;
  font-style: normal;
  border:none;
  background:url(<?php echo prefix_url; ?>assets/images/browse.png) no-repeat;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  color:#ccc;
}
</style>
<div class="row">
  <div class="col-xs-12">
    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
  </div>
</div>
<div class="row">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Borrow</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
      <?php if (isset($borrow)): ?>
        <?=form_open(base_url().'borrow/update/'.$borrow->borrow_id, 'role="form" class="form-horizontal"'); ?>
      <?php else: ?>
        <?=form_open(base_url().'borrow/add', 'role="form" class="form-horizontal"'); ?>
      <?php endif; ?>
      <?php

      ?>
      <div class="form-group">
        <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Warehouse: *</label>
        <div class="col-sm-6 col-xs-12">
          <select name="warehouse_id" class="form-control" required>
            <option value="">Select Warehouse ...</option>
            <?php foreach ($warehouses as $value): ?>
              <option value="<?php echo $value->warehouse_id ?>" <?php if(isset($borrow) && $borrow->warehouse_id == $value->warehouse_id){echo "selected";} ?>><?php echo $value->warehouse_name; ?></option>
            <?php endforeach; ?>
          </select>
        </div><span class="visible-xs"><br/><br/></span>
      </div>
      <div class="form-group">
        <label for="lblemployee" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee: </label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" class="form-control" readonly="readonly" value="<?php if(isset($borrow) && $borrow->employeename){echo $borrow->employeename;} ?>" name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
          <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php if(isset($borrow) && $borrow->taken_by_uid){echo $borrow->taken_by_uid;} ?>"/>
          <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;"
          onclick="open_popup('inventory/employee/');" title="Browse Employee" />
        </div>
      </div>
      <div class="form-group">
        <label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Borrow Date: *</label>
        <div class="col-sm-6 col-xs-12">
          <input id="borrow_date" required readonly data-date-format="yyyy-mm-dd" value="<?php if(isset($borrow) && $borrow->borrow_date){echo $borrow->borrow_date;} ?>"  class="form-control datepicker" placeholder="Borrow Date" type="text" name="borrow_date" />
        </div>
      </div>
      <div class="form-group">
        <label for="lblproject" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Project: </label>
        <div class="col-sm-6 col-xs-12">
          <select name="project_uid" class="form-control" required>
            <option value="">Select Project ...</option>
            <?php foreach ($project as $value): ?>
              <option value="<?php echo $value->project_uid ?>" <?php if(isset($borrow) && $borrow->project_uid == $value->project_uid){echo "selected";} ?>><?php echo $value->project_name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <input type="hidden" name="type_input" id="type_input" value="">
      <div class="form-group">
        <label for="lblproject" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Item: </label>
        <div class="col-sm-6 col-xs-12">
            <input type="text" class="form-control" readonly="readonly" value="<?php echo (isset($borrow)) ? $borrow->item_name : ''; ?>" name="item_master_name" id="item_name" required placeholder="Item Name" />
            <input type="hidden" name="item_master_id" id="item_id" value="<?php echo (isset($borrow)) ? $borrow->item_id : ''; ?>"/>
            <input type="hidden" name="item_master_code" id="item_code" value="<?php echo (isset($borrow)) ? $borrow->item_code : ''; ?>"/>
            <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;"
            onclick="open_popup('inventory/browse_item/all','item');" title="Browse Item" />
        </div>
      </div>
      <div class="form-group">
        <label for="lblproject" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Return Date: </label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" readonly name="item_end_date" class="form-control datepicker" required  value="<?php echo $borrow->end_date; ?>" placeholder="Return Date" data-date-format="yyyy-mm-dd">
        </div>
      </div>
      <div class="form-group">
        <label for="note" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Note: </label>
        <div class="col-sm-6 col-xs-12">
          <textarea name="note" rows="2" class="form-control" id="note"><?php if(isset($borrow) && $borrow->note){echo $borrow->note;} ?></textarea>
        </div>
      </div>
      <table class="table table-bordered table-striped" id="itemsTable">
        <thead>
          <tr>
            <th>No.</th>
            <th>Items</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody id="item_area">
          <?php if (isset($borrow_details) && $borrow_details != null): ?>
            <?php foreach ($borrow_details as $valueDetails): ?>
              <tr class="tr_clone master_clone">
                <td class="numberRow-itemsTable">
                </td>
                <td>
                    <input type="text" name="item_name[]" class="form-control item_name" readonly value="<?php echo $valueDetails->item_name ?>">
                    <input type="hidden" name="item_id[]" class="item_id" value="<?php echo $valueDetails->item_id ?>">
                    <input type="hidden" name="item_code[]" class="item_code" value="<?php echo $valueDetails->item_code ?>">
                </td>
                <td> <input type="text" name="quantities[]" class="form-control quantities" value="<?php echo $valueDetails->quantities; ?>" > </td>
                <td> <input type="text" name="end_date[]" class="form-control datepicker"   value="<?php echo $valueDetails->end_date; ?>" placeholder="Return Date" data-date-format="yyyy-mm-dd"> </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="deleteClone(this,'itemsTable')"> Delete </button>
                  <input type="hidden" name="borrow_detail_id[]" value="<?php echo $valueDetails->borrow_detail_id ?>">
                </td>
              </tr>
            <?php endforeach; ?>

            <?php else: ?>
              <tr class="tr_clone master_clone" style="display:none;">
                <td class="numberRow-itemsTable"> </td>
                <td>
                    <input type="text" name="item_name[]" class="form-control item_name" readonly value="">
                    <input type="hidden" name="item_id[]" class="item_id" value="">
                    <input type="hidden" name="item_code[]" class="item_code" value="">
                </td>
                <td> <input type="text" name="quantities[]" class="form-control quantities" > </td>
                <td> <input type="text" name="end_date[]" class="form-control datepicker"  placeholder="Return Date" data-date-format="yyyy-mm-dd"> </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="deleteClone(this,'itemsTable')"> Delete </button>
                  <input type="hidden" name="borrow_detail_id[]" value="">
                </td>
              </tr>
          <?php endif; ?>

        </tbody>
      </table><br/>
      <a class="btn btn-default btn-flat" type="button" onclick="open_popup('inventory/browse_item/accessories','accessories')"><i class="fa fa-search"></i> Browse</a>
      <!-- <a class="btn btn-default btn-flat" type="button" onclick="addRow(this,'itemsTable')"><i class="glyphicon glyphicon-plus-sign"></i> Add More Row</a> -->
      <br/><br/>

      <table class="table table-bordered table-striped" id="softwareTable">
        <thead>
          <tr>
            <th>No.</th>
            <th>Software</th>
            <th>Description</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody id="item_area">
          <?php if (isset($software) && $software != null): ?>
            <?php foreach ($software as $value): ?>
              <tr class="tr_clone">
                <td class="numberRow-softwareTable"> </td>
                <td> <input type="text" name="software[]" class="form-control" value="<?php echo $value->software_name ?>"> </td>
                <td> <input type="text" name="description[]" class="form-control" value="<?php echo $value->software_description ?>"> </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="deleteClone(this,'softwareTable')"> Delete </button>
                  <input type="hidden" name="software_id[]" value="<?php echo $value->software_id ?>">
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr class="tr_clone">
              <td class="numberRow-softwareTable"> </td>
              <td> <input type="text" name="software[]" class="form-control"> </td>
              <td> <input type="text" name="description[]" class="form-control"> </td>
              <td>
                <button type="button" class="btn btn-danger" onclick="deleteClone(this,'softwareTable')"> Delete </button>
                <input type="hidden" name="software_id[]">
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table><br/>
      <a class="btn btn-default btn-flat" type="button" onclick="addRow('softwareTable')"><i class="glyphicon glyphicon-plus-sign"></i> Add More Row</a>
      <br/><br/>
      <div>
        <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3"><i class="glyphicon glyphicon-ok"></i> Save</button>&nbsp;
        <button type="reset" class="btn btn-default btn-flat">Reset</button>
      </div>
      <?=form_close(); ?>
      <br/>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div><!-- /.row -->
<script>
reNumber('itemsTable');
reNumber('softwareTable');
var arrObj = [];
get_arr_obj();
function addRow(table) {
    var $tr   = $("#"+table).find('.tr_clone').last();
    // var allTr = $("#"+table).find('.tr_clone');
    var $clone = $tr.clone();

    $clone.find(':text').val('');
    var number = parseInt($('.numberRow-'+table).last().text());
    $tr.after($clone);
    //$clone.find(':text').attr('required',true);
    $clone.find('input').val('');
    $clone.find('select').val('');
    reNumber(table);
    $clone.show();
}

function get_arr_obj() {
    $('.master_clone').each(function(index,obj) {
        var target = $(obj);
        var code = target.find('.item_code').val();
        var qty = target.find('.quantities').val();
        arrObj[code] = [];
        arrObj[code]['qty'] = qty;
        arrObj[code]['target'] = target;
    })
}

// function deleteClone(e,table) {
// 	var allTr = $("#"+table).find('.tr_clone');
//     var $tr = $(e).closest(".tr_clone");
//     var value_code = $tr.find('.item_code').val();
//     if (arrObj[value_code]) {
//         arrObj.splice(value_code,1)
//     }
// 	if (allTr.length > 1) {
// 		var $remove = $tr.remove();
// 		reNumber(table);
// 	}else {
//         check_master = $("#"+table).find('.master_clone');
//         if (check_master.length == 1) {
//             check_master.find(':input').val('');
//             check_master.hide();
//         }
//     }
// }

function deleteClone(e,table) {
	var allTr = $("#"+table).find('.tr_clone');
    var $tr = $(e).closest(".tr_clone");
    var value_code = $tr.find('.item_code').val();
    if (arrObj[value_code]) {
        arrObj.splice(value_code,1)
    }
	// if (allTr.length > 1) {
		var $remove = $tr.remove();
		reNumber(table);
	// }else {
  //       check_master = $("#"+table).find('.master_clone');
  //       if (check_master.length == 1) {
  //           check_master.find(':input').val('');
  //           check_master.hide();
  //       }
  //   }
}

function reNumber(table) {
	var i = 1;
	$(".numberRow-"+table).each(function() {
		$( this ).text( i );
		i++
	});
}

function datePicker() {
	$('.datepicker').datepicker();
}

$('.datepicker').datepicker();
</script>
