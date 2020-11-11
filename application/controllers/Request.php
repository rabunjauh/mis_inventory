<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		// if($this->input->post()){
		// 	$select_filter_argument = $this->input->post('select_filter');
		// 	$data['all_items'] = $this->dashboard_model->all_items($select_filter_argument);	
		// }
		$data = [];
		$data['title'] = 'Form Request Items';
		$data['header'] = $this->load->view('header/head', '', TRUE);
		$data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
		$data['content'] = $this->load->view('form/formRequestItems', $data, TRUE);
		$data['footer'] = $this->load->view('footer/footer', '', TRUE);
		$this->load->view('main', $data);

	}

}

/* End of file Request.php */
/* Location: ./application/controllers/Request.php */
