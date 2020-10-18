<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	public function machine_types(){		
			// $sql = "SELECT machine_type.machine_type_desc, SUM(inventory.inventory_quantity) as total_qty FROM inventory LEFT JOIN items ON items.item_id = inventory.item_id left join machine_type on items.machine_type = machine_type.machine_type_id group BY machine_type.machine_type_desc";
			$sql = "SELECT items.item_id, machine_type.machine_type_desc, SUM(invoice_purchase_details.quantities) AS total, SUM(inventory.inventory_quantity) as spare, SUM(inventory.inventory_damage_qtt) AS damage
 					FROM inventory 
					LEFT JOIN invoice_purchase_details USING (item_id) 
					LEFT JOIN items ON inventory.item_id = items.item_id
					LEFT JOIN machine_type ON items.machine_type = machine_type.machine_type_id
					GROUP BY machine_type.machine_type_desc";
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