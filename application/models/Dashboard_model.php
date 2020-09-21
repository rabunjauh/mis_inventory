<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	public function all_items($select_filter = ""){
		if(!$select_filter == ""){
			if($select_filter == "machine_type"){

				$sql = "SELECT machine_type.machine_type_desc, SUM(inventory.inventory_quantity) AS qty FROM inventory LEFT JOIN items ON items.item_id = inventory.item_id LEFT JOIN machine_type ON items.machine_type = machine_type.machine_type_id GROUP BY machine_type.machine_type_desc ASC";
			}else if($select_filter == "category"){
				$sql = "SELECT category.cat_name, SUM(inventory.inventory_quantity) as qty FROM category LEFT JOIN items ON items.cat_id = category.cat_id LEFT JOIN inventory ON items.item_id = inventory.item_id GROUP BY category.cat_name";
			}else{
				$sql = "SELECT model.model_desc, SUM(inventory.inventory_quantity) as qty FROM inventory LEFT JOIN items ON items.item_id = inventory.item_id LEFT JOIN model ON items.model = model.model_id GROUP BY model.model_desc ASC";
			}
		}else{
			$sql = "SELECT machine_type.machine_type_desc, SUM(inventory.inventory_quantity) as qty FROM inventory LEFT JOIN items ON items.item_id = inventory.item_id left join machine_type on items.machine_type = machine_type.machine_type_id group BY machine_type.machine_type_desc";
		}		
			$query = $this->db->query($sql);
			return $query->result();
	}

	public function borrow_items_workstations(){		
			$sql = "SELECT items.machine_type, SUM(item_borrow.borrow_status) as qty FROM item_borrow LEFT JOIN items ON items.item_id = item_borrow.item_id WHERE item_borrow.borrow_status = 1 GROUP BY items.machine_type";		
			$query = $this->db->query($sql);
			return $query->num_rows();	
	}

	public function borrow_items_accessories(){		
			$sql = "SELECT items.machine_type, SUM(item_borrow_detail.quantities) as qty FROM item_borrow_detail LEFT JOIN items ON items.item_id = item_borrow_detail.item_id WHERE item_borrow_detail.return_quantity = 0 GROUP BY items.machine_type";		
			$query = $this->db->query($sql);
			return $query->result();	
	}

	public function items_machine_type(){
		$sql = "SELECT DISTINCT machine_type FROM items";
		$query = $this->db->query($sql);
		// var_dump($query->result());
		return $query->result();
	}

}

/* End of file Dashboard_model.php */
/* Location: ./application/models/Dashboard_model.php */