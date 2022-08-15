<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model{
    /* QUERY TO SEE THE PRODUCT, CATEGORY, SPECIFIC CATEGORY */
    private $query = "SELECT item_name,description,products.id,category,stock,price,total_sold,url,is_main
        FROM specific_categories
        INNER JOIN  products ON  products.id = specific_categories.product_id
        INNER JOIN categories ON categories.id = specific_categories.category_id
        LEFT JOIN product_images ON product_images.product_id = products.id";
    /* GET ALL PRODUCTS AND THE MAIN IMAGE */
    public function get_all_products(){
        return $this->db->query($this->query." WHERE is_main = 1")->result_array();
    }
    /* GET ALL CATEGORIES */
    public function get_all_category(){
        return $this->db->query("SELECT * FROM categories")->result_array();
    }
    /* GET THE CERTAIN PRODUCT WHERE ID */
    public function get_product_id($id){
        return $this->db->query($this->query." WHERE products.id = ?",
        array($this->security->xss_clean($id)))->result_array();
    }

    /* ADD A NEW PRODUCT AND TAKE ITS ID */
    public function add_product($input){
        $product_query = "INSERT INTO products(item_name,description,price,stock,total_sold,created_at) VALUES(?,?,?,?,'0',NOW())";
        $product_values = array($this->security->xss_clean($input['product_name']),
        $this->security->xss_clean($input['product_desc']),
        $this->security->xss_clean($input['price']),
        $this->security->xss_clean($input['stock']));
        return $this->db->insert_id($this->db->query($product_query,$product_values));
    }
    /* THE ADD INPUT FIELD IN THE ADD/EDIT FORM QUERY AND TAKE ITS ID*/
    public function add_category($category){
        return $this->db->insert_id($this->db->query("INSERT INTO category(category,created_at) values(?,NOW())",
        array($this->security->xss_clean($category['product_add_category']))));
    }
    /* SET THE CATEGORY IN SPECIFIC CATEGORY ! */
    public function set_category($product_id,$category_id){
        return $this->db->query("INSERT INTO specific_categories(product_id,category_id) VALUES(?,?)",
        array($this->security->xss_clean($product_id),$this->security->xss_clean($category_id)));
    }
    /* PRODUCT IMAGES */
    public function add_product_image($product_id,$pictures){
        return $this->db->query("INSERT INTO product_images(product_id,url,is_main,created_at) VALUES(?,?,?,NOW())",
        array(
        $this->security->xss_clean($product_id),
        $this->security->xss_clean($pictures['url'],
        $this->security->xss_clean($pictures['img_upload_main_id']))));
    }
    /* UPDATE BUTTON IN EDIT FORM TO UPDATE THE PRODUCT */
    public function update_product(){
        
    }
    /* UPDATE CATEGORY */
    public function update_category($id,$input){
        return $this->db->query('UPDATE categories SET category = ?, updated_at = NOW() WHERE id = ?',
        array($this->security->xss_clean($input),$this->security->xss_clean($id)));
    }
    /* DELETE CATEGORY QUERY */
    public function delete_category($id){
        return $this->db->query('DELETE FROM categories WHERE id = ?',array($this->security->xss_clean($id)));
    }
}