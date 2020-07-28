<script src="<?php echo prefix_url;?>assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo prefix_url;?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo prefix_url;?>assets/css/datepicker.css" />
<style>
.datepicker{z-index:1151 !important;}
</style>

<div class="box">
  <div class="box-header">
    <span class="box-title">Transaction</span>
    <?php if ($this->session->userdata('role')): ?>
      <div class="content-nav text-center">
        <div class="btn-group col-md-8 col-sm-10 col-xs-10 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">
          <a href="<?=base_url('inventory/item_in'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-plus-sign"></i> Transaction In</a>
          <a href="<?=base_url('inventory/item_out'); ?>" class="btn btn-default btn-flat "><i class="glyphicon glyphicon-minus-sign"></i> Transaction Out</a>
          <a data-toggle="modal" data-target="#exportPurchase" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i> Purchase <span class="hidden-sm hidden-xs">Transactions</span></a>
          <a onclick="openExport('<?php echo base_url().'csv/download_csv_invoice_out' ?>','Export Sales Transactions')" data-target="#exportSales" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i> Sales <span class="hidden-sm hidden-xs">Transactions</span></a>
          <a onclick="openExport('<?php echo base_url().'csv/download_csv_invoice_out_detail' ?>','Export Sales Transactions Details')" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i> Sales <span class="hidden-sm hidden-xs">Transactions</span></a>
          <a href="#" data-toggle="modal" data-target="#filterModal" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-search"></i><span class="hidden-xs"> Filter</span></a>

        </div>
      </div>
    <?php endif; ?>
  </div>  <!-- .box-header -->
  <br/>
  <div class="">
    <ul class="nav nav-tabs nav-justified">
      <li><a href="<?=base_url('invoice/'); ?>"><strong>In</strong></a></li>
      <li class="active"><a href="<?=base_url('invoice/sales'); ?>" ><strong>Out</strong></a></li>
    </ul>
    <div class="box-body table-responsive">
      <div class="tab-content">
        <div class="tab-pane active" id="sales">
          <table id="" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Warehouse</th>
                <th>Emp Name</th>
                <th>Cost Center</th>
                <th>Project</th>
                <th class="hidden-xs">Grand Total</th>
                <th class="hidden-xs">User</th>
                <th class="hidden-xs">Actual Date</th>
                <th class="hidden-xs">Note</th>
                <?php if ($this->session->userdata('role')): ?>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($results)): ?>
                <?php foreach ($results as $sale): ?>
                  <tr>
                    <td><?php echo $sale->date; ?></td>
                    <td><?php echo $sale->warehouse_name; ?></td>
                    <td><?php echo $sale->employee_name; ?></td>
                    <td><?php echo $sale->cost_center; ?></td>
                    <td><?php echo $sale->project; ?></td>
                    <td class="hidden-xs"><?php echo $sale->quantities; ?>
                    </td>
                    <td class="hidden-xs"><?php echo $sale->user_full_name; ?>
                    </td>
                    <td class="hidden-xs"><?php
                    $exp = explode("-",$sale->actual_date);
                    echo $exp[2]."/".$exp[1]."/".$exp[0];
                    ?>
                  </td>
                  <td class="hidden-xs"><?php echo $sale->note; ?>
                  </td>
                  <?php if ($this->session->userdata('role')): ?>
                    <td>
                      <a href="<?=base_url('invoice/view/sales/'.$sale->invoice_id); ?>" data-toggle="tooltip" data-placement="top" title="View Invoice"><i class="glyphicon glyphicon-eye-open"></i></a>&nbsp;
                      <a href="<?=base_url('invoice/modify/sales/'.$sale->invoice_id); ?>" data-toggle="tooltip" data-placement="top" title="Modify"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;
                      <a data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')" href="<?=base_url('invoice/delete/sales/'.$sale->invoice_id); ?>" class=""><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6">
                  <span>No data available.</span>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <th>Date</th>
              <th>Warehouse</th>
              <th>Emp Name</th>
              <th>Cost Center</th>
              <th>Project</th>
              <th class="hidden-xs">Grand Total</th>
              <th class="hidden-xs">User</th>
              <th class="hidden-xs">Actual Date</th>
              <th class="hidden-xs">Note</th>
              <?php if ($this->session->userdata('role')): ?>
                <th>Action</th>
              <?php endif; ?>
            </tr>
          </tfoot>
        </table>
        <ul class="pagination">
          <?php foreach ($links as $link) {
            echo $link;
          } ?>
        </ul>
      </div><!-- .tab-pane -->
    </div><!-- .tab-content -->
  </div><!-- .tab-content -->
