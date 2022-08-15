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
		$this->load->model('Shop');
		$view_data['items'] = $this->Shop->get_page(1);
        $this->load->view('product/products_page',$view_data);
    }
	/* GO TO THE USER CART VIEW PAGE */
    public function user_cart(){
        $view_data = array('url'=> '/login',
			'title'=>'Login');
        $this->load->view('product/cart_page',$view_data);
    }
	/* IF ITEM IS CLICK GO TO IT'S INFO VIEW PAGE */
	public function item_page($id){
		$this->load->model('Admin');
		$view_data['items'] = $this->Admin->get_product_id($id);
		$this->load->view('product/item_page',$view_data);
	}
	/* GET TOTAL PAGES */
	public function page(){
		$this->load->model('Shop');
        $view_data['pages'] = $this->Shop->the_page();
		$this->load->view('partials/pagination',$view_data);
    }
	/*
	public function get_the_page($curr_page){
		$this->load->model('Shop');
		$view_data['items'] = $this->Shop->get_page($curr_page);
		$view_data['pages'] = $this->Shop->the_page();
		$this->load->view('product/products_page',$view_data);
	}*/
	/* ADD ITEM IN SESSION */
    public function addCart($id){
		$this->load->model('Shop');
		$data = $this->Shop->getID($id);
		$item = $this->session->userdata('all_cart');
		if($this->input->post($id)<0){
			$this->session->set_flashdata('errors','INVALID');
			redirect('','refresh');
		}else{
			$item[$id] = ['name' => $data['item_name'],'qty'=>$this->security->xss_clean($this->input->post($id)),'price' => $data['price']];
			echo '<pre>';
			$this->session->set_userdata('all_cart',$item);
			echo '</pre>';
			$curr_cart = 0;
			$total_price = 0;
			foreach($item as $total){
				$curr_cart = $curr_cart + $total['qty'];
				$total_price += $total['qty'] * $total['price'];
				$this->session->set_userdata('total_cart',$curr_cart);
				$this->session->set_userdata('total_price',$total_price);
			}
	
	//  echo '<pre>';
	//  var_dump($this->session->userdata());
	//  echo '</pre>';
	redirect('http://reshopping.localhost','refresh');
		}
    }
	/* DESTROY AN ITEM IN SESSION */
	public function destroy($id){
	$view_data = $this->session->userdata('all_cart');
		unset($view_data[$id]);
		$curr_cart = 0;
		$total_price = 0;
		foreach($view_data as $total){
			$curr_cart = $curr_cart + $total['qty'];
			$total_price += $total['qty'] * $total['price'];
			$this->session->set_userdata('total_cart',$curr_cart);
			$this->session->set_userdata('total_price',$total_price);
		}
		$this->session->set_userdata('all_cart',$view_data);
	//  echo '<pre>';
	//  var_dump($this->session->userdata());
	//  echo '</pre>';
		redirect('http://reshopping.localhost/cart','refresh');
	}
}