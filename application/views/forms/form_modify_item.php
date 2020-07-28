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
            <h3 class="box-title">Modify Item</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?=form_open_multipart(base_url().'items/modify/'.$item->item_id, 'role="form" class="form-horizontal"'); ?>
            <div class="form-group">
                <label for="category" class="control-label col-xs-2">Category: *</label>
                <div class="col-sm-9 col-xs-12">
                    <select name="category" id="category" class="form-control" >
                    <?php $categories = $this->db->get('category')->result();
                    foreach ($categories as $category) {?>
                        <option value="<?php echo $category->cat_id;?>" <?php echo ($category->cat_id == $item->cat_id)?'selected':'';?>><?php echo $category->cat_name;?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="category" class="control-label col-xs-2">Measurement: *</label>
                <div class="col-sm-9 col-xs-12">
                    <select name="measurement_id" id="measurement_id" class="form-control" >
                      <option value=""> Select Maesurement</option>
                    <?php
                    foreach ($measurement as $value) {?>
                        <option value="<?php echo $value->measurement_id;?>" <?php echo ($value->measurement_id == $item->measurement_id)?'selected':'';?>><?php echo $value->measurement;?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_material_status" class="col-sm-2 hidden-xs control-label  col-xs-2">Material Status: *</label>
                <div class="col-sm-9 col-xs-12">
                  <select name="item_material_status" id="item_material_status" class="form-control" onchange="changeMaterial(this)">
                    <!--<option value="0" <?php if($item->item_material_status == 0){echo "selected";} ?>>Consumable</option>-->
                    <option value="1" <?php if($item->item_material_status == 1){echo "selected";} ?>>Stock</option>
                  </select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_code" class="control-label col-xs-2">Item Code: *</label>
                <div class="col-sm-9 col-xs-12">
                    <?=form_input('item_code', $item->item_code, 'id="item_code" placeholder="Item Code" class="form-control" required') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="item_name" class="control-label col-xs-2">Item Name: *</label>
                <div class="col-sm-9 col-xs-12">
                    <?=form_input('item_name', $item->item_name, 'id="item_name" placeholder="Item Name" class="form-control" required') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="item_description" class="control-label col-xs-2">Item Description: </label>
                <div class="col-sm-9 col-xs-12">
                    <?php
                    $data_text = array(
                        'name'        => 'item_description',
                        'id'          => 'item_description',
                        'value'       => $item->item_description,
                        'rows'        => '4',
                        'placeholder' => 'Item Description',
                        'class'       => 'form-control',
                      );
                    echo form_textarea($data_text) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="item_image" class="control-label col-xs-2">Image: </label>
                <div class="col-sm-9 col-xs-12">
                      <?php $image_link = './item_iamages/'.$item->item_code.'_thumb.jpg'; ?>
                      <?php if(file_exists ($image_link)): ?>
                        <img id="img-<?=$item->item_id;?>" src="<?=base_url($image_link);?>" alt="image-<?=$item->item_id;?>"><br/><br/>
                      <?php endif; ?>
                    <?=form_upload('item_image', '', 'id="item_image" class="form-control"') ?>
                    <p class="help-block"><i class="glyphicon glyphicon-warning-sign"></i> Allowed only max_size = 150KB, max_width = 1024px, max_height = 768px, types = gif | jpg | png .</p>
                    <p class="text-muted pull-right">* Required fields.</p>
                </div>
            </div>
            <!-- hidden -->
            

            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="maintenance_status" class="col-sm-2 hidden-xs control-label col-xs-2">Maintenance: *</label>
                <div class="col-sm-9 col-xs-12">
                  <select name="maintenance_status" id="maintenance_status" class="form-control" onchange="changeStatus(this,'maintanance')">
                    <option value="1" <?php if($item->maintenance_status == 1){echo "selected";} ?> >Yes</option>
                    <option value="0" <?php if($item->maintenance_status == 0){echo "selected";} ?> >No</option>
                  </select>
                </div>
            </div>

            <div class="form-group maintanance" style="<?php if($item->maintenance_status == 0){echo "display:none;";} ?>">
                <label for="maintanance_start" class="col-sm-2 hidden-xs control-label col-xs-2">Date: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input id="maintanance_start" value="<?php echo $item->maintenance_previous_date ?>" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="maintenance_previous_date" />

                </div>
            </div>
            <div class="form-group maintanance" style="<?php if($item->maintenance_status == 0){echo "display:none;";} ?>">
              <label for="maintanance_end" class="col-sm-2 hidden-xs control-label col-xs-2">Expired: *</label>
              <div class="col-sm-9 col-xs-12">
                <input id="maintanance_end" value="<?php echo $item->maintenance_next_date ?>" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="maintenance_next_date" />
              </div>
            </div>

            <!-- new -->
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="predecessor" class="col-sm-2 hidden-xs control-label col-xs-2">Accsesories: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="checkbox" name="accessories" value="1" <?php if($item->accessories){echo "checked";} ?>>
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
              <label for="service_tag" class="col-sm-2 hidden-xs control-label col-xs-2">Service Tag: *</label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" name="service_tag" value="<?php echo $item->service_tag ?>" id="service_tag" class="form-control">
              </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
              <label for="express_service" class="col-sm-2 hidden-xs control-label col-xs-2">Express Service Code: *</label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" name="express_service" value="<?php echo $item->express_service ?>" id="express_service" class="form-control">
              </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="machine_type" class="col-sm-2 hidden-xs control-label col-xs-2">Machine Type: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="machine_type" value="<?php echo $item->machine_type ?>" id="machine_type" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="model" class="col-sm-2 hidden-xs control-label col-xs-2">Model: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="model" value="<?php echo $item->model ?>" id="model" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="operating_system" class="col-sm-2 hidden-xs control-label col-xs-2">Operating System: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="operating_system" value="<?php echo $item->operating_system ?>" id="operating_system" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="processor" class="col-sm-2 hidden-xs control-label col-xs-2">Processor: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="processor" value="<?php echo $item->processor ?>" id="processor" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="memory" class="col-sm-2 hidden-xs control-label col-xs-2">Memory: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="memory" value="<?php echo $item->memory ?>" id="memory" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="hdd" class="col-sm-2 hidden-xs control-label col-xs-2">Hard Disk Capacity: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="hdd" value="<?php echo $item->hdd ?>" id="hdd" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="vga" class="col-sm-2 hidden-xs control-label col-xs-2">Vga: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="vga" id="vga" value="<?php echo $item->vga ?>" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="computer_name" class="col-sm-2 hidden-xs control-label col-xs-2">Computer Name: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="computer_name" value="<?php echo $item->computer_name ?>" id="computer_name" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="warranty_status" class="col-sm-2 hidden-xs control-label col-xs-2">Warranty: *</label>
                <div class="col-sm-9 col-xs-12">
                  <select name="warranty_status" id="warranty_status" class="form-control" onchange="changeStatus(this,'warranty')">
                    <option value="1" <?php if($item->warranty_status == 1){echo "selected";} ?> >Yes</option>
                    <option value="0" <?php if($item->warranty_status == 0){echo "selected";} ?> >No</option>
                  </select>
                </div>
            </div>
            <div class="form-group warranty stock" style="<?php if($item->warranty_status == 0){echo "display:none;";} ?>">
                <label for="warranty_start" class="col-sm-2 hidden-xs control-label col-xs-2">Start: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input id="warranty_start" value="<?php echo $item->start_warranty; ?>" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="start_warranty" />
                </div>
            </div>
            <div class="form-group warranty stock" style="<?php if($item->warranty_status == 0){echo "display:none;";} ?>">
                <label for="warranty_end" class="col-sm-2 hidden-xs control-label col-xs-2">End: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input id="warranty_end" value="<?php echo $item->end_warranty ?>" readonly data-date-format="yyyy-mm-dd"  class="form-control datepicker"  type="text" name="end_warranty" />
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="product_key_windows" class="col-sm-2 hidden-xs control-label col-xs-2">Product Key Windows: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="product_key_windows" value="<?php echo $item->product_key_windows; ?>" id="product_key_windows" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="product_key_office" class="col-sm-2 hidden-xs control-label col-xs-2">Product Key Office: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="product_key_office" value="<?php echo $item->product_key_office; ?>" id="product_key_office" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="product_key_others" class="col-sm-2 hidden-xs control-label col-xs-2">Product Key Others: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="product_key_others" value="<?php echo $item->product_key_others; ?>" id="product_key_others" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="accsesories" class="col-sm-2 hidden-xs control-label col-xs-2">Accsesories: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="accsesories" value="<?php echo $item->accsesories; ?>" id="accsesories" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="monitor" class="col-sm-2 hidden-xs control-label col-xs-2">Monitor: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="monitor" value="<?php echo $item->monitor; ?>" id="monitor" class="form-control">
                </div>
            </div>
            <div class="form-group stock" style="<?php if($item->item_material_status == 0){echo "display:none;";} ?>">
                <label for="predecessor" class="col-sm-2 hidden-xs control-label col-xs-2">Predecessor: *</label>
                <div class="col-sm-9 col-xs-12">
                  <input type="text" name="predecessor" value="<?php echo $item->predecessor; ?>" id="predecessor" class="form-control">
                </div>
            </div>
            <!--  -->
            <div>
                <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2"><i class="glyphicon glyphicon-ok"></i> Save</button>&nbsp;
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

$('.datepicker').datepicker({
  autoclose : true
});

function changeMaterial(e) {
  var value = $(e).val();
  if (value == 0) {
    $('.stock').hide();
    $('.calibration').hide();
    $('.maintanance').hide();

  }else {
    $('.stock').show();
    $('.calibration').show();
    $('.maintanance').show();
    $('#item_calibration_status option[value=1]').attr('selected','selected');
    $('#maintenance_status option[value=1]').attr('selected','selected');
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
