<?php
$ids = explode(',', $invoice->item_ids);
$price = explode(',', $invoice->unit_prices);
$quantity = explode(',', $invoice->quantities);
?>
<div class="pull-right">
    <a class="btn btn-default" onclick="window.print()"><i class="glyphicon glyphicon-print"></i> Print</a>
    <div class="row">&nbsp;</div>
</div>

<div id="invoice" class="paid">
    <div class="this-is">
        <strong>Purchase Invoice</strong>
    </div><!-- invoice headline -->

    <header id="header">
            <div class="col-xs-12">
        <div class="invoice-intro">
            <h1><?php echo $invoice->supplier_name;?></h1>
        </div>

        <dl class="invoice-meta">
            <dt class="invoice-due">&nbsp;</dt>
            <dd>&nbsp;</dd>
            <dt class="invoice-number">Transaction #</dt>
            <dd><?php echo $invoice->invoice_id ?></dd>
            <dt class="invoice-date">Date of Transaction</dt>
            <dd><?php echo $invoice->date ?></dd>
        </dl>
        </div>
    </header>
    <!-- e: invoice header -->

    <section id="parties" style="border: solid 1px white;">
        <div class="col-xs-12">
        <div class="invoice-from">
            <h2>From:</h2>
            <div id="hcard-Admiral-Valdore" class="vcard">
                <div class="org">Name: <?php echo $invoice->supplier_name; ?></div>
                <div class="org">Contact Person: <?php echo $invoice->supplier_key_person; ?></div>
                <div class="tel">Phone: <?php echo $invoice->supplier_phone; ?></div>
                <div class="street-address">Address: <?php echo $invoice->supplier_address; ?></div>
            </div><!-- e: vcard -->
        </div><!-- e invoice-from -->
        <div class="invoice-status">&nbsp;
        </div><!-- e: invoice-status -->
        <div class="invoice-to">
            <h2>Taken By:</h2>
            <div id="hcard-Hiram-Roth" class="vcard">
                <div class="org">Name: <?php echo $invoice->user_full_name; ?></div>
                <div class="tel">Phone: <?php echo $invoice->user_phone; ?></div>
                <div class="org">Email: <?php echo $invoice->user_email; ?></div>
            </div><!-- e: vcard -->
        </div><!-- e invoice-to -->
        </div>
    </section><!-- e: invoice partis -->

    <section class="invoice-financials">
        <div class="invoice-items">
            <div class="col-xs-12">
            <table>
                <caption class="caption">Detail</caption>
                <thead>
                    <tr>
                        <th class="item_code">Code</th>
                        <th class="item_desc">Item &amp; Description</th>
                        <th>Quantity</th>
                        <th>Measurement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoice_items as $value): ?>
                        <tr>
                            <td><?php echo $value->item_code ?></td>
                            <td><?php echo $value->item_name ?></td>
                            <td><?php echo $value->quantities ?></td>
                            <td><?php echo $value->measurement ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div><!-- e: invoice items -->
        <br/><br/>

        <br/><br/><br/>

        <?php if ($invoice->note != '') { ?>
            <div class="invoice-notes col-xs-12">
                <h6>Note:</h6>
                <p><?php echo $invoice->note; ?></p>
            </div><!-- e: invoice-notes -->
        <?php } else { ?>
            <br/><br/><br/>
        <?php } ?>
    </section><!-- e: invoice financials -->
    <br/><br/>   <br/><br/>   <br/>
    <section id="signature">
        <div class="col-md-12">
            <div class="col-md-4">
                <p>&nbsp;___________________________</p>
                <p> &nbsp;&nbsp;&nbsp;
                    Signed for <?php echo $this->session->userdata('brand'); ?>, Date
                </p>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <p>______________________________</p>
                <p> &nbsp;&nbsp;&nbsp;&nbsp;
                    Signed for supplier, Date
                </p>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
    </section>

    <footer id="footer">
        <p>&nbsp;
        </p>
    </footer>
</div><!-- e: invoice -->
