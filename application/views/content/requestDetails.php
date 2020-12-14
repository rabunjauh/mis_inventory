<div class="pull-right">
  <a class="btn btn-default" href="<?php echo base_url('items/'); ?>"><i class="glyphicon glyphicon-circle-arrow-left	Try it"></i> Back</a>
  <div class="row">&nbsp;</div>
</div>

<div id="invoice" class="paid">
  <div class="this-is">
    <strong>Request Details</strong>
  </div>

  <section id="parties" style="border: solid 1px white;">
    <div class="col-xs-12">
      <div class="invoice-from">
        <h2>Employee Details:</h2>
        <div id="hcard-Admiral-Valdore" class="vcard">
          <?php
          if ($request->uid) {
            $employeeName = $request->employeename;
            $dateOfJoin = $request->join_date;
            $position = $request->positionExisting;
            $department = $request->departmentExisting;
            $company = $request->companyExisting;
          } else {
            $employeeName = $request->employeeName;
            $dateOfJoin = $request->dateOfJoin;
            $position = $request->positiondesc;
            $department = $request->deptdesc;
            $company = $request->company;
          }
          ?>
          <div class="org">Request ID: <?= $request->requestID; ?></div>
          <div class="org">Employee Status: <?= $request->statusDesc; ?></div>
          <div class="org">Employee Name: <?= $employeeName; ?></div>
          <div class="org">Company: <?= $company; ?></div>
          <br>

        </div><!-- e: vcard -->
      </div><!-- e invoice-from -->
      <div class="invoice-status">&nbsp;
      </div><!-- e: invoice-status -->
      <div class="invoice-from">
        <h2> &nbsp;</h2>
        <div id="hcard-Admiral-Valdore" class="vcard">
          <div class="org">Department: <?= $department; ?></div>
          <div class="org">Position : <?= $position; ?></div>
          <div class="org">Join Date: <?= $dateOfJoin; ?></div>
          <div class="org">Request Date: <?= $request->dateOfRequest; ?></div>
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
              <th>Employee ID</th>
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
            <?php if (sizeof($borrowDetails) > 0) : ?>
              <?php foreach ($borrowDetails as $value) : ?>
                <tr>
                  <td><?php echo $value->employeename ?></td>
                  <td><?php echo $value->fingerid ?></td>
                  <td><?php echo $value->project_name ?></td>
                  <td><?php echo $value->warehouse_name ?></td>
                  <td><?php echo $value->borrow_date ?></td>
                  <td><?php echo $value->note ?></td>
                  <td><?php echo isset(($value->quantities)) ? $value->quantities : '1';  ?></td>
                  <td>
                    <?php
                    if (isset($value->return_quantity)) {
                      echo $value->return_quantity;
                    } elseif (isset($value->return_date)) {
                      echo '1';
                    }
                    ?>
                  </td>
                  <td><?php if ($value->return_date == '') {
                        echo "<p style='color:red;'>Not Return</p>";
                      } else {
                        echo "<p style='color:green;'>Return</p>";
                      }; ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
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
    <br /><br />

    <br /><br /><br />

    <?php if (isset($borrow->note) != '') { ?>
      <div class="invoice-notes col-xs-12">
        <h6>Note:</h6>
        <p><?php echo $borrow->note; ?></p>
      </div><!-- e: invoice-notes -->
    <?php } else { ?>
      <br /><br /><br />
    <?php } ?>
  </section><!-- e: invoice financials -->
  <br /><br /> <br /><br /> <br />

  <footer id="footer">
    <p>&nbsp;
    </p>
  </footer>
</div><!-- e: invoice -->