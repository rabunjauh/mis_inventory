<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	public function all_items($select_filter = ""){
		if(!$select_filter == ""){
			if($select_filter == "machine_type"){
				$sql = "SELECT items.machine_type, SUM(inventory.inventory_quantity) as qty FROM inventory LEFT JOIN items ON items.item_id = inventory.item_id GROUP BY items.machine_type";
			}else if($select_filter == "category"){
				$sql = "SELECT category.cat_name, SUM(inventory.inventory_quantity) as qty FROM category LEFT JOIN items ON items.cat_id = category.cat_id LEFT JOIN inventory ON items.item_id = inventory.item_id GROUP BY category.cat_name";
			}
		}else{
			$sql = "SELECT items.machine_type, invoice_purchase_details.item_id, SUM(quantities) as qty FROM invoice_purchase_details LEFT JOIN items ON items.item_id = invoice_purchase_details.item_id GROUP BY items.machine_type";
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