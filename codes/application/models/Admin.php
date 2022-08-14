<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model{
    private $query = "SELECT item_name,description,products.id,category,url,stock,price,total_sold 
    FROM products 
    INNER JOIN categories ON categories.id = products.category_id
    INNER JOIN product_images ON product_id = products.id";
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
    public function update_product(){
        
    }
    public function update_category($id,$input){
        return $this->db->query('UPDATE categories SET category = ?, updated_at = NOW() WHERE id = ?',
        array($this->security->xss_clean($input),$this->security->xss_clean($id)));
    }
    public function delete_category($id){
        return $this->db->query('DELETE FROM categories WHERE id = ?',array($this->security->xss_clean($id)));
    }
}