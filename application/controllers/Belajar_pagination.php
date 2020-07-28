<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Belajar_pagination extends CI_Controller {

	public function index()
	{
		$data['judul'] = 'List of Items';
		$this->load->model('Bp_model', 'items');
		// $data['items'] = $this->items->getAllItems();


		// Pagination
		// config
		$config['base_url'] = 'http://localhost/mis_inventory/belajar_pagination/index';
		$config['total_rows'] = $this->items->countAllItems();
		$config['per_page'] = 10;

		// Styling
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '&rsaquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&lsaquo;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><span>';
		$config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		// $config['attributes'] = array('class' => 'page-link');

		// initialize
		$this->pagination->initialize($config);


		$data['start'] = $this->uri->segment(3);
		$data['items'] = $this->items->getItems($config['per_page'], $data['start']);

		$data['header'] = $this->load->view('header/head', '', TRUE);
	    $data['navigation'] = $this->load->view('header/navigation', $data, TRUE);
	    $data['content'] = $this->load->view('content/bel_pag', $data, TRUE);
	    $data['footer'] = $this->load->view('footer/footer', '', TRUE);
	    $this->load->view('main', $data);
	}

}

/* End of file Belajar_pagination.php */
/* Location: ./application/controllers/Belajar_pagination.php */