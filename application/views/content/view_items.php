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
                    <a href="<?=base_url('items/'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-ok-sign"></i> Stock</a>
                    <a href="<?=base_url('items/add_item'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-plus-sign"></i> <span class="hidden-xs"> Add New Item</span></a>
                    <a href="<?=base_url('items/view_barcodes'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-barcode"></i> <span class="hidden-xs"> View Barcodes</span></a>
                    <a href="<?=base_url('csv/items_download_'.$type); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i><span class="hidden-xs"> Download</span></a>
                    <a href="<?=base_url('csv'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plane"></i> CSV Upload</a>
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
                        <th>Item Code</th>
                        <th class="hidden-xs">Image</th>
                        <th>Item Name</th>
                        <th class="hidden-xs">Category</th>
                        <th class="hidden-xs">Measurement</th>
                        <th class="hidden-md hidden-sm hidden-xs">Item Description</th>
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
                                <td class="hidden-xs">
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
                                <td class="hidden-xs">
                                    <?php $str = '[';
                                    foreach ($categories as $value) {
                                        $str .= "{value:" . $value->cat_id . ",text:'" . $value->cat_name . " '},";
                                    }
                                    $str = substr($str, 0, -1);
                                    $str .= "]"; ?>
                                    <a href="#" data-name="cat_id" data-type="select" data-url="<?=base_url('items/update_item'); ?>" data-source="<?=$str; ?>" data-value="<?=$item->cat_id; ?>" data-pk="<?=$item->item_id; ?>" class="data-modify-<?=$item->item_id; ?> no-style"><?=$item->cat_name; ?></a>
                                </td>
                                <td class="hidden-md hidden-sm hidden-xs">
                                    <a href="#" data-name="measurement_id" data-type="text" data-url="<?=base_url('items/update_item'); ?>" data-pk="<?=$item->item_id; ?>" class="data-modify-<?=$item->item_id; ?> no-style"><?=$item->measurement; ?></a>
                                </td>
                                <td class="hidden-md hidden-sm hidden-xs">
                                    <a href="#" data-name="item_description" data-type="textarea" data-rows="4" data-url="<?=base_url('items/update_item'); ?>" data-pk="<?=$item->item_id; ?>" class="data-modify-<?=$item->item_id; ?> no-style"><?=$item->item_description; ?></a>
                                </td>
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
        </div>
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
                        <?php foreach ($categories as $value): ?>
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
    var url = "<?php echo base_url()."items/filter/".$type."?cat_id="; ?>";
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
