<div class="row">
    <div class="col-xs-12">
        <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    </div>
</div>
<div class="row">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Add Project</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?=form_open(base_url('settings/add_project'), 'role="form" class="form-horizontal"'); ?>
            <div class="form-group">
                <label for="warehouse_name" class="col-sm-2 hidden-xs control-label col-xs-offset-1 col-xs-2">Project Name: *</label>
                <div class="col-sm-8 col-xs-12">
                    <?=form_input('project_name', set_value('project_name'), 'id="project_name" placeholder="Project Name" class="form-control" required') ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-offset-1 col-sm-offset-3">
                    <p class="text-muted">* Required fields.</p>
                </label>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-flat col-xs-6 col-xs-offset-2  col-sm-offset-3"><i class="glyphicon glyphicon-ok"></i> Save</button>&nbsp;
                <button type="reset" class="btn btn-default btn-flat">Reset</button>
            </div>
            <?=form_close(); ?>
            <br/>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div><!-- /.row -->