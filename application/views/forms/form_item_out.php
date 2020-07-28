<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />
<?php //echo "<pre/>"; print_r($warehouses); exit(); ?>
<?php
$rowOption = '';
foreach ($items as $item) {
        $rowOption .= '<option value="' . $item->item_id . '">' . $item->item_name .' [Code: '.$item->item_code.']'.' [Qtt: '.$item->inventory_quantity.']'.'</option>';
}
?>
<script>
function addRow() {
var item_count = $("#item_area tr").length + 1;

var item = '<tr><td><select class="form-control" name="item[' + item_count + ']" ><option value="">Selsect Item</option>';
        item += '<?php echo $rowOption; ?>';
        item += '</select></td>';
        item += '<td ><input class="form-control" type="text" name="quantity[' + item_count + ']" placeholder="Quantity"/></td>';
        item += '</tr>';

         $('#item_area').append(item);
}
function open_popup() {
    window.open('<?php echo prefix_url;?>inventory/employee/', 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
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
            <h3 class="box-title">Item Out</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <?=form_open(base_url().'inventory/item_out', 'role="form" class="form-horizontal"'); ?>
            <?php
            $option = array();
            $option[0] = 'Select Warehouse';
            foreach ($warehouses as $warehouse) {
                    $option[$warehouse->warehouse_id] = $warehouse->warehouse_name ;
            }

			$option_costcenter = array();
            $option_costcenter[0] = 'Select Cost Center';
            foreach ($costcenter as $costcenter) {
                    $option_costcenter[$costcenter->cost_center_uid] = $costcenter->cost_center;
            }

			$option_project = array();
            $option_project[0] = 'Select Project';
            foreach ($project as $project) {
                    $option_project[$project->project_uid] = $project->project_name;
            }
            ?>
            <div class="form-group">
                <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Warehouse: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('warehouse', $option, '', 'id="warehouse" class="form-control"') ?>
                </div><span class="visible-xs"><br/><br/></span>
                <a href="<?=base_url('settings/add_warehouse'); ?>" class="btn btn-warning btn-flat col-xs-offset-2 col-xs-8 col-sm-2 col-sm-offset-0">Add Warehouse</a>
            </div>
            <div class="form-group">
                <label for="lblemployee" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee: </label>
                <div class="col-sm-6 col-xs-12">
                 	<input type="text" class="form-control" readonly="readonly"  name="txtemployeename" id="txtemployeename" placeholder="Employee Name" />
        			<input type="hidden" name="txtempid" id="txtempid"/>
                	 <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;"
                	onclick="open_popup();" title="Browse Employee" />
                </div>
            </div>
            <div class="form-group">
            	<label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Date: *</label>
                 <div class="col-sm-6 col-xs-12">
                    <input id="txtactualdate" readonly data-date-format="dd/mm/yyyy"  class="form-control" placeholder="Actual Date" type="text" name="txtactualdate" />
                </div>

            </div>
            <div class="form-group">
                <label for="lblcostcenter" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Cost Center: </label>
                <div class="col-sm-6 col-xs-12">
                	 <?=form_dropdown('txtcostcenter', $option_costcenter, '', 'id="txtcostcenter" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="lblproject" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Project: </label>
                <div class="col-sm-6 col-xs-12">
                	<?=form_dropdown('txtproject', $option_project, '', 'id="txtproject" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="note" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Note: </label>
                <div class="col-sm-6 col-xs-12">
                    <?php
                    $data_text = array(
                        'name'        => 'note',
                        'id'          => 'note',
                        'rows'        => '2',
                        'placeholder' => 'Invoice note',
                        'class'       => 'form-control',
                      );
                    echo form_textarea($data_text) ?>
                </div>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="item_area">
                    <tr>
                        <td>
                            <?php
                            $rowOption = '';
                            $option = array();
                            $option[] = 'Select Item';
                            foreach ($items as $item) {
                                    $option[$item->item_id] = $item->item_code.' - '.$item->item_name.' [Code: '.$item->item_code.']'.' [Qtt: '.$item->inventory_quantity.']';
                            }
                            ?>
                           <?=form_dropdown('item[1]', $option, '', 'class="form-control"') ?>
                        </td>
                        <td><?=form_input('quantity[1]', set_value('quantity[1]'), 'placeholder="Quantity" class="form-control" required') ?></td>
                    </tr>
                </tbody>
            </table><br/>
            <a class="btn btn-default btn-flat" type="button" href="javascript:addRow()"><i class="glyphicon glyphicon-plus-sign"></i> Add More Row</a>
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
	$('#txtactualdate').datepicker();
</script>
