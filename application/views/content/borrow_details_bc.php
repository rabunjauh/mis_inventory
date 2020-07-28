
<div class="pull-right">
  <div class="row">&nbsp;</div>
  <div class="row">&nbsp;</div>
  <a class="btn btn-default" onclick="window.print()"><i class="glyphicon glyphicon-print"></i> Print</a>
</div>

<div id="invoice" >
  <div class="this-is">
    <strong>Borrow Details</strong>
  </div><!-- invoice headline -->

  <header id="header">
    <div class="col-xs-12">
      <div class="invoice-intro">
        <h1><?php echo $borrow->project_name;?></h1>
      </div>

      <dl class="invoice-meta">
        <dt class="invoice-due">&nbsp;</dt>
        <dd>&nbsp;</dd>
        <dt class="invoice-number">Borrow #</dt>
        <dd><?php echo $borrow->borrow_id ?></dd>
        <dt class="invoice-date">Date borrowed</dt>
        <dd><?php echo $borrow->borrow_date ?></dd>
      </dl>
    </div>
  </header>
  <!-- e: invoice header -->

  <section id="parties" style="border: solid 1px white;">
    <div class="col-xs-12">
      <div class="invoice-from">
        <h2>Borrow By:</h2>
        <div id="hcard-Admiral-Valdore" class="vcard">
          <div class="org">Name: <?php echo $borrow->employee_name; ?></div>
          <div class="org">Project Name: <?php echo $borrow->project_name; ?></div>
          <div class="org">Warehouse Name: <?php echo $borrow->warehouse_name; ?></div>
        </div><!-- e: vcard -->
      </div><!-- e invoice-from -->
      <div class="invoice-status">&nbsp;
      </div><!-- e: invoice-status -->

    </div>
  </section><!-- e: invoice partis -->

  <section class="invoice-financials">
    <div class="invoice-items">
      <div class="col-xs-12">
        <table>
          <caption class="caption">Detail</caption>
          <thead>
            <tr>
              <th class="item_code">Items Code</th>
              <th class="item_desc">Items</th>
              <th>Borrow Qty</th>
              <th>Return Qty</th>
              <th>Measurement</th>
              <th>Borrow Date</th>
              <th>Return Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (sizeof($borrow_details) > 0): ?>
              <?php foreach ($borrow_details as $value): ?>
                <tr>
                  <th class="item_code"><?php echo $value->item_code; ?></th>
                  <th class="item_code"><?php echo $value->item_name; ?></th>
                  <th><?php echo $value->quantities; ?></th>
                  <th><?php echo $value->return_quantity; ?></th>
                  <th><?php echo $value->measurement; ?></th>
                  <th><?php echo $value->end_date; ?></th>
                  <th><?php if($value->return_date == ''){echo "<p style='color:red;'>Not Return</p>";}else{echo "<p style='color:green;'>Return</p>";} ; ?></th>
                </tr>
              <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7">
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
