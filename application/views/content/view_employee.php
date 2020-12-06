<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<link rel="stylesheet" href="<?php echo prefix_url;?>assets/css/style_popup.css" type="text/css">
	<script  type="text/javascript" src="<?php echo prefix_url;?>assets/js/jquery-2.0.3.min.js"></script>
	<style type="text/css">
	.style2 {	color: #FFFFFF;
		font-weight: bold;
	}
	</style>
	<script type="text/javascript">
	function ChangeColor(tableRow, highLight){
		if (highLight){
			tableRow.style.backgroundColor = '#dcfac9';
		}else{
			tableRow.style.backgroundColor = '';
		}
	}
	function send_emp(code, desc, date){
		if (window.opener.document.getElementById('txtempid')) {
			window.opener.document.getElementById('txtempid').value = code;
			window.opener.document.getElementById('txtemployeename').value = desc;
		}
		if (window.opener.$(".employee")) {
			window.opener.$(".employee").append($("<option></option>")
                 .attr("value",code)
								 .attr("selected",true)
                 .text(desc));
		}
		window.close();
	}
	$(document).ready(function(e) {
		$("#p").change(function(e) {
			var searchby = $("#p").val();
			if(searchby == "nm" || searchby == "ei"){
				$("#param_value").show();
				$("#fieldsupervisor").hide();
				$("#fielddepartment").hide();
				$("#fieldposition").hide();
			}else if(searchby == "dp"){
				$("#param_value").hide();
				$("#fieldsupervisor").hide();
				$("#fielddepartment").show();
				$("#fieldposition").hide();
			}else if(searchby == "ps"){
				$("#param_value").hide();
				$("#fieldsupervisor").hide();
				$("#fielddepartment").hide();
				$("#fieldposition").show();
			}else if(searchby == "sp"){
				$("#param_value").hide();
				$("#fieldsupervisor").show();
				$("#fielddepartment").hide();
				$("#fieldposition").hide();
			}
		});
	});

	</script>
</head>
<title>Employee Data</title>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<br />
				<strong><font size=2><div align="left">Employee Data</div></font></strong>
			</td>
			<td>
				<br /><div align="right"><button class="style29" onclick="window.close();">Close Window</button></div>
			</td>
		</tr>
		<tr>
			<td><br /></td>
		</tr>
		<tr>
			<td colspan="2">
				<?php
				echo form_open('inventory/emp_search/');
				?>
				<table>
					<tr>
						<td>Search by :</td>
						<td>
							<select name="p" id="p" class="form1" >
								<option value="0" >[Select]</option>
								<option value="nm" >Name</option>
								<option value="ei" >Employee ID</option>
								<option value="dp" >Department</option>
								<option value="ps" >Position</option>
								<option value="sp" >Supervisor</option>
							</select>
						</td>
						<td id="param_value">
							<input class="form1" type="text" name="pv" id="pv" value=""/>
						</td>
						<td id="fieldsupervisor" style="display:none;">
							<select id="txtsupervisor" name="txtsupervisor" class="form1" >
								<option value="0">[Select Supervisor]</option>
								<?php foreach($getsupervisor_list as $item){ ?>
									<option value="<?php echo $item->fingerid."_".$item->employeename; ?>"><?php echo $item->employeename;?></option>
									<?php } ?>
								</select>
							</td>
							<td id="fielddepartment" style="display:none;">
								<select id="txtdepartment" name="txtdepartment" class="form1">
									<option value="0">[Select Department]</option>
									<?php foreach($getdepartment_list as $item){ ?>
										<option value="<?php echo $item->iddept."_".$item->deptdesc; ?>"><?php echo $item->deptdesc;?></option>
										<?php } ?>
									</select>
								</td>
								<td id="fieldposition" style="display:none;">
									<select id="txtposition" name="txtposition" class="form1">
										<option value="0">[Select Position]</option>
										<?php foreach($getposition_list as $item){ ?>
											<option value="<?php echo $item->idposition."_".$item->positiondesc; ?>"><?php echo $item->positiondesc;?></option>
											<?php } ?>
										</select>
									</td>
									<td>
										<input type="submit" name="submit" id="submit" value="Search" class="style29"/>
									</td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
				<?php if($search == "true"){ ?>
					<tr bgcolor="#FFCC00">
						<td style="padding:10px;" width="95%" align="left">Searching By
							<?php
							if($search_by == "nm"){
								echo "Name = <b>$search_value</b> ";
							}else if($search_by == "ei"){
								echo "Employee ID = <b>$search_value</b> ";
							}else if($search_by == "dp"){
								$exp = explode("_",$search_value);
								echo "Department = <b>".urldecode($exp[1])."</b> ";
							}else if($search_by == "ps"){
								$exp = explode("_",$search_value);
								echo "Position = <b>".urldecode($exp[1])."</b> ";
							}else if($search_by == "sp"){
								$exp = explode("_",$search_value);
								echo "Supervisor = <b>".urldecode($exp[1])."</b> ";
							}
							?>
						</td>
						<td style="padding:10px;" width="95%" align="right">
							<a href="<?php echo prefix_url;?>inventory/employee/"><img src="<?php echo prefix_url;?>assets/images/delete.png" title="Close Search" border="0" /></a>
						</td>
					</tr>
					<?php } ?>
					<tr align="left">
						<td colspan="2"><?php
						if($employeelist_total > 1){
							echo $this->pagination->create_links();
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<br />
						<div align="center">

							<table width="100%" cellpadding="6" cellspacing="1" bgcolor="#CCCCCC" id="data_popup" >
								<tr>
									<td width="1%" bgcolor="#555555"><div align="center" class="style2">No</div></td>
									<td bgcolor="#555555"><div align="center" class="style2">
										Employee No
									</div></td>
									<td bgcolor="#555555"><div align="center" class="style2">

										Name
									</div></td>
									<td bgcolor="#555555"><div align="center" class="style2">
										Department
									</div></td>
									<td bgcolor="#555555"><div align="center" class="style2">
										Position
									</div></td>
									<td bgcolor="#555555"><div align="center" class="style2">
										Supervisor
									</div></td>
									<td bgcolor="#555555"><div align="center" class="style2">
										Join Date
									</div></td>
								</tr>
								<?php
								$a=1;
								$bg="";
								foreach($employee_list as $item){
									if($a%2==0){
										$bg="bgcolor=\"#DDDDDD\"";
									}else{
										$bg="bgcolor=\"#eeeeee\"";
									}
									$slashdate = $item->join_date;	
									$date = date_create_from_format('j-M-Y', $slashdate);
									?>
									<tr <?php echo $bg; ?> style="cursor:pointer;" onmouseover="ChangeColor(this, true);" onmouseout="ChangeColor(this, false);" onClick="send_emp('<?php  echo $item->fingerid;?>','<?php echo addslashes($item->employeename); ?>', '<?= date_format($date, 'Y-m-d') ?>')">
										<td><?php echo ($offset+$a);  ?></td>
										<td align="center"><?php echo $item->employeeno;?></td>
										<td align="center"><?php echo $item->employeename;?></td>
										<td align="center"><?php echo $item->emp_dept;?></td>
										<td align="center"><?php echo $item->emp_pos;?></td>
										<td align="center"><?php echo $item->supervisor_name;?></td>
										<td align="center"><?php echo $item->join_date;?></td>
									</tr>
									<?php $a++; } ?>

								</table>
							</div>
						</td>
					</tr>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table>
</body>
</html>
