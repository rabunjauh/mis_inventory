<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Items extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('item_model');
    $this->load->model('inventory_model');
    $this->load->model('borrow_model');
    $this->load->model('settings_model');
    $this->load->library("pagination");
    if (!$this->session->userdata('log')) {
      redirect(base_url('login'));
    }
  }

  public function filter($type)
  {
    $lengtFilter = sizeof($this->input->get());
    $filter = $this->input->get();
    $config = array();
    $config["base_url"] = base_url() . "items/filter/".$type."?";
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
    $total_row = $this->item_model->count_filter($filter,$type);
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
    }
    else{
      $page = 0;
    }
    $data["results"] = $this->item_model->fetch_data_filter($config["per_page"], $page,$filter,$type);
    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['type'] = $type;
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['items'] = $this->item_model->index();
    $data['categories'] = $this->item_model->get_categories();
    if ($type == 'stock') {
      $data['content'] = $this->load->view('content/view_items_stock', $data, TRUE);
    }else {
      $data['content'] = $this->load->view('content/view_items', $data, TRUE);
    }
    $data['extra_footer'] = $this->load->view('footer/x-editable_scripts', '', TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);

  }

  public function consumable()
  {
    $data = array();
    $config = array();
    $config["base_url"] = base_url() . "items/consumable";
    $total_row = $this->item_model->record_count('consumable');
    $config["total_rows"] = $total_row;
    $config["per_page"] = 10;
    $config['num_links'] = 5;
    $config['cur_tag_open'] = '&nbsp;<a class="current">';
    $config['cur_tag_close'] = '</a>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $this->pagination->initialize($config);
    if($this->uri->segment(3)){
      $page = ($this->uri->segment(3)) ;
    }
    else{
      $page = 0;
    }
    $type = 'consumable';
    $data["results"] = $this->item_model->fetch_data($config["per_page"], $page,'consumable');
    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['items'] = $this->item_model->index();
    $data['categories'] = $this->item_model->get_categories();
    $data['type'] = $type;
    $data['content'] = $this->load->view('content/view_items', $data, TRUE);
    $data['extra_footer'] = $this->load->view('footer/x-editable_scripts', '', TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function index()
  {
    $data = array();
    $config = array();
    $config["base_url"] = base_url() . "items/index";
    $total_row = $this->item_model->record_count('stock');
    $config["total_rows"] = $total_row;
    $config["per_page"] = 10;
    $config['num_links'] = 5;
    $config['cur_tag_open'] = '&nbsp;<a class="current">';
    $config['cur_tag_close'] = '</a>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $type = 'stock';
    $this->pagination->initialize($config);
    if($this->uri->segment(3)){
      $page = ($this->uri->segment(3)) ;
    }
    else{
      $page = 0;
    }
    $data["results"] = $this->item_model->fetch_data($config["per_page"], $page,'stock');
    $str_links = $this->pagination->create_links();
    $data["links"] = explode('&nbsp;',$str_links );
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['items'] = $this->item_model->index();
    $data['categories'] = $this->item_model->get_categories();
    $data['type'] = $type;
    $data['content'] = $this->load->view('content/view_items_stock', $data, TRUE);
    $data['extra_footer'] = $this->load->view('footer/x-editable_scripts', '', TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function add_item()
  {
    if (!$this->session->userdata('role')) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    if ($this->input->post()) {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('item_code', 'Item Code', 'required|is_unique[items.item_code]');
      $this->form_validation->set_rules('item_name', 'Item Name', 'required');
      $this->form_validation->set_rules('category_id', 'Category', 'callback_category_check');
      if ($this->form_validation->run() != FALSE) {
        $form_info = array();
        if ($_FILES['item_image']['name']) {
          $config['upload_path'] = './item_iamages/';
          $config['allowed_types'] = 'gif|jpg|png';
          $config['file_name'] = $this->input->post('item_code');
          $config['overwrite'] = TRUE;
          $config['max_size'] = '150';
          $config['max_width'] = '1024';
          $config['max_height'] = '768';

          $this->load->library('upload', $config);
          if (!$this->upload->do_upload('item_image')) {
            $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
            $this->session->set_flashdata('message', $error['error']);
            redirect(base_url('items/add_item'));
          } else {
            $data = array('upload_data' => $this->upload->data());
            //Resize images
            $this->resize_image($data['upload_data']['full_path'], $data['upload_data']['file_name']);
            $form_info['img_info'] = $data;
          }
        }

        $form_info['code'] = addslashes($this->input->post('item_code', TRUE));
        $form_info['description'] = addslashes($this->input->post('item_description', TRUE));
        $form_info['name'] = addslashes($this->input->post('item_name', TRUE));
        $form_info['category'] = $this->input->post('category_id', TRUE);
        $form_info['measurement_id'] = $this->input->post('measurement_id', TRUE);

        //$form_info['item_calibration_status'] = $this->input->post('item_calibration_status', TRUE);
        $form_info['maintenance_status'] = $this->input->post('maintenance_status', TRUE);
        $form_info['item_material_status'] = $this->input->post('item_material_status', TRUE);
        $form_info['warranty_status'] = $this->input->post('warranty_status', TRUE);

        if ($form_info['item_material_status'] == 1) {
          if ($form_info['maintenance_status'] == 1) {
            $form_info['maintenance_next_date'] = $this->input->post('maintenance_next_date', TRUE);
            $form_info['maintenance_previous_date'] = $this->input->post('maintenance_previous_date', TRUE);
            if ($form_info['maintenance_previous_date'] == '') {
              $form_info['maintenance_previous_date'] = date("Y-m-d");
            }
            if ($form_info['maintenance_next_date'] == '') {
              $form_info['maintenance_next_date'] = date('Y-m-d', strtotime("+1 months", strtotime($form_info['maintenance_previous_date'])));
            }
          }
          /*if ($form_info['item_calibration_status'] == 1) {
            $form_info['item_start_date'] = $this->input->post('item_start_date', TRUE);
            $form_info['item_expired_date'] = $this->input->post('item_expired_date', TRUE);
            if ($form_info['item_start_date'] == '') {
              $form_info['item_start_date'] = date("Y-m-d");
            }
            if ($form_info['item_expired_date'] == '') {
              $form_info['item_expired_date'] = date('Y-m-d', strtotime("+1 months", strtotime($form_info['item_start_date'])));
            }
          }*/
          if ($form_info['warranty_status'] == 1) {
            $form_info['start_warranty'] = $this->input->post('start_warranty', TRUE);
            $form_info['end_warranty'] = $this->input->post('end_warranty', TRUE);
            if ($form_info['start_warranty'] == '') {
              $form_info['start_warranty'] = date("Y-m-d");
            }
            if ($form_info['end_warranty'] == '') {
              $form_info['end_warranty'] = date('Y-m-d', strtotime("+1 months", strtotime($form_info['start_warranty'])));
            }
          }
          //
          $form_info['service_tag'] = $this->input->post('service_tag', TRUE);
          $form_info['express_service'] = $this->input->post('express_service', TRUE);
          $form_info['machine_type'] = $this->input->post('machine_type', TRUE);
          $form_info['manufacture'] = $this->input->post('manufacture', TRUE);
          $form_info['model'] = $this->input->post('model', TRUE);
          $form_info['operating_system'] = $this->input->post('operating_system', TRUE);
          $form_info['processor'] = $this->input->post('processor', TRUE);
          $form_info['memory'] = $this->input->post('memory', TRUE);
          $form_info['hdd'] = $this->input->post('hdd', TRUE);
          $form_info['vga'] = $this->input->post('vga', TRUE);
          $form_info['computer_name'] = $this->input->post('computer_name', TRUE);
          $form_info['product_key_windows'] = $this->input->post('product_key_windows', TRUE);
          $form_info['product_key_office'] = $this->input->post('product_key_office', TRUE);
          $form_info['product_key_others'] = $this->input->post('product_key_others', TRUE);
          $form_info['accessories'] = $this->input->post('accessories', TRUE);
          $form_info['predecessor'] = $this->input->post('predecessor', TRUE);
          //
        }
        if (!$this->item_model->save_item($form_info)) {
          $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('items/add_item'));
        } else {
          $this->barcode($form_info['code']);
          $message = '<div class="alert alert-success alert-dismissable">'
          . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
          . 'Item Added Successfully!'
          . '</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('items'));
        }
      }
    }
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['categories'] = $this->item_model->get_categories();
    $data['measurement'] = $this->settings_model->get_measurement();
    $data['machine_types'] = $this->item_model->get_machine_types();
    $data['manufactures'] = $this->item_model->get_manufactures();
    $data['models'] = $this->item_model->get_models();
    $data['operating_systems'] = $this->item_model->get_operating_systems();
    $data['processors'] = $this->item_model->get_processors();
    $data['memories'] = $this->item_model->get_memories();
    $data['hard_disks'] = $this->item_model->get_hard_disks();
    $data['vga'] = $this->item_model->get_vga();
    $data['content'] = $this->load->view('forms/form_add_item', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function modify($id = NULL)
  {
    if (!is_numeric($id) OR is_null($id)) {
      $message = '<div class="alert alert-danger">Not allowed!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items'));
    }

    if ($this->input->post()) {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('category', 'Category', 'required');
      $this->form_validation->set_rules('item_name', 'Item Name', 'required');
      if ($this->form_validation->run() != FALSE) {
        $form_info = array();
        if ($_FILES['item_image']['name']) {
          $config['upload_path'] = './item_iamages/';
          $config['allowed_types'] = 'gif|jpg|png';
          $config['file_name'] = $this->input->post('item_code');
          $config['overwrite'] = TRUE;

          $this->load->library('upload', $config);
          if (!$this->upload->do_upload('item_image')) {
            $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
            $this->session->set_flashdata('message', $error['error']);
            redirect(base_url('items/modify/'.$id));
          } else {
            $data = array('upload_data' => $this->upload->data());
            //Resize images
            $this->resize_image($data['upload_data']['full_path'], $data['upload_data']['file_name']);
            $form_info['img_info'] = $data;
          }
        }

        $form_info['description'] = addslashes($this->input->post('item_description', TRUE));
        $form_info['name'] = addslashes($this->input->post('item_name', TRUE));
        $form_info['category'] = $this->input->post('category', TRUE);
        $form_info['measurement_id'] = $this->input->post('measurement_id', TRUE);

       // $form_info['item_calibration_status'] = $this->input->post('item_calibration_status', TRUE);
        $form_info['maintenance_status'] = $this->input->post('maintenance_status', TRUE);
        $form_info['item_material_status'] = $this->input->post('item_material_status', TRUE);
        $form_info['warranty_status'] = $this->input->post('warranty_status', TRUE);

        if ($form_info['item_material_status'] == 1) {
          if ($form_info['maintenance_status'] == 1) {
            $form_info['maintenance_next_date'] = $this->input->post('maintenance_next_date', TRUE);
            $form_info['maintenance_previous_date'] = $this->input->post('maintenance_previous_date', TRUE);
            if ($form_info['maintenance_previous_date'] == '') {
              $form_info['maintenance_previous_date'] = date("Y-m-d");
            }
            if ($form_info['maintenance_next_date'] == '') {
              $form_info['maintenance_next_date'] = date('Y-m-d', strtotime("+1 months", strtotime($form_info['maintenance_previous_date'])));
            }
          }
          /*if ($form_info['item_calibration_status'] == 1) {
            $form_info['item_start_date'] = $this->input->post('item_start_date', TRUE);
            $form_info['item_expired_date'] = $this->input->post('item_expired_date', TRUE);
            if ($form_info['item_start_date'] == '') {
              $form_info['item_start_date'] = date("Y-m-d");
            }
            if ($form_info['item_expired_date'] == '') {
              $form_info['item_expired_date'] = date('Y-m-d', strtotime("+1 months", strtotime($form_info['item_start_date'])));
            }
          }*/
          if ($form_info['warranty_status'] == 1) {
            $form_info['start_warranty'] = $this->input->post('start_warranty', TRUE);
            $form_info['end_warranty'] = $this->input->post('end_warranty', TRUE);
            if ($form_info['start_warranty'] == '') {
              $form_info['start_warranty'] = date("Y-m-d");
            }
            if ($form_info['end_warranty'] == '') {
              $form_info['end_warranty'] = date('Y-m-d', strtotime("+1 months", strtotime($form_info['start_warranty'])));
            }
          }
          //
          $form_info['service_tag'] = $this->input->post('service_tag', TRUE);
          $form_info['express_service'] = $this->input->post('express_service', TRUE);
          $form_info['machine_type'] = $this->input->post('machine_type', TRUE);
          $form_info['model'] = $this->input->post('model', TRUE);
          $form_info['operating_system'] = $this->input->post('operating_system', TRUE);
          $form_info['processor'] = $this->input->post('processor', TRUE);
          $form_info['memory'] = $this->input->post('memory', TRUE);
          $form_info['hdd'] = $this->input->post('hdd', TRUE);
          $form_info['vga'] = $this->input->post('vga', TRUE);
          $form_info['computer_name'] = $this->input->post('computer_name', TRUE);
          $form_info['product_key_windows'] = $this->input->post('product_key_windows', TRUE);
          $form_info['product_key_office'] = $this->input->post('product_key_office', TRUE);
          $form_info['product_key_others'] = $this->input->post('product_key_others', TRUE);
          $form_info['accessories'] = $this->input->post('accessories', TRUE);
          $form_info['predecessor'] = $this->input->post('predecessor', TRUE);
		  $form_info['item_code'] = $this->input->post('item_code', TRUE);
          //
        }

        if (!$this->item_model->update_item($form_info, $id)) {
          $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('items/modify/'.$id));
        } else {
          $message = '<div class="alert alert-success alert-dismissable">'
          . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
          . 'Item Added Successfully!'
          . '</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('items'));
        }
      }
    }
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['item'] = $this->item_model->get_item_by_id($id);
    if (!$data['item'] || sizeof($data['item']) <= 0) {
      $message = '<div class="alert alert-danger">No Data</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items'));
    }
    $data['measurement'] = $this->settings_model->get_measurement();
    $data['content'] = $this->load->view('forms/form_modify_item', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function view_barcodes()
  {
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['extra_head'] = $this->load->view('header/barcode', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['items'] = $this->item_model->index();
    $data['content'] = $this->load->view('content/view_barcodes', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function add_measurement()
  {
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('forms/form_add_measurement', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function save_measurement()
  {
    if (!$this->session->userdata('role')) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules('measurement', 'required|alpha_dash');
    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('message', validation_errors('<div class="alert alert-danger">', '</div>'));
      redirect(base_url('items/add_measurement'));
    } else {
      if (!$this->item_model->save_measurement()) {
        $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      } else {
        $message = '<div class="alert alert-success alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . 'Measurement Added Successfully!'
        . '</div>';
      }
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    }
  }

  public function update_measurement()
  {
    echo ($this->item_model->update_measurement()) ? 'TRUE' : 'FALSE';
  }

  public function delete_measurement($id = '')
  {
    if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    if (!$this->item_model->delete_measurement($id)) {
      $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    } else {
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . 'Measurement Deleted!'
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    }
  }

  public function add_category()
  {
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('forms/form_add_category', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function save_category()
  {
    if (!$this->session->userdata('role')) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules('cat_name', 'Category Name', 'required|alpha_dash');
    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('message', validation_errors('<div class="alert alert-danger">', '</div>'));
      redirect(base_url('items/add_category'));
    } else {
      if (!$this->item_model->save_category()) {
        $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      } else {
        $message = '<div class="alert alert-success alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . 'Category Added Successfully!'
        . '</div>';
      }
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items/add_item'));
    }
  }

  public function update_category()
  {
    echo ($this->item_model->update_category()) ? 'TRUE' : 'FALSE';
  }

  public function delete_category($id = '')
  {
    if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    if (!$this->item_model->delete_category($id)) {
      $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    } else {
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . 'Category Deleted!'
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    }
  }

  public function add_machine_type()
  {
    $data = array();
    $data['header'] = $this->load->view('header/head', '', TRUE);
    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
    $data['content'] = $this->load->view('forms/form_add_machine_type', $data, TRUE);
    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
    $this->load->view('main', $data);
  }

  public function save_machine_type()
  {
    if (!$this->session->userdata('role')) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules('cat_name', 'Category Name', 'required|alpha_dash');
    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('message', validation_errors('<div class="alert alert-danger">', '</div>'));
      redirect(base_url('items/add_category'));
    } else {
      if (!$this->item_model->save_category()) {
        $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      } else {
        $message = '<div class="alert alert-success alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . 'Category Added Successfully!'
        . '</div>';
      }
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items/add_item'));
    }
  }

  public function update_machine_type()
  {
    echo ($this->item_model->update_category()) ? 'TRUE' : 'FALSE';
  }

  public function delete_machine_type($id = '')
  {
    if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    if (!$this->item_model->delete_category($id)) {
      $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    } else {
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . 'Category Deleted!'
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('settings'));
    }
  }

  public function update_item()
  {
    echo ($this->item_model->update_item()) ? 'TRUE' : 'FALSE';
  }

  public function update_image()
  {
    $id = $this->input->post('item-id', TRUE);
    $item = $this->item_model->get_item_by_id($id);
    if ($_FILES['item_image']['name']) {
      $config['upload_path'] = './item_iamages/';
      $config['allowed_types'] = 'gif|jpg|png';
      $config['file_name'] = $item->item_code;
      $config['overwrite'] = TRUE;
      $config['max_size'] = '150';
      $config['max_width'] = '1024';
      $config['max_height'] = '768';

      $this->load->library('upload', $config);
      if (!$this->upload->do_upload('item_image')) {
        $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
        $this->session->set_flashdata('message', $error['error']);
        redirect(base_url('items'));
      } else {
        $data = array('upload_data' => $this->upload->data());
        //Resize images
        $this->resize_image($data['upload_data']['full_path'], $data['upload_data']['file_name']);
        if (!$this->item_model->update_item_image($id, $data)) {
          $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('items'));
        } else {
          $message = '<div class="alert alert-success alert-dismissable">'
          . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
          . 'Image Updated Successfully!'
          . '</div>';
          $this->session->set_flashdata('message', $message);
          redirect(base_url('items'));
        }
      }
    }
  }

  public function delete_item($id = NULL)
  {
    if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
      exit('<div class="alert alert-danger">Not allowed!</div>');
    }
    if (!$this->item_model->delete_item($id)) {
      $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items'));
    } else {
      $message = '<div class="alert alert-success alert-dismissable">'
      . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
      . 'Item Deleted!'
      . '</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items'));
    }
  }

  public function upload_item_image($id)
  {
    $path_parts = pathinfo($_FILES['item_image']['name']);       // Get file info
    $ext_type = array('gif','jpg','jpe','jpeg','png');  // Allowed extension
    if (in_array($path_parts['extension'], $ext_type)) {
      if(move_uploaded_file($_FILES['item_image']['tmp_name'], './item_iamages/' . $id . '.jpg')){
        //Resize images
        $this->resize_image('./item_iamages/' . $id . '.jpg', $id . '.jpg');
        $message = '<div class="alert alert-success alert-dismissable">'
        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
        . 'Image Updated Successfully!'
        . '</div>';
      }else{
        $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred!</div>';
      }
    }else{
      $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>File type is not allowed!</div>';
    }
    return $message;
  }

  public function resize_image($path, $file)
  {
    $config['image_library'] = 'gd2';
    $config['source_image'] = $path;
    $config['create_thumb'] = TRUE;
    $config['maintain_ratio'] = TRUE;
    $config['width'] = 75;
    $config['height'] = 50;
    $config['new_image'] = './item_iamages/' . $file;

    $this->load->library('image_lib', $config);
    $this->image_lib->resize();
  }

  //Barcode generation function, using Zend library
  public function barcode($code)
  {
    $code = strtoupper($code);
    $this->load->library('zend');
    $this->zend->load('Zend/Barcode');
    $img = Zend_Barcode::factory('code39', 'image', array('text' => $code), array())->draw();
    imagejpeg($img, 'barcodes/' . $code . '.jpg', 100);
    imagedestroy($img);
  }
  //End of Barcode generation function

  public function category_check($val)
  {
    //Callback Function for form validation
    if ($val == 0) {
      $this->form_validation->set_message('category_check', 'Select Category.');
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function view($id)
  {
    $items = $this->item_model->get_item_by_id($id);
    if ($items && $items->item_material_status == 1) {
      $inventory = $this->inventory_model->get_inventory_by_item($id);
      $borrowDetails = $this->borrow_model->get_borrowDetails_by_item($id);
      $borrow = $this->borrow_model->get_borrow_by_item($id);
      if ($borrow) {
        array_push($borrowDetails,$borrow);
      }
      $data['borrowed'] = $this->count_borrowed($borrowDetails);
      $data['item'] = $items;
      $data['inventory'] = $inventory;
      $data['borrowDetails'] = $borrowDetails;
      $data['header'] = $this->load->view('header/head', '', TRUE);
      $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
      $data['extra_head'] = $this->load->view('header/invoice', '', TRUE);
      $data['content'] = $this->load->view('content/item_details', $data, TRUE);
      $data['footer'] = $this->load->view('footer/footer', '', TRUE);
      $this->load->view('main', $data);
    }else {
      $message = '<div class="alert alert-danger">Item Not Found!</div>';
      $this->session->set_flashdata('message', $message);
      redirect(base_url('items'));
    }
  }

  public function count_borrowed($data)
  {
    $total = 0;
    foreach ($data as $value) {
      if (!$value->return_date) {
        $tmp = $value->quantities - $value->return_quantity;
        $total += $tmp;
      }
    }
    return $total;
  }

}
