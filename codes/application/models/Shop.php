<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Model{
    private $query = "SELECT item_name,description,products.id,category,stock,price,total_sold,url,is_main
    FROM specific_categories
        INNER JOIN  products ON  products.id = specific_categories.product_id
        INNER JOIN categories ON categories.id = specific_categories.category_id
        LEFT JOIN product_images ON product_images.product_id = products.id WHERE is_main = 1";
      /* GET THE DATA IN THE CURRENT PAGE */
      public function get_page($page){
        $record_per_page = 1;
        if(!isset($page)){
            $page = 1;
        }
            $start = ($page-1)* $record_per_page;
            return $this->db->query($this->query." ORDER BY price ASC LIMIT $start,$record_per_page")->result_array();
        }
        /* GET ALL TOTAL PAGE */
        public function the_page(){
            $record_per_page = 1;
            $page = $this->db->query("SELECT * FROM products")->result_array();
            $total = $this->roundNum(count($page)/ $record_per_page);
            return $total;
        }
        /* ROUND OFF THE the_page $total (also you can use the ceil() function)*/
        public function roundNum($num){
            if($num <= 0.4){
                return $num; 
            }else{
                return $num;
            }
        }
}