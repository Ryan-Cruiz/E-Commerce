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
		$view_data = array('url'=> '/logOut',
			'title'=>'Log Out');
		$view_data['items'] = $this->Shop->get_all_items(1,1); // GET THE FIRST PAGE AND VALUE OF SELECT
		$view_data['categories'] = $this->Shop->count_category_item();
        $this->load->view('product/products_page',$view_data);
    }
	/* GO TO THE USER CART VIEW PAGE */
    public function user_cart(){
		$result = $this->User->validate_users();
		if($result == 'user'){
			$view_data = array('url'=> '/logOut',
			'title'=>'Log Out');
		}else{
			$view_data = array('url'=> '/login',
			'title'=>'Login');
		}
	//	$view_data['data'] = $this->Shop->getAll();
		if($this->session->userdata('all_cart') == TRUE){
			$items = $this->session->userdata('all_cart');
			$view_data['item_bought'] = $items;
		}else{
			$this->session->set_userdata('total_cart',0);
			$this->session->set_userdata('total_price',0);
			$view_data['item_bought'] = array();
		}
        $this->load->view('product/cart_page',$view_data);
    }
	/* IF ITEM IS CLICK GO TO IT'S INFO VIEW PAGE */
	public function item_page($id){
		$result = $this->User->validate_users();
		if($result == 'user'){
			$view_data = array('url'=> '/logOut',
			'title'=>'Log Out');
		}else{
			$view_data = array('url'=> '/login',
			'title'=>'Login');
		}
		$this->load->model('Admin');
		$view_data['similar'] = $this->Shop->get_similar_item($id);
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

	/* ADD ITEM IN SESSION */
    public function add_cart($id){
		$result = $this->User->validate_users();
		if($result == 'user' || $result == 'admin'){
		
		}else{
			$this->Shop->add_item($id,$this->input->post('order_qty'));
			redirect('/shops/item_page/'.$id);
		}
    }
	/* DESTROY AN ITEM IN SESSION */
	public function destroy($id){
		$result = $this->User->validate_users();
		if($result == 'user' || $result == 'admin'){
		
		}else{
			$this->Shop->delete_item($id);
			redirect('/mycart');
		}
	}

	/* STRIPE API PAYMENT METHOD */
	public function handlePayment(){
		$result = $this->Shop->billing_validator();
		if($result != 'success'){
			
			redirect('mycart');
		}else{
			$this->session->set_flashdata('success', 'Payment has been successful. Thanks For Purchasing!');
			
			$items = $this->session->userdata('all_cart');
			require_once('application/libraries/stripe-php/init.php');
			$output ='';
			foreach($items as $item){
				$output .= " |------------------| 
				{$item['name']} |------------------| 
				{$item['qty']} |------------------| 
				{$item['price']} |------------------| 
				{$item['name']}";	
				echo '<br>';
			}
			\Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
			\Stripe\Charge::create([
					"amount" => $this->session->userdata('total_price')*100,
					"currency" => "php",
					"source" => $stripeToken,
					"description" => '|------------------|Item Name |------------------| Quantity |------------------| Per Price |------------------| Item Total Price |------------------| '.$output
			]);
			redirect('payment/success'); 
		}    
    }
}