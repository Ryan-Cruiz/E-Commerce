<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User');
        $this->load->model('Admin');
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
        $data = $this->Admin->get_all_products();
        $category = $this->Admin->get_all_category();
        $this->page_redirection(array('title'=>'Product Dashboard','data' => $data,'category'=> $category),'product_dashboard');
    }
    public function order_detail(){
        $this->page_redirection(array('title'=>'Order Details'),'order_details');
    }
    /* EDIT VIEW PAGE FORM */
    public function get_edit($id){
        $edit['data'] = $this->Admin->get_product_id($id);
        echo json_encode($edit);
    }
    public function update_item(){

    }
    public function add_item(){

    }

    public function images($category_name,$img_length){
        for($i = 0; $i<$img_length;$i++){
            $target_dir = '/assets/img/'.$category_name.'/'; // category file name
            $target_file = $target_dir . basename($_FILES["myFile"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["myFile"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $uploadOk = 0;
            }
            if($uploadOk == 1) {
                if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $target_file)) {
                } else {
                    $alert_message[] = "Sorry, there was an error uploading your file.";
                }
            }
        }
    }

    /* CATEGORIES */
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
}