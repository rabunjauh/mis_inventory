<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function get_category(){
		$sql = "SELECT cat_name FROM category";

		$query = $this->db->query($sql);
		return $query->result();
	}

}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */