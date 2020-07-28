<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Wasco Energy">
        <title>Wasco Energy - Inventory</title>
		<style>
		.style10 {
			color: #777;
			font-size: 10px;
		}
		</style>
        
        <!--Header-->
        <?php echo $header; ?>
        
        <!--Page Specific Header-->
        <?php
        if (isset($extra_head)) {
            echo $extra_head;
        }
        ?>
    </head>
    <body>
        <div class="container">
        	<?php
				if(isset($menu)=="login"){
			?>
        		<div class="row text-center">
            <?php }else{ ?>
            	<div class="row">
            <?php } ?>
            	<img src="<?php echo prefix_url;?>assets/images/wasco.png" alt="">
            </div>
            <!--Top Navigation-->
            <div class="row">
                <?php echo (isset($navigation)) ? $navigation : ''; ?>
            </div>
            <div id="note">
                <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';?>   
            </div>
            <section class="content">
                <?php echo (isset($content)) ? $content : ''; ?>
            </section> <!--content-->

            <!--Footer-->
            <?php echo $footer; ?>
            <!--Page Specific Footer-->
            <?php
            if (isset($extra_footer)) {
                echo $extra_footer;
            }
            ?>
            <div class="row text-center">
            <span class="style10">
            Copyright © <?php echo date('Y'); ?> Wasco Energy Inventory V.1.0 . All Right Reserved. | Developed By <a href="http://wascoenergy.com" target="_blank">PT WEI - MIS</a>
            </span>
            </div>
        </div> <!--Container-->
        
    </body>
</html>