<div class="box  col-xs-12">
    <div class="box-header">
        <div class="content-nav">
            <a href="javascript:window.print()" class="btn btn-default btn-flat col-md-3 col-sm-4 col-xs-6 col-md-offset-3 col-sm-offset-2 col-xs-offset-2">
                <i class="glyphicon glyphicon-print"></i> Print
            </a>
        </div>
    </div><!-- /.box-header -->

		<div class="box-header" align="center" style="text-align: center">
          <span class="box-title pull-center" align="center" style="text-align: center"><!--<img src="<?php echo base_url(); ?>assets/images/main-logo.png" width="100" height="80" />--> PT WASCO ENGINEERING INDONESIA</span>
      </div><!-- /.box-header -->
      <div class="box-header" align="center" style="text-align: center">
          <span class="box-title pull-center" align="center" style="text-align: center">IT EQUIPMENT CHECKLIST</span>
      </div><!-- /.box-header -->
      <div class="box-body table-responsive">
        <div id="head" >
          <div id="section" class="section-header" >
            <strong> IT EQUIPMENT CHECKLIST </strong>
          </div>
          <table class="content-table">
            <tr>
              <td class="content-first"> Name of Employee </td>
              <td class="content-space"> : </td>
              <td class="content-text" colspan="5"> <?php echo $borrow->employee_name; ?> </td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td class="content-first"> Department </td>
              <td class="content-space"> : </td>
              <td class="content-text" colspan="5"> <?php echo $borrow->emp_dept; ?> </td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td class="content-first"> ASSET NUMBER </td>
              <td class="content-space"> : </td>
              <td class="content-text" colspan="5"> <?php echo $borrow->item_code; ?> </td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td class="content-first"> Location </td>
              <td class="content-space"> : </td>
              <td class="content-text" colspan="5">&nbsp;  </td>
              <td >&nbsp;</td>
            </tr>
          </table>
          <table class="content-table borderTable">
            <tr>
              <th> No. </th>
              <th> Asset Id. </th>
              <th> DESCRIPTION. </th>
              <th> Quantity. </th>
            </tr>
              <?php $i = 1; ?>
              <?php if ($borrow->item_id): ?>
                <tr>
                  <td class="accesories-first"><?php echo $i ?> </td>
                  <td class="accesories-code"><?php echo $borrow->item_code ?> </td>
                  <td class="accesories-text"><?php echo $borrow->item_name ?> </td>
                  <td class="accesories-first"><?php echo '1' ?> </td>
                  <?php $i++; ?>
                </tr>

              <?php endif; ?>

              <?php foreach ($borrow_details as $value): ?>
                <tr>
                  <td class="accesories-first"><?php echo $i ?> </td>
                  <td class="accesories-code"><?php echo $value->item_code ?> </td>
                  <td class="accesories-text"><?php echo $value->item_name ?> </td>
                  <td class="accesories-first"><?php echo $value->quantities ?> </td>
                </tr>
                <?php $i++; ?>
              <?php endforeach; ?>
          </table>
          <table class="content-table">
            <tr>
              <td class="content-first"> Remark </td>
              <td class="content-space"> : </td>
              <td class="content-remark"> <?php echo substr($borrow->note,0,40); ?> </td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td class="content-first">&nbsp;  </td>
              <td class="content-space">&nbsp;  </td>
              <td class="content-remark"> <?php echo substr($borrow->note,41); ?> </td>
              <td >&nbsp;</td>
            </tr>
          </table>
        </div>
        <div id="footer">
          <div id="section-footer" class="section-footer">
            <strong> ACKNOWLEDGEMENT </strong>
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
