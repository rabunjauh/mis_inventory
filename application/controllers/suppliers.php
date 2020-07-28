<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_model');
        if (!$this->session->userdata('log')) {
            redirect(base_url('login'));
        }
    }

    public function index()
    {
        $data = array();

        $config = array();
        $config["base_url"] = base_url() . "suppliers/index";
        $total_row = $this->supplier_model->record_count_supplier();
        $config["total_rows"] = $total_row;
        $config["per_page"] = 5;
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
        $data["results"] = $this->supplier_model->fetch_data_supplier($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;',$str_links );


        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', '', TRUE);
        $data['suppliers'] = $this->supplier_model->index();
        $data['content'] = $this->load->view('content/view_suppliers', $data, TRUE);
        $data['extra_footer'] = $this->load->view('footer/x-editable_scripts', '', TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function add_supplier()
    {
        if($this->input->post()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_emails');
            $this->form_validation->set_rules('key_person', 'Key Person', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim');
            if ($this->form_validation->run() != FALSE) {
                if (!$this->supplier_model->save_supplier()) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('suppliers/add_supplier'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Supplier Added Successfully!'
                            . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('suppliers'));
                }
            }
        }
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', '', TRUE);
        $data['content'] = $this->load->view('forms/form_add_supplier', '', TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function modify($id = NULL)
    {
        if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
            $message = '<div class="alert alert-danger">Not allowed!</div>';
            $this->session->set_flashdata('message', $message);
            redirect('suppliers');
        }
        if ($this->input->post()) {
// echo "<pre/>"; print_r($_POST); exit();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'trim|required');
            $this->form_validation->set_rules('supplier_key_person', 'Contact Person', 'trim|required');
            $this->form_validation->set_rules('supplier_email', 'Email', 'trim|required|valid_emails');
            if ($this->form_validation->run() != FALSE) {
                if (!$this->supplier_model->update_supplier($id)) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect('suppliers/modify/'.$id);
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Updated Successfully!'
                            . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect('suppliers');
                }
            }
        }
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', '', TRUE);
        $data['supplier'] = $this->supplier_model->get_supplier_detail($id);
        $data['content'] = $this->load->view('forms/form_modify_supplier', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function update_supplier() {
        echo ($this->supplier_model->update_supplier()) ? 'TRUE' : 'FALSE';
    }

    public function delete_supplier($id = NULL)
    {
        if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
            exit('<div class="alert alert-danger">Not allowed!</div>');
        }
        if (!$this->supplier_model->delete_supplier($id)) {
            $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('suppliers'));
        } else {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Supplier Deleted!'
                    . '</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('suppliers'));
        }
    }
}
