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
            <h3 class="box-title">Add New Item</h3>
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
                    $option_machine_type[$value->machine_type_id] = $value->machine_type;
            }
            ?>
            <div class="form-group">
                <label for="category_id" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Category: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('category_id', $option, '', 'id="category_id" class="form-control"') ?>
                </div>
                <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#catModal">Add New Category</button>
            </div>

            <div class="form-group">
                <label for="measurement_id" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Measurement: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('measurement_id', $optionMeasurement, '', 'id="measurement_id" class="form-control"') ?>
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
                <label for="item_code" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Asset No: *</label>
                <div class="col-sm-8 col-xs-12">
                    <?=form_input('item_code', set_value('item_code'), 'id="item_code" placeholder="Item Code" class="form-control" required') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="item_name" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Item Name: *</label>
                <div class="col-sm-8 col-xs-12">
                    <?=form_input('item_name', set_value('item_name'), 'id="item_name" placeholder="Item Name" class="form-control" required') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="item_description" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Item Description: </label>
                <div class="col-sm-8 col-xs-12">
                    <?php
                    $data_text = array(
                        'name'        => 'item_description',
                        'id'          => 'item_description',
                        'value'       => $this->session->flashdata('item_description'),
                        'rows'        => '5',
                        'placeholder' => 'Item Description',
                        'class'       => 'form-control',
                      );
                    echo form_textarea($data_text) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="item_image" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Image: </label>
                <div class="col-sm-8 col-xs-12">
                    <?=form_upload('item_image', '', 'id="item_image" class="form-control"') ?>
                    <p class="help-block"><i class="glyphicon glyphicon-warning-sign"></i> Allowed only max_size = 150KB, max_width = 1024px, max_height = 768px, types = gif | jpg | png .</p>
                </div>
            </div>
            <!-- hidden -->
            


            <div class="form-group stock">
                <label for="maintenance_status" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Maintenance: *</label>
                <div class="col-sm-6 col-xs-12">
                  <select name="maintenance_status" id="maintenance_status" class="form-control" onchange="changeStatus(this,'maintanance')">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
            </div>
            <div class="form-group maintanance stock">
                <label for="maintanance_start" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Date: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input id="maintanance_start" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="maintenance_previous_date" />
                </div>
            </div>
            <div class="form-group maintanance stock">
                <label for="maintanance_end" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Expired: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input id="maintanance_end" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="maintenance_next_date" />
                </div>
            </div>
            <!-- new -->
            <div class="form-group stock">
                <label for="predecessor" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Accsesories: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="checkbox" name="accessories" value="1">
                </div>
            </div>
            <div class="form-group stock">
                <label for="service_tag" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Service Tag: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="service_tag" id="service_tag" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="express_service" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Express Service Code: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="express_service" id="express_service" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="machine_type" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Machine Type: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="machine_type" id="machine_type" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="machine_type_id" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Machine Type: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('machine_type_id', $option_machine_type, '', 'id="machine_type_id" class="form-control"') ?>
                </div>
            </div>

            <div class="form-group stock">
                <label for="model" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Model: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="model" id="model" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="operating_system" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Operating System: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="operating_system" id="operating_system" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="processor" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Processor: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="processor" id="processor" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="memory" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Memory: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="memory" id="memory" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="hdd" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Hard Disk Capacity: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="hdd" id="hdd" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="vga" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Vga: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="vga" id="vga" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="computer_name" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Computer Name: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="computer_name" id="computer_name" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="warranty_status" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Warranty: *</label>
                <div class="col-sm-6 col-xs-12">
                  <select name="warranty_status" id="warranty_status" class="form-control" onchange="changeStatus(this,'warranty')">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
            </div>
            <div class="form-group warranty stock">
                <label for="warranty_start" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Start: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input id="warranty_start" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="start_warranty" />
                </div>
            </div>
            <div class="form-group warranty stock">
                <label for="warranty_end" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">End: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input id="warranty_end" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="end_warranty" />
                </div>
            </div>
            <div class="form-group stock">
                <label for="product_key_windows" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Product Key Windows: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="product_key_windows" id="product_key_windows" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="product_key_office" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Product Key Office: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="product_key_office" id="product_key_office" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="product_key_others" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Product Key Others: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="product_key_others" id="product_key_others" class="form-control">
                </div>
            </div>
            <div class="form-group stock">
                <label for="predecessor" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Predecessor: *</label>
                <div class="col-sm-6 col-xs-12">
                  <input type="text" name="predecessor" id="predecessor" class="form-control">
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
