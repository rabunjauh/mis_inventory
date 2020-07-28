<div class="box">
	<div class="box-body table-responsive">
		<div><h3><?php echo $title; ?></h3></div>
		<div><h5>Quantity by Type</h5></div>
		<div class="row">
		<?php foreach ($items_type as $value) { ?>								
		<div class="col-lg-3" style="margin: 5px;">
			<table class="table table-bordered table-striped">
				<tr><th colspan="2"><?php echo $value->machine_type; ?></th></tr>
				<tr>
					<td>Total</td>
					<td>
						<?php 
							if ($totalItemByType[$value->machine_type]["totalByType"]) {
								echo $totalItemByType[$value->machine_type]["totalByType"];
							}
							else{
								echo "0";
							}

						?>							
					</td>
				</tr>

				<tr>
					<td>Borrowed</td>
					<td>
						<?php if($borrowItemByType[$value->machine_type]["borrowByType"]){
							echo $borrowItemByType[$value->machine_type]["borrowByType"];
						}
						else{
							echo "0";
						} ?>
					</td>
				</tr>
				<tr><td>Spare</td><td>10</td></tr>
			</table>
		</div>
		<?php } ?>
		</div>
	</div>
</div>
			
<div class="box">
	<div class="box-body table-responsive">
		<div><h5>Total Quantity by Type</h5></div>
			
		<div class="row">
			<?php foreach ($item_categories as $cat){ ?>
				<div class="col-sm-2 col-lg-2 col-xm-1" style="padding: 0px; margin: 15px; background-color: #9c9898;">
					<div style="margin: 5px;">
						<h3>	
							<?php
								if($totals[$cat->cat_id]["totalinventory"]){
									echo $totals[$cat->cat_id]["totalinventory"];
								}else{
									echo 0;
								}
							?>						
						</h3>
					</div>
					<a href="" style="text-decoration: none;"><div style="padding: 5px; background-color: #FFFFFF; border-style: solid; border-color: #9c9898; border-width: 1px;"><?php echo $cat->cat_name; ?></div></a>
				</div>				
			<?php 	}  ?>
		</div>
	</div>
</div>
	
			
			<div class="bg-primary">	
			

			<h5>Spare Quantity</h5>
			<table class="table table-bordered table-striped">
				<tr>
					<th>Items Name</th>
					<th>Quantity</th>
				</tr>
			<?php 
			// var_dump($totals);
			//$jsondt = json_decode($totals);
			//var_dump($jsondt);
			foreach ($item_categories as $cat){ ?>
					<tr>
						<td><a href="<?php echo base_url('dashboard/total'); ?>"><?php echo $cat->cat_name; ?></a></td>
						<td>
							<?php
								if($totals[$cat->cat_id]["totalinventory"]){
									echo $totals[$cat->cat_id]["totalinventory"];
								}else{
									echo 0;
								}
							?>
						</td>

					</tr>				
			<?php 	}  ?>
			</table>
			</div>		
		
