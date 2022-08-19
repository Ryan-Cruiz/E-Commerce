<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User');
        $this->load->model('Admin');
        $this->load->model('Shop');
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
    /* ORDER PAGE DASHBOARD VIEW */
    public function order_page(){
        $history =  $this->Admin->get_history();
        $this->page_redirection(array('title'=>'Order Dashboard','history' => $history),'order_dashboard');
    }
    /* PRODUCT DASHBOARD VIEW */
    public function product_dashboard($page){
        $data = $this->Admin->get_all_products($page);
        $category = $this->Admin->get_all_category();
        $page = $this->Shop->the_page('products',array());
        $this->page_redirection(array('title'=>'Product Dashboard',
        'data' => $data,'category'=> $category,'pages' => $page,'action' => '/products'),'product_dashboard');
    }
    /* PRODUCT DASHBOARD SEARCH ITEM */
    public function search_item(){
        $category = $this->Admin->get_all_category();
        $data = $this->Admin->search_product_item($this->input->post('admin_products_search'));
         $this->page_redirection(array('title'=>'Product Dashboard',
        'data' => $data,'category'=> $category,'pages' => 0,'action' => '/search'),'product_dashboard');
    }
    /* ORDER DETAILS HISTORY */
    public function order_detail(){
        $this->page_redirection(array('title'=>'Order Details'),'order_details');
    }
    /* EDIT VIEW PAGE FORM */
    public function get_edit($id){
        $edit['data'] = $this->Admin->get_product_id($id);
        echo json_encode($edit);
    }
    public function update_item(){
        if(!$this->input->post('product_add_category')){
            $result = $this->Admin->category_validate($this->input->post('curr_category'));
            if($result == 0){
                // error
            }else{

            }
        }else{
            $result = $this->Admin->category_validate($this->input->post('product_add_category'));
            if($result == 0){
                $this->Admin->add_category($this->input->post('product_add_category'));
            }else{
                // error
            }
        }
    }
    /* DELETE A PRODUCT ITEM */
    public function delete_item($id){
        $this->Admin->delete_product($id);
        $this->Admin->get_all_category();
    }

    /* VALIDATE CATEGORY IF EXIST AND ADD ITEM  */
      public function add_item(){
       // $this->output->enable_profiler(true);
        if(!$this->input->post('product_add_category')){ // not a create new category
            $result = $this->Admin->category_validate($this->input->post('curr_category'));
            if($result['counts'] == 0){
                // error
            }else{
                $this->Admin->add_product($this->input->post(),$this->input->post('curr_category'),$result['id']);
            }
        }else{ // create a new category
            $result = $this->Admin->category_validate($this->input->post('product_add_category'));
            if($result['counts'] == 0){
                $category_id = $this->Admin->add_category($this->input->post('product_add_category'));
                $this->Admin->add_product($this->input->post(),$this->input->post('curr_category'),$category_id);
            }else{
                // error
            }
        }
        redirect('products/1');
    }

    /*------------------CATEGORIES--------------------- */

    /* GET CATEGORIES */
    public function get_category(){
        $category['data'] = $this->Admin->get_all_category();
        $category['csrf'] = array('name'=>$this->security->get_csrf_token_name(),
        "hash"=>$this->security->get_csrf_hash());
        echo json_encode($category);
    }
    /* EDIT CATEGORY */
    public function edit_category($id){
      //  $this->output->enable_profiler(true);
        $this->Admin->update_category($id,$this->input->post('category'));
    }
    /* DELETE CATEGORY */
    public function delete_category($id){
        $this->Admin->delete_category($id);
    }
    /* --------------ORDER HISTORY-------------------- */

    public function update_status($id){ // table,status,page
       // $this->output->enable_profiler(true);
       $this->Admin->get_status($this->input->post('admin_orders_update'),$id);
       //redirect('admin');
    }
    public function search_status(){
       // $this->output->enable_profiler(true);
        if($this->input->post('admin_orders_status') == ''){
            $view_data = $this->Admin->search_status('t.id',$this->input->post('admin_orders_search'),1);
        }else{
            $view_data = $this->Admin->search_status('status',$this->input->post('admin_orders_status'),1);
        }
        $this->page_redirection(array('title'=>'Order Dashboard','history' => $view_data),'order_dashboard');
    }
}