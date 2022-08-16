<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Shop');
		$this->load->model('User');
	}
    public function index(){
        $curr_session = $this->session->userdata('logged_in');
        if(!$curr_session){
			redirect('anonymous');
        }else{
           redirect('dashboard');
		}
    }
	/* GUEST PAGE */
	public function guest(){
		$result = $this->User->validate_users();
		if($result == 'user'){
			redirect('dashboard');
		}else if($result =='admin'){
			redirect('admin');
		}else{
			$view_data = array('url'=> '/login',
			'title'=>'Login');
			$this->load->model('Shop');
			$view_data['items'] = $this->Shop->get_all_items(1,1);
			$view_data['categories'] = $this->Shop->count_category_item();
			$this->load->view('product/products_page',$view_data);
		}
	}
	/* LOGGED IN USERS */
    public function main(){
		$view_data['items'] = $this->Shop->get_all_items(1,1); // GET THE FIRST PAGE AND VALUE OF SELECT
		$view_data['categories'] = $this->Shop->count_category_item();
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
        $view_data['pages'] = $this->Shop->the_page();
		$this->load->view('partials/pagination',$view_data);
    }
	/* TOTAL PAGE FOR JQUERY TO GET */
	public function get_total_page(){
        $view_data = $this->Shop->the_page();
		echo json_encode($view_data);
	}
	/* GET THE CURRENT PAGE */
	public function get_the_page($curr_page,$order,$category){
		//$this->output->enable_profiler(true);
		/* CHECK THE THIRD PARAMETER IF IT'S  UNDEFINED OR 0 (THIS IS NOT EFFICIENT WAY TO SEARCH BECAUSE
		IT EATS UP 2 QUERY AND THE THIRD PARAMETER IS HANG UP UNDEFINED(I PAY FOR MY CAUSE FOR 
		NOT CHECKING THAT IT CAN RUN ON ONE FORM(MIGHT REFACTOR THIS IF HAVE A TIME)))*/
		if($category == 'undefined' || $category == 0){ 
			$view_data['items'] = $this->Shop->get_all_items($curr_page,$order); 
		}else{
			$view_data['items'] = $this->Shop->order_by($curr_page,$order,$category); // GET THE THIRD PARAMETER
		}
		$this->load->view('partials/items',$view_data);
	}
	/* CART */
	
	/* ADD ITEM IN SESSION */
    public function addCart($id){
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