<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "wasco_inventory_sys";

$dbcon = mysql_connect($host,$user,$pass);
mysql_select_db($db,$dbcon);

$now = date("Y-m-d");
$query = mysql_query("SELECT * FROM invoice_out");
while($dt = mysql_fetch_assoc($query)){
	//echo $dt["item_ids"];
	
	$exp = explode(",",$dt["item_ids"]);
	$exp1 = explode(",",$dt["quantities"]);
	
	$count = count($exp);
	if($count > 1){
		//echo "alot";
		for($i=0;$i<$count;$i++){
			$itemid =  $exp[$i];
			$quantity = $exp1[$i];
			$checkitem = mysql_query("SELECT * FROM items WHERE item_id = '".$itemid."'");
			$num_rows = mysql_num_rows($checkitem);
			if($num_rows > 0){
				$checkinventory = mysql_query("SELECT * FROM inventory WHERE item_id = '".$itemid."'");
				$num_rows_inventory = mysql_num_rows($checkinventory);
				if($num_rows_inventory > 0){
					$queryupdate = mysql_query("UPDATE inventory SET inventory_quantity = (inventory_quantity-$quantity),inventory_update = '$now' WHERE item_id = '".$itemid."'  ");
				}
				
			}
			
		}
		
	}else{
		$itemid = $dt["item_ids"];
		$quantity = $dt["quantities"];
		$checkitem = mysql_query("SELECT * FROM items WHERE item_id = '".$itemid."'");
		$num_rows = mysql_num_rows($checkitem);
		
		if($num_rows > 0){
			$checkinventory = mysql_query("SELECT * FROM inventory WHERE item_id = '".$itemid."'");
			$num_rows_inventory = mysql_num_rows($checkinventory);
			
			if($num_rows_inventory > 0){
				$queryupdate = mysql_query("UPDATE inventory SET inventory_quantity = (inventory_quantity-$quantity),inventory_update = '$now' WHERE item_id = '".$itemid."'  ");
			}
			
		}
	}
	//echo "<br>";
	
}



?>