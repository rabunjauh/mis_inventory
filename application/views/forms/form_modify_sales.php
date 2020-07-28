<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />
<?php //echo "<pre/>"; print_r($invoice); exit(); ?>
<?php
$ids = explode(',', $invoice->item_ids);
$price = explode(',', $invoice->unit_prices);
$quantity = explode(',', $invoice->quantities);
?>
<script type="text/javascript">
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
            <h3 class="box-title">Modify Sales</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <?=form_open(base_url().'invoice/update/sales/'.$invoice->invoice_id, 'role="form" class="form-horizontal"'); ?>
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
                    <?=form_dropdown('warehouse', $option, $invoice->warehouse_id, 'id="warehouse" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
            	<label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Date: *</label>
                 <div class="col-sm-6 col-xs-12">
                    <input id="txtactualdate" readonly data-date-format="dd/mm/yyyy"  value="<?php echo set_value('txtactualdate',$invoice->actual_date);?>" class="form-control" placeholder="Actual Date" type="text" name="txtactualdate" />
                </div>
            	
            </div>
            <div class="form-group">
                <label for="lblemployee" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee: </label>
                <div class="col-sm-6 col-xs-12">
                 	<input type="text" class="form-control" readonly="readonly"  name="txtemployeename" value="<?php echo $invoice->employee_name; ?>" id="txtemployeename" placeholder="Employee Name" />
        			 <input type="hidden" name="txtempid" id="txtempid" value="<?php echo $invoice->taken_by_uid; ?>" />
                	 <input type="button" class="choose" name="choose" style="width: 20px; height: 20px; display:inline-block;" 
                	onclick="open_popup();" title="Browse Employee" />
                </div>
            </div>
            <div class="form-group">
                <label for="lblcostcenter" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Cost Center: </label>
                <div class="col-sm-6 col-xs-12">
                	 <?=form_dropdown('txtcostcenter', $option_costcenter, $invoice->cost_center_uid, 'id="txtcostcenter" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="lblproject" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Project: </label>
                <div class="col-sm-6 col-xs-12">
                	<?=form_dropdown('txtproject', $option_project, $invoice->project_uid, 'id="txtproject" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="note" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Note: </label>
                <div class="col-sm-6 col-xs-12">
                    <?php
                    $data_text = array(
                        'name'        => 'note',
                        'id'          => 'note',
                        'value'       => $invoice->note,
                        'rows'        => '2',
                        'placeholder' => 'Invoice note',
                        'class'       => 'form-control',
                      );
                    echo form_textarea($data_text) ?>
                </div>
            </div>
            <?php
            $rowOption = '';
            $option = array();
            $option[0] = 'Remove The Item';
            foreach ($items as $item) {
                    $option[$item->item_id] = $item->item_name;                                    
            }
            ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="item_area">
                    <?php 
                    foreach ($ids as $key => $id){ ?>
                    <tr>
                        <td>
                           <?=form_dropdown('item['.($key+1).']', $option, $id, 'class="form-control"') ?>
                        </td>
                        <td><?=form_input('unit_price['.($key+1).']', $price[$key], 'placeholder="Unit Price" class="form-control" required') ?></td>
                        <td><?=form_input('quantity['.($key+1).']', $quantity[$key], 'placeholder="Quantity" class="form-control" required') ?></td>
                    </tr>
                    <?php 
                    } ?>
                </tbody>
            </table><br/><br/>
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