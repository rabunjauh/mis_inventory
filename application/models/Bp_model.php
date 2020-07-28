<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bp_model extends CI_Model {
	public function getAllItems(){
		return $this->db->get('items')->result_array();
	}
	public function getItems($limit, $start){
		return $this->db->get_where('items', array('machine_type' => 'LAPTOP'), $limit, $start)->result_array();
	}
	
	public function countAllItems(){
		return $this->db->get('items')->num_rows();
	}
}

/* End of file Bp_model.php */
/* Location: ./application/models/Bp_model.php */