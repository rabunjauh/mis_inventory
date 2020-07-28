<div class="box">
	<div class="box-body table-responsive">
<h3>Borrowed Items</h3>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>No</th>
				<th>Serial Number</th>
				<th>Item Name</th>
				<th>Machine Type</th>
				<th>Borrow By</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($results as $result): ?>
				<tr>
					<td>
						<?php 
							if(!$start){
								$start = 0;
							}
								echo ++$start; 
						?>
					</td>
					<td><?php echo $result->service_tag; ?></td>
					<td><?php echo $result->item_name; ?></td>
					<td><?php echo $result->machine_type; ?></td>
					<td><?php echo $result->employee_name; ?></td>
					<td>
						<a href="">Modify</a>
						<a href="">Delete</a>
					</td>
				</tr>
			<?php endforeach ?>	
		</tbody>
	</table>

	 <?php echo $this->pagination->create_links(); ?>
</div>
</div>