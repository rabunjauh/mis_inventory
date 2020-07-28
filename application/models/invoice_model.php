<?php

class Invoice_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_purchases()
    {
        $this->db->select('*')
        ->from('invoice_purchase')
        ->order_by('invoice_purchase.date','desc')
        ->join('supplier', 'supplier.supplier_id = invoice_purchase.supplier_id', 'left')
        ->join('user', 'user.user_id = invoice_purchase.user_id', 'left');
        $this->db->order_by("invoice_purchase.actual_date", "desc");

        $result = $this->db->get()->result();
        return $result;
    }

    public function record_count_sales($type) {
        return $this->db->count_all("invoice_out");
    }

    public function count_filter_invoice($arr)
    {
        $sql = "SELECT count(*) total FROM invoice_purchase
        LEFT JOIN supplier ON supplier.supplier_id = invoice_purchase.supplier_id";
        $filter = $this->get_query($arr);
        if ($filter != '') {
            $sql .= $filter;
        }
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function fetch_data_purchases_filter($limit, $offset,$filter)
    {

        $sql = "SELECT * FROM invoice_purchase
        LEFT JOIN supplier ON supplier.supplier_id = invoice_purchase.supplier_id
        LEFT JOIN user ON user.user_id = invoice_purchase.user_id";
        $filter = $this->get_query($filter);

        if ($filter != '') {
            $sql .= $filter;
        }

        $sql .= " ORDER BY invoice_purchase.actual_date DESC";

        if(!$offset){
            $sql .= " LIMIT $limit";
        }else{
            $sql .= " LIMIT $offset, $limit";
        }
        $query = $this->db->query($sql);
        return $query->result();

    }

    public function count_filter_invoice_out($arr)
    {
        $sql = "SELECT count(*) total FROM invoice_out io";
        $filter = $this->get_query($arr);
        if ($filter != '') {
            $sql .= $filter;
        }
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function get_emp($fingerid){
        $sql = "SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=$fingerid";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function fetch_data_sales_filter($limit, $offset, $filter)
    {
        $sql = "SELECT wh.*,us.*,io.invoice_id,io.warehouse_id,io.actual_date,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=io.taken_by_uid)AS employee_name,
        (SELECT project_name FROM project WHERE project_uid=io.project_uid)AS project,
        (SELECT cost_center FROM cost_center WHERE cost_center_uid=io.cost_center_uid)AS cost_center,
        io.item_ids,io.quantities,io.unit_prices,io.total_price,io.date,io.user_id,io.note
        FROM invoice_out io
        LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = io.warehouse_id
        LEFT OUTER JOIN  user us ON us.user_id = io.user_id";

        $filter = $this->get_query($filter);
        if ($filter != '') {
            $sql .= $filter;
        }

        $sql .= " ORDER BY io.date DESC";

        if(!$offset){
            $sql .= " LIMIT $limit";
        }else{
            $sql .= " LIMIT $offset, $limit";
        }
        $query = $this->db->query($sql);
        return $query->result();

    }

    public function get_query($filter)
    {
        $sql = '';
        if (isset($filter) && sizeof($filter) > 0) {
            $i = 0;
            foreach ($filter as $value) {
                $tmpVal = $value['value'];
                if ($i == 0) {
                    $sql .= " WHERE ".$value['tabel'].".".$value['key'].$value['action']."'$tmpVal'";
                }else {
                    $sql .= " AND ".$value['tabel'].".".$value['key'].$value['action']."'$tmpVal'";
                }
                $i++;
            }
        }
        return $sql;
    }

    public function fetch_data_sales($limit, $offset) {
        $sql = "SELECT wh.*,us.*,io.invoice_id,io.warehouse_id,io.actual_date,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=io.taken_by_uid)AS employee_name,
        (SELECT project_name FROM project WHERE project_uid=io.project_uid)AS project,
        (SELECT cost_center FROM cost_center WHERE cost_center_uid=io.cost_center_uid)AS cost_center,
        io.item_ids,io.quantities,io.unit_prices,io.total_price,io.date,io.user_id,io.note
        FROM invoice_out io
        LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = io.warehouse_id
        LEFT OUTER JOIN  user us ON us.user_id = io.user_id
        ORDER BY io.date desc
        LIMIT $limit OFFSET $offset";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return false;
    }

    public function record_count_purchases($type) {
        return $this->db->count_all("invoice_purchase");
    }

    public function fetch_data_purchases($limit, $offset) {
        $this->db->select('*')
        ->order_by('invoice_purchase.date','desc')
        ->join('supplier', 'supplier.supplier_id = invoice_purchase.supplier_id', 'left')
        ->join('user', 'user.user_id = invoice_purchase.user_id', 'left');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('invoice_purchase');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return false;
    }

    public function get_invoice_purchase_detail()
    {
        $sql = "SELECT sp.*,us.*,ip.invoice_id,ip.supplier_id,ip.item_ids,
        ip.quantities,ip.unit_prices,ip.total_price,ip.date,ip.actual_date,
        ip.user_id,ip.note
        FROM invoice_purchase ip
        INNER JOIN supplier sp ON sp.supplier_id = ip.supplier_id
        INNER JOIN user us ON us.user_id = ip.user_id";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function filter_invoice_purchase_detail($filter)
    {
        $sql = "SELECT sp.*,us.*,ip.invoice_id,ip.supplier_id,ip.item_ids,
        ip.quantities,ip.unit_prices,ip.total_price,ip.date,ip.actual_date,
        ip.user_id,ip.note
        FROM invoice_purchase ip
        INNER JOIN supplier sp ON sp.supplier_id = ip.supplier_id
        INNER JOIN user us ON us.user_id = ip.user_id";
        if (isset($filter) && sizeof($filter) > 0) {
            $i = 0;
            foreach ($filter as $value) {
                $tmpVal = $value['value'];
                if ($i == 0) {
                    $sql .= " WHERE ".$value['tabel'].".".$value['key'].$value['action']."'$tmpVal'";
                }else {
                    $sql .= " AND ".$value['tabel'].".".$value['key'].$value['action']."'$tmpVal'";
                }
                $i++;
            }
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function filter_invoice_sales_detail($filter)
    {
        $sql = "SELECT wh.*,us.*,io.*,
        (SELECT warehouse_name FROM warehouse WHERE warehouse_id = io.warehouse_id)AS warehouse_name,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=io.taken_by_uid)AS employee_name,
        (SELECT project_name FROM project WHERE project_uid=io.project_uid)AS project,
        (SELECT (SELECT deptdesc FROM wasco_fingerman.tblfile_department WHERE iddept = te.iddept)
        FROM wasco_fingerman.tblmas_employee te
        WHERE te.fingerid = io.taken_by_uid)AS employee_dept,
        (SELECT cost_center FROM cost_center WHERE cost_center_uid=io.cost_center_uid)AS cost_center
        FROM invoice_out io
        LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = io.warehouse_id
        LEFT OUTER JOIN  user us ON us.user_id = io.user_id";

        if (isset($filter) && sizeof($filter) > 0) {
            $i = 0;
            foreach ($filter as $value) {
                $tmpVal = $value['value'];
                if ($i == 0) {
                    $sql .= " WHERE ".$value['tabel'].".".$value['key'].$value['action']."'$tmpVal'";
                }else {
                    $sql .= " AND ".$value['tabel'].".".$value['key'].$value['action']."'$tmpVal'";
                }
                $i++;
            }
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_invoice_sales_detail()
    {
        $sql = "SELECT wh.*,us.*,io.invoice_id,io.warehouse_id,
        (SELECT warehouse_name FROM warehouse WHERE warehouse_id = io.warehouse_id)AS warehouse_name,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=io.taken_by_uid)AS employee_name,
        (SELECT project_name FROM project WHERE project_uid=io.project_uid)AS project,
        (SELECT (SELECT deptdesc FROM wasco_fingerman.tblfile_department WHERE iddept = te.iddept)
        FROM wasco_fingerman.tblmas_employee te
        WHERE te.fingerid = io.taken_by_uid)AS employee_dept,
        (SELECT cost_center FROM cost_center WHERE cost_center_uid=io.cost_center_uid)AS cost_center,
        io.item_ids,io.quantities,io.unit_prices,io.total_price,io.date,io.user_id,io.note,io.actual_date
        FROM invoice_out io
        LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = io.warehouse_id
        LEFT OUTER JOIN  user us ON us.user_id = io.user_id";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_inventory($type)
    {
        $sql = "SELECT iv.inventory_quantity,it.*,iv.inventory_damage_qtt,
        iv.inventory_added,iv.inventory_update,ca.cat_name , tbl_measurement.measurement
        FROM inventory iv
        INNER JOIN items it ON iv.item_id = it.item_id
        INNER JOIN category ca ON it.cat_id = ca.cat_id
        left JOIN tbl_measurement ON it.measurement_id = tbl_measurement.measurement_id";
        if ($type == 'consumable') {
            $sql .= " WHERE it.item_material_status = 0 ";
        }else {
            $sql .= " WHERE it.item_material_status = 1 ";
        }
        $sql .= "ORDER BY item_name ASC ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_item()
    {
        $sql = "SELECT * FROM items";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_sales()
    {
        //$this->db->select('*')
        //         ->from('invoice_out')
        //        ->order_by('invoice_out.date','desc')
        //         ->join('warehouse', 'warehouse.warehouse_id = invoice_out.warehouse_id', 'left')
        //         ->join('user', 'user.user_id = invoice_out.user_id', 'left');

        // $result = $this->db->get()->result();
        //return $result;
        $sql = "SELECT wh.*,us.*,io.invoice_id,io.warehouse_id,io.actual_date,
        (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=io.taken_by_uid)AS employee_name,
        (SELECT project_name FROM project WHERE project_uid=io.project_uid)AS project,
        (SELECT cost_center FROM cost_center WHERE cost_center_uid=io.cost_center_uid)AS cost_center,
        io.item_ids,io.quantities,io.unit_prices,io.total_price,io.date,io.user_id,io.note
        FROM invoice_out io
        LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = io.warehouse_id
        LEFT OUTER JOIN  user us ON us.user_id = io.user_id";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_invoice_by_id($type, $id)
    {
        if($type == 'purchase'){
            //$this->db->select('*')
            //     ->from('invoice_purchase')
            //     ->where('invoice_id', $id)
            //     ->order_by('invoice_purchase.date','desc')
            //     ->join('supplier', 'supplier.supplier_id = invoice_purchase.supplier_id', 'left')
            //     ->join('user', 'user.user_id = invoice_purchase.user_id', 'left');

            $sql = "SELECT sp.*,us.*,ip.invoice_id,ip.supplier_id,ip.item_ids,
            ip.quantities,ip.unit_prices,ip.total_price,ip.date,ip.actual_date,
            ip.user_id,ip.note
            FROM invoice_purchase ip
            INNER JOIN supplier sp ON sp.supplier_id = ip.supplier_id
            INNER JOIN user us ON us.user_id = ip.user_id
            WHERE ip.invoice_id = ".$this->db->escape($id);
            $query = $this->db->query($sql);
            return $query->row();
            //$result = $this->db->get()->row();
            // return $result;
        }else if($type == 'sales'){
            // $this->db->select('*')
            //     ->from('invoice_out')
            //     ->where('invoice_id', $id)
            //     ->order_by('invoice_out.date','desc')
            //     ->join('warehouse', 'warehouse.warehouse_id = invoice_out.warehouse_id', 'left');

            $sql = "SELECT wh.*,io.invoice_id,io.warehouse_id,io.taken_by_uid,io.project_uid,io.cost_center_uid,
            (SELECT employeename FROM wasco_fingerman.tblmas_employee WHERE fingerid=io.taken_by_uid)AS employee_name,
            io.item_ids,io.quantities,io.unit_prices,io.total_price,io.date,io.user_id,io.note,
            DATE_FORMAT(io.actual_date,'%d/%m/%Y')AS actual_date
            FROM invoice_out io
            LEFT OUTER JOIN  warehouse wh ON wh.warehouse_id = io.warehouse_id
            WHERE io.invoice_id = ".$this->db->escape($id)."
            ORDER BY io.date DESC";

            $query = $this->db->query($sql);
            return $query->row();
            //$result = $this->db->get()->row();
            // return $result;
        }
    }

    public function update($type, $id)
    {
        $items = $this->input->post('item', TRUE);
        $unit_price = $this->input->post('unit_price', TRUE);
        $quantity = $this->input->post('quantity', TRUE);

        $ids = '';
        $u_price = '';
        $qtt = '';
        $total_price = 0;
        foreach ($items as $key => $item) {
            if($items[$key] AND ($items[$key] != 0) AND (is_numeric($items[$key]))){
                if($unit_price[$key] AND ($unit_price[$key] != 0) AND (is_numeric($unit_price[$key]))){
                    if($quantity[$key] AND ($quantity[$key] != 0) AND (is_numeric($quantity[$key]))){
                        $ids .= $item.',';
                        $qtt .= $quantity[$key].',';
                        $u_price .= $unit_price[$key].',';
                        $total_price = ($total_price + ($unit_price[$key] * $quantity[$key]));
                    }else{
                        return FALSE;
                    }
                }else{
                    return FALSE;
                }
            }
        }

        $ids = substr($ids, 0, -1);
        $u_price = substr($u_price, 0, -1);
        $qtt = substr($qtt, 0, -1);

        $info['item_ids'] = $ids;
        $info['unit_prices'] = $u_price;
        $info['quantities'] = $qtt;
        $info['total_price'] = $total_price;
        $info['user_id'] = $this->session->userdata('user_id');
        $info['note'] = $this->input->post('note', TRUE);
        $info['date'] = date('Y-m-d');

        if($type == 'purchase'){
            $txtactualdate = $this->input->post('txtactualdate', TRUE);
            $exp = explode("/",$txtactualdate);
            $txtactualdate = $exp["2"]."-".$exp[1]."-".$exp[0];
            $info['supplier_id'] = $this->input->post('supplier', TRUE);
            $info['actual_date'] = $txtactualdate;
            $this->db->where('invoice_id', $id);
            $this->db->update('invoice_purchase', $info);
        }else if($type == 'sales'){
            $txtactualdate = $this->input->post('txtactualdate', TRUE);
            $exp = explode("/",$txtactualdate);
            $txtactualdate = $exp["2"]."-".$exp[1]."-".$exp[0];
            $info['warehouse_id'] = $this->input->post('warehouse', TRUE);
            $info['taken_by_uid'] = $this->input->post('txtempid', TRUE);
            $info['cost_center_uid'] = $this->input->post('txtcostcenter', TRUE);
            $info['project_uid'] = $this->input->post('txtproject', TRUE);
            $info['actual_date'] = $txtactualdate;
            $this->db->where('invoice_id', $id);
            $this->db->update('invoice_out', $info);
        }

        if ($this->db->affected_rows() == 1) {
            return TRUE;
        }else{
            return FALSE;
        }

    }

    public function delete($type, $id)
    {
        $this->db->where('invoice_id', $id);

        if($type == 'purchase'){
            $this->db->delete('invoice_purchase');
        }else if($type == 'sales'){
            $this->db->delete('invoice_out');
        }

        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_invoice_migrate($type)
    {
        $result = array();
        if ($type == 'purchase') {
            $this->db->select('*')
            ->from('invoice_purchase');
            $this->db->where('item_ids !=','');
            $result = $this->db->get()->result();
        }elseif ($type == 'sales') {
            $this->db->select('*')
            ->from('invoice_out');
            $this->db->where('item_ids !=','');
            $result = $this->db->get()->result();
        }

        return $result;
    }

    public function save_invoice_details($invoice_id,$invoice_date,$items,$quantities,$type)
    {
        $res = $this->check_invoice_details($invoice_id,$items,$type);
        if (sizeof($res) <= 0) {
            if($type == 'purchase'){
                $data['invoice_purchase_id'] = $invoice_id;
                $data['item_id'] = $items;
                $data['quantities'] = $quantities;
                $data['invoice_date'] = $invoice_date;
                $this->db->insert('invoice_purchase_details', $data);
            }else if($type == 'sales'){
                $data['invoice_out_id'] = $invoice_id;
                $data['item_id'] = $items;
                $data['quantities'] = $quantities;
                $data['invoice_date'] = $invoice_date;
                $this->db->insert('invoice_out_details', $data);
            }
        }
    }

    public function check_invoice_details($invoice_id,$items,$type)
    {
        $result = null;
        if ($type == 'purchase') {
            $this->db->select('*')
            ->from('invoice_purchase_details');
            $this->db->where('item_id',$items);
            $this->db->where('invoice_purchase_id',$invoice_id);
            $result = $this->db->get()->row();
        }elseif ($type == 'sales') {
            $this->db->select('*')
            ->from('invoice_out_details');
            $this->db->where('item_id',$items);
            $this->db->where('invoice_out_id',$invoice_id);
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function get_invoice_details($type, $id)
    {
        if ($type == 'purchase') {
            $sql = "SELECT id.*,it.item_code,it.item_name,tm.measurement
            FROM invoice_purchase_details id
            INNER JOIN items it ON it.item_id = id.item_id
            LEFT JOIN tbl_measurement tm ON it.measurement_id = tm.measurement_id
            WHERE id.invoice_purchase_id = ".$this->db->escape($id);
            $query = $this->db->query($sql);
            return $query->result();
        }else {
            $sql = "SELECT id.*,it.item_code,it.item_name,tm.measurement
            FROM invoice_out_details id
            INNER JOIN items it ON it.item_id = id.item_id
            LEFT JOIN tbl_measurement tm ON it.measurement_id = tm.measurement_id
            WHERE id.invoice_out_id = ".$this->db->escape($id);
            $query = $this->db->query($sql);
            return $query->result();
        }
    }

}
