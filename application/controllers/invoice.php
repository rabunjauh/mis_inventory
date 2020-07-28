<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('invoice_model');
    $this->load->model('item_model');
    $this->load->model('settings_model');
    $this->load->model('supplier_model');
    if (!$this->session->userdata('log')) {
      redirect(base_url('login'));
    }
  }

  public function filter_invoice_out(){
    $lengtFilter = sizeof($this->input->get());
    $filter = $this->input->get();
    $config = array();
    $config["base_url"] = base_url() . "invoice/filter_invoice_out?";
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

    //filter_invoice
    $dateFrom = $filter['actual_date_from'];
    $dateTo = $filter['actual_date_to'];

    $warehouse = $filter['warehouse_id'];
    $project = $filter['project_uid'];
    $cost_center = $filter['cost_center_uid'];
    $employee = $filter['employee_id'];

    if($dateFrom == 'all' && $dateTo == 'all'){
      $dateFrom = 'all';
      $dateTo = 'all';
    }elseif ($dateFrom == 'all' && $dateTo !== '') {
      $dateFrom = $dateTo;
    }elseif ($dateFrom !== '' && $dateTo == 'all') {
      $dateTo = $dateFrom;
    }

    $filter = [];
    //
    if ($warehouse !== 'all') {
      $filter[] = $this->putQuery('io','warehouse_id',$warehouse);
    }

    if ($dateFrom !== 'all' && $dateTo !== 'all') {
      $filter[] = $this->putQuery('io','actual_date',date('Y-m-d', strtotime($dateFrom)),'>=');
      $filter[] = $this->putQuery('io','actual_date',date('Y-m-d', strtotime($dateTo)),'<=');
    }

    if ($project !== 'all') {
      $filter[] = $this->putQuery('io','project_uid',$project);
    }

    if ($cost_center !== 'all') {
      $filter[] = $this->putQuery('io','cost_center_uid',$cost_center);
    }

    if ($employee !== 'all') {
      $filter[] = $this->putQuery('io','taken_by_uid',$employee);
      $data['employeename'] = $this->invoice_model->get_emp($employee)->employeename;
    }

    //

    $total_row = $this->invoice_model->count_filter_invoice_out($filter)->total;
    $config["total_rows"] = $total_row;
    $config["per_page"] = 10;
   // $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 5;
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $this->pagination->initialize($config);

    if($_GET['page']){
      $page = $_GET['page'] ;
    }
    else{
      $page = 0;
    }

    $data["results"] = $this->invoice_model->fetch_data_sales_filter($config["per_page"], $page, $filter);

    //filter
    $data['warehouse'] = $this->settings_model->get_warehouses();
    $data['suppliers'] = $this->supplier_model->index();
    $data['costcenter'] = $this->settings_model->get_costcenter();
    $data['project'] = $this->settings_model->get_project();
    //

    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('content/view_invoice_sales', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }


  public function filter_invoice(){
    $lengtFilter = sizeof($this->input->get());
    $filter = $this->input->get();
    $config = array();
    $config["base_url"] = base_url() . "invoice/filter_invoice?";
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

    $dateFrom = $filter['actual_date_from'];
    $dateTo = $filter['actual_date_to'];
    $supplier = $filter['supplier_id'];

    if($dateFrom == 'all' && $dateTo == 'all'){
      $dateFrom = 'all';
      $dateTo = 'all';
    }elseif ($dateFrom == 'all' && $dateTo !== '') {
      $dateFrom = $dateTo;
    }elseif ($dateFrom !== '' && $dateTo == 'all') {
      $dateTo = $dateFrom;
    }

    if ($supplier != 'all') {
      $arrFilter[] = $this->putQuery('supplier','supplier_id',$supplier);
    }
    if ($dateFrom !== 'all' && $dateTo !== 'all') {
      $arrFilter[] = $this->putQuery('invoice_purchase','actual_date',date('Y-m-d', strtotime($dateFrom)),'>=');
      $arrFilter[] = $this->putQuery('invoice_purchase','actual_date',date('Y-m-d', strtotime($dateTo)),'<=');
    }

    $total_row = $this->invoice_model->count_filter_invoice($arrFilter)->total;
    $config["total_rows"] = $total_row;
    $config["per_page"] = 10;
    //$config['use_page_numbers'] = TRUE;
    $config['num_links'] = 5;
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $this->pagination->initialize($config);

    if($_GET['page']){
      $page = $_GET['page'] ;
    }
    else{
      $page = 0;
    }

    $data["results"] = $this->invoice_model->fetch_data_purchases_filter($config["per_page"], $page, $arrFilter);
    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);

    //filter
    $data['warehouse'] = $this->settings_model->get_warehouses();
    $data['suppliers'] = $this->supplier_model->index();
    $data['costcenter'] = $this->settings_model->get_costcenter();
    $data['project'] = $this->settings_model->get_project();

    //
    $data['content'] = $this->load->view('content/view_invoice', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);

  }


  public function putQuery($tbl,$key,$val,$action = '=')
  {
    $arr['tabel'] = $tbl;
    $arr['key'] = $key;
    $arr['value'] = $val;
    $arr['action'] = $action;
    return $arr;
  }

  public function index()
  {
    $data = array();
    $config = array();
    $config["base_url"] = base_url() . "invoice/index";
    $total_row = $this->invoice_model->record_count_purchases();
    $config["total_rows"] = $total_row;
    $config["per_page"] = 10;
    //$config['use_page_numbers'] = TRUE;
    $config['num_links'] = 5;
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';

    $this->pagination->initialize($config);
    if($this->uri->segment(3)){
      $page = ($this->uri->segment(3)) ;
    }
    else{
      $page = 0;
    }
    $data["results"] = $this->invoice_model->fetch_data_purchases($config["per_page"], $page);
    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);

    //filter
    $data['warehouse'] = $this->settings_model->get_warehouses();
    $data['suppliers'] = $this->supplier_model->index();
    $data['costcenter'] = $this->settings_model->get_costcenter();
    $data['project'] = $this->settings_model->get_project();
    //
    // $data['purchases'] = $this->invoice_model->get_purchases();
    // $data['sales'] = $this->invoice_model->get_sales();
    $data['content'] = $this->load->view('content/view_invoice', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function sales()
  {
    $data = array();
    $config = array();
    $config["base_url"] = base_url() . "invoice/sales";
    $total_row = $this->invoice_model->record_count_sales();
    $config["total_rows"] = $total_row;
    $config["per_page"] = 10;
    //$config['use_page_numbers'] = TRUE;
    $config['num_links'] = 5;
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span>';
    $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';

    $this->pagination->initialize($config);
    if($this->uri->segment(3)){
      $page = ($this->uri->segment(3)) ;
    }
    else{
      $page = 0;
    }
    $data["results"] = $this->invoice_model->fetch_data_sales($config["per_page"], $page);

    //filter
    $data['warehouse'] = $this->settings_model->get_warehouses();
    $data['suppliers'] = $this->supplier_model->index();
    $data['costcenter'] = $this->settings_model->get_costcenter();
    $data['project'] = $this->settings_model->get_project();
    //

    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('content/view_invoice_sales', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function view($type, $id){
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['extra_head'] = $this->load->view('header/invoice', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['items'] = $this->item_model->index();
    $data['invoice'] = $this->invoice_model->get_invoice_by_id($type, $id);
    $data['invoice_items'] = $this->invoice_model->get_invoice_details($type, $id);
    if($type == 'purchase'){
      $data['content'] = $this->load->view('content/invoice_purchase', $data, TRUE);
    }else if($type == 'sales'){
      $data['content'] = $this->load->view('content/invoice_sale', $data, TRUE);
    }
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);

  }

  public function modify($type, $id){
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['extra_head'] = $this->load->view('header/invoice', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['items'] = $this->item_model->index();
    $data['invoice'] = $this->invoice_model->get_invoice_by_id($type, $id);
    if($type == 'purchase'){
      $this->load->model('supplier_model');
      $data['suppliers'] = $this->supplier_model->index();
      $data['content'] = $this->load->view('forms/form_modify_purchase', $data, TRUE);
    }else if($type == 'sales'){
      $this->load->model('settings_model');
      $data['costcenter'] = $this->settings_model->get_costcenter();
      $data['project'] = $this->settings_model->get_project();
      $data['warehouses'] = $this->settings_model->get_warehouses();
      $data['content'] = $this->load->view('forms/form_modify_sales', $data, TRUE);
    }
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);

  }

  public function delete($type, $id){
    if (!$this->session->userdata('role')) {
      $message = '<div class="alert alert-danger">You are not allowed to do this!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('invoice'));
    }

    if (!$this->invoice_model->delete($type, $id)) {
      $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('invoice'));
    } else {
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . 'Invoice Deleted!'
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('invoice'));
    }
  }

  public function update($type, $id){
    $this->load->library('form_validation');
    if($type == 'purchase'){
      $this->form_validation->set_rules('supplier', 'Supplier', 'callback_supplier_id_check');
      $this->form_validation->set_rules('txtactualdate', 'Date', 'required');
    }else if($type == 'sales'){
      $this->form_validation->set_rules('warehouse', 'Warehouse', 'callback_supplier_id_check');
      $this->form_validation->set_rules('txtactualdate', 'Date', 'required');
    }
    if ($this->form_validation->run() != FALSE) {
      if (!$this->invoice_model->update($type, $id)) {
        $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('invoice/modify/'.$type.'/'.$id));
      } else {
        $message = '<div class="alert alert-success alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . 'Invoice Updated Successfully!'
        . '</div>';
        $this->session->set_flashdata('message', $message);
        redirect(base_url('invoice'));
      }
    }else{
      $this->session->set_flashdata('message', '<div class="alert alert-danger  alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'.form_error('supplier').'</div>');
      redirect(base_url('invoice/modify/'.$type.'/'.$id));
    }
  }

  public function supplier_id_check($val)
  {
    //Callback Function for form validation
    if ($val == 0) {
      $this->form_validation->set_message('supplier_id_check', 'Select supplier');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function invoice_migrate($type = 'purchase')
  {
      $invoice = $this->invoice_model->get_invoice_migrate($type);
      foreach ($invoice as $value) {
          $items = explode(',',$value->item_ids);
          $quantities = explode(',',$value->quantities);
          for ($i=0; $i < sizeof($items); $i++) {
              $valItem = $items[$i];
              if ($valItem) {
                  $valQty = (isset($quantities[$i])) ? $quantities[$i] : 0 ;
                  $this->invoice_model->save_invoice_details($value->invoice_id,$value->actual_date,$valItem,$valQty,$type);
              }
          }
      }
  }

}
