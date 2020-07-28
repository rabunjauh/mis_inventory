<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('dashboard_model');
	}


	public function index()
	{
		$data = [];

		// $device = $this->dashboard_model->items_machine_type();
		// for($i = 0; $i < sizeof($device); $i++){			
		// 	array_push($data,["dev_qty" => $this->dashboard_model->all_items($device[$i]->machine_type), "dev_type" => $device[$i]->machine_type]);
		// $data[] =  ["dev_qty" => $this->dashboard_model->all_items($device[$i]->machine_type), "dev_type" => $device[$i]->machine_type];
		// }
		// var_dump($data[0]);
		// die;
		$data['title'] = 'Dashboard';
		$data['header'] = $this->load->view('header/head', '', TRUE);
		$data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
		// $data['all_devices'] =
		// $all_devices_qty = [];
		// $all_devices_type = [];		
		// $data['all_desktop'] = $this->dashboard_model->all_items($desktop);		
		// $data['all_laptop'] = $this->dashboard_model->all_items('laptop');
				
		// $data['borrowed_desktop'] = $this->dashboard_model->borrow_items($desktop);		
		// $data['borrowed_laptop'] = $this->dashboard_model->borrow_items($laptop);		
		$data['content'] = $this->load->view('content/view_dashboard', $data, TRUE);
		$data['footer'] = $this->load->view('footer/footer', '', TRUE);
		$this->load->view('main', $data);

	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */
