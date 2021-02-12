<?php

class Inventory_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->db->select('*')
        ->from('inventory')
        ->join('items', 'inventory.item_id = items.item_id', 'left')
        ->join('category', 'items.cat_id = category.cat_id', 'left')
        ->where('items.item_material_status', 0);
        $result = $this->db->get()->result();
        return $result;
    }

    public function fetch_data_damage($limit, $offset)
    {
        $this->db->select('*')
        ->where('inventory.inventory_damage_qtt >', 0)
        ->join('items', 'inventory.item_id = items.item_id', 'left')
        ->join('category', 'items.cat_id = category.cat_id', 'left');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('inventory');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function record_count_damage()
    {
        $damage = $this->get_damage_items();
        return sizeof($damage);
    }

    //consumable
    public function record_count_inventory($type)
    {
        $sql = "SELECT count(*) total FROM inventory
        INNER JOIN items ON items.item_id = inventory.item_id";
        
        if ($type == "consumable") {
            $sql .= " WHERE items.item_material_status = 0";
        }elseif ($type == "stock" || $type == 'all') {
            $sql .= " WHERE items.item_material_status = 1 AND items.accessories = 0";
        }elseif ($type == "accessories") {
            $sql .= " WHERE items.accessories = 1";
        }

        $query = $this->db->query($sql);
        return $query->row()->total;
    }

    // public function fetch_data_inventory($limit, $offset, $type)
    // {

    //     if ($type == 'inventory') {
    //         $this->db->select('*')
    //         ->join('inventory', 'inventory.item_id = items.item_id', 'left')
    //         ->join('tbl_measurement', 'tbl_measurement.measurement_id = items.measurement_id', 'left')
    //         ->join('category', 'items.cat_id = category.cat_id', 'left');
    //     }else{
    //         $this->db->select('*')
    //         ->join('items', 'inventory.item_id = items.item_id', 'left')
    //         ->join('tbl_measurement', 'tbl_measurement.measurement_id = items.measurement_id', 'left')
    //         ->join('category', 'items.cat_id = category.cat_id', 'left');
    //     }

    //     if ($type == 'consumable') {
    //         $this->db->where('items.item_material_status =', 0);
    //     }elseif ($type == "stock" || $type == 'all') {
    //         $this->db->where('items.item_material_status =', 1);
    //         $this->db->where('items.accessories =', 0);
    //     }elseif ($type == "accessories") {
    //         $this->db->where('items.accessories =', 1);
    //     }
    //     $this->db->order_by("inventory_update", "desc");
    //     $this->db->limit($limit, $offset);
    //     if ($type == 'inventory') {
    //         $query = $this->db->get('items');
    //     }else{
    //         $query = $this->db->get('inventory');
    //     }
    //     if ($query->num_rows() > 0) {
    //         foreach ($query->result() as $row) {
    //             $data[] = $row;
    //         }
    //         return $data;
    //     }
    //     return false;
    // }

    public function fetch_data_inventory($limit, $offset, $type)
    {
        if ($type == 'inventory') {
            $this->db->select('
                it.item_id, 
                it.item_code, 
                it.item_name, 
                it.service_tag, 
                it.express_service, 
                ma.manufacture_desc, 
                inv.inventory_quantity, 
                inv.alert_qtt, 
                mt.machine_type_desc, 
                mo.model_desc, 
                os.operating_system_desc, 
                p.processor_type, 
                me.memory_size, 
                hd.hard_disk_size, 
                it.computer_name
            ')
            ->join('tbl_measurement tm', 'tm.measurement_id = it.measurement_id', 'left')
            ->join('category c', 'it.cat_id = c.cat_id', 'left')
            ->join('machine_type mt', 'it.machine_type = mt.machine_type_id', 'left')
            ->join('manufacture ma', 'it.manufacture = ma.manufacture_id', 'left')
            ->join('model mo', 'it.model = mo.model_id', 'left')
            ->join('operating_system os', 'it.operating_system = os.operating_system_id', 'left')
            ->join('processor p', 'it.processor = p.processor_id', 'left')
            ->join('memory me', 'it.memory = me.memory_id', 'left')
            ->join('hard_disk hd', 'it.hdd = hd.hard_disk_id', 'left')
            ->join('vga v', 'it.vga = v.vga_id', 'left')
            ->join('inventory inv', 'inv.item_id = it.item_id');
        }else{
            $this->db->select('
                it.item_id, 
                it.item_code, 
                it.item_name, 
                it.service_tag, 
                it.express_service, 
                ma.manufacture_desc, 
                inv.inventory_quantity, 
                inv.alert_qtt, 
                mt.machine_type_desc, 
                mo.model_desc, 
                os.operating_system_desc, 
                p.processor_type, 
                me.memory_size, 
                hd.hard_disk_size, 
                it.computer_name
            ')
            ->join('items it', 'inv.item_id = it.item_id')
            ->join('tbl_measurement', 'tbl_measurement.measurement_id = it.measurement_id', 'left')
            ->join('category c', 'it.cat_id = c.cat_id', 'left')
            ->join('machine_type mt', 'it.machine_type = mt.machine_type_id', 'left')
            ->join('manufacture ma', 'it.manufacture = ma.manufacture_id', 'left')
            ->join('model mo', 'it.model = mo.model_id', 'left')
            ->join('operating_system os', 'it.operating_system = os.operating_system_id', 'left')
            ->join('processor p', 'it.processor = p.processor_id', 'left')
            ->join('memory me', 'it.memory = me.memory_id', 'left')
            ->join('hard_disk hd', 'it.hdd = hd.hard_disk_id', 'left')
            ->join('vga v', 'it.vga = v.vga_id', 'left');
        }

        if ($type == 'consumable') {
            $this->db->where('it.item_material_status =', 0);
        }elseif ($type == "stock" || $type == 'all') {
            $this->db->where('it.item_material_status =', 1);
            $this->db->where('it.accessories =', 0);
        }elseif ($type == "accessories") {
            $this->db->where('it.accessories =', 1);
        }
        $this->db->order_by("inventory_update", "desc");
        $this->db->limit($limit, $offset);
        if ($type == 'inventory') {
            $query = $this->db->get('items it');
        }else{
            $query = $this->db->get('inventory inv');
        }

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    

    //search

    public function record_count_inventory_search($type,$txtsearchby,$txtsearch)
    {
        if ($type == "inventory") {
            $sql = "SELECT count(*) total FROM items
            INNER JOIN inventory ON items.item_id = inventory.item_id";
        }else{
            $sql = "SELECT count(*) total FROM inventory
            INNER JOIN items ON items.item_id = inventory.item_id";
        }

        if ($type == "consumable") {
            $sql .= " WHERE items.item_material_status = 0";
        }elseif ($type == "stock" || $type == 'all') {
            $sql .= " WHERE items.item_material_status = 1 AND items.accessories = 0";
        }elseif ($type == "accessories") {
            $sql .= " WHERE items.accessories = 1";
        }
        if ($txtsearchby != '0') {
            if ($txtsearchby == 'cat_id') {
                $sql .= " AND items.".$txtsearchby." = ".$txtsearch;
            }else {
                $sql .= " AND items.".$txtsearchby." LIKE '%".$txtsearch."%'";

            }
        }
        $query = $this->db->query($sql);
        return $query->row()->total;
    }

    public function fetch_data_inventory_search($limit, $offset, $type,$txtsearchby,$txtsearch)
    {
        if ($type == 'inventory') {
            $this->db->select('
                it.item_id, 
                it.item_code, 
                it.item_name, 
                it.service_tag, 
                it.express_service, 
                ma.manufacture_desc, 
                inv.inventory_quantity, 
                inv.alert_qtt, 
                mt.machine_type_desc, 
                mo.model_desc, 
                os.operating_system_desc, 
                p.processor_type, 
                me.memory_size, 
                hd.hard_disk_size, 
                it.computer_name
            ')
            ->join('tbl_measurement tm', 'tm.measurement_id = it.measurement_id', 'left')
            ->join('category c', 'it.cat_id = c.cat_id', 'left')
            ->join('machine_type mt', 'it.machine_type = mt.machine_type_id', 'left')
            ->join('manufacture ma', 'it.manufacture = ma.manufacture_id', 'left')
            ->join('model mo', 'it.model = mo.model_id', 'left')
            ->join('operating_system os', 'it.operating_system = os.operating_system_id', 'left')
            ->join('processor p', 'it.processor = p.processor_id', 'left')
            ->join('memory me', 'it.memory = me.memory_id', 'left')
            ->join('hard_disk hd', 'it.hdd = hd.hard_disk_id', 'left')
            ->join('vga v', 'it.vga = v.vga_id', 'left')
            ->join('inventory inv', 'inv.item_id = it.item_id');
        }else{
            $this->db->select('
                it.item_id, 
                it.item_code, 
                it.item_name, 
                it.service_tag, 
                it.express_service, 
                ma.manufacture_desc, 
                inv.inventory_quantity, 
                inv.alert_qtt, 
                mt.machine_type_desc, 
                mo.model_desc, 
                os.operating_system_desc, 
                p.processor_type, 
                me.memory_size, 
                hd.hard_disk_size, 
                it.computer_name
            ')
            ->join('items it', 'inv.item_id = it.item_id')
            ->join('tbl_measurement', 'tbl_measurement.measurement_id = it.measurement_id', 'left')
            ->join('category c', 'it.cat_id = c.cat_id', 'left')
            ->join('machine_type mt', 'it.machine_type = mt.machine_type_id', 'left')
            ->join('manufacture ma', 'it.manufacture = ma.manufacture_id', 'left')
            ->join('model mo', 'it.model = mo.model_id', 'left')
            ->join('operating_system os', 'it.operating_system = os.operating_system_id', 'left')
            ->join('processor p', 'it.processor = p.processor_id', 'left')
            ->join('memory me', 'it.memory = me.memory_id', 'left')
            ->join('hard_disk hd', 'it.hdd = hd.hard_disk_id', 'left')
            ->join('vga v', 'it.vga = v.vga_id', 'left');
        }


        if ($type == 'consumable') {
            $this->db->where('items.item_material_status =', 0);
        }elseif ($type == "stock" || $type == 'all') {
            $this->db->where('items.item_material_status =', 1);
            $this->db->where('items.accessories =', 0);
        }elseif ($type == "accessories") {
            $this->db->where('items.accessories =', 1);
        }
        if ($txtsearchby != '0') {
            $this->db->like('items.'.$txtsearchby, $txtsearch);
        }
        $this->db->order_by("inventory_update", "desc");
        $this->db->limit($limit, $offset);
        if ($type == 'inventory') {
            $query = $this->db->get('items');
        }else{
            $query = $this->db->get('inventory');
        }
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }



    public function count_filter($arr,$material)
    {
        $opt = $this->get_query($arr);
        $sql = "SELECT count(*) total FROM inventory INNER JOIN items ON items.item_id = inventory.item_id WHERE items.item_material_status = '$material'";
        if ($opt != '') {
            $sql .= " AND ".$opt;
        }
        $query = $this->db->query($sql);
        return $query->row();
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

    public function fetch_data_inventory_filter($limit, $offset, $filter,$material)
    {
        $opt = $this->get_query($filter);
        $sql = "SELECT 
                    it.item_code, 
                    it.item_name, 
                    it.service_tag,
                    it.express_service,
                    it.computer_name,
                    mt.machine_type_desc, 
                    ma.manufacture_desc,
                    mo.model_desc, 
                    os.operating_system_desc,
                    p.processor_type,
                    me.memory_size, 
                    hd.hard_disk_size, 
                    it.item_id AS item_item_id,
                    inv.item_id AS inventory_item_id,
                    inv.inventory_quantity, 
                    inv.alert_qtt 
        FROM inventory inv
        JOIN items it ON it.item_id = inv.item_id 
        LEFT JOIN machine_type mt ON it.machine_type = mt.machine_type_id
        LEFT JOIN manufacture ma ON it.manufacture = ma.manufacture_id
        LEFT JOIN model mo ON it.model = mo.model_id
        LEFT JOIN operating_system os ON it.operating_system = os.operating_system_id
        LEFT JOIN processor p ON it.processor = p.processor_id
        LEFT JOIN memory me ON it.memory = me.memory_id
        LEFT JOIN hard_disk hd ON it.hdd = hd.hard_disk_id
        
        WHERE it.item_material_status = '$material'";
        if ($opt != '') {
            $sql .= " AND ".$opt;
        }
        $sql .= " ORDER BY inv.inventory_update DESC";
        if(!$offset){
            $sql .= " LIMIT $limit";
        }else{
            $sql .= " LIMIT $offset, $limit";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }


    public function employee_list($num,$offset)
    {
        if(!$offset){
            $limit = "LIMIT $num";
        }else{
            $limit = "LIMIT $offset, $num";
        }
        $sql = "SELECT te.idemployee,te.employeeno,te.employeename,te.fingerid,te.stsactive,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid = te.supervisor_id)AS supervisor_name,
        (SELECT positiondesc FROM wasco_fingerman.tblfile_position WHERE idposition = te.idposition)AS emp_pos,
        (SELECT deptdesc FROM wasco_fingerman.tblfile_department WHERE iddept = te.iddept)AS emp_dept,
        DATE_FORMAT(join_date,'%d/%m/%Y')AS join_date
        FROM wasco_fingerman.tblmas_employee te
        ORDER BY te.idemployee DESC $limit";
        $query = $this->db->query($sql);
        return $query->result();

    }

    function get_employee_bypage_total()
    {
        $query = $this->db->query("SELECT * FROM wasco_fingerman.tblmas_employee ORDER BY idemployee DESC");
        $count = $query->num_rows();
        return $count;
    }


    function get_emp_search_list($num,$offset,$txtsearchby,$txtsearch)
    {
        if(!$offset){
            $limit = "LIMIT $num";
        }else{
            $limit = "LIMIT $offset, $num";
        }

        $filter = "";
        if($txtsearchby == "nm"){
            $filter = " WHERE te.employeename LIKE  '%".$this->db->escape_like_str($txtsearch)."%'";
        }else if($txtsearchby == "ei"){
            $filter = " WHERE te.employeeno LIKE '%".$this->db->escape_like_str($txtsearch)."%'";
        }else if($txtsearchby == "dp"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.iddept = ".$this->db->escape($exp[0]);
        }else if($txtsearchby == "sp"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.supervisor_id = ".$this->db->escape($exp[0]);
        }else if($txtsearchby == "ps"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.idposition = ".$this->db->escape($exp[0]);
        }else if($txtsearchby == "st"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.stsactive = ".$this->db->escape($exp[0]);
        }

        $sql = "SELECT te.idemployee,te.employeeno,te.employeename,te.fingerid,te.stsactive,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid = te.supervisor_id)AS supervisor_name,
        (SELECT positiondesc FROM wasco_fingerman.tblfile_position WHERE idposition = te.idposition)AS emp_pos,
        (SELECT deptdesc FROM wasco_fingerman.tblfile_department WHERE iddept = te.iddept)AS emp_dept,
        DATE_FORMAT(join_date,'%d/%m/%Y')AS join_date
        FROM wasco_fingerman.tblmas_employee te
        $filter
        ORDER BY te.idemployee DESC $limit;";

        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_emp_search_bypage_total($txtsearchby,$txtsearch)
    {
        $filter = "";
        if($txtsearchby == "nm"){
            $filter = " WHERE te.employeename LIKE  '%".$this->db->escape_like_str($txtsearch)."%'";
        }else if($txtsearchby == "ei"){
            $filter = " WHERE te.employeeno LIKE '%".$this->db->escape_like_str($txtsearch)."%'";
        }else if($txtsearchby == "dp"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.iddept = ".$this->db->escape($exp[0]);
        }else if($txtsearchby == "sp"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.supervisor_id = ".$this->db->escape($exp[0]);
        }else if($txtsearchby == "ps"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.idposition = ".$this->db->escape($exp[0]);
        }else if($txtsearchby == "st"){
            $exp = explode("_",$txtsearch);
            $filter = " WHERE te.stsactive = ".$this->db->escape($exp[0]);
        }

        $query = $this->db->query("SELECT te.idemployee FROM wasco_fingerman.tblmas_employee te $filter ;");

        $count = $query->num_rows();
        return $count;
    }

    function get_supervisor_list()
    {
        $sql = "SELECT * FROM wasco_fingerman.tblmas_employee WHERE fingerid IN (SELECT supervisor_id FROM wasco_fingerman.tblmas_employee GROUP BY supervisor_id);";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_department_list()
    {
        $sql = "SELECT * FROM wasco_fingerman.tblfile_department;";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_position_list()
    {
        $sql = "SELECT * FROM wasco_fingerman.tblfile_position;";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_employeeStatus()
    {
        $sql = "SELECT * FROM tbl_employee_status;";
        $query = $this->db->query($sql);
        return $query->result();
    }


    public function get_damage_items()
    {
        $this->db->select('*')
        ->where('inventory.inventory_damage_qtt >', 0)
        ->from('inventory')
        ->join('items', 'inventory.item_id = items.item_id', 'left')
        ->join('category', 'items.cat_id = category.cat_id', 'left');
        $result = $this->db->get()->result();
        return $result;
    }

    public function item_in()
    {
        $items = $this->input->post('item_id', TRUE);
        $quantity = $this->input->post('quantity', TRUE);
        $alrt_quantity = $this->input->post('alrt_quantity', TRUE);
        $inventories = $this->db->get('inventory')->result();
        $txtactualdate = $this->input->post('txtactualdate', TRUE);
        $exp = explode("/",$txtactualdate);
        $txtactualdate = $exp["2"]."-".$exp[1]."-".$exp[0];

        $ids = '';
        $u_price = '';
        $qtt = '';
        $total_price = 0;
        $info['supplier_id'] = $this->input->post('supplier', TRUE);
        $info['quantities'] = array_sum($quantity);
        $info['user_id'] = $this->session->userdata('user_id');
        $info['note'] = addslashes($this->input->post('note', TRUE));
        $info['actual_date'] = $txtactualdate;
        $info['date'] = date('Y-m-d');
        $this->db->insert('invoice_purchase', $info);
        $item_in_id = $this->db->insert_id();
        

        foreach ($items as $key => $item) { 
			$i = 0;
			
            if($items[$key] AND ($items[$key] != 0) AND (is_numeric($items[$key]))){
                if($quantity[$key] AND ($quantity[$key] != 0) AND (is_numeric($quantity[$key]))){
                    foreach ($inventories as $inventory) {
                        if($inventory->item_id == $items[$key]){ $i++;
                            $data['inventory_quantity'] = ($inventory->inventory_quantity + $quantity[$key]);
                            if ($alrt_quantity[$key] AND ($alrt_quantity[$key] != 0) AND (is_numeric($alrt_quantity[$key]))) {
                                $data['alert_qtt'] = $alrt_quantity[$key];
                            }
                            $data['inventory_update'] = date('Y-m-d');
                            $this->db->where('item_id', $items[$key]);
                            $this->db->update('inventory', $data);
                            break;
                        }
                    }
                    if (!$i) {
                        if ($alrt_quantity[$key] AND ($alrt_quantity[$key] != 0) AND (is_numeric($alrt_quantity[$key]))) {
                            $data['alert_qtt'] = $alrt_quantity[$key];
                        }
                        $data['item_id'] = $items[$key];
                        $data['inventory_quantity'] = $quantity[$key];
                        $data['inventory_added'] = date('Y-m-d');
                        $data['inventory_update'] = date('Y-m-d');
                        $this->db->insert('inventory', $data);
                    }
                    if ($this->db->affected_rows() == 1) {
                        $res = $this->save_item_in_details($item_in_id,$items[$key],$quantity[$key]);
                    }
                }
            }
        }
        return TRUE;
    }

    public function save_item_in_details($item_in_id,$items,$quantity)
    {
        $data['invoice_purchase_id'] = $item_in_id;
        $data['item_id'] = $items;
        $data['quantities'] = $quantity;
        $data['invoice_date'] = date('Y-m-d');
        $this->db->insert('invoice_purchase_details', $data);
    }

    public function item_out()
    {
        $items = $this->input->post('item', TRUE);
        $quantity = $this->input->post('quantity', TRUE);
        $txtactualdate = $this->input->post('txtactualdate', TRUE);
        $exp = explode("/",$txtactualdate);
        $txtactualdate = $exp["2"]."-".$exp[1]."-".$exp[0];
        $inventories = $this->db->get('inventory')->result();

        $ids = '';
        $u_price = '';
        $qtt = '';
        $total_price = 0;
        $alert_items = '';
        foreach ($items as $key => $item) {
            if($items[$key] AND ($items[$key] != 0) AND (is_numeric($items[$key]))){

                if($quantity[$key] AND ($quantity[$key] != 0) AND (is_numeric($quantity[$key]))){

                    foreach ($inventories as $inventory) {
                        if($inventory->item_id == $items[$key]){
                            $data['inventory_quantity'] = ($inventory->inventory_quantity - $quantity[$key]);

                            if ($data['inventory_quantity'] < 0) {
                                continue;
                            }

                            $data['inventory_update'] = date('Y-m-d');
                            $this->db->where('item_id', $items[$key]);
                            $this->db->update('inventory', $data);
                            if ($this->db->affected_rows() == 1) {
                                $ids .= $item.',';
                                $qtt .= $quantity[$key].',';
                            } else {
                                continue;
                            }
                            if ($data['inventory_quantity'] <= $inventory->alert_qtt) {
                                if($this->session->userdata('alert')){
                                    $item = $this->db->get_where('items', array('item_id' => $items[$key]))->row();
                                    $alert_items .= $item->item_name.',';
                                }
                            }
                        }
                    }
                }

            }
        }

        if($alert_items != ''){
            $alert_items = substr($alert_items, 0, -1);

            $from = $this->session->userdata('user_email');
            $to = $this->session->userdata('alert_email');
            $suject = 'Inventory getting low!';
            $message_body = 'Hello, <br/>'
            .'The items '. $alert_items .' is getiing under the alert quantity. Please take necessary actions.<br/>'
            .'Thanks,<br/>'. $this->session->userdata('brand');

            $config = Array(
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->from($from);
            $this->email->to($to);
            $this->email->subject($suject);
            $this->email->message($message_body);
            $this->email->send();
        }

        $ids = substr($ids, 0, -1);
        $qtt = substr($qtt, 0, -1);
        $info['taken_by_uid'] = $this->input->post('txtempid', TRUE);
        $info['cost_center_uid'] = $this->input->post('txtcostcenter', TRUE);
        $info['project_uid'] = $this->input->post('txtproject', TRUE);
        $info['warehouse_id'] = $this->input->post('warehouse', TRUE);
        $info['item_ids'] = $ids;
        $info['quantities'] = $qtt;
        $info['actual_date'] = $txtactualdate;
        $info['user_id'] = $this->session->userdata('user_id');
        $info['note'] = addslashes($this->input->post('note', TRUE));
        $info['date'] = date('Y-m-d');
        if($this->db->insert('invoice_out', $info)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function update_damage_item($operation)
    {
        $id = $this->input->post('item_id', TRUE);
        $quantity = $this->input->post('quantity', TRUE);
        $item = $this->db->get_where('inventory', array('item_id' => $id),1)->row();

        $info = array();
        if($operation === 'add'){
            if($item->inventory_quantity >= $quantity){
                $info['inventory_quantity'] = ($item->inventory_quantity - $quantity);
                $quantity = ($item->inventory_damage_qtt + $quantity);
            } else{
                return FALSE;
            }
        }else if($operation === 'remove'){
            if($item->inventory_damage_qtt >= $quantity){
                $info['inventory_quantity'] = ($item->inventory_quantity + $quantity);
                $quantity = ($item->inventory_damage_qtt - $quantity);
            } else{
                return FALSE;
            }
        }

        $info['inventory_damage_qtt'] = $quantity;
        $this->db->where('item_id', $id);
        $this->db->update('inventory', $info);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function item_accessories()
    {
        $this->db->select('*')
        ->join('items', 'inventory.item_id = items.item_id', 'left')
        ->join('tbl_measurement', 'tbl_measurement.measurement_id = items.measurement_id', 'left')
        ->join('category', 'items.cat_id = category.cat_id', 'left');
        $this->db->where('items.item_material_status =', 1);
        $this->db->where('items.accessories =', 1);
        $this->db->order_by("inventory_update", "desc");
        $query = $this->db->get('inventory');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return false;
    }

    public function itemBorrow()
    {
        $this->db->select('*')
        ->join('items', 'inventory.item_id = items.item_id', 'left')
        ->join('tbl_measurement', 'tbl_measurement.measurement_id = items.measurement_id', 'left')
        ->join('category', 'items.cat_id = category.cat_id', 'left');
        $this->db->where('items.item_material_status =', 1);
        $this->db->where('items.accessories =', 0);
        $this->db->order_by("inventory_update", "desc");
        $query = $this->db->get('inventory');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return false;
    }

    public function get_inventory_by_item($item_id)
    {
        $this->db->select('*')
        ->from('inventory')
        ->where('item_id =', $item_id);
        $result = $this->db->get()->row();
        return $result;
		}

	public function getInventoryQuantityByID($item_id)
	{
		$result = $this->db->get_where('inventory', ['item_id' => $item_id])->row();
		return $result;
	}

    public function update_inventory($data,$item_id)
    {
        $data['inventory_update'] = date('Y-m-d');
        $this->db->where('item_id', $item_id);
        $this->db->update('inventory', $data);
    }

    function get_item_list($num,$offset){
        if(!$offset){
            $limit = "LIMIT $num";
        }
        else
        {
            $limit = "LIMIT $offset, $num";
        }
        $sql = "SELECT * FROM items it
        LEFT OUTER JOIN tbl_measurement tm ON it.measurement_id = tm.measurement_id
        LEFT OUTER JOIN category ca ON it.cat_id = ca.cat_id
        ORDER BY it.item_name ASC
        $limit";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_item_total()
    {
        $query = $this->db->query("SELECT * FROM items it
            LEFT OUTER JOIN tbl_measurement tm ON it.measurement_id = tm.measurement_id
            LEFT OUTER JOIN category ca ON it.cat_id = ca.cat_id
            ORDER BY it.item_name ASC");
            $count = $query->num_rows();
            return $count;
    }

    public function updateInventoryModifyBorrow($item_id, $quantity){
			echo "model item_id:";
			var_dump($item_id);
			$intItemID = intval($item_id);
			echo "<br>";
			echo "model quantity:";
			var_dump($quantity);
			
			// $data = array(
			// 					'inventory_quantity' => $quantity
			// );
			// $this->db->where('item_id', $item_id);
			// $this->db->update('inventory', $data);
			$sql = "update inventory set inventory_quantity = $quantity where item_id = $item_id";
			$query = $this->db->query($sql); 
    }

}