</div><!-- nav-tabs-custom -->
</div> <!-- .box -->
<div id="exportPurchase" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title">Export Purchase Transactions</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
          <?=form_open(base_url().'csv/download_csv_invoice_purchase', 'role="form" class="form-horizontal"'); ?>
          <div class="form-group">
            <label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Date From:</label>
            <div class="col-sm-6 col-xs-12">
              <input id="filterDateFrom" readonly data-date-format="dd/mm/yyyy"  class="form-control filterDate" placeholder="Fillter Date" type="text" name="filterDateFrom" />
            </div>
            <div class="col-sm-4 col-xs-12">
              <button type="button" class="btn btn-danger" onclick="clearDate(this)"> Clear </button>
            </div>
          </div>
          <div class="form-group">
            <label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">To:</label>
            <div class="col-sm-6 col-xs-12">
              <input id="filterDateTo" readonly data-date-format="dd/mm/yyyy"  class="form-control filterDate" placeholder="Fillter Date" type="text" name="filterDateTo" />
            </div>
            <div class="col-sm-4 col-xs-12">
              <button type="button" class="btn btn-danger" onclick="clearDate(this)"> Clear </button>
            </div>
          </div>
          <div class="form-group">
            <label for="supplier" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Supplier:</label>
            <div class="col-sm-6 col-xs-12">
              <select name="supplier" id="supplier" class="form-control">
                <option value="all">All</option>
                <?php
                foreach ($suppliers as $value) {
                  ?>
                  <option value="<?php echo $value->supplier_id ?>"><?php echo $value->supplier_name ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div>
            <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3">Export</button>&nbsp;
          </div>
          <?=form_close(); ?>
          <br/>
        </div><!-- /.box-body -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="exportSales" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title">Export Sales Transactions</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
            <?=form_open(base_url().'csv/download_csv_invoice_out', 'role="form" class="form-horizontal" id="exportModalForm"'); ?>
            <div class="form-group">
            	<label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Date From:</label>
                 <div class="col-sm-6 col-xs-12">
                    <input id="filterDateFrom" readonly data-date-format="dd/mm/yyyy"  class="form-control filterDate" placeholder="Fillter Date" type="text" name="filterDateFrom" />
                </div>
                <div class="col-sm-4 col-xs-12">
                  <button type="button" class="btn btn-danger" onclick="clearDate(this)"> Clear </button>
               </div>
            </div>
            <div class="form-group">
            	<label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">To:</label>
                 <div class="col-sm-6 col-xs-12">
                    <input id="filterDateTo" readonly data-date-format="dd/mm/yyyy"  class="form-control filterDate" placeholder="Fillter Date" type="text" name="filterDateTo" />
                </div>
                <div class="col-sm-4 col-xs-12">
                  <button type="button" class="btn btn-danger" onclick="clearDate(this)"> Clear </button>
               </div>
            </div>
            <div class="form-group">
            	<label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Warehouse:</label>
                 <div class="col-sm-6 col-xs-12">
                    <select name="warehouse" id="warehouse" class="form-control">
                      <option value="all">All</option>
                      <?php
                      foreach ($warehouse as $value) {
                        ?>
                        <option value="<?php echo $value->warehouse_id ?>"><?php echo $value->warehouse_name ?></option>
                        <?php
                      }
                       ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
            	<label for="cost_center" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Cost Center:</label>
                 <div class="col-sm-6 col-xs-12">
                    <select name="cost_center" id="cost_center" class="form-control">
                      <option value="all">All</option>
                      <?php
                      foreach ($costcenter as $value) {
                        ?>
                        <option value="<?php echo $value->cost_center_uid ?>"><?php echo $value->cost_center ?></option>
                        <?php
                      }
                       ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
            	<label for="project" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Project:</label>
                 <div class="col-sm-6 col-xs-12">
                    <select name="project" id="project" class="form-control">
                      <option value="all">All</option>
                      <?php
                      foreach ($project as $value) {
                        ?>
                        <option value="<?php echo $value->project_uid ?>"><?php echo $value->project_name ?></option>
                        <?php
                      }
                       ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
            	<label for="employee" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee:</label>
                 <div class="col-sm-6 col-xs-12">
                    <select name="employee" id="employee" class="form-control employee" onchange="changeValue(this)">
                      <option value="all">All</option>
                      <option value="browse">browse</option>
                    </select>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3">Export</button>&nbsp;
            </div>
            <?=form_close(); ?>
            <br/>
        </div><!-- /.box-body -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title">Filter</h4>
      </div>
      <div class="modal-body">
        <div class="box-body form-horizontal">
          <div class="form-group">
            <label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Date From:</label>
               <div class="col-sm-6 col-xs-12">
                  <input id="filterDateFrom_sales" readonly data-date-format="dd-mm-yyyy" value="<?php if(isset($_GET['actual_date_from'])){echo $_GET['actual_date_from'];} ?>" class="form-control filterDate" placeholder="Fillter Date" type="text" name="filterDateFrom" />
              </div>
              <div class="col-sm-4 col-xs-12">
                <button type="button" class="btn btn-danger" onclick="clearDate(this)"> Clear </button>
             </div>
          </div>
          <div class="form-group">
            <label for="date" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">To:</label>
               <div class="col-sm-6 col-xs-12">
                  <input id="filterDateTo_sales" readonly data-date-format="dd-mm-yyyy" value="<?php if(isset($_GET['actual_date_to'])){echo $_GET['actual_date_to'];} ?>" class="form-control filterDate" placeholder="Fillter Date" type="text" name="filterDateTo" />
              </div>
              <div class="col-sm-4 col-xs-12">
                <button type="button" class="btn btn-danger" onclick="clearDate(this)"> Clear </button>
             </div>
          </div>
          <div class="form-group">
            <label for="warehouse" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Warehouse:</label>
            <div class="col-sm-6 col-xs-12">
              <select name="warehouse" id="warehouse_id" class="form-control">
                <option value="all">All</option>
                <?php
                foreach ($warehouse as $value) {
                  ?>
                  <option <?php if(isset($_GET['warehouse_id']) && $_GET['warehouse_id'] == $value->warehouse_id){echo "selected";} ?> value="<?php echo $value->warehouse_id ?>"><?php echo $value->warehouse_name ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="cost_center" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Cost Center:</label>
            <div class="col-sm-6 col-xs-12">
              <select name="cost_center" id="cost_center_uid" class="form-control">
                <option value="all">All</option>
                <?php
                foreach ($costcenter as $value) {
                  ?>
                  <option <?php if(isset($_GET['cost_center_uid']) && $_GET['cost_center_uid'] == $value->cost_center_uid){echo "selected";} ?>  value="<?php echo $value->cost_center_uid ?>"><?php echo $value->cost_center ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="project" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Project:</label>
            <div class="col-sm-6 col-xs-12">
              <select name="project" id="project_uid" class="form-control">
                <option value="all">All</option>
                <?php
                foreach ($project as $value) {
                  ?>
                  <option <?php if(isset($_GET['project_uid']) && $_GET['project_uid'] == $value->project_uid){echo "selected";} ?> value="<?php echo $value->project_uid ?>"><?php echo $value->project_name ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="employee" class="col-sm-2 hidden-xs control-label col-xs-offset-0 col-xs-2">Employee:</label>
            <div class="col-sm-6 col-xs-12">
              <select name="employee" id="employee_id" class="form-control employee" onchange="changeValue(this)">
                <option value="all">All</option>
                <option value="browse">browse</option>
                <?php if (isset($employeename) && isset($_GET['employee_id'])): ?>
                  <option value="<?php echo $_GET['employee_id'];?>" selected><?php echo $employeename; ?> </option>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div>
            <button type="button" onclick="submitFilter()" class="btn btn-success"> Search </button>
          </div>
          <br/>
        </div><!-- /.box-body -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>


function submitFilter() {
  var filterDateFrom_sales = $("#filterDateFrom_sales" ).val() ;
  var filterDateTo_sales = $("#filterDateTo_sales" ).val() ;
  var warehouse_id = $("#warehouse_id" ).val();
  var cost_center_uid = $("#cost_center_uid" ).val();
  var project_uid = $("#project_uid").val();
  var employee_id = $("#employee_id").val();
  var url = "<?php echo base_url()."invoice/filter_invoice_out?project_uid="; ?>";
  url += project_uid;
  url += "&warehouse_id="+warehouse_id;
  url += "&cost_center_uid="+cost_center_uid;
  url += "&employee_id="+employee_id;

  if (filterDateTo_sales == "") {
    url += "&actual_date_to=all";
  }else {
    url += "&actual_date_to="+filterDateTo_sales;
  }

  if (filterDateFrom_sales == "") {
    url += "&actual_date_from=all";
  }else {
    url += "&actual_date_from="+filterDateFrom_sales;
  }

  location.replace(url);

}



function clearDate(e) {
  $(e).parent().parent().find('.filterDate').val('');
}

$('.filterDate').datepicker();
$('.clearDate').click(function(e) {
  $('.filterDate').val('');
})

$('#exportSalesDetails').on('hidden.bs.modal', function () {
  removeAtr('.employee');
})

$('#exportSales').on('hidden.bs.modal', function () {
  removeAtr('.employee');
})

function openExport(url,title) {
  $('#exportSales').modal('show');
  $('#exportSales').find('#title').text(title);
  $('#exportSales').find('#exportModalForm').attr('action',url);
}

function changeValue(e) {
  var data = $(e).val();
  removeAtr(e);

  if (data == 'browse') {
    $(e).val('all')
    open_popup();
  }else if (data == 'all') {
    $(e).val('all')
  }
}

function removeAtr(e) {
  $(e).find('option').remove();
  $(e)
  .append($("<option></option>")
  .attr("value",'all')
  .text('All'));
  $(e)
  .append($("<option></option>")
  .attr("value",'browse')
  .text('Browse'));
}


function open_popup() {
  window.open('<?php echo prefix_url;?>inventory/employee/', 'popuppage', 'width=700,location=0,toolbar=0,menubar=0,resizable=1,scrollbars=yes,height=500,top=100,left=100');
}
</script>
