<?php

class Borrow_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
  }

  public function count_borrow()
  {
    $sql = "SELECT count(*) total FROM item_borrow WHERE dlt = 0";
    $query = $this->db->query($sql);
    return $query->row()->total;
  }

  public function fetch_data_borrow($limit, $offset)
  {
    $sql = "SELECT ib.*,wh.warehouse_name,us.user_full_name, pj.project_name,
    (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=ib.taken_by_uid)AS employee_name
    FROM item_borrow ib
    LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = ib.warehouse_id
    LEFT OUTER JOIN  project pj ON pj.project_uid = ib.project_uid
    LEFT OUTER JOIN  user us ON us.user_id = ib.created_by
    WHERE ib.dlt = 0";

    $sql .= " ORDER BY ib.borrow_id DESC";
    if ($limit) {
      if(!$offset){
        $sql .= " LIMIT $limit";
      }else{
        $sql .= " LIMIT $limit OFFSET $offset";
      }
    }

    $query = $this->db->query($sql);
    return $query->result();
  }

  public function saveBorrow($data)
  {
    $this->db->insert('item_borrow', $data);
    if ($this->db->affected_rows() == 1) {
        return $this->db->insert_id();
    } else {
        return FALSE;
    }
  }

  public function save_details($data)
  {
    $this->db->insert('item_borrow_detail', $data);
    if ($this->db->affected_rows() == 1) {
        return $this->db->insert_id();
    } else {
        return FALSE;
    }
  }

  public function get_borrow($id)
  {
    $this->db->select('*')
    ->from('item_borrow')
    ->join('wasco_fingerman.tblmas_employee', 'tblmas_employee.fingerid = item_borrow.taken_by_uid', 'left')
    ->join('items', 'items.item_id = item_borrow.item_id', 'left')
    ->where('borrow_id =', $id)
    ->where('dlt =', 0);
    $result = $this->db->get()->row();
    return $result;
  }

  public function get_borrow_details($id)
  {
    $this->db->select('*')
    ->from('item_borrow_detail')
    ->join('items', 'items.item_id = item_borrow_detail.item_id', 'left')
    ->join('tbl_measurement', 'items.measurement_id = tbl_measurement.measurement_id', 'left')
    ->where('borrow_id =', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
    return false;
  }

  public function updateBorrow($data,$id)
  {
    $data['updated_by'] = date('Y-m-d');
    $this->db->where('borrow_id', $id);
    return $this->db->update('item_borrow', $data);
  }

  public function updateDetails($data,$id)
  {
    $this->db->where('borrow_detail_id', $id);
    return $this->db->update('item_borrow_detail', $data);
  }

  public function get_borrow_details_byid($id)
  {
    $this->db->select('*')
    ->from('item_borrow_detail')
    ->where('borrow_detail_id =', $id);
    $query = $this->db->get();
    return $query->row();
  }


  public function get_view_borrow($id)
  {
    $sql = "SELECT ib.*,it.*,wh.warehouse_name,us.user_full_name, pj.project_name,
    (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=ib.taken_by_uid)AS employee_name,
    (SELECT status_employee FROM wasco_fingerman.tblmas_employee WHERE fingerid=ib.taken_by_uid)AS status_employee,
    (SELECT positiondesc FROM wasco_fingerman.tblfile_position WHERE idposition = emp.idposition)AS emp_pos,
    (SELECT project FROM wasco_fingerman.tbl_project WHERE project_id = emp.project_id)AS emp_project,
    (SELECT deptdesc FROM wasco_fingerman.tblfile_department WHERE iddept = emp.iddept)AS emp_dept
    FROM item_borrow ib
    LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = ib.warehouse_id
    LEFT OUTER JOIN  project pj ON pj.project_uid = ib.project_uid
    LEFT OUTER JOIN  user us ON us.user_id = ib.created_by
    LEFT OUTER JOIN  items it ON it.item_id = ib.item_id
    LEFT OUTER JOIN  wasco_fingerman.tblmas_employee emp ON emp.fingerid = ib.taken_by_uid
    WHERE ib.dlt = 0 AND ib.borrow_id = $id";
    $query = $this->db->query($sql);
    return $query->row();

  }

  public function delete($id)
  {
    $data['deleted_date'] = date('Y-m-d');
    $data['dlt'] = 1;
    $data['deleted_by'] = $this->session->userdata('user_id');
    $this->db->where('borrow_id', $id);
    return $this->db->update('item_borrow', $data);
  }

  public function return_borrow($id)
  {
    $detail = $this->get_borrow_details($id);
    $totalDetails = sizeof($detail);
    $borrow = $this->get_view_borrow($id);
    $i = 0;
    foreach ($detail as $value) {
      if ($value->return_date != '') {
        $i++;
      }
    }
    if ($i == $totalDetails && $borrow->item_return_date) {
      $data['borrow_status'] = 1;
      $data['return_date'] = date('Y-m-d');
      $this->updateBorrow($data,$id);
    }
    return true;
  }

  public function count_borrow_filter($txtfilterwarehouse,$txtfilterproject,$txtfilterstatus,$user,$borrow_date)
  {
    /*$opt = $this->get_query($filter,'item_borrow');

    $sql = "SELECT count(*) total FROM item_borrow WHERE dlt = 0";
    if ($opt != '') {
      $sql .= $opt;
    }
    $query = $this->db->query($sql);
    return $query->row()->total;*/

	$filter = "";

	if($txtfilterwarehouse!=""){
		if($txtfilterwarehouse != "all"){
			$filter .= " AND warehouse_id = ".$this->db->escape($txtfilterwarehouse)."";
		}
	}

	if($txtfilterproject!=""){
		if($txtfilterproject != "all"){
			$filter .= " AND project_uid = ".$this->db->escape($txtfilterproject)."";
		}
	}


	if($txtfilterstatus!=""){
		if($txtfilterstatus != "all"){
			$filter .= " AND borrow_status = ".$this->db->escape($txtfilterstatus)."";
		}
	}

  if($user!=""){
    if($user != "all"){
      $filter .= " AND taken_by_uid = ".$this->db->escape($user)."";
    }
  }

  if($borrow_date!=""){
    if($borrow_date != "all"){
      $filter .= " AND borrow_date = ".$this->db->escape($borrow_date)."";
    }
  }


	$query = $this->db->query("SELECT borrow_id total FROM item_borrow WHERE dlt = 0
	$filter ");

	$count = $query->num_rows();
	return $count;

  }

  public function fetch_data_borrow_filter($limit, $offset,$txtfilterwarehouse,$txtfilterproject,$txtfilterstatus,$user,$borrow_date)
  {

	 $filter = "";

	if($txtfilterwarehouse!=""){
		if($txtfilterwarehouse != "all"){
			$filter .= " AND ib.warehouse_id = ".$this->db->escape($txtfilterwarehouse)."";
		}
	}

	if($txtfilterproject!=""){
		if($txtfilterproject != "all"){
			$filter .= " AND ib.project_uid = ".$this->db->escape($txtfilterproject)."";
		}
	}


	if($txtfilterstatus!=""){
		if($txtfilterstatus != "all"){
			$filter .= " AND ib.borrow_status = ".$this->db->escape($txtfilterstatus)."";
		}
	}

  if($user!=""){
    if($user != "all"){
      $filter .= " AND ib.taken_by_uid = ".$this->db->escape($user)."";
    }
  }

  if($borrow_date!=""){
    if($borrow_date != "all"){
      $filter .= " AND ib.borrow_date = ".$this->db->escape($borrow_date)."";
    }
  }

    $sql = "SELECT ib.*,wh.warehouse_name,us.user_full_name, pj.project_name,
    (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=ib.taken_by_uid)AS employee_name
    FROM item_borrow ib
    LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = ib.warehouse_id
    LEFT OUTER JOIN  project pj ON pj.project_uid = ib.project_uid
    LEFT OUTER JOIN  user us ON us.user_id = ib.created_by
    WHERE ib.dlt = 0
	$filter
	ORDER BY ib.borrow_id DESC";
    if ($limit) {
      if(!$offset){
        $sql .= " LIMIT $limit";
      }else{
        $sql .= " LIMIT $limit OFFSET $offset";
      }
    }
    $query = $this->db->query($sql);
    return $query->result();
  }

  public function get_query($arr,$table)
  {
    $opt = '';
    foreach ($arr as $key => $value) {
      if ($value !== "all") {
        $opt .= " AND $table.".$key." = '".$value."'";
      }
    }
    return $opt;
  }

  public function get_borrowDetails_by_item($id)
  {
    $this->db->select('*')
    ->from('item_borrow_detail')
    ->join('item_borrow', 'item_borrow.borrow_id = item_borrow_detail.borrow_id', 'left')
    ->join('wasco_fingerman.tblmas_employee', 'tblmas_employee.fingerid = item_borrow.taken_by_uid', 'left')
    ->join('warehouse', 'warehouse.warehouse_id = item_borrow.warehouse_id', 'left')
    ->join('project', 'project.project_uid = item_borrow.project_uid', 'left')
    ->where('item_borrow.dlt =', 0)
    ->where('item_borrow_detail.item_id =', $id);
    $result = $this->db->get()->result();
    return $result;
  }

  // public function get_borrow_by_item($id)
  // {
  //   $this->db->select('*')
  //   ->from('item_borrow')
  //   ->join('wasco_fingerman.tblmas_employee', 'tblmas_employee.fingerid = item_borrow.taken_by_uid', 'left')
  //   ->join('warehouse', 'warehouse.warehouse_id = item_borrow.warehouse_id', 'left')
  //   ->join('project', 'project.project_uid = item_borrow.project_uid', 'left')
  //   ->where('item_borrow.dlt =', 0)
  //   ->where('item_borrow.item_id =', $id);
  //   $result = $this->db->get()->row();
  //   return $result;
  // }

  public function get_borrow_by_item($id)
  {
    $this->db->select('*')
    ->from('item_borrow')
    ->join('wasco_fingerman.tblmas_employee', 'tblmas_employee.fingerid = item_borrow.taken_by_uid', 'left')
    ->join('warehouse', 'warehouse.warehouse_id = item_borrow.warehouse_id', 'left')
    ->join('project', 'project.project_uid = item_borrow.project_uid', 'left')
    ->where('item_borrow.item_id =', $id);
    $result = $this->db->get()->result();
    return $result;
  }

  public function save_software($data)
  {
    $this->db->insert('items_software', $data);
    if ($this->db->affected_rows() == 1) {
        return $this->db->insert_id();
    } else {
        return FALSE;
    }
  }

  public function get_software_details($id)
  {
    $this->db->select('*')
    ->from('items_software')
    ->where('borrow_id =', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
    return false;
  }

  public function updateSoftware($data,$id)
  {
    $this->db->where('software_id', $id);
    return $this->db->update('items_software', $data);
  }

  public function removeSoftware($arrId,$borrowId)
  {
    $count = sizeof($arrId);
    $i = 1;
    $sql = "DELETE FROM items_software WHERE borrow_id = '$borrowId' AND software_id NOT IN ('";
    foreach ($arrId as $value) {
      if ($count == $i) {
        $sql .= $value."')";
      }else {
        $sql .= $value."','";
      }
      $i++;
    }
    $query = $this->db->query($sql);
    return 1;
  }

  public function removeBorrowDetails($arrId,$borrowId)
  {
    $count = sizeof($arrId);
    $i = 1;
	
	if(count($arrId) > 0){
	
		$sql = "DELETE FROM item_borrow_detail WHERE borrow_id = '$borrowId' AND borrow_detail_id NOT IN ('";
		foreach ($arrId as $value) {
		  if ($count == $i) {
			$sql .= $value."')";
		  }else {
			$sql .= $value."','";
		  }
		  $i++;
		}
		$query = $this->db->query($sql);
		return $query;
	}else{
		return 1;
	}
  }

  public function get_user_by_fingerid($id)
  {
    $this->db->select('*')
    ->from('wasco_fingerman.tblmas_employee')
    ->where('fingerid =', $id);
    $result = $this->db->get()->row();
    return $result;
  }

}

?>
