<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('User');
	}
	/* VIEW PAGES */

	/* GO TO THE LOGIN PAGE */
	public function index(){
		//$this->session->set_userdata('logged_in',FALSE);
		$result = $this->User->validate_users();
		if($result == 'user'){
			redirect('dashboard');
		}else if($result =='admin'){
			redirect('admin');
		}else{
			$view_data = array('url'=> '/register',
			'title'=>'Register');
			$this->load->view('login_register/login_page',$view_data);
			$this->load->view('login_register/partials/login');
		}
	}
	/* GO TO THE REGISTER PAGE */
	public function register(){
		$result = $this->User->validate_users();
		if($result == 'user'){
			redirect('dashboard');
		}else if($result =='admin'){
			redirect('admin');
		}else{
			$view_data = array('url'=> '/login',
			'title'=>'Login');
			$this->load->view('login_register/login_page',$view_data);
			$this->load->view('login_register/partials/register');
		}
	}
	/* FUTURE PLAN FOR GUEST WHO WANTS TO VIEW THE INNER PAGE */
	public function guest(){
		$result = $this->User->validate_users();
		if($result == 'user'){
			redirect('dashboard');
		}else if($result =='admin'){
			redirect('admin');
		}else{
			$view_data = array('url'=> '/login',
			'title'=>'Login');
			$this->load->view('product/products_page',$view_data);
		}
	}
	/* Form actions */
	/* LOGIN PROCESS VALIDATING INPUT AND REDIRECTING INSIDE SHOP WITH USER */
	public function login_event(){
		// $this->output->enable_profiler(TRUE); 
		$result = $this->User->login_validator();
			if($result == 'success'){
			  $data =  $this->User->login_process($this->input->post());
				if($data == 'admin'){
					redirect('admin');
				}else if($data == 'user'){
					redirect('dashboard');
				}else{
					$this->session->set_flashdata('errors','<p>WRONG CREDENTIALS!</p>');
					redirect('login');
				}
			}else{
				$this->session->set_flashdata('errors', $result); 
				redirect('login');
			}
	}
	public function register_process(){
		//$this->output->enable_profiler(TRUE); 
		$email= $this->input->post('email');
		$contact = $this->input->post('contact');
		$result = $this->User->create_validator($email,$contact);
		if($result =='success'){
			$data = $this->User->insert_user($this->input->post());
			if($data != 1){
				$this->User->setUser($data,$this->input->post());
				redirect('dashboard');
			}else{
				$this->session->set_userdata('is_admin',TRUE);
				$this->User->setUser($data,$this->input->post());
				$this->User->setAdmin($data);
				redirect('admin');
				
			}
		}else if($result == 'email'){
			$this->session->set_flashdata('errors', "<p>Email is already taken</p>");
			redirect('register');
		}else if($result == 'contact'){
			$this->session->set_flashdata('errors',"<p>Contact Number is already taken</p>");
			redirect('register');
		}else{
			$this->session->set_flashdata('errors', $result);
			redirect('register');
		}
	}
	   /* Process of the sign Out button will destroy all sessions */
	 public function log_out(){
			$this->session->unset_userdata('curr_user');
			$this->session->set_userdata('logged_in',FALSE);
			$this->session->set_userdata('is_admin',FALSE);
			session_destroy();
			redirect('login');
	}
}