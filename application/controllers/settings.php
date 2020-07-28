<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('user_model');
        if (!$this->session->userdata('log')) {
            redirect(base_url('login'));
        }
    }
    public function index()
    {
        $this->load->model('item_model');
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
        $data['users'] = $this->user_model->index();
        $data['warehouses'] = $this->settings_model->get_warehouses();
        $data['costcenter'] = $this->settings_model->get_costcenter();
        $data['project'] = $this->settings_model->get_project();
        $data['categories'] = $this->item_model->get_categories();
        $data['settings'] = $this->settings_model->index();
        $data['measurement'] = $this->settings_model->get_measurement();
        $data['extra_footer'] = $this->load->view('footer/x-editable_scripts', '', TRUE);
        $data['content'] = $this->load->view('content/view_settings', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }

    public function update()
    {
        echo ($this->settings_model->update_settings()) ? 'TRUE' : 'FALSE';
    }

    public function add_warehouse()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('incharge', 'Incharge', 'callback_user_check');
            $this->form_validation->set_rules('warehouse_name', 'Warehouse Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Warehouse Email', 'trim|valid_email');
            $this->form_validation->set_rules('phone', 'Warehouse Phone', 'trim');
            if ($this->form_validation->run() != FALSE) {
                if (!$this->settings_model->save_warehouse()) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('settings/add_warehouse'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Warehouse Added Successfully!'
                            . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('settings'));
                }
            }
        }
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', '', TRUE);
        $data['users'] = $this->user_model->index();
        $data['content'] = $this->load->view('forms/form_add_warehouse', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }


    public function update_warehouse()
    {
        echo ($this->settings_model->update_warehouse()) ? 'TRUE' : 'FALSE';
    }

    public function delete_warehouse($id = NULL)
    {
        if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
            $message = '<div class="alert alert-danger">Not allowed!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        }
        if (!$this->settings_model->delete_warehouse($id)) {
            $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        } else {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Warehouse Deleted!'
                    . '</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        }
    }


	public function add_costcenter()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('cost_center', 'Cost Center', 'trim|required');
            if ($this->form_validation->run() != FALSE) {
				$cost_center_uid = $this->settings_model->generateHash();
                if (!$this->settings_model->save_costcenter($cost_center_uid)) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('settings/add_costcenter'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Cost Center Added Successfully!'
                            . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('settings'));
                }
            }
        }
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', '', TRUE);
        $data['users'] = $this->user_model->index();
        $data['content'] = $this->load->view('forms/form_add_costcenter', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }


    public function update_costcenter()
    {
        echo ($this->settings_model->update_costcenter()) ? 'TRUE' : 'FALSE';
    }

    public function delete_costcenter($id = NULL)
    {
        if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
            $message = '<div class="alert alert-danger">Not allowed!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        }
        if (!$this->settings_model->delete_costcenter($id)) {
            $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        } else {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Cost Center Deleted!'
                    . '</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        }
    }


	public function add_project()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('project_name', 'Project Name', 'trim|required');
            if ($this->form_validation->run() != FALSE) {
				$project_uid = $this->settings_model->generateHash();
                if (!$this->settings_model->save_project($project_uid)) {
                    $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('settings/add_costcenter'));
                } else {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Project Added Successfully!'
                            . '</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('settings'));
                }
            }
        }
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['navigation'] = $this->load->view('header/navigation', '', TRUE);
        $data['users'] = $this->user_model->index();
        $data['content'] = $this->load->view('forms/form_add_project', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', '', TRUE);
        $this->load->view('main', $data);
    }


    public function update_project()
    {
        echo ($this->settings_model->update_project()) ? 'TRUE' : 'FALSE';
    }

    public function delete_project($id = NULL)
    {
        if (!$this->session->userdata('role') OR !is_numeric($id) OR is_null($id)) {
            $message = '<div class="alert alert-danger">Not allowed!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        }
        if (!$this->settings_model->delete_project($id)) {
            $message = '<div class="alert alert-danger">An ERROR occurred!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        } else {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Project Deleted!'
                    . '</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('settings'));
        }
    }

    public function user_check($val)
    {
        //Callback Function for form validation
        if (!$val) {
            $this->form_validation->set_message('user_check', 'Select Incharge.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
