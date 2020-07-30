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
	function send_item(code,desc,item_id){
		var this_ = window.opener;
		var type_input = this_.$('#type_input').val();
		console.log(type_input);
		if (type_input == 'item') {
			this_.$('#item_id').val(item_id);
			this_.$('#item_code').val(code);
			this_.$('#item_name').val(desc);
		}else if (type_input == 'accessories' || type_input == 'inventory') {
			var clone_check = this_.$('.master_clone');
			if (clone_check.css('display') == 'none') {
				clone_check.show();
				var check_obj = check_obj_arr(this_,code,desc,item_id,clone_check);
			}else {
				if (!this_.arrObj[code]) {
					this_.addRow('itemsTable');
					var target = this_.$('.master_clone').last();
				}else {
					target = this_.arrObj[code]['target'];
				}
				var check_obj = check_obj_arr(this_,code,desc,item_id,target);
			}
			if (type_input == 'accessories') {
				this_.datePicker();
			}
		}
		window.close();
	}

	function check_obj_arr(this_,code,desc,item_id,target) {
		if (!this_.arrObj[code]) {
			this_.arrObj[code] = [];
			this_.arrObj[code]['qty'] = 1;
			this_.arrObj[code]['target'] = target;
			target.find('.item_name').val(desc);
			target.find('.item_code').val(code);
			target.find('.item_id').val(item_id);
			target.find('.quantities').val(this_.arrObj[code]['qty']);
			return true;
		}else {
			this_.arrObj[code]['qty'] = parseInt(this_.arrObj[code]['qty']) + 1;
			target.find('.quantities').val(this_.arrObj[code]['qty']);
			return false;
		}
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
	function changeSearch() {
		if ($('#param').val() == 'cat_id') {
			$('#text_search').val('');
			$('#param_category').show();
			$('#param_value').hide();
		}else {
			$('#text_search').val('');
			$('#param_category').hide();
			$('#param_value').show();
		}
	}
	</script>
</head>
<title>Items Data</title>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<br />
				<strong><font size=2><div align="left">Items Data</div></font></strong>
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
				<form class="" action="<?php echo prefix_url.'inventory/browse_item_search/'.$type ?>" method="post">
					<table>
						<tr>
							<td>Search by :</td>
							<td>
								<select name="param" id="param" class="form1" onchange="changeSearch()">
									<option value="0" >[Select]</option>
									<option value="item_code" <?php echo (isset($txtsearchby) && $txtsearchby == "item_code") ? 'selected' : '' ?>> Item Code </option>
									<option value="item_name" <?php echo (isset($txtsearchby) && $txtsearchby == "item_name") ? 'selected' : '' ?>> Item Name</option>
									<option value="service_tag" <?php echo (isset($txtsearchby) && $txtsearchby == "service_tag") ? 'selected' : '' ?>> Service Tag</option>
									<option value="machine_type" <?php echo (isset($txtsearchby) && $txtsearchby == "machine_type") ? 'selected' : '' ?>> Machine Type</option>
									<option value="model" <?php echo (isset($txtsearchby) && $txtsearchby == "model") ? 'selected' : '' ?>> Model</option>
									<option value="express_service" <?php echo (isset($txtsearchby) && $txtsearchby == "express_service") ? 'selected' : '' ?>>Express Service Code</option>
									<option value="cat_id" <?php echo (isset($txtsearchby) && $txtsearchby == "cat_id") ? 'selected' : '' ?>>Category</option>
								</select>
							</td>
							<td id="param_value">
								<input class="form1" type="text" name="text_search" id="text_search" value="<?php echo (isset($txtsearchby) && $txtsearchby != "cat_id")  ? $txtsearch : '';?>"/>
							</td>
							<td id="param_category" style="display:none;">
								<select class="form1"  name="cat_id">
									<option value="all" <?php echo (isset($txtsearchby) && $txtsearchby == "cat_id" && $txtsearch == 'all')  ? 'selected' : '';?> > All </option>
									<?php foreach ($category as $value): ?>
										<option value="<?php echo $value->cat_id ?>" <?php echo (isset($txtsearchby) && $txtsearchby == "cat_id" && $txtsearch == $value->cat_id)  ? 'selected' : '';?> ><?php echo $value->cat_name ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="submit" name="submit" id="submit" value="Search" class="style29"/></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
		<?php if($search == "true"){ ?>
			<tr bgcolor="#FFCC00">
				<td style="padding:10px;" width="95%" align="left">Searching By
					<?php echo $txtsearchby.' : '.$txtsearch; ?>
				</td>
				<td style="padding:10px;" width="95%" align="right">
					<a href="<?php echo prefix_url;?>inventory/browse_item/<?php echo $type; ?>"><img src="<?php echo prefix_url;?>assets/images/delete.png" title="Close Search" border="0" /></a>
				</td>
			</tr>
		<?php } ?>
		<tr align="left">
			<td colspan="2"><?php
			if($total_rows > 9){
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
							Item Code
						</div></td>
						<td bgcolor="#555555"><div align="center" class="style2">
							Item Name
						</div></td>
						<td bgcolor="#555555"><div align="center" class="style2">
							Quantity
						</div></td>
						<td bgcolor="#555555"><div align="center" class="style2">
							Service Tag
						</div></td>
						<td bgcolor="#555555"><div align="center" class="style2">
							Express Service Code
						</div></td>
						<td bgcolor="#555555"><div align="center" class="style2">
							Machine Type
						</div></td>
						<td bgcolor="#555555"><div align="center" class="style2">
							Model
						</div></td>
					</tr>
					<?php
					$a=1;
					$bg="";
					foreach($results as $item){
						if($a%2==0){
							$bg="bgcolor=\"#DDDDDD\"";
						}else{
							$bg="bgcolor=\"#eeeeee\"";
						}
						?>
						<tr <?php echo $bg; ?> style="cursor:pointer;" onmouseover="ChangeColor(this, true);" onmouseout="ChangeColor(this, false);" onClick="send_item('<?php  echo $item->item_code;?>','<?php echo $item->item_name; ?>','<?php echo $item->item_id; ?>')">
							<td><?php echo ($offset+$a);  ?></td>
							<td align="center"><?php echo $item->item_code;?></td>
							<td align="center"><?php echo $item->item_name;?></td>
							<td align="center"><?php echo $item->inventory_quantity;?></td>
							<td align="center"><?php echo $item->service_tag;?></td>
							<td align="center"><?php echo $item->express_service;?></td>
							<td align="center"><?php echo $item->machine_type;?></td>
							<td align="center"><?php echo $item->model;?></td>
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

<script type="text/javascript">
changeSearch();
</script>
