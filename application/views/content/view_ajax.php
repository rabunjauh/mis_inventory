<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Machine Type</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($all_items)): ?>
        	
            <?php
            	$no = 1;
            	foreach ($all_items as $all_item): 
            ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $all_item->machine_type; ?></td>
                    <td><?= $all_item->qty; ?></td>                                    
                </tr>
            <?php 
            	$no++;
        		endforeach; 
            ?>
                
        <?php else: ?>
                <tr>
                    <td colspan="7">
                        <span>No data available.</span>
                    </td>
                </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Machine Type</th>
            <th>Quantity</th>
        </tr>
    </tfoot>
</table>