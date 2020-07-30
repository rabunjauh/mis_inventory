<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sso extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('login_model');
        if ($this->session->userdata('log')) {
            $this->session->sess_destroy();
        }
    }

    public function index($apikey,$username)
    {
      	
		$result = $this->login_model->get_sso($apikey,$username);
		
		if($result==1)	// login OK
		{
			
			$query = $this->db->get_where('user', 
			array(
				'user_email' => $username."@wascoenergy.com"
				),
			1);
			$result1 = $query->row();
			
			if ($result1) {
				$settings = $this->db->get('settings',1)->row();
	
				$this->session->set_userdata('log', TRUE);
				$this->session->set_userdata('role', $result1->user_role);
				$this->session->set_userdata('user_name', $result1->user_full_name);
				$this->session->set_userdata('user_email', $result1->user_email);
				$this->session->set_userdata('user_id', $result1->user_id);
				if ($settings) {
					$this->session->set_userdata('brand', $settings->brand_name);
					$this->session->set_userdata('alert', $settings->alert_on);
					$this->session->set_userdata('alert_email', $settings->alert_email);
					$this->session->set_userdata('address', $settings->address);
					$this->session->set_userdata('phone', $settings->phone);
				}
				
				redirect(base_url('inventory'));
			
			}else{
				redirect(base_url('login'));
			}
			
		}else{
			redirect(base_url('login'));
		}
		
               
       
    }

    
}
