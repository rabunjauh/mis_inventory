<div class="container box">	
    <div class="box-header">
        <span class="box-title"><?php echo $title ?></span>
    </div><!-- /.box-header -->

	<div class="row" style="padding-top: 5px;">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>No</th>
                    <th>Machine Type</th>
                    <th>Total</th>
                    <th>Spare</th>
                    <th>Borrowed</th>
                    <th>Damage</th>
                </tr>	
                <?php 
                    $no = 1;
                    foreach ($machine_types as $machine_type):
                ?>				
                <tr>
                    <td><?php echo $no ?></td>                    
                    <td><?php echo $machine_type->machine_type_desc ?></td>                    
                    <td><?php echo $machine_type->total ?></td>
                    <td><?php echo $machine_type->spare ?></td>
                    <td><?php echo ($machine_type->total-$machine_type->spare) - $machine_type->damage ?></td>
                    <td><?php echo $machine_type->damage ?></td>
                    
                </tr>	
                <?php
                    $no++;
                    endforeach 
                ?>			
            </table>
        </div>
	</div>	
</div>	
