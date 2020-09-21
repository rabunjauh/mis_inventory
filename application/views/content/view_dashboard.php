<div class="box">
    <div class="box-header">
        <span class="box-title"><?= $title ?></span>
    </div><!-- /.box-header -->
    <div class="">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#all_items" data-toggle="tab"><strong>All Items</strong></a></li>
            <li><a href="#borrowed" data-toggle="tab"><strong>Borrowed</strong></a></li>
            <li><a href="#spare" data-toggle="tab"><strong>Spare</strong></a></li>
        </ul>
        <div class="box-body table-responsive">
            <div class="tab-content">
                <div class="tab-pane active" id="all_items">
                    <?php if ($this->session->userdata('role')): ?>
                        <div class="content-nav">
                            <div class="btn-group col-md-4 col-sm-8 col-xs-8 col-md-offset-4 col-sm-offset-2 col-xs-offset-2">
                                <a href="<?php echo base_url('settings/add_warehouse'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-plus-sign"></i> Add Warehouse </a>
                                <a href="<?=base_url('csv/download_csv/warehouse'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i> Download csv</a>
                            </div>
                        </div><br/><br/><br/>
                    <?php endif; ?>
					<?= form_open(base_url('dashboard/')) ?>
						<div class="form-group">
							<label for="select_filter">View By :</label>
							<select name="select_filter" id="select_filter" class="form-control" required>
								<option value="">View By</option>
								<option value="machine_type">Machine Type</option>
								<option value="category">Category</option>
								<option value="model">Model</option>
							</select>
						</div>

                        <div class="form-group">
                            <button type="submit" id="select_filter_submit" name="select_filter_submit">Submit</button>
                        </div>	
					<?=form_close(); ?>							
                   <div id="table_all_items">
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
                                        <td><?= $all_item->machine_type_desc; ?></td>
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
                   </div>
                </div><!-- .tab-pane -->
                

                <div class="tab-pane" id="borrowed">
                    <?php if ($this->session->userdata('role')): ?>
                        <div class="content-nav text-center">
                            <div class="btn-group col-md-4 col-sm-8 col-xs-8 col-md-offset-4 col-sm-offset-2 col-xs-offset-2">
                                <a href="<?php echo base_url('settings/add_costcenter'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-plus-sign"></i> Add Cost Center </a>
                                <a href="<?=base_url('csv/download_csv/costcenter'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i> Download csv</a>
                            </div>
                        </div><br/><br/><br/>
                    <?php endif; ?>
                    <table id="" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Cost Center UID</th>
                                <th>Cost Center</th>
                                <?php if ($this->session->userdata('role')): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($costcenter)): ?>
                                <?php foreach ($costcenter as $costcenter): ?>
                                    <tr>
                                    	<td><? echo $costcenter->cost_center_uid; ?></td>
                                        <td>
                                            <a href="#" data-name="cost_center" data-type="text" data-url="<?= base_url('settings/update_costcenter'); ?>" data-pk="<?= $costcenter->cost_center_id; ?>" class="data-modify-<?= $costcenter->cost_center_id; ?> no-style"><?= $costcenter->cost_center; ?></a>
                                        </td>
                                        <?php if ($this->session->userdata('role')): ?>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-default modify" name="modify-<?= $costcenter->cost_center_id; ?>"><i class="glyphicon glyphicon-edit"></i><span class="hidden-sm hidden-xs">  Modify</span></a>
                                                <a onclick="return confirm('Are you sure you want to delete this cost center?')" href="<?= base_url('settings/delete_costcenter/' . $costcenter->cost_center_id); ?>"  class="btn btn-sm btn-default"><i class="glyphicon glyphicon-trash"></i><span class="hidden-sm hidden-xs"> Delete</span></a>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
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
                                <th>Cost Center UID</th>
                                <th>Cost Center</th>
                                <?php if ($this->session->userdata('role')): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div><!-- .tab-pane -->
                <div class="tab-pane" id="spare">
                    <?php if ($this->session->userdata('role')): ?>
                        <div class="content-nav text-center">
                            <div class="btn-group col-md-4 col-sm-8 col-xs-8 col-md-offset-4 col-sm-offset-2 col-xs-offset-2">
                                <a href="<?php echo base_url('settings/add_project'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-plus-sign"></i> Add Project </a>
                                <a href="<?=base_url('csv/download_csv/project'); ?>" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-save"></i> Download csv</a>
                            </div>
                        </div><br/><br/><br/>
                    <?php endif; ?>
                    <table id="" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            	<th>Project UID</th>
                                <th>Project Name</th>

                                <?php if ($this->session->userdata('role')): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($project)): ?>
                                <?php foreach ($project as $project): ?>
                                    <tr>
                                    	<td><? echo $project->project_uid; ?></td>
                                        <td>
                                            <a href="#" data-name="project_name" data-type="text" data-url="<?= base_url('settings/update_project'); ?>" data-pk="<?= $project->project_id; ?>" class="data-modify-<?= $project->project_id; ?> no-style"><?= $project->project_name; ?></a>
                                        </td>
                                        <?php if ($this->session->userdata('role')): ?>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-default modify" name="modify-<?= $project->project_id; ?>"><i class="glyphicon glyphicon-edit"></i><span class="hidden-sm hidden-xs">  Modify</span></a>
                                                <a onclick="return confirm('Are you sure you want to delete this warehouse?')" href="<?= base_url('settings/delete_project/' . $warehouse->project_id); ?>"  class="btn btn-sm btn-default"><i class="glyphicon glyphicon-trash"></i><span class="hidden-sm hidden-xs"> Delete</span></a>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
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
                                <th>Project UID</th>
                                <th>Project Name</th>
                                <?php if ($this->session->userdata('role')): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div><!-- .tab-pane -->
            </div><!-- .tab-content -->
        </div><!-- .tab-content -->
    </div><!-- nav-tabs-custom -->
</div> <!-- .box -->
<script>
let select_filter = document.getElementById('select_filter');
let table_all_items = document.getElementById('table_all_items');
const baseUrl = '<?php echo base_url("dashboard/view_dashboard") ?>';
select_filter.addEventListener('change', function(){

    //create ajax object
    let xhr = new XMLHttpRequest();

    // check if ajax is ready
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            console.log(select_filter.value);
            table_all_items.innerHTML = xhr.responseText;
        }
    }

    // ajax execution
    xhr.open('GET', baseUrl + '/' + select_filter.value, true);
    xhr.send();
})
</script>