<style>
.current{
  color: #fff!important;
  background-color: #337ab7!important;
}
</style>
<div class="box">
  <div class="box-header">
    <span class="box-title">Items <?php echo $type ?></span>
    <?php if ($this->session->userdata('role')): ?>
      <div class="content-nav">
        <div class="btn-group col-md-8 col-sm-8 col-xs-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
          <!--<a href="<?=base_url('items/consumable'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-ok-sign"></i> Consumable</a>-->
          <a href="<?=base_url('items/add_item'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-plus-sign"></i> <span > Add New Item</span></a>
          <a href="<?=base_url('items/view_barcodes'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-barcode"></i> <span > View Barcodes</span></a>
          <a href="<?=base_url('csv/items_download_'.$type); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i><span > Download</span></a>
          <a href="<?=base_url('csv'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plane"></i> CSV Upload</a>
          <a href="#" data-toggle="modal" data-target="#filterModal" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-search"></i><span > Filter</span></a>
        </div>
      </div>
    <?php endif; ?>
  </div><!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="example" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Asset ID</th>
          <th>Image</th>
          <th>Item Name</th>
          <th>Service Tag</th>
          <th>Express Service Code</th>
          <th>Machine Type</th>
          <th>Model</th>
          <th>Operating System</th>
          <th>Processor</th>
          <th>Memory</th>
          <th>Hardisk</th>
          <th>Computer Name</th>
          <?php if ($this->session->userdata('role')): ?>
            <th>Action</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($results)): ?>
          <?php foreach ($results as $item): ?>
            <tr>
              <td>
                <a href="#" data-name="item_code" data-type="text" data-url="<?=base_url('items/update_item'); ?>" data-pk="<?=$item->item_id; ?>" class="data-modify-<?=$item->item_id; ?> no-style"><?=$item->item_code; ?></a>
              </td>
              <td >
                <?php $image_link = './item_iamages/'.$item->item_image_name.'_thumb'.$item->item_image_type; ?>
                <?php if(($item->item_image_name) AND file_exists ($image_link)): ?>
                  <a href="#imgDisplay" id="display-image-<?=$item->item_id;?>" data-id="display-image-<?=$item->item_id;?>" data-toggle="modal" class="display-image">
                    <img id="img-<?=$item->item_id;?>" src="<?=base_url($image_link);?>" alt="image-<?=$item->item_id;?>">
                  </a>
                <?php else: ?>
                  <img id="img-<?=$item->item_id;?>" src="<?=base_url('./item_iamages/image-placeholder_thumb.jpg');?>" alt="image-<?=$item->item_id;?>">
                <?php endif; ?>
              </td>
              <td>
                <a href="#" data-name="item_name" data-type="textarea" data-rows="4" data-url="<?=base_url('items/update_item'); ?>" data-pk="<?=$item->item_id; ?>" class="data-modify-<?=$item->item_id; ?> no-style"><?=$item->item_name; ?></a>
              </td>
              <td><?php echo $item->service_tag ?></td>
              <td><?php echo $item->express_service ?></td>
              <td><?php echo $item->machine_type ?></td>
              <td><?php echo $item->model ?></td>
              <td><?php echo $item->operating_system ?></td>
              <td><?php echo $item->processor ?></td>
              <td><?php echo $item->memory ?></td>
              <td><?php echo $item->hdd ?></td>
              <td><?php echo $item->computer_name ?></td>
              <?php if ($this->session->userdata('role')): ?>
                <td>
                  <?php if ($type == 'stock'): ?>
                    <a href="<?=base_url('items/view/'.$item->item_id); ?>" data-toggle="tooltip" data-placement="top" title="Details"><i class="glyphicon glyphicon-eye-open"></i></a>&nbsp;
                  <?php endif; ?>
                  <a href="#viewBarcode" class="barcode" data-id="<?=$item->item_code;?>" data-toggle="modal"><span data-toggle="tooltip" data-placement="top" title="View Barcode" ><i class="glyphicon glyphicon-barcode"></i></span></a>&nbsp;
                  <a href="<?php echo base_url('items/modify/' . $item->item_id); ?>"><i data-toggle="tooltip" data-placement="top" title="Modify Item" class="glyphicon glyphicon-edit"></i></a>&nbsp;
                  <a href="#imgChange" class="update" data-id="<?=$item->item_id;?>" data-toggle="modal"><span data-toggle="tooltip" data-placement="top" title="Update Image" ><i class="glyphicon glyphicon-picture"></i></span></a>&nbsp;
                  <a data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')" href="<?=base_url('items/delete_item/'.$item->item_id); ?>" class=""><i class="glyphicon glyphicon-trash"></i></a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>

        <?php endif; ?>
      </tbody>
    </table>
    <ul class="pagination">
      <?php foreach ($links as $link) {
        echo "<li>". $link."</li>";
      } ?>
    </ul>

  </div><!-- /.box-body -->
