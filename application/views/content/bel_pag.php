<div class="container">
	<div class="row">
		<div class="col-md-10">
			<h3 class="mt-3">List Items</h3>

			<table class="table">
				<thead>
					<tr>
						<th>No</th>
						<th>Items</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						if(!$start){
							$start = 1;
						}

						$start;

					 ?>

					<?php foreach ($items as $item): ?>
					<tr>
						<td><?php echo $start++; ?></td>
						<td><?php echo $item['item_name']; ?></td>
						<td>
							<a href="" class="badge">Detail</a> |
							<a href="" class="badge">Edit</a> |
							<a href="" class="badge">Delete</a>
						</td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>

			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>
</div>