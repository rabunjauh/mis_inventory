
<div class="pull-right">
  <a class="btn btn-default" onclick="window.print()"><i class="glyphicon glyphicon-print"></i> Print</a>
  <br>
  <a class="btn btn-default" href="<?php echo base_url('items/'); ?>"><i class="glyphicon glyphicon-circle-arrow-left	Try it"></i> Back</a>
  <div class="row">&nbsp;</div>
</div>

<div id="invoice" class="paid">
  <div class="this-is">
    <strong>Item Details</strong>
  </div><!-- invoice headline -->

  <header id="header">
    <div class="col-xs-12">
      <div class="invoice-intro">
        <h1><?php echo $item->item_name;?></h1>
      </div>

      <dl class="invoice-meta">
        <dt class="invoice-due">Service Tag</dt>
        <dd><?php echo $item->service_tag; ?></dd>
        <dt class="invoice-number">Manufacture</dt>
        <dd><?php echo $item->manufacture_desc; ?></dd>
        <dt class="invoice-date">Machine Type</dt>
        <dd><?php echo $item->machine_type_desc ?></dd>
        <dt class="invoice-date">Model</dt>
        <dd><?php echo $item->model_desc ?></dd>
      </dl>
    </div>
  </header>
  <!-- e: invoice header -->
  <section id="parties" style="border: solid 1px white;">
    <div class="col-xs-12">
      <div class="invoice-from">
        <h2>Details:</h2>
        <div id="hcard-Admiral-Valdore" class="vcard">
          <div class="org">Asset ID: <?php echo $item->item_code; ?></div>
          <div class="org">Operating System: <?php echo $item->operating_system_desc; ?></div>
          <div class="org">Computer Name: <?php echo $item->computer_name; ?></div>
          <div class="org">Processor: <?php echo $item->processor_type; ?></div>
          <div class="org">Memory: <?php echo $item->memory_size; ?></div>
          <div class="org">Hardisk: <?php echo $item->hard_disk_size; ?></div>
          <div class="org">VGA: <?php echo $item->vga_model; ?></div>
          <br>

        </div><!-- e: vcard -->
      </div><!-- e invoice-from -->
      <div class="invoice-status">&nbsp;
      </div><!-- e: invoice-status -->
      <div class="invoice-from">
        <h2> &nbsp;</h2>
        <div id="hcard-Admiral-Valdore" class="vcard">
          <div class="org">Warranty Status: <?php if($item->warranty_status == 0){echo "No Warranty";}else {echo "Warranty";}; ?></div>
          <div class="org">Warranty Date: <br><?php if($item->warranty_status == 1){echo $item->start_warranty." / ".$item->end_warranty;} ?></div>
          <br>
          <div class="org">Product Key Windows: <?php echo $item->product_key_windows; ?></div>
          <div class="org">Product Key Office: <?php echo $item->product_key_office; ?></div>
          <div class="org">Product Key Others: <?php echo $item->product_key_others; ?></div>
          <br>
        </div><!-- e: vcard -->
      </div><!-- e invoice-from -->
    </div>
  </section><!-- e: invoice partis -->

  <section class="invoice-financials">
    <div class="invoice-items">
      <div class="col-xs-12">
        <table>
          <caption class="caption">Borrow Details</caption>
          <thead>
            <tr>
              <th>Borrow By</th>
              <th>Project Name</th>
              <th>Warehouse Name</th>
              <th>Borrow Date</th>
              <th>Note</th>
              <th>Borrow Qty</th>
              <th>Return Qty</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (sizeof($borrowDetails) > 0): ?>
              <?php foreach ($borrowDetails as $value): ?>
                <tr>
                  <td><?php echo $value->employeename ?></td>
                  <td><?php echo $value->project_name ?></td>
                  <td><?php echo $value->warehouse_name ?></td>
                  <td><?php echo $value->borrow_date ?></td>
                  <td><?php echo $value->note ?></td>
                  <td><?php echo ($value->quantities) ? $value->quantities : '1';  ?></td>
                  <td><?php echo (!$value->return_quantity && $value->return_date) ? '1' : $value->return_quantity; ?> </td>
                  <td><?php if($value->return_date == ''){echo "<p style='color:red;'>Not Return</p>";}else{echo "<p style='color:green;'>Return</p>";} ; ?></td>
                </tr>
              <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8">
                    No Data
                  </td>
                </tr>
            <?php endif; ?>

          </tbody>
        </table>
      </div>
    </div><!-- e: invoice items -->
    <br/><br/>

    <br/><br/><br/>

    <?php if ($borrow->note != '') { ?>
      <div class="invoice-notes col-xs-12">
        <h6>Note:</h6>
        <p><?php echo $borrow->note; ?></p>
      </div><!-- e: invoice-notes -->
      <?php } else { ?>
        <br/><br/><br/>
        <?php } ?>
      </section><!-- e: invoice financials -->
      <br/><br/>   <br/><br/>   <br/>

      <footer id="footer">
        <p>&nbsp;
        </p>
      </footer>
    </div><!-- e: invoice -->
