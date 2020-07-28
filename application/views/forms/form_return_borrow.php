<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />

<script>

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
      <h3 class="box-title">Borrow</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
      <?=form_open(base_url().'borrow/return_borrow/'.$borrow->borrow_id, 'role="form" class="form-horizontal"'); ?>
      <div class="form-group">
        <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Warehouse: </label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" value="<?php echo $borrow->warehouse_name; ?>" class="form-control" disabled>
        </div><span class="visible-xs"><br/><br/></span>
      </div>
      <div class="form-group">
        <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Project: </label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" value="<?php echo $borrow->project_name; ?>" class="form-control" disabled>
        </div><span class="visible-xs"><br/><br/></span>
      </div>
      <div class="form-group">
        <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee: </label>
        <div class="col-sm-6 col-xs-12">
          <input type="text" value="<?php echo $borrow->employee_name; ?>" class="form-control" disabled>
        </div><span class="visible-xs"><br/><br/></span>
      </div>
      <div class="form-group">
        <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Item: </label>
        <div class="col-sm-6 col-xs-12">
          <div class="form-control"> <?php echo $borrow->item_name; ?> </div>
        </div><span class="visible-xs"><br/><br/></span>
      </div>
      <div class="form-group">
        <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Return: </label>
        <div class="col-sm-6 col-xs-12">
          <?php if ($borrow->item_return_date): ?>
              <div class="form-control"> Return </div>
            <?php else: ?>
              <input type="checkbox" name="return_status" value="1">
          <?php endif; ?>
        </div><span class="visible-xs"><br/><br/></span>
      </div>
      <table class="table table-bordered table-striped" id="itemsTable">
        <thead>
          <tr>
            <th>No.</th>
            <th>Items</th>
            <th>Borrowed Qty</th>
            <th>Date</th>
            <th>Status</th>
            <th>Returned Qty</th>
            <th>Return Qty</th>
          </tr>
        </thead>
        <tbody id="item_area">
          <?php $i = 1;
          foreach ($borrow_details as $valueDetails): ?>
          <tr class="tr_clone">
            <td class="numberRow">
              <?php echo $i ; $i++; ?>
            </td>
            <td>
              <?php echo $valueDetails->item_name; ?>
            </td>
            <td> <?php echo $valueDetails->quantities; ?> </td>
            <td> <?php echo $valueDetails->end_date; ?> </td>
            <td>
              <?php if($valueDetails->return_date == ''){echo "<p style='color:red;'>Not Return</p>";}else{echo "<p style='color:green;'>Return</p>";} ; ?>
              <input type="hidden" name="borrow_detail_id[]" value="<?php echo $valueDetails->borrow_detail_id ?>">
            </td>
            <td>
              <?php echo $valueDetails->return_quantity ?>
            </td>
            <td>

              <input type="text" name="return_quantity[]" class="form-control" <?php if($valueDetails->return_date != ''){echo "readonly";}; ?>>
            </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table><br/>
    <br/><br/>
    <div>
      <?php if ($borrow->borrow_status == 0): ?>
        <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-1  col-sm-offset-2"><i class="glyphicon glyphicon-ok"></i> Save</button>&nbsp;
        <button type="reset" class="btn btn-default btn-flat">Reset</button>
      <?php endif; ?>
      <a class="btn btn-default" href="<?php echo base_url('borrow'); ?>"><i class="glyphicon glyphicon-circle-arrow-left	Try it"></i> Back</a>
    </div>
    <?=form_close(); ?>
    <br/>
  </div><!-- /.box-body -->
</div><!-- /.box -->
</div><!-- /.row -->
<script>

</script>
