<?php

class Item_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->db->select('*')
                ->from('items')
                ->join('category', 'items.cat_id = category.cat_id','left')
                ->join('tbl_measurement', 'items.measurement_id = tbl_measurement.measurement_id','left');
		$this->db->order_by("items.item_id", "ASC");		
        $result = $this->db->get()->result();
        return $result;
    }

    public function record_count($type)
    {
      $sql = "SELECT count(*) total FROM items";
      if ($type == "consumable") {
        $sql .= " WHERE item_material_status = 0";
      }else {
        $sql .= " WHERE item_material_status = 1";
      }
      $query = $this->db->query($sql);
      return $query->row()->total;
    }

    public function count_filter($arr,$type)
    {
      $opt = $this->get_query($arr);
      $sql = "SELECT count(*) total FROM items";

      if ($type == "consumable") {
        $sql .= " WHERE item_material_status = 0";
      }else {
        $sql .= " WHERE item_material_status = 1";
      }

      if ($opt != '') {
        $sql .= " AND ".$opt;
      }
      $query = $this->db->query($sql);
      return $query->row()->total;
    }

    public function get_query($arr)
    {
      $opt = '';
      foreach ($arr as $key => $value) {

        if ($value !== "all" && $key != "page") {
          if ($opt != '') {
            $opt .= " AND ";
          }
          if ($key != "cat_id" && $key != "page") {
            $opt .= "items.".$key." LIKE '%".$value."%'";
          }elseif ($key == "cat_id") {
            $opt .= "items.".$key." IN (".$value.")";
          }
        }
      }
      return $opt;
    }

    public function fetch_data_filter($limit, $offset, $filter,$type)
    {
      $opt = $this->get_query($filter);
      $sql = "SELECT * FROM items
      LEFT JOIN category ON items.cat_id = category.cat_id
      LEFT JOIN tbl_measurement ON tbl_measurement.measurement_id = items.measurement_id";

      if ($type == "consumable") {
        $sql .= " WHERE item_material_status = 0";
      }else {
        $sql .= " WHERE item_material_status = 1";
      }

      if ($opt != '') {
        $sql .= " AND ".$opt;
      }

      if(!$offset){
        $sql .= " LIMIT $limit";
      }
      else
      {
        $sql .= " LIMIT $offset, $limit";
      }
      $query = $this->db->query($sql);
      return $query->result();
    }

    // public function fetch_data($limit, $offset,$type)
    // {
    //   $this->db->select('*')
    //   ->join('category', 'items.cat_id = category.cat_id','left')
    //   ->join('tbl_measurement', 'items.measurement_id = tbl_measurement.measurement_id','left');

    //   if ($type == "consumable") {
    //     $this->db->where('item_material_status', 0);
    //   }else {
    //     $this->db->where('item_material_status', 1);
    //   }
    //   if ($limit) {
    //     $this->db->limit($limit,$offset);
    //   }
    //   $query = $this->db->get('items');
    //   if ($query->num_rows() > 0) {
    //     foreach ($query->result() as $row) {
    //       $data[] = $row;
    //     }

    //     return $data;
    //   }
    //   return false;
    // }

    public function fetch_data($limit, $offset,$type)
    {
      $this->db->select('*')
      ->join('category', 'items.cat_id = category.cat_id','left')
      ->join('tbl_measurement', 'items.measurement_id = tbl_measurement.measurement_id','left')
      ->join('machine_type', 'items.machine_type = machine_type.machine_type_id', 'left')
      ->join('manufacture', 'items.manufacture = manufacture.manufacture_id', 'left')
      ->join('model', 'items.model = model.model_id', 'left')
      ->join('operating_system', 'items.operating_system = operating_system.operating_system_id', 'left')
      ->join('processor', 'items.processor = processor.processor_id', 'left')
      ->join('memory', 'items.memory = memory.memory_id', 'left')
      ->join('hard_disk', 'items.hdd = hard_disk.hard_disk_id', 'left')
      ->join('vga', 'items.vga = vga.vga_id', 'left');

      if ($type == "consumable") {
        $this->db->where('item_material_status', 0);
      }else {
        $this->db->where('item_material_status', 1);
      }

      if ($limit) {
        $this->db->limit($limit,$offset);
      }

      $query = $this->db->get('items');
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
          $data[] = $row;
        }

        return $data;
      }
      return false;
    }

    public function get_categories()
    {
        return $this->db->get('category')->result();
    }

    public function get_machine_types()
    {
        return $this->db->get('machine_type')->result();
    }

    public function get_manufactures()
    {
        return $this->db->get('manufacture')->result();
    }

    public function get_models()
    {
        return $this->db->get('model')->result();
    }

    public function get_operating_systems()
    {
        return $this->db->get('operating_system')->result();
    }

    public function get_processors()
    {
        return $this->db->get('processor')->result();
    }

    public function get_memories()
    {
        return $this->db->get('memory')->result();
    }

    public function get_hard_disks()
    {
        return $this->db->get('hard_disk')->result();
    }

    public function get_vga()
    {
        return $this->db->get('vga')->result();
    }

    public function get_items()
    {
      $this->db->select('*')->from('items');
      $result = $this->db->get()->result();

      return $result;
    }

    public function get_item_by_id($id)
    {
      $this->db->select('*')
              ->from('items')
              ->join('category', 'items.cat_id = category.cat_id','left')
              ->join('tbl_measurement', 'items.measurement_id = tbl_measurement.measurement_id','left')
              ->join('machine_type', 'items.machine_type = machine_type.machine_type_id','left')
              ->join('manufacture', 'items.manufacture = manufacture.manufacture_id','left')
              ->join('model', 'items.model = model.model_id','left')
              ->join('operating_system', 'items.operating_system = operating_system.operating_system_id','left')
              ->join('processor', 'items.processor = processor.processor_id','left')
              ->join('memory', 'items.memory = memory.memory_id','left')
              ->join('hard_disk', 'items.hdd = hard_disk.hard_disk_id','left')
              ->join('vga', 'items.vga = vga.vga_id','left')
              ->where('items.item_id',$id);
      $result = $this->db->get()->row();
      return $result;
    }

    public function save_item($input)
    {
        $info = array();
        $info['item_code'] = $input['code'];
        $info['cat_id'] = $input['category'];
        $info['measurement_id'] = $input['measurement_id'];
        $info['item_name'] = $input['name'];
        $info['item_description'] = $input['description'];
        $info['item_image_name'] = $input['img_info']['upload_data']['raw_name'];
        $info['item_image_type'] = $input['img_info']['upload_data']['file_ext'];
        $info['item_calibration_status'] = $input['item_calibration_status'];
        $info['maintenance_status'] = $input['maintenance_status'];
        $info['item_material_status'] = $input['item_material_status'];

        $info['maintenance_previous_date'] = $input['maintenance_previous_date'];
        $info['maintenance_next_date'] = $input['maintenance_next_date'];
        $info['item_start_date'] = $input['item_start_date'];
        $info['item_expired_date'] = $input['item_expired_date'];
        $info['service_tag'] = $input['service_tag'];
        $info['express_service'] = $input['express_service'];
        $info['machine_type'] = $input['machine_type'];
        $info['manufacture'] = $input['manufacture'];
        $info['model'] = $input['model'];
        $info['operating_system'] = $input['operating_system'];
        $info['processor'] = $input['processor'];
        $info['memory'] = $input['memory'];
        $info['hdd'] = $input['hdd'];
        $info['vga'] = $input['vga'];
        $info['computer_name'] = $input['computer_name'];
        $info['product_key_windows'] = $input['product_key_windows'];
        $info['product_key_office'] = $input['product_key_office'];
        $info['product_key_others'] = $input['product_key_others'];
        $info['accessories'] = $input['accessories'];
        $info['predecessor'] = $input['predecessor'];
        $info['start_warranty'] = $input['start_warranty'];
        $info['end_warranty'] = $input['end_warranty'];
        $info['warranty_status'] = $input['warranty_status'];


        $this->db->insert('items', $info);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function save_category()
    {
        $info = array();
        $info['cat_name'] = addslashes(ucfirst($this->input->post('cat_name', TRUE)));

        $this->db->insert('category', $info);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function save_measurement()
    {
        $info = array();
        $info['measurement'] = addslashes($this->input->post('measurement', TRUE));

        $this->db->insert('tbl_measurement', $info);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function update_measurement()
    {
        if (!$this->session->userdata('role')) {
            return FALSE;
        }
        $data = array();
        $data['measurement'] = $this->input->post('value', TRUE);

        $this->db->where('measurement_id', (int) $this->input->post('pk', TRUE));
        $this->db->update('tbl_measurement', $data);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete_measurement($id)
    {
        $this->db->where('measurement_id', $id);
        $this->db->delete('tbl_measurement');
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_category()
    {
        if (!$this->session->userdata('role')) {
            return FALSE;
        }
        $data = array();
        $data['cat_name'] = ucfirst($this->input->post('value', TRUE));

        $this->db->where('cat_id', (int) $this->input->post('pk', TRUE));
        $this->db->update('category', $data);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete_category($id)
    {
        $this->db->where('cat_id', $id);
        $this->db->delete('category');
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_item_image($id, $input)
    {
        $info = array();
        $info['item_image_name'] = $input['upload_data']['raw_name'];
        $info['item_image_type'] = $input['upload_data']['file_ext'];

        $this->db->where('item_id', $id);
        $this->db->update('items', $info);
        return TRUE;
    }


    public function update_item($input, $id)
    {
        $info['cat_id'] = $input['category'];
		$info['item_code'] = $input['item_code'];
        $info['item_name'] = $input['name'];
        $info['measurement_id'] = $input['measurement_id'];
        $info['item_description'] = $input['description'];
        if (isset($input['img_info'])) {
            $info['item_image_name'] = $input['img_info']['upload_data']['raw_name'];
            $info['item_image_type'] = $input['img_info']['upload_data']['file_ext'];
        }
        $info['item_calibration_status'] = $input['item_calibration_status'];
        $info['maintenance_status'] = $input['maintenance_status'];
        $info['item_material_status'] = $input['item_material_status'];

        $info['maintenance_previous_date'] = $input['maintenance_previous_date'];
        $info['maintenance_next_date'] = $input['maintenance_next_date'];
        $info['item_start_date'] = $input['item_start_date'];
        $info['item_expired_date'] = $input['item_expired_date'];


        $info['service_tag'] = $input['service_tag'];
        $info['express_service'] = $input['express_service'];
        $info['machine_type'] = $input['machine_type'];
        $info['model'] = $input['model'];
        $info['operating_system'] = $input['operating_system'];
        $info['processor'] = $input['processor'];
        $info['memory'] = $input['memory'];
        $info['hdd'] = $input['hdd'];
        $info['vga'] = $input['vga'];
        $info['computer_name'] = $input['computer_name'];
        $info['product_key_windows'] = $input['product_key_windows'];
        $info['product_key_office'] = $input['product_key_office'];
        $info['product_key_others'] = $input['product_key_others'];
        $info['accessories'] = $input['accessories'];
        $info['predecessor'] = $input['predecessor'];
        $info['start_warranty'] = $input['start_warranty'];
        $info['end_warranty'] = $input['end_warranty'];
        $info['warranty_status'] = $input['warranty_status'];
        // var_dump($info);
        $this->db->where('item_id', $id);
        $this->db->update('items', $info);

        return $info;
    }

    public function delete_item($id)
    {
        $this->db->where('item_id', $id);
        $this->db->delete('items');
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
