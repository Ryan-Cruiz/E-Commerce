<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops extends CI_Controller{
    public function index(){
        $curr_session = $this->session->userdata('logged_in');
        if(!$curr_session){
            redirect('anonymous');
        }else{
           redirect('dashboard');
		}
    }
    public function main(){
        $this->load->view('product/products_page');
    }
    public function user_cart(){
        $this->load->view('product/cart_page');
    }
}