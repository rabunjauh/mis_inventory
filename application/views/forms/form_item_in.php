<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />
<?php //echo "<pre/>"; print_r($warehouses); exit(); ?>

<div class="row">
    <div class="col-xs-12">
        <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    </div>
</div>
<div class="row">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Item In</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?=form_open(base_url().'inventory/item_in', 'role="form" class="form-horizontal"'); ?>
            <?php
            $option = array();
            $option[0] = 'Select Supplier';
            foreach ($suppliers as $supplier) {
                    $option[$supplier->supplier_id] = $supplier->supplier_name ;
            }
            ?>
            <div class="form-group">
                <label for="supplier" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Supplier: *</label>
                <div class="col-sm-6 col-xs-12">
                    <?=form_dropdown('supplier', $option, '', 'id="supplier" class="form-control"') ?>
                </div><span class="visible-xs"><br/><br/></span>
                <a href="<?=base_url('suppliers/add_supplier'); ?>" class="btn btn-warning btn-flat col-xs-offset-2 col-xs-8 col-sm-2 col-sm-offset-0">Add Supplier</a>
            </div>
            <div class="form-group">
            	<label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Date: *</label>
                 <div class="col-sm-6 col-xs-12">
                    <input id="txtactualdate" readonly data-date-format="dd/mm/yyyy"  class="form-control" placeholder="Actual Date" type="text" name="txtactualdate" />
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
            <input type="hidden" name="type_input" id="type_input" value="">
            <table class="table table-bordered table-striped" id="itemsTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Items</th>
                        <th>Quantity</th>
                        <th>Alert Qtt.</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="item_area">
                    <tr class="tr_clone master_clone" style="display:none;">
                        <td class="numberRow"> </td>
                        <td>
                            <input type="text" name="item_name[]" class="form-control item_name" readonly value="">
                            <input type="hidden" name="item_id[]" class="item_id" value="">
                            <input type="hidden" name="item_code[]" class="item_code" value="">
                        </td>
                        <td> <input type="text" name="quantity[]" value="" placeholder="Quantity" class="form-control quantities" required> </td>
                        <td> <input type="text" name="alrt_quantity[]" value="" placeholder="Alert Quantity" class="form-control"> </td>
                        <td><button type="button" class="btn btn-danger" onclick="deleteClone(this,'itemsTable')"> Delete </button></td>
                    </tr>
                </tbody>
            </table><br/>
            <a class="btn btn-default btn-flat" type="button" onclick="open_popup('inventory/browse_item/inventory','inventory')"><i class="fa fa-search"></i> Browse</a>
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
    var arrObj = [];
    reNumber('itemsTable');
	$('#txtactualdate').datepicker();
    function open_popup(url,value) {
        $('#type_input').val(value);
        window.open('<?php echo prefix_url;?>'+url, 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
    }

    function deleteClone(e,table) {
    	var allTr = $("#"+table).find('.tr_clone');
        var $tr = $(e).closest(".tr_clone");
        var value_code = $tr.find('.item_code').val();
        if (arrObj[value_code]) {
            arrObj.splice(value_code,1)
        }
    	if (allTr.length > 1) {
    		var $remove = $tr.remove();
    		reNumber(table);
    	}else {
            check_master = $("#"+table).find('.master_clone');
            if (check_master.length == 1) {
                check_master.find(':input').val('');
                check_master.hide();
            }
        }
    }

    function addRow(table) {
        var $tr   = $("#"+table).find('.tr_clone').last();
        var allTr = $("#"+table).find('.tr_clone');
        var $clone = $tr.clone();

        $clone.find(':text').val('');
        var number = parseInt($('.numberRow-'+table).last().text());
        $tr.after($clone);
        $clone.find(':text').attr('required',true);
        $clone.find('input').val('');
        $clone.find('select').val('');
        reNumber(table);
        $clone.show();
    }

    function reNumber() {
        var i = 1;
        $(".numberRow").each(function() {
            $( this ).text( i );
            i++
        });
    }
</script>
