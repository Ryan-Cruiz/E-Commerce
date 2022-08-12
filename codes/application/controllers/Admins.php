<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User');
    }
    public function index(){
      $this->order_page();
    }
    public function page_redirection($array,$page){
        $result = $this->User->validate_users();
		if($result == 'user'){
			redirect('dashboard');
		}else if($result =='admin'){
            $view_data = $array;
			$this->load->view('admin/admin_page',$view_data);
            $this->load->view('admin/partials/'.$page);
        }else{
            redirect('anonymous');
        }
    }
    public function order_page(){
        $this->page_redirection(array('title'=>'Order Dashboard'),'order_dashboard');
    }
    public function product_dashboard(){
        $this->page_redirection(array('title'=>'Product Dashboard'),'product_dashboard');
    }
    public function order_detail(){
        $this->page_redirection(array('title'=>'Order Details'),'order_details');
    }
}