<div class="box  col-xs-12">
    <div class="box-header">
        <div class="content-nav">
            <a href="javascript:window.print()" class="btn btn-default btn-flat col-md-3 col-sm-4 col-xs-6 col-md-offset-3 col-sm-offset-2 col-xs-offset-2">
                <i class="glyphicon glyphicon-print"></i> Print
            </a>
        </div>
    </div><!-- /.box-header -->
      <div class="box-header">
          <span class="box-title pull-right">Asset Custody Form</span>
      </div><!-- /.box-header -->
      <div class="box-body table-responsive">
        <div id="head">
          <div id="section" class="section-header">
            <strong> SECTION A : ASSET DETAILS </strong>
          </div>
          <div class="aggrement">
            <p>
              All employees have the responsibility of safeguarding assets issued to them by the company.<br>
              In the event of  loss or damage (due to negligence of the employee),
              the employee shall pay for the cost of the replacement.
            </p>
          </div>
          <div class="aggrement">
            <p>
              Employee must return all the assets under his custody on the last day of his employment or<br> when the reason for having it no longer exists.
              (All computer related equipment should return to MIS Dept directly).
            </p>
          </div>
          <table class="content-table">
            <tr>
              <td class="content-first"> ID </td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->taken_by_uid; ?> </td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>

            </tr>
            <tr>
              <td class="content-first"> Name of Employee </td>
              <td class="content-space"> : </td>
              <td class="content-text" colspan="3"> <?php echo $borrow->employee_name; ?> </td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td class="content-first"> Status</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php if($borrow->status_employee){echo "Permanent";}else{echo "Contract Staff";}; ?> </td>
              <td class="content-space">&nbsp;</td>
              <td class="content-first"> Position</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->emp_pos; ?> </td>
            </tr>
            <tr>
              <td class="content-first"> Department</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->emp_dept; ?> </td>
              <td class="content-space">&nbsp;</td>
              <td class="content-first"> Project</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->emp_project; ?> </td>
            </tr>
            <tr>

            </tr>
            <tr>
              <td class="content-first"> Email </td>
              <td class="content-space"> : </td>
              <td class="content-text" colspan="5"> <?php echo str_replace(" ",".",strtolower($borrow->employee_name)) ;?> </td>
            </tr>
          </table>
        </div>
        <div id="mid">
          <div class="section-mid">
            <strong> &nbsp; </strong>
          </div>
          <div class="header-text">
            <strong> Hardware </strong>
          </div>
          <table class="content-table">
            <tr>
              <td class="content-first"> Asset No. </td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->item_code; ?> </td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td class="content-first"> Machine Type</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->machine_type_desc; ?> </td>
              <td class="content-space">&nbsp;</td>
              <td class="content-first"> Model</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->model_desc; ?> </td>
            </tr>
            <tr>
              <td class="content-first"> Serial No./Service Tag</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->service_tag; ?> </td>
              <td class="content-space">&nbsp;</td>
              <td class="content-first"> Memory</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->memory_size; ?> </td>
            </tr>
            <tr>
              <td class="content-first"> Processor</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->processor_type; ?> </td>
              <td class="content-space">&nbsp;</td>
              <td class="content-first"> Hardisk</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->hard_disk_size; ?> </td>
            </tr>
            <tr>
              <td class="content-first"> Operating System</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->operating_system_desc; ?> </td>
              <td class="content-space">&nbsp;</td>
              <td class="content-first"> VGA Type</td>
              <td class="content-space"> : </td>
              <td class="content-text"> <?php echo $borrow->vga_manufacture . " " . $borrow->vga_model; ?> </td>
            </tr>
          </table>

          <div class="header-text">
            <strong> Accessories </strong>
          </div>
          <table class="content-table accessories">
            <tr>
              <th colspan="2">No. </th>
              <th colspan="2">Asset Id. </th>
              <th colspan="2">Item. </th>
              <th>Quantity. </th>
            </tr>
            <?php $i = 1; ?>
            <?php foreach ($borrow_details as $value): ?>
              <tr>
              <td class="accesories-first"><?php echo $i ?> </td>
              <td class="accesories-space">&nbsp; </td>
              <td class="accesories-code"><?php echo $value->item_code ?> </td>
              <td class="accesories-space">&nbsp; </td>
              <td class="accesories-text"><?php echo $value->item_name ?> </td>
              <td class="accesories-space">&nbsp; </td>
              <td class="accesories-first"><?php echo $value->quantities ?> </td>
              <td >&nbsp; </td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          </table>

          <div class="header-text">
            <strong>Software </strong>
          </div>
          <table class="content-table software">
            <tr>
              <th colspan="2">No. </th>
              <th colspan="2">Software Id. </th>
              <th colspan="2">Software Description. </th>
              <th>Quantity. </th>
            </tr>
            <?php $i = 1; ?>
            <?php foreach ($software as $value): ?>
            <tr>
            <td class="accesories-first"><?php echo $i ?> </td>
              <td class="accesories-space">&nbsp; </td>
              <td class="accesories-code"><?php echo $value->item_code ?> </td>
              <td class="accesories-space">&nbsp; </td>
              <td class="accesories-text"><?php echo $value->item_name ?> </td>
              <td class="accesories-space">&nbsp; </td>
              <td class="accesories-first"><?php echo $value->quantities ?> </td>
              <td >&nbsp; </td>
            </tr>

              <?php $i++; ?>
            <?php endforeach; ?>
          </table>

        </div>
        <div id="footer">
          <div id="section-footer" class="section-footer">
            <strong> SECTION B : ACKNOWLEDGEMENT </strong>
          </div>
          <table class="footer-table">
            <tr>
              <td class="footer-first"> Received By </td>
              <td class="footer-space"> : </td>
              <td class="footer-text"> <?php echo $borrow->employee_name; ?> </td>
              <td class="footer-first"> Returned By </td>
              <td class="footer-space"> : </td>
              <td class="footer-text"> <?php echo ($borrow->return_date) ? $borrow->employee_name : '' ?> </td>
            </tr>
            <tr>
              <td class="footer-first"> Signature </td>
              <td class="footer-space"> : </td>
              <td class="footer-signature">  </td>
              <td class="footer-first"> Signature </td>
              <td class="footer-space"> : </td>
              <td class="footer-signature">  </td>
            </tr>
            <tr>
              <td class="footer-first"> Date Received </td>
              <td class="footer-space"> : </td>
              <td class="footer-text"> <?php echo $borrow->borrow_date; ?> </td>
              <td class="footer-first"> Date Returned </td>
              <td class="footer-space"> : </td>
              <td class="footer-text">  <?php echo $borrow->return_date; ?></td>
            </tr>
          </table>
        </div>
      </div><!-- /.box-body -->
      <!-- <div class="pagebreak"> </div> -->



</div><!-- /.box -->
