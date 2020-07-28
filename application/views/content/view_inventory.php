<style>
.current{
  color: #fff!important;
  background-color: #337ab7!important;
}
</style>
<div class="box">
  <div class="box-header">
    <span class="box-title">Inventory - Consumable</span>
    <?php if ($this->session->userdata('role')): ?>
      <div class="content-nav">
        <div class="btn-group col-sm-8 col-xs-10 col-sm-offset-2 col-xs-offset-1">
         
          <a href="<?=base_url('inventory/stock'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-ok-sign"></i> Stock</a>
          <a href="<?=base_url('inventory/item_in'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plus-sign"></i> Inventory In</a>
          <a href="<?=base_url('inventory/item_out'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-minus-sign"></i> Inventory Out</a>
          <a href="<?=base_url('csv/download_inventory/consumable'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i><span class="hidden-xs"> Download</span></a>
          <a href="#" data-toggle="modal" data-target="#filterModal" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-search"></i><span class="hidden-xs"> Filter</span></a>
        </div>
      </div>
    <?php endif; ?>
  </div><!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="example" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Item Code</th>
          <th>Item Name</th>
          <th class="hidden-xs">Category</th>
          <th class="hidden-md hidden-sm hidden-xs">Item Description</th>
          <th>Quantity</th>
          <th>Measurement</th>
          <th class="hidden-sm hidden-xs">Alert Qtt.</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($results)): ?>
          <?php foreach ($results as $item): ?>
            <tr class="<?=($item->inventory_quantity <= $item->alert_qtt)?'danger':''; ?>">
              <td><?=$item->item_code; ?></td>
              <td><?=$item->item_name; ?></td>
              <td class="hidden-xs"><?=$item->cat_name; ?></td>
              <td class="hidden-md hidden-sm hidden-xs"><?=$item->item_description; ?></td>
              <td><?=$item->inventory_quantity; ?></td>
              <td><?=$item->measurement; ?></td>
              <td class="hidden-sm hidden-xs"><?=$item->alert_qtt; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Item Code</th>
          <th>Item Name</th>
          <th class="hidden-xs">Category</th>
          <th class="hidden-md hidden-sm hidden-xs">Item Description</th>
          <th>Quantity</th>
          <th>Measurement</th>
          <th class="hidden-sm hidden-xs">Alert Qtt.</th>
        </tr>
      </tfoot>
    </table>
    <ul class="pagination">
      <?php foreach ($links as $link) {
        echo "<li>". $link."</li>";
      } ?>
    </ul>
  </div><!-- /.box-body -->
</div><!-- /.box -->

<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter</h4>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <label class="control-label">
            Item Code:
          </label>
          <div class="">
            <input id="item_code" name="item_code" value="<?php if(isset($_GET['item_code'])){echo $_GET['item_code'];} ?>" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">
            Item Name:
          </label>
          <div class="">
            <input id="item_name" name="item_name" value="<?php if(isset($_GET['item_name'])){echo $_GET['item_name'];} ?>" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">
            Item Description:
          </label>
          <div class="">
            <input id="item_description" name="item_description" value="<?php if(isset($_GET['item_description'])){echo $_GET['item_description'];} ?>"  class="form-control">
          </div>
        </div>

        <?php
          if (!isset($_GET['cat_id'])) {
            $cat_id = ["all"];
          }else {
            $cat_id = explode(",",$_GET['cat_id']);
          }

         ?>
        <div class="form-group">
          <label class="control-label">
            Category:
          </label>
          <div class="row">
            <div class="col-md-3">
              <div class="checkbox">
                <label><input type="checkbox" id="allCategory" name="cat_id" value="all" <?php if(in_array("all", $cat_id)){ echo "checked";} ?>>All</label>
              </div>
            </div>
            <?php foreach ($category as $value): ?>
              <div class="col-md-3">
                <div class="checkbox">
                  <label><input type="checkbox" name="cat_id" <?php if(in_array($value->cat_id, $cat_id)){ echo "checked";} ?> value="<?php echo $value->cat_id ?>"><?php  echo $value->cat_name; ?></label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="form-group">
          <div class="">
            <button type="button" onclick="submitFilter()" class="btn btn-success"> Search </button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>

$("input[name='cat_id']" ).click(function(e) {
  var value = $(e.currentTarget).val();
  if (value == "all") {
    var cat = $( "input[name='cat_id']" );
    cat.each(function( index ) {
      $(this).prop('checked', false);
    });
    $('#allCategory').prop('checked', true);
  }else {
    $('#allCategory').prop('checked', false);
  }
})


function submitFilter() {
  var cat = $( "input[name='cat_id']:checked" );
  var item_description = $( "#item_description" ).val() ;
  var item_code = $( "#item_code" ).val();
  var item_name = $( "#item_name" ).val();
  var url = "<?php echo base_url()."inventory/filter?cat_id="; ?>";
  var i = 1;
  var leng = cat.length;

  cat.each(function( index ) {
    url += $(this).val();
    if (i != leng) {
      url += ","
    }
    i++;
  });
  if (item_description == "") {
    url += "&item_description=all&";
  }else {
    url += "&item_description="+item_description+"&";
  }
  if (item_code == "") {
    url += "item_code=all&";
  }else {
    url += "item_code="+item_code+"&";
  }
  if (item_name == "") {
    url += "item_name=all";
  }else {
    url += "item_name="+item_name;
  }
  location.replace(url);

}
</script>
