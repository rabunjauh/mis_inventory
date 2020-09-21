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
                    <td>
                        <?php
                            if(isset($all_item->machine_type_desc)){
                                echo $all_item->machine_type_desc;  
                            }elseif(isset($all_item->cat_name)){
                                echo $all_item->cat_name;
                            }elseif(isset($all_item->model_desc)){
                                echo $all_item->model_desc;
                            }
                        ?>
                    </td>
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