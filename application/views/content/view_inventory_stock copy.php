<style>
.current{
  color: #fff!important;
  background-color: #337ab7!important;
}
</style>
<div class="box">
  <div class="box-header">
    <span class="box-title">Inventory - Stock</span>
    <?php if ($this->session->userdata('role')): ?>
      <div class="content-nav">
        <div class="btn-group col-sm-8 col-xs-10 col-sm-offset-2 col-xs-offset-1">
         <a href="<?=base_url('borrow'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-shopping-cart"></i> Borrow</a>
          <!--<a href="<?=base_url('inventory'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-ok-sign"></i> Consumable</a>-->
          <!-- <a href="<?=base_url('inventory/stock'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-ok-sign"></i> Stock</a> -->
          <a href="<?=base_url('inventory/item_in'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plus-sign"></i> Inventory In</a>
          <!-- <a href="<?=base_url('inventory/item_out'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-minus-sign"></i> Inventory Out</a> -->
          <a href="<?=base_url('csv/download_inventory/stock'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i><span class="hidden-xs"> Download</span></a>
          <a href="#" data-toggle="modal" data-target="#filterModal" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-search"></i><span class="hidden-xs"> Filter</span></a>
        </div>
      </div>
    <?php endif; ?>
  </div><!-- /.box-header -->
  <div class="box-body table-responsive">
      <div style="overflow-x:auto;">
          <table id="example" class="table table-bordered table-striped">
              <thead>
                  <tr>
                      <th>Asset ID</th>
                      <th>Item Name</th>
                      <th>Service Tag</th>
                      <th>Express Service Code</th>
                      <th>Machine Type</th>
                      <th>Manufacture</th>
                      <th>Model</th>
                      <th>Operating System</th>
                      <th>Processor</th>
                      <th>Memory</th>
                      <th>Hardisk</th>
                      <th>Computer Name</th>
                      <th>Quantity</th>
                      <th>Alert Qtt.</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (!empty($results)): ?>
                      <?php foreach ($results as $item): ?>
                          <tr class="<?=($item->inventory_quantity <= $item->alert_qtt)?'danger':''; ?>">
                              <td><?=$item->item_code; ?></td>
                              <td><?=$item->item_name; ?></td>
                              <td><?php echo $item->service_tag ?></td>
                              <td><?php echo $item->express_service ?></td>
                              <td><?php echo $item->machine_type_desc ?></td>
                              <td><?php echo $item->manufacture_desc ?></td>
                              <td><?php echo $item->model_desc ?></td>
                              <td><?php echo $item->operating_system_desc ?></td>
                              <td><?php echo $item->processor_type ?></td>
                              <td><?php echo $item->memory_size ?></td>
                              <td><?php echo $item->hard_disk_size ?></td>
                              <td><?php echo $item->computer_name ?></td>
                              <td><?=$item->inventory_quantity; ?></td>
                              <td><?=$item->alert_qtt; ?></td>
                          </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="14"> No Data </td>
                      </tr>
                  <?php endif; ?>
              </tbody>
              <tfoot>
                  <tr>
                      <th>Asset ID</th>
                      <th>Item Name</th>
                      <th>Service Tag</th>
                      <th>Express Service Code</th>
                      <th>Machine Type</th>
                      <th>Manufacture</th>
                      <th>Model</th>
                      <th>Operating System</th>
                      <th>Processor</th>
                      <th>Memory</th>
                      <th>Hardisk</th>
                      <th>Computer Name</th>
                      <th>Quantity</th>
                      <th>Alert Qtt.</th>
                  </tr>
              </tfoot>
          </table>
          <ul class="pagination">
              <?php foreach ($links as $link) {
                  echo "<li>". $link."</li>";
              } ?>
          </ul>
      </div>
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
            Search By:
          </label>
          <div class="">
            <select class="form-control" name="search_type" id="search_type">
              <option value="all" <?php echo (isset($search_type) && $search_type == "all") ? 'selected' : '' ?>>All</option>
              <option value="item_code" <?php echo (isset($search_type) && $search_type == "item_code") ? 'selected' : '' ?>> Item Code </option>
              <option value="item_name" <?php echo (isset($search_type) && $search_type == "item_name") ? 'selected' : '' ?>> Item Name</option>
              <option value="service_tag" <?php echo (isset($search_type) && $search_type == "service_tag") ? 'selected' : '' ?>> Service Tag</option>
              <option value="machine_type" <?php echo (isset($search_type) && $search_type == "machine_type") ? 'selected' : '' ?>> Machine Type</option>
              <option value="model" <?php echo (isset($search_type) && $search_type == "all") ? 'model' : '' ?>> Model</option>
              <option value="express_service" <?php echo (isset($search_type) && $search_type == "express_service") ? 'selected' : '' ?>>Express Service Code</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">
            Text Search:
          </label>
          <div class="">
            <input id="text_search" name="text_search" class="form-control" value="<?php echo (isset($text_search)) ? $text_search : '' ?>">
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
           <div class="">
             <select class="form-control" name="cat_id" multiple>
               <option value="all" <?php if(in_array("all", $cat_id)){ echo "selected";} ?>> All </option>
               <?php foreach ($category as $value): ?>
                 <option value="<?php echo $value->cat_id ?>" <?php if(in_array($value->cat_id, $cat_id)){ echo "selected";} ?>><?php echo $value->cat_name ?></option>
               <?php endforeach; ?>
             </select>
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
function submitFilter() {
  var cat = $( "[name='cat_id']" ).val();
  var search_type = $( "#search_type" ).val() ;
  var text_search = $( "#text_search" ).val() ;
  var url = "<?php echo base_url()."inventory/filter_stock?cat_id="; ?>";
  var i = 1;
  var leng = cat.length;

  var urlCat = '';
  for (var i = 0; i < cat.length; i++) {
    if (cat[i] == 'all') {
      urlCat = 'all';
      break;
    }else {
      urlCat += cat[i];
      if (i != leng-1) {
        urlCat += ","
      }
    }
  }
  url += urlCat;

  if (search_type !== "all") {
    if (text_search == '') {
      url += "&"+search_type+"=all&";
    }else {
      url += "&"+search_type+"="+text_search+"&";
    }
    url += "search_type="+search_type
  }
  location.replace(url);

}
</script>
