<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('dashboard_model');
		if (!$this->session->userdata('log')) {
	      redirect(base_url('login'));
	    }
	}


	// public function index()
	// {
	// 	$data = [];
	// 	$data['title'] = 'Dashboard';
	// 	$data['header'] = $this->load->view('header/head', '', TRUE);
	// 	$data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
	// 	$data['all_items'] = $this->dashboard_model->all_items();		
	// 	$data['borrowed_workstations'] = $this->dashboard_model->borrow_items_workstations();		
	// 	$data['borrowed_accessories'] = $this->dashboard_model->borrow_items_accessories();		
	// 	// $data['all_laptop'] = $this->dashboard_model->all_items('laptop');
	// 	$data['content'] = $this->load->view('content/view_dashboard', $data, TRUE);
	// 	$data['footer'] = $this->load->view('footer/footer', '', TRUE);
	// 	$this->load->view('main', $data);

	// }

	public function index(){
		// if($this->input->post()){
		// 	$select_filter_argument = $this->input->post('select_filter');
		// 	$data['all_items'] = $this->dashboard_model->all_items($select_filter_argument);	
		// }
		$data = [];
		$data['title'] = 'Dashboard';
		$data['header'] = $this->load->view('header/head', '', TRUE);
		$data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
		$data['machine_types'] = $this->dashboard_model->machine_types();		
		// $data['borrowed_workstations'] = $this->dashboard_model->borrow_items_workstations();		
		// $data['borrowed_accessories'] = $this->dashboard_model->borrow_items_accessories();		
		// $data['all_laptop'] = $this->dashboard_model->all_items('laptop');
		$data['content'] = $this->load->view('content/view_dashboard', $data, TRUE);
		$data['footer'] = $this->load->view('footer/footer', '', TRUE);
		$this->load->view('main', $data);

	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */
