<div class="box">
  <div class="box-header">
    <span class="box-title">Request Details</span>
  </div><!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        <div class="col-lg-6">
          <?php 
            if($request->uid){
              $employeeName = $request->employeename;
              $dateOfJoin = $request->join_date;
              $position = $request->positionExisting;
              $department = $request->departmentExisting;
              $company = $request->companyExisting;
              $employeeno = $request->employeeno;
            }else{
              $employeeName = $request->employeeName;
              $dateOfJoin = $request->dateOfJoin;
              $position = $request->positiondesc;
              $department = $request->deptdesc;
              $company = $request->company;
              $employeeno = "-"; 
            }
          ?>
          <p>Request ID : <?= $request->requestID ?></p>
          <p>Employee Status : <?= $request->employeeStatus ?></p>
          <p>Employee No : <?= $request->employeeno ?></p>
          <p>Employee Name : <?= $employeeName ?></p>
          <p>Company : <?= $company ?></p>
        </div>

        <div class="col-lg-6">
          <p>Phone : <?= $request->phone ?></p>
          <p>Department : <?= $department ?></p>
          <p>Designation : <?= $position ?></p>
          <p>Join Date : <?= $dateOfJoin ?></p>
          <p>Request Date : <?= $request->dateOfRequest ?></p>
        </div>
      </div>

      <div style="overflow-x:auto;">
        <table id="example" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Items</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requestDetails)): ?>
                    <?php 
                      $no = 1;
                      foreach ($requestDetails as $value): 
                    ?>
                        <tr>
                            <td>
                              <?php                                  
                                echo $no;
                              ?>
                            </td>
                            <td><?= $value->suggestion ?></td>
                            <td><?= $value->remarks ?></td>
                        </tr>
                    <?php 
                      $no++;
                      endforeach; 
                    ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            No Data
                        </td>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
      </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->