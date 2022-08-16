<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Model{
    private $query = "SELECT item_name,description,products.id,category,stock,price,total_sold,url,is_main
        FROM specific_categories
            INNER JOIN  products ON  products.id = specific_categories.product_id
            INNER JOIN categories ON categories.id = specific_categories.category_id
            LEFT JOIN product_images ON product_images.product_id = products.id WHERE is_main = 1";
    public function get_all_items($page,$order){
        $record_per_page = 1;
        $value = '';
        if(!isset($page)){
            $page = 1;
        }
        $start = ($page-1)* $record_per_page;
        if($order == 2){
            $value = 'ORDER BY price DESC';
        }else if($order == 3){
            $value = 'ORDER BY total_sold DESC';
        }else{
            $value = 'ORDER BY price ASC';
        }
        return $this->db->query($this->query." $value LIMIT $start,$record_per_page")->result_array();
    }
    public function count_category_item(){
        return $this->db->query("SELECT categories.id,category,count(*) as counts
		    FROM specific_categories
		    INNER JOIN categories ON categories.id = specific_categories.category_id 
		    GROUP BY category")->result_array();
    }
    /* GET THE DATA IN THE CURRENT PAGE */
    public function get_page($query,$values,$page,$order){
    $record_per_page = 1;
    if(!isset($page)){
        $page = 1;
    }
        $start = ($page-1)* $record_per_page;
        return $this->db->query($query." $order LIMIT $start,$record_per_page",array($this->security->xss_clean($values)))->result_array();
    }
    /* GET ALL TOTAL PAGE */
    public function the_page(){
        $record_per_page = 1;
        $page = $this->db->query("SELECT count(*) as page FROM products")->row_array();
        $total = $this->round_num($page['page']/ $record_per_page);
        return $total;
    }
    public function order_by($page,$order,$category){
        $value = '';
        if($order == 2){
            $value = 'ORDER BY price DESC';
        }else if($order == 3){
            $value = 'ORDER BY total_sold DESC';
        }else{
            $value = 'ORDER BY price ASC';
        }
       return $this->get_page($this->query." AND category_id = ?",$this->security->xss_clean($category),$this->security->xss_clean($page),$value);
    }
    /* ROUND OFF THE the_page $total (also you can use the ceil() function)*/
    public function round_num($num){
        if($num <= 0.4){
            return $num; 
        }else{
            return $num;
        }
    }
}