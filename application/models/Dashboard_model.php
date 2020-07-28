<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	public function all_items($machine_type){
			$sql = "SELECT * FROM invoice_purchase_details LEFT JOIN items on invoice_purchase_details.item_id = items.item_id WHERE items.machine_type = '$machine_type'";
			$query = $this->db->query($sql);
			return $query->num_rows();	
	}

	public function borrow_items($machine_type){		
			$sql = "SELECT * FROM item_borrow LEFT JOIN items ON item_borrow.item_id = items.item_id WHERE items.machine_type = '$machine_type' AND item_borrow.borrow_status = 1";		
			$query = $this->db->query($sql);
			return $query->num_rows();	
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