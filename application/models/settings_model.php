<?php

class Settings_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    return $this->db->get('settings',1)->row();
  }

  public function update_settings()
  {
    $name = $this->input->post('name');
    $data = array();
    switch ($name) {
      case 'brand_name':
      $data['brand_name'] = addslashes($this->input->post('value', TRUE));
      break;
      case 'address':
      $data['address'] =  addslashes($this->input->post('value', TRUE));
      break;
      case 'phone':
      $data['phone'] = $this->input->post('value', TRUE);
      break;
      case 'alert_email':
      $email = $this->input->post('value', TRUE);
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        return FALSE;
      }
      $data['alert_email'] = $email;
      break;
      case 'alert_on':
      $data['alert_on'] = $this->input->post('value', TRUE);
      break;
      default:
      return FALSE;
      break;
    }

    if($this->input->post('pk', TRUE)){
      $this->db->where('settings_id', (int) $this->input->post('pk', TRUE));
      $this->db->update('settings', $data);
    }else{
      $this->db->insert('settings', $data);
    }

    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  //     warehouses control
  public function get_warehouses()
  {
    $result = $this->db->select('*')
    ->from('warehouse')
    ->order_by("warehouse_name", "desc")
    ->get()->result();
    return $result;
  }

  public function save_warehouse()
  {
    $info = array();
    $info['warehouse_name'] = addslashes($this->input->post('warehouse_name', TRUE));
    $info['warehouse_phone'] = $this->input->post('phone', TRUE);
    $info['warehouse_email'] = $this->input->post('email', TRUE);
    $info['warehouse_address'] = addslashes($this->input->post('address', TRUE));
    $info['warehouse_incharge'] = $this->input->post('incharge', TRUE);
    $this->db->insert('warehouse', $info);
    if ($this->db->affected_rows() == 1) {
      return $this->db->insert_id();
    } else {
      return FALSE;
    }
  }

  public function update_warehouse()
  {
    if (!$this->session->userdata('role')) {
      return FALSE;
    }
    $name = $this->input->post('name');
    $data = array();
    $this->db->where('warehouse_id', (int) $this->input->post('pk', TRUE));
    switch ($name) {
      case 'warehouse_name':
      $name = addslashes($this->input->post('value', TRUE));
      $data['warehouse_name'] = $name;
      break;
      case 'warehouse_incharge':
      $data['warehouse_incharge'] = $this->input->post('value', TRUE);
      break;
      case 'warehouse_phone':
      $data['warehouse_phone'] = $this->input->post('value', TRUE);
      break;
      case 'warehouse_email':
      $email = $this->input->post('value', TRUE);
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        return FALSE;
      }
      $data['warehouse_email'] = $email;
      break;
      case 'warehouse_address':
      $data['warehouse_address'] = addslashes($this->input->post('value', TRUE));
      break;
      default:
      return FALSE;
      break;
    }
    $this->db->update('warehouse', $data);
    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function delete_warehouse($id)
  {
    $this->db->where('warehouse_id', $id);
    $this->db->delete('warehouse');
    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  //     costcenter control
  public function get_costcenter()
  {
    $result = $this->db->select('*')
    ->from('cost_center')
    ->order_by("cost_center", "asc")
    ->get()->result();
    return $result;
  }

  public function save_costcenter($cost_center_uid)
  {
    $info = array();
    $info['cost_center'] = addslashes($this->input->post('cost_center', TRUE));
    $info['cost_center_uid'] = $cost_center_uid;
    $this->db->insert('cost_center', $info);
    if ($this->db->affected_rows() == 1) {
      return $this->db->insert_id();
    } else {
      return FALSE;
    }
  }

  public function update_costcenter()
  {
    if (!$this->session->userdata('role')) {
      return FALSE;
    }
    $name = $this->input->post('name');
    $data = array();
    $this->db->where('cost_center_id', (int) $this->input->post('pk', TRUE));
    switch ($name) {
      case 'cost_center':
      $name = addslashes($this->input->post('value', TRUE));
      $data['cost_center'] = $name;
      break;
      default:
      return FALSE;
      break;
    }
    $this->db->update('cost_center', $data);
    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function delete_costcenter($id)
  {
    $this->db->where('cost_center_id', $id);
    $this->db->delete('cost_center');
    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  //     costcenter control
  public function get_project()
  {
    $result = $this->db->select('*')
    ->from('project')
    ->order_by("project_name", "asc")
    ->get()->result();
    return $result;
  }

  public function save_project($project_uid)
  {
    $info = array();
    $info['project_name'] = addslashes($this->input->post('project_name', TRUE));
    $info['project_uid'] = $project_uid;
    $this->db->insert('project', $info);
    if ($this->db->affected_rows() == 1) {
      return $this->db->insert_id();
    } else {
      return FALSE;
    }
  }

  public function update_project()
  {
    if (!$this->session->userdata('role')) {
      return FALSE;
    }
    $name = $this->input->post('name');
    $data = array();
    $this->db->where('project_id', (int) $this->input->post('pk', TRUE));
    switch ($name) {
      case 'project_name':
      $name = addslashes($this->input->post('value', TRUE));
      $data['project_name'] = $name;
      break;
      default:
      return FALSE;
      break;
    }
    $this->db->update('project', $data);
    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function delete_project($id)
  {
    $this->db->where('project_id', $id);
    $this->db->delete('project');
    if ($this->db->affected_rows() == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  //Measurement

  public function get_measurement()
  {
      $result = $this->db->select('*')
              ->from('tbl_measurement')
              ->get()->result();
      return $result;
  }

  function generateHash()
  {
    $hash = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    for($i = 0; $i < 10; $i++)
    {
      $hash .= $chars[mt_rand(0, 61)];
    }
    return $hash;
  }




}