</div><!-- /.box -->

<!-- script For Update Image -->
<script type="text/javascript">
$(document).on("click", ".update", function () {
  var itemId = $(this).data('id'); //Get the id
  var imgsrc = $('img[alt="image-'+itemId+'"]').attr('src');
  imgsrc = imgsrc.replace('_thumb','');
  $(".modal-body #modal-img").attr('src', imgsrc);
  $(".modal-body #item-id").val( itemId );
});

// For display Image preview
function PreviewImage() {
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById("item_image").files[0]);
  oFReader.onload = function (oFREvent) {
    document.getElementById("modal-img").src = oFREvent.target.result;
  };
};
</script>

<!-- Modal For Update Image -->
<div class="modal fade" id="imgChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?=form_open_multipart(base_url().'items/update_image', 'role="form" class="form-horizontal"'); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Change Item Image</h4>
      </div>
      <div class="modal-body">
        <p class="text-center">Current Image</p>
        <div id="img-change" class="text-center">
          <img id="modal-img" src="" alt="Not available"  style="max-height: 200px; max-width: 300px;">
        </div><br/><br/>
        <input type="hidden" name="item-id" id="item-id" value=""/>
        <div class="form-group">
          <div class="col-xs-12">
            <span class="btn btn-lg btn-default btn-flat btn-file col-xs-10 col-xs-offset-1 ">
              <i class="glyphicon glyphicon-open"></i> Choose Image <input type="file"  id="item_image" name="item_image" onchange="PreviewImage();">
            </span>
          </div><br/><br/>
          <div class="col-xs-12">
            <p class="help-block"><i class="glyphicon glyphicon-warning-sign"></i> Allowed only max_size = 150KB, max_width = 1024px, max_height = 768px, types = gif | jpg | png .</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary col-xs-6 col-sm-offset-2 btn-flat">Upload</button>
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
      </div>
      <?=form_close(); ?>
    </div> <!-- modal-content -->
  </div> <!-- .modal-dialog  -->
</div> <!-- .modal  -->

<!-- script For Display Image -->
<script type="text/javascript">
$(document).on("click", ".display-image", function () {
  var itemId = $(this).data('id'); //Get the id
  var imgId = $('a#'+itemId).html();
  imgId = imgId.replace('_thumb','');
  $("#display-image").html( imgId );
});
</script>

<!-- Modal For Display Image -->
<div class="modal fade" id="imgDisplay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-body text-center" id="display-image"></div>
  </div> <!-- .modal-dialog  -->
</div> <!-- .modal  -->


<!-- script For Barcode Image -->
<script type="text/javascript">
$(document).on("click", ".barcode", function () {
  var itemId = $(this).data('id'); //Get the id
  img = '<img src="<?php echo base_url();?>barcodes/'+itemId+'.jpg">';
  $("#view-barcode").html( img );
});
</script>

<!-- Modal For Barcode view -->
<div class="modal fade" id="viewBarcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Barcode Image</h4>
      </div>
      <div class="modal-body text-center" id="view-barcode"><p>Can't show the barcode.</p></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
      </div>
    </div> <!-- modal-content -->
  </div> <!-- .modal-dialog  -->
</div> <!-- .modal  -->

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
              <?php foreach ($categories as $value): ?>
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
  var url = "<?php echo base_url()."items/filter/".$type."?cat_id="; ?>";
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
