<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
        $this->load->model('item_model');
        if (!$this->session->userdata('log')) {
            redirect(base_url('login'));
        }
    }

    public function index()
    {
        $data = array();
        $config = array();
        $config["base_url"] = base_url() . "inventory/index";
        $total_row = $this->inventory_model->record_count_inventory('stock');
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
        }else{
            $page = 0;
        }
        $data["results"] = $this->inventory_model->fetch_data_inventory($config["per_page"], $page,'stock');
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $data["category"] = $this->item_model->get_categories();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['inventory'] = $this->inventory_model->index();
        $data['content'] = $this->load->view('content/view_inventory_stock', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function filter_stock(){
        $lengtFilter = sizeof($this->input->get());
        $filter = $this->input->get();
        $config = array();
        $config["base_url"] = base_url() . "inventory/filter_stock?";
        $i = 1;
        foreach ($filter as $key => $value) {
            if ($key != 'page') {
                if ($value == "") {
                    $config["base_url"] .= $key."=all";
                }else {
                    $config["base_url"] .= $key."=".$value;
                }
                if ($i != $lengtFilter) {
                    $config["base_url"] .= "&";
                }
            }
            $i++;
        }
        $search_type = $filter['search_type'];
        $data['text_search'] = $filter["$search_type"];
        $data['search_type'] = $search_type;
        unset($filter['search_type']);
        $total_row = $this->inventory_model->count_filter($filter,1)->total;
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        if($_GET['page']){
            $page = $_GET['page'] ;
        }else{
            $page = 0;
        }
        $data["results"] = $this->inventory_model->fetch_data_inventory_filter($config["per_page"], $page,$filter,1);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links);
        $data["category"] = $this->item_model->get_categories();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['inventory'] = $this->inventory_model->index();
        $data['content'] = $this->load->view('content/view_inventory_stock', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function filter(){
        $lengtFilter = sizeof($this->input->get());
        $filter = $this->input->get();
        $config = array();
        $config["base_url"] = base_url() . "inventory/filter?";
        $i = 1;
        foreach ($filter as $key => $value) {
            if ($value == "") {
                $config["base_url"] .= $key."=all";
            }else {
                $config["base_url"] .= $key."=".$value;
            }
            if ($i != $lengtFilter) {
                $config["base_url"] .= "&";
            }
            $i++;
        }
        $total_row = $this->inventory_model->count_filter($filter,0)->total;
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        if($_GET['page']){
            $page = $_GET['page'] ;
        }else{
            $page = 0;
        }
        $data["results"] = $this->inventory_model->fetch_data_inventory_filter($config["per_page"], $page,$filter,0);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $data["category"] = $this->item_model->get_categories();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['inventory'] = $this->inventory_model->index();
        $data['content'] = $this->load->view('content/view_inventory', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function consumable()
    {
        $data = array();
        $config = array();
        $config["base_url"] = base_url() . "inventory/index";
        $total_row = $this->inventory_model->record_count_inventory('consumable');
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
        }else{
            $page = 0;
        }
        $data["results"] = $this->inventory_model->fetch_data_inventory($config["per_page"], $page,'consumable');
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $data["category"] = $this->item_model->get_categories();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['inventory'] = $this->inventory_model->index();
        $data['content'] = $this->load->view('content/view_inventory', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }


    function employee()
    {
        $data['search'] = 'false';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = '&laquo;';
        $config['prev_link'] = '&lsaquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&rsaquo;';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $data['employeelist_total'] = $this->inventory_model->get_employee_bypage_total();
        $config['base_url'] = base_url().'inventory/employee/';
        $config['total_rows'] = $data['employeelist_total'];
        $config['per_page'] = '20';
        $config['uri_segment'] = '3';
        $this->pagination->initialize($config);
        $data['employee_list'] = $this->inventory_model->employee_list($config['per_page'],$this->uri->segment(3));
        $data['getposition_list'] = $this->inventory_model->get_position_list();
        $data['getsupervisor_list'] = $this->inventory_model->get_supervisor_list();
        $data['getdepartment_list'] = $this->inventory_model->get_department_list();
        $data['offset'] = $this->uri->segment(3);
        $this->load->view('content/view_employee', $data);

    }

    function emp_search($txtsearch=false,$txtsearchby=false)
    {
        $data['search'] = 'true';
        if(!$txtsearchby){
            $txtsearchby = (htmlspecialchars($this->input->post('p',TRUE))!="")?htmlspecialchars($this->input->post('p',TRUE)):"null";
            if(!$txtsearch){
                if($txtsearchby == "nm" ||$txtsearchby == "ei" ){
                    $txtsearch = (htmlspecialchars($this->input->post('pv',TRUE))!="")?htmlspecialchars($this->input->post('pv',TRUE)):"null";
                }else if($txtsearchby == "dp" ){
                    $txtsearch = (htmlspecialchars($this->input->post('txtdepartment',TRUE))!="")?htmlspecialchars($this->input->post('txtdepartment',TRUE)):"null";
                }else if($txtsearchby == "ps"){
                    $txtsearch = (htmlspecialchars($this->input->post('txtposition',TRUE))!="")?htmlspecialchars($this->input->post('txtposition',TRUE)):"null";
                }else if($txtsearchby == "sp" ){
                    $txtsearch = (htmlspecialchars($this->input->post('txtsupervisor',TRUE))!="")?htmlspecialchars($this->input->post('txtsupervisor',TRUE)):"null";
                }
            }
        }
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = '&laquo;';
        $config['prev_link'] = '&lsaquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&rsaquo;';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $data['employeelist_total'] = $this->inventory_model->get_emp_search_bypage_total($txtsearchby,$txtsearch);
        $config['base_url'] = base_url().'inventory/emp_search/'.$txtsearch.'/'.$txtsearchby;
        $config['total_rows'] = $data['employeelist_total'];
        $config['per_page'] = '20';
        $config['uri_segment'] = '5';
        $this->pagination->initialize($config);
        $data['employee_list'] = $this->inventory_model->get_emp_search_list($config['per_page'],$this->uri->segment(5),$txtsearchby,$txtsearch);
        $data['getposition_list'] = $this->inventory_model->get_position_list();
        $data['getsupervisor_list'] = $this->inventory_model->get_supervisor_list();
        $data['getdepartment_list'] = $this->inventory_model->get_department_list();
        $data['offset'] = $this->uri->segment(5);
        $data['search_value'] = $txtsearch;
        $data['search_by'] = $txtsearchby;
        $this->load->view('content/view_employee', $data);
    }

    public function item_in()
    {
        if (!$this->session->userdata('role')) {
            $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('inventory'));
        }
        if($this->input->post()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('quantity[]', 'Quantity', 'required|integer');
            $this->form_validation->set_rules('supplier', 'Supplier', 'callback_supplier_id_check');
            $this->form_validation->set_rules('txtactualdate', 'Date', 'required');
            if ($this->form_validation->run() != FALSE) {
                if (!$this->inventory_model->item_in()) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('inventory/item_in'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Inventory Updated Successfully!'
                    . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('inventory'));
                }
            }
        }
        $this->load->model('supplier_model');
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['suppliers'] = $this->supplier_model->index();
        $data['items'] = $this->item_model->get_items();
        $data['content'] = $this->load->view('forms/form_item_in', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function item_out()
    {
        if (!$this->session->userdata('role')) {
            $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('inventory'));
        }
        if($this->input->post()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('quantity[0]', 'Quantity', 'required|integer');
            $this->form_validation->set_rules('warehouse', 'Warehouse', 'callback_warehouse_id_check');
            $this->form_validation->set_rules('txtactualdate', 'Date', 'required');
            if ($this->form_validation->run() != FALSE) {
                if (!$this->inventory_model->item_out()) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('inventory/item_out'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Inventory Updated Successfully!'
                    . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('inventory'));
                }
            }
        }
        $this->load->model('settings_model');
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['items'] = $this->inventory_model->index();
        $data['costcenter'] = $this->settings_model->get_costcenter();
        $data['project'] = $this->settings_model->get_project();
        $data['warehouses'] = $this->settings_model->get_warehouses();
        $data['content'] = $this->load->view('forms/form_item_out', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function damage_items()
    {
        $data = array();
        $config = array();
        $config["base_url"] = base_url() . "inventory/damage_items";
        $total_row = $this->inventory_model->record_count_damage();
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
        }
        else{
            $page = 0;
        }
        $data["results"] = $this->inventory_model->fetch_data_damage($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['damage_items'] = $this->inventory_model->get_damage_items();
        $data['content'] = $this->load->view('content/view_damage_items', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function update_damage_item($operation = 'add')
    {
        if (!$this->session->userdata('role')) {
            $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('inventory'));
        }
        if($this->input->post()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer');
            $this->form_validation->set_rules('item_id', 'Item', 'callback_item_id_check');
            if ($this->form_validation->run() != FALSE) {
                if (!$this->inventory_model->update_damage_item($operation)) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('inventory/damage_items'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Inventory Updated Successfully!'
                    . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('inventory/damage_items'));
                }
            }
        }
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);

        if($operation === 'add'){
            $data['items'] = $this->inventory_model->index();
            $data['content'] = $this->load->view('forms/form_add_damage_item', $data, TRUE);
        }else if($operation === 'remove'){
            $data['items'] = $this->inventory_model->get_damage_items();
            $data['content'] = $this->load->view('forms/form_remove_damage_item', $data, TRUE);
        }else{
            show_404();
        }
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function warehouse_id_check($val)
    {
        //Callback Function for form validation
        if ($val == 0) {
            $this->form_validation->set_message('warehouse_id_check', 'Select warehouse.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function supplier_id_check($val)
    {
        //Callback Function for form validation
        if ($val == 0) {
            $this->form_validation->set_message('supplier_id_check', 'Select supplier.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function browse_item($type)
    {
        $data = array();
        $config = array();
        $config["base_url"] = base_url() . "inventory/browse_item/".$type;
        $total_row = $this->inventory_model->record_count_inventory($type);
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = '&laquo;';
        $config['prev_link'] = '&lsaquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&rsaquo;';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config["uri_segment"] = 4;
        $this->pagination->initialize($config);
        if($this->uri->segment(4)){
            $page = ($this->uri->segment(4)) ;
        }else{
            $page = 0;
        }
        $data['offset'] = $this->uri->segment(4);
        $data['type'] = $type ;
        $data['search_by'] = '' ;
        $data['search_value'] = '' ;
        $data['total_rows'] = $total_row;
        $data["category"] = $this->item_model->get_categories();
        $data["results"] = $this->inventory_model->fetch_data_inventory($config["per_page"], $page,$type);
        // var_dump($data["results"]);die;
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $data["category"] = $this->item_model->get_categories();
        $data['inventory'] = $this->inventory_model->index();
        $this->load->view('content/view_items_browse', $data);
    }

    public function browse_item_search($type,$txtsearchby=false,$txtsearch=false)
    {
        $data = array();
        $config = array();
        $data['search'] = 'true';
        if(!$txtsearchby){
            $txtsearchby = (htmlspecialchars($this->input->post('param',TRUE))!="")?htmlspecialchars($this->input->post('param',TRUE)):"null";
            if(!$txtsearch){
                if($txtsearchby == "cat_id" ){
                    $txtsearch = (htmlspecialchars($this->input->post('cat_id',TRUE))!="")?htmlspecialchars($this->input->post('cat_id',TRUE)):"null";
                }else {
                    $txtsearch = (htmlspecialchars($this->input->post('text_search',TRUE))!="")?htmlspecialchars($this->input->post('text_search',TRUE)):"null";
                }
            }
        }
        $config["base_url"] = base_url() . "inventory/browse_item_search/".$type."/".$txtsearchby."/".$txtsearch."/";
        $total_row = $this->inventory_model->record_count_inventory_search($type,$txtsearchby,$txtsearch);
        $config["total_rows"] = $total_row;
        $config["per_page"] = 10;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = '&laquo;';
        $config['prev_link'] = '&lsaquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&rsaquo;';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config["uri_segment"] = 6;
        $this->pagination->initialize($config);
        if($this->uri->segment(6)){
            $page = ($this->uri->segment(6)) ;
        }else{
            $page = 0;
        }
        $data['offset'] = $this->uri->segment(6);
        $data['type'] = $type;
        $data['search_by'] = '' ;
        $data['search_value'] = '' ;
        $data['total_rows'] = $total_row;
        $data["category"] = $this->item_model->get_categories();
        $data["results"] = $this->inventory_model->fetch_data_inventory_search($config["per_page"], $page,$type,$txtsearchby,$txtsearch);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );
        $data["category"] = $this->item_model->get_categories();
        $data['inventory'] = $this->inventory_model->index();
        $data['txtsearch'] = $txtsearch;
        $data['txtsearchby'] = $txtsearchby;
        $this->load->view('content/view_items_browse', $data);
    }

}
