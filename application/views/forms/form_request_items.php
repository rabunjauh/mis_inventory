tes rumah
<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />

<div class="row">
    <div class="col-xs-12">
        <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    </div>
</div>
<div class="row">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">MIS Request Form</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <?=form_open_multipart(base_url().'items/add_item', 'role="form" class="form-horizontal"'); ?>
            <?php
            $option = array();
            $option[0] = 'Select Category';
            foreach ($categories as $value) {
                    $option[$value->cat_id] = $value->cat_name;
            }

            $optionMeasurement = array();
            $optionMeasurement[0] = 'Select Measurement';
            foreach ($measurement as $value) {
                    $optionMeasurement[$value->measurement_id] = $value->measurement;
            }

            $option_machine_type = array();
            $option_machine_type[0] = 'Select Machine Type';
            foreach ($machine_types as $value) {
                    $option_machine_type[$value->machine_type_id] = $value->machine_type_desc;
            }

            $option_manufacture = array();
            $option_manufacture[0] = 'Select Machine Type';
            foreach ($manufactures as $value) {
                    $option_manufacture[$value->manufacture_id] = $value->manufacture_desc;
            }

            $option_model = array();
            $option_model[0] = 'Select Model';
            foreach ($models as $value) {
                    $option_model[$value->model_id] = $value->model_desc;
            }

            $option_operating_system = array();
            $option_operating_system[0] = 'Select Operating System';
            foreach ($operating_systems as $value) {
                    $option_operating_system[$value->operating_system_id] = $value->operating_system_desc;
            }

            $option_processor = array();
            $option_processor[0] = 'Select Processor';
            foreach ($processors as $value) {
                    $option_processor[$value->processor_id] = $value->processor_type;
            }
            
            $option_memory = array();
                        $option_memory[0] = 'Select Memory';
                        foreach ($memories as $value) {
                                $option_memory[$value->memory_id] = $value->memory_size;
                        }

            $option_harddisk = array();
            $option_harddisk[0] = 'Select Hard Disk';
           foreach ($hard_disks as $hard_disk) {
                    $option_harddisk[$hard_disk->hard_disk_id] = $hard_disk->hard_disk_size;
            }

            $option_vga = array();
            $option_vga[0] = 'Select VGA';
            foreach ($vga as $value) {
                    $option_vga[$value->vga_id] = $value->vga_model;
            }

            ?>
            <div class="form-group">
                <label for="lbl_employee_status" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Employee Status: *</label>
                <div class="col-sm-8 col-xs-12">
                    <input type="radio" id="radio_employee_status" name="radio_employee_status[]" value="male">
                    <label for="male">New Staff</label><br>
                    <input type="radio" id="radio_employee_status" name="radio_employee_status[]" value="female">
                    <label for="female">Existing Staff</label><br>
                    <input type="radio" id="radio_employee_status" name="radio_employee_status[]" value="other">
                    <label for="other">Resignation</label>
                    <input type="radio" id="radio_employee_status" name="radio_employee_status[]" value="other">
                    <label for="other">Transfer</label>
                </div>
            </div>

            <div class="form-group">
                <label for="lbl_employee" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee Name: *</label>
                <div class="col-sm-6 col-xs-12">
                    <input type="text" class="form-control" readonly="readonly" value="<?php if(isset($borrow) && $borrow->employeename){echo $borrow->employeename;} ?>" name="txtemployeename" id="txtemployeename" required placeholder="Employee Name" />
                    <input type="hidden" name="taken_by_uid" id="txtempid" value="<?php if(isset($borrow) && $borrow->taken_by_uid){echo $borrow->taken_by_uid;} ?>"/>
                    <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" onclick="open_popup('inventory/employee/');" title="Browse Employee" />
                </div>
            </div>

            <div class="form-group">
                <label for="lbl_department" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Group / Department: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('dropdown_department', $option_machine_type, '', 'id="dropdown_department" class="form-control"') ?>
                </div>
            </div>

            <div class="form-group">
                <label for="lbl_date_of_join" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Join: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input id="txt_date_of_join" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="txt_date_of_join" />
                </div>
            </div>

            <div class="form-group">
                <label for="lbl_date_of_request" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date of Request: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input id="txt_date_of_request" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="txt_date_of_request" />
                </div>
            </div>

            <div class="form-group">
                <label for="item_material_status" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Material Status: *</label>
                <div class="col-sm-6 col-xs-12">
                  <select name="item_material_status" id="item_material_status" class="form-control" onchange="changeMaterial(this)">
                    <!--<option value="0">Consumable</option>-->
                    <option value="1">Stock</option>
                  </select>
                </div>
            </div>

            <div class="form-group">
                <label for="lbl_designation" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Designation: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('dropdown_department', $option_machine_type, '', 'id="dropdown_department" class="form-control"') ?>
                </div>
            </div>

            <div class="form-group stock">
                <label for="lbl_phone" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Office Direct Line/Mobile No. : *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="txt_phone" id="txt_phone" class="form-control">
                </div>
            </div>

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
            <?=form_close(); ?>
            <br/>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div><!-- /.row -->
<!-- Modal -->
<div class="modal fade" id="catModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?=form_open_multipart(base_url('items/save_category'), 'role="form" class="form-horizontal"'); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Category</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <div class="col-sm-10 col-xs-12 col-sm-offset-1">
                <?=form_input('cat_name', '', 'id="cat_name" placeholder="Category Name" class="form-control" required') ?>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary col-xs-6 col-sm-offset-2 btn-flat"><i class="glyphicon glyphicon-ok"></i> Save</button>
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
      </div>
      <?=form_close(); ?>
    </div>
  </div>
</div>

<script>
// $('.stock').hide();
// $('.calibration').hide();
// $('.maintanance').hide();
// $('.warranty').hide();

$('.datepicker').datepicker({
  autoclose : true
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
  }else {
    $('.stock').show();
    $('.calibration').show();
    $('.maintanance').show();
    $('.warranty').show();

  }
}

function changeStatus(e,type) {
  var value = $(e).val();
  if (value == 0) {
    $('.'+type).hide();
    $('#'+type+'_start').val('');
    $('#'+type+'_end').val('');
  }else {
    $('.'+type).show();
  }
}

</script>
