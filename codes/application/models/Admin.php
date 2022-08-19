<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model{
    /* QUERY TO SEE THE PRODUCT, CATEGORY, SPECIFIC CATEGORY */
    private $query = "SELECT item_name,categories.id as c_id,description,products.id as p_id,category,stock,price,total_sold,url,is_main
        FROM specific_categories
        INNER JOIN  products ON  products.id = specific_categories.product_id
        INNER JOIN categories ON categories.id = specific_categories.category_id
        LEFT JOIN product_images ON product_images.product_id = products.id";
    /* GET ALL PRODUCTS AND THE MAIN IMAGE */
    public function get_all_products($page){
        $record_per_page = 3;
        if(!isset($page)){
            $page = 1;
        }
            $start = ($page-1)* $record_per_page;
        return $this->db->query($this->query." WHERE is_main = 1 LIMIT $start,$record_per_page")->result_array();
    }

    /* GET THE CERTAIN PRODUCT WHERE ID */
    public function get_product_id($id){
        return $this->db->query($this->query." WHERE products.id = ?",
        array($this->security->xss_clean($id)))->result_array();
    }

    /* ADD A NEW PRODUCT AND TAKE ITS ID */
    public function add_product_info($input){
        $product_query = "INSERT INTO products(item_name,description,price,stock,total_sold,created_at) VALUES(?,?,?,?,'0',NOW())";
        $product_values = array($this->security->xss_clean($input['product_name']),
        $this->security->xss_clean($input['product_desc']),
        $this->security->xss_clean($input['product_price']),
        $this->security->xss_clean($input['product_qty']));
        return $this->db->insert_id($this->db->query($product_query,$product_values));
    }

    /* DELETE PRODUCT */
    public function delete_product($product_id){
        $id = $this->security->xss_clean($product_id);
        $this->db->query('DELETE FROM specific_categories WHERE product_id = ?',array($id));
        $this->db->query('DELETE FROM product_images WHERE product_id = ?',array($id));
        $this->db->query('DELETE FROM products WHERE id = ?',array($id));

    }
    /* UPDATE BUTTON IN EDIT FORM TO UPDATE THE PRODUCT */
    public function update_product(){
        
    }
    /* ADD THE PRODUCT TO THE DATABASE */
    public function add_product($inputs,$folder,$category_id){
       $product_id = $this->add_product_info($inputs);
        $this->images($product_id,$inputs,$folder);
        $this->set_category($product_id,$category_id);
    }
      /* SEARCH ITEM PRODUCT */
    public function search_product_item($input){
            return $this->db->query($this->query." WHERE item_name LIKE ? OR id = ? AND is_main = 1",array($this->security->xss_clean($input.'%'),$this->security->xss_clean($input)))->result_array();
    }
    /* SEARCH ORDER ID */
    public function search_order($input){
        return $this->db->query($this->query." WHERE id = ?",array($this->security->xss_clean($input.'%')))->result_array();
    }
    /* GET THE ITEM IMAGES 
    LOOP THE ARRAY OF IMAGES AND FETCH IT ONE BY ONE */
    public function images($product_id,$pictures,$category_name){
        mkdir('assets/img/products/'.$category_name);
        for($i=0; $i<count($_FILES['myFile']);$i++){
            $target_dir = 'assets/img/products/'.$category_name.'/'; // category file name
            $target_file = $target_dir . basename($_FILES['myFile']['name'][$i]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES['myFile']["tmp_name"][$i]);
            if($check !== false) {
                $uploadOk = 1;
            }
            // if (file_exists($target_file)) {
            //    // echo "Sorry, file already exists.";
            //   // $target_file = $target_dir .'dupName_'.$i.'_'. basename($_FILES['myFile']['name'][$i]);
            //     $uploadOk = 0;
            //     }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != 'webp' && $imageFileType != 'jfif' ) {
                $uploadOk = 0;
            }
            if($uploadOk == 1) {
                if(!move_uploaded_file($_FILES['myFile']["tmp_name"][$i], $target_file)){
                   return 'Error';
                }else{
                //    return "Successfully Added The Item Images";
                    $this->db->query("INSERT INTO product_images(product_id,url,is_main,created_at) VALUES(?,?,?,NOW())",
                   array(
                   $this->security->xss_clean($product_id),
                   $this->security->xss_clean('/'.$target_file),
                   $this->security->xss_clean($pictures['img_upload_main_id'][$i])));
                }
            }
        }
    }
    /* ADD UPDATE VALIDATOR */
    public function add_update_validator(){
        $this->load->library("form_validation");
        $this->form_validation->set_rules("product_name", "Item Name", "trim|required|max_length[255]");
        $this->form_validation->set_rules("product_desc", "Description", "trim|required|max_length[255]");
        $this->form_validation->set_rules("product_price", "Price", "trim|required|less_than_equal_to[0]|numeric");
        $this->form_validation->set_rules("product_qty", "Item Quantity", "trim|required|less_than_equal_to[0]|integer");
        if (empty($_FILES['userfile']['name'])){
            $this->form_validation->set_rules('myFile', 'Images', 'required');
        }
        if(!$this->form_validation->run()){
            return validation_errors();
        }else{
            return 'success';
        }
   }


    /*-----------------------------END OF PRODUCT ----------------------------------- */

    /* ------------------------------CATEGORY-------------------------------------- */
     /* GET ALL CATEGORIES */
    public function get_all_category(){
        return $this->db->query("SELECT * FROM categories")->result_array();
    }
    public function category_validate($category){
        return $this->db->query('SELECT count(*) as counts,id FROM categories WHERE category = ?',array($this->security->xss_clean($category)))->row_array();
    }
    /* THE ADD INPUT FIELD IN THE ADD/EDIT FORM QUERY AND TAKE ITS ID*/
    public function add_category($category){
        return $this->db->insert_id($this->db->insert_id($this->db->query("INSERT INTO categories(category,created_at) VALUES(?,NOW())",
        array($this->security->xss_clean($category)))));
    }
    /* SET THE CATEGORY IN SPECIFIC CATEGORY ! */
    public function set_category($product_id,$category_id){
        return $this->db->query("INSERT INTO specific_categories(product_id,category_id) VALUES(?,?)",
        array($this->security->xss_clean($product_id),$this->security->xss_clean($category_id)));
    }
    /* UPDATE CATEGORY */
    public function update_category($id,$input){
        return $this->db->query("UPDATE categories SET category = ?, updated_at = NOW() WHERE id = ?",
        array($this->security->xss_clean($input),$this->security->xss_clean($id)));
    }
    /* DELETE CATEGORY QUERY */
    public function delete_category($id){
        $this->db->query('DELETE FROM specific_categories WHERE category_id = ?',array($this->security->xss_clean($id)));
        return $this->db->query('DELETE FROM categories WHERE id = ?',array($this->security->xss_clean($id)));
    }
    /* -------------------END OF CATEGORY ----------------------- */

    /* ----------------------HISTORY----------------------------- */
   /* status 1 shipping,2 complete,3 pending(user logged in), 0 cancelled
        type 1 will be shipping and 2 for billing */
    private $history_query = "SELECT c.id as c_id,t.id as t_id,g.first_name,t.created_at,address,c.total,status_type FROM transactions as t
        INNER JOIN carts as c ON cart_id = c.id 
        INNER JOIN guests as g ON c.guest_id = g.id
        INNER JOIN addresses as a ON a.guest_id = g.id WHERE add_type = 2";

    /* ALL HISTORY */
    public function get_history(){
        return $this->db->query($this->history_query)->result_array();
    }
    /* -------------------VALIDATIONS----------------------------*/
    public function is_category_exist($inputs,$current_category){
        $result = $this->category_validate($this->security->xss_clean($current_category));
        if(!$current_category){ // not a create new category
            if($result['counts'] != 0){
                $this->add_product($inputs,$current_category,$result['id']);
            }
        }else{ // create a new category
            if($result['counts'] == 0){
                $category_id = $this->Admin->add_category($current_category);
                $this->add_product($inputs,$current_category,$category_id);
            }
        }
    }
    /* UPDATE STATUS TYPE */
    public function get_status($status,$cart_id){
        return $this->db->query("UPDATE carts SET status_type = ?, updated_at = NOW() WHERE id =?"
        ,array($this->security->xss_clean($status),$this->security->xss_clean($cart_id)));
    }
    /* SEARCH THE STATUS AND SEARCH INPUT */
    public function search_status($table,$status,$page){
        $record_per_page = 3;
        if(!isset($page)){
            $page = 1;
        }
            $start = ($page-1)* $record_per_page;
        return $this->db->query($this->history_query." OR $table = ? LIMIT $start,$record_per_page"
        ,array($this->security->xss_clean($status)))->result_array();
    }
    public function order_detail($id){
        return $this->db->query("")->result_array();
    }
}