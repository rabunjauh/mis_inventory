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
  <div>
      <p><span id="data-count"></span></p>
  </div>
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
              <tbody id="t_body_inventory">
                  
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
// window.addEventListener('load', async function() {
//     const inventories = await getInventory();    
//     wrapData(inventories);
// });

// function getInventory() {
//   return fetch('<?= base_url('inventory/get_inventory'); ?>')
//     .then(response => response.json());
// }

// function wrapData(inventories){
//   const tBodyInventory = document.getElementById('t_body_inventory');
//   let rows = '';
//   inventories.forEach(inventory => {
//   rows += `<tr>
//             <td>${inventory.item_code}</td>
//             <td>${inventory.item_name}</td>
//             <td>${inventory.service_tag}</td>
//             <td>${inventory.express_service}</td>
//             <td>${inventory.machine_type_desc}</td>
//             <td>${inventory.manufacture_desc}</td>
//             <td>${inventory.model_desc}</td>
//             <td>${inventory.operating_system_desc}</td>
//             <td>${inventory.processor_type}</td>
//             <td>${inventory.memory_size}</td>
//             <td>${inventory.hard_disk_size}</td>
//             <td>${inventory.computer_name}</td>
//             <td>${inventory.inventory_quantity}</td>
//             <td>${inventory.alert_qtt}</td>
//           </tr>`;
//   });

//   tBodyInventory.innerHTML = rows;
// }


window.addEventListener('load', async function() {
    const currentPage = 1;
    const perPage = 10;
    const countInventories = await getCountInventory();
    const allPage = Math.ceil(countInventories / perPage);    
    const url = '<?= base_url('inventory/get_inventory') ?>';
    const totalRow = document.getElementById('data-count');
    spanText = document.createTextNode('Total Data: ')
    totalRow.append(spanText);
    totalRow.append(countInventories);
    // console.log(inventories);  
    // console.log(countInventories);  
    

    // Pagination Navigation
    const paginationElement = document.querySelector('.pagination');
    let page = '';
    let pageUrl = '';
    
    page += `<li>
              <a href='' aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>`;
    
    // for (let i = 1; i <= (allPage - allPage) + 5 ; i++) {
    //   // currentPage = i;
    //   // let pageUrl = `${url}/${(i * perPage) - perPage}`;
    //   // if ()
    //   page += `
    //     <li><a href=${pageUrl}>${i}</a></li>
    //   `;
    // }

    
    
    page +=`<li>
              <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
          </li>`;
          
    paginationElement.innerHTML = page;
    if (pageUrl){
      url = pageUrl;
    }

    const inventories = await getInventory(perPage, url);
    wrapData(inventories);
});

function getInventory(perPage, url) {
  // let url = '';
  // url += '<?= base_url('inventory/get_inventory'); ?>';
  url += '/' + perPage;
  return fetch(url)
    .then(response => response.json());
}

function getCountInventory() {
 return fetch('<?= base_url('inventory/count_inventory/'); ?>')
    .then(response => response.json());
}

function wrapData(inventories){
  const tBodyInventory = document.getElementById('t_body_inventory');
  let rows = '';
  inventories.forEach(inventory => {
  rows += `<tr>
            <td>${inventory.item_code}</td>
            <td>${inventory.item_name}</td>
            <td>${inventory.service_tag}</td>
            <td>${inventory.express_service}</td>
            <td>${inventory.machine_type_desc}</td>
            <td>${inventory.manufacture_desc}</td>
            <td>${inventory.model_desc}</td>
            <td>${inventory.operating_system_desc}</td>
            <td>${inventory.processor_type}</td>
            <td>${inventory.memory_size}</td>
            <td>${inventory.hard_disk_size}</td>
            <td>${inventory.computer_name}</td>
            <td>${inventory.inventory_quantity}</td>
            <td>${inventory.alert_qtt}</td>
          </tr>`;
  });

  tBodyInventory.innerHTML = rows;
}

window.addEventListener('click', function(e) {
  console.log(e);
});



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
