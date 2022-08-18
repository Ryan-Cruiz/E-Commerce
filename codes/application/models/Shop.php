<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Model{
    private $query = "SELECT item_name,description,products.id,category,stock,price,total_sold,url,is_main
        FROM specific_categories
            INNER JOIN  products ON  products.id = specific_categories.product_id
            INNER JOIN categories ON categories.id = specific_categories.category_id
            LEFT JOIN product_images ON product_images.product_id = products.id WHERE is_main = 1";
    public function get_all_items($page,$order){
        $record_per_page = 3;
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
    $record_per_page = 3;
    if(!isset($page)){
        $page = 1;
    }
        $start = ($page-1)* $record_per_page;
        return $this->db->query($query." $order LIMIT $start,$record_per_page",array($this->security->xss_clean($values)))->result_array();
    }
    /* GET ALL TOTAL PAGE */
    public function the_page(){
        $record_per_page = 3;
        $page = $this->db->query("SELECT count(*) as page FROM products")->row_array();
        $total = ceil($page['page']/ $record_per_page);
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
    public function get_similar_item($id){
        $this->load->model("Admin");
        $data =  $this->Admin->get_product_id($this->security->xss_clean($id));
        return $this->db->query("SELECT products.id,item_name,price,url FROM products
        INNER JOIN specific_categories ON products.id = specific_categories.product_id
        INNER JOIN categories ON specific_categories.category_id = categories.id
        INNER JOIN product_images ON product_images.product_id = products.id
        WHERE NOT products.id = ? AND is_main = 1 AND item_name LIKE '{$data[0]['item_name']}%'",array($this->security->xss_clean($id)),)->result_array();
    }
    /* CART */
    /* ADD ITEM IN SESSION 
	get item id in Shop, check validation
	if validation is false store an item with a key on $item ( associative array ) the item name,qty,
	price,and item_total*/
    public function add_item($id,$inputs){
        $this->load->model("Admin");
        $data = $this->Admin->get_product_id($id);
		$item = $this->session->userdata('all_cart');
		if($this->input->post('order_qty')<=0){
			return;
		}else{
			$item[$id] = ['name' => $data[0]['item_name'],'qty'=>$this->security->xss_clean($inputs),
			'price' => $data[0]['price'],'item_total'=> $this->security->xss_clean($inputs)*$data[0]['price']];
			// echo '<pre>';
			$this->session->set_userdata('all_cart',$item);
			// echo '</pre>';
			$curr_cart = 0;
			$total_price = 0;
			foreach($item as $total){
				$curr_cart = $curr_cart + $total['qty'];
				$total_price += $total['qty'] * $total['price'];
				$this->session->set_userdata('total_cart',$curr_cart);
				$this->session->set_userdata('total_price',$total_price);
			}
	  	echo '<pre>';
  		var_dump($this->session->userdata());
	  	echo '</pre>';
		}
    }
    	/* DESTROY AN ITEM IN SESSION 
	EDIT: I JUST REUSE THIS (this is from shopping spree)
	store all_cart in $items and then unset $items['key'] and run a loop to update the 
	current price,item bought by the user*/
    public function delete_item($id){
        $items = $this->session->userdata('all_cart');
		unset($items[$id]);
		$curr_cart = 0;
		$total_price = 0;
		foreach($items as $total){
			$curr_cart = $curr_cart + $total['qty'];
			$total_price += $total['qty'] * $total['price'];
			$this->session->set_userdata('total_cart',$curr_cart);
			$this->session->set_userdata('total_price',$total_price);
		}
		$this->session->set_userdata('all_cart',$items);
	//  echo '<pre>';
	//  var_dump($this->session->userdata());
	//  echo '</pre>';
	
    }
    /* ADD TRANSACTION INFO */
    public function user_transaction($inputs){
       
    }
    /* PROCESS THE GUEST ITEMS */
    public function guest_transaction($cart_id){
       $items = $this->session->userdata('all_cart');
       foreach($items as  $item){
       $transaction[] = ['item' => $item['name'],'qty' => $item['qty'],
       'price' =>$item['price'],'item_total' =>$item['item_total']];
        }
        $all_items = json_encode($transaction);
        var_dump($all_items);
        $query = "INSERT INTO transactions(item,cart_id,total_item,total_price,created_at) VALUES(?,?,?,?,NOW())";
        $values = array($all_items,$cart_id,
        $this->session->userdata('total_cart'),
        $this->session->userdata('total_price'));
        return $this->db->query($query,$values);
    }
    /* PROCESS THE GUEST TRANSACTION PAYOUT 
     status 1 shipping,2 complete,3 pending(user logged in), 0 cancelled
     type 1 will be shipping and 2 for billing*/
    public function guest_process($inputs){
        $guest_id = $this->insert_guest($inputs);
        $this->insert_address($guest_id,$inputs,1,'_ship');
        $this->insert_address($guest_id,$inputs,2,'_bill');
        $cart_id = $this->add_to_cart($guest_id,1);
        $this->guest_transaction($cart_id);
    }
    /* INSERT FIRST THE GUEST AND TAKE IT'S ID */
    public function insert_guest($inputs){
        return $this->db->insert_id($this->db->query("INSERT INTO guests(first_name,last_name,created_at) VALUES(?,?,NOW())"
        ,array($this->security->xss_clean($inputs['first_name_bill']),$this->security->xss_clean($inputs['last_name_bill']))));
    }
    /* ADD A CART OF USER/GUEST 
        status 1 shipping,2 complete,3 pending(user logged in), 0 cancelled*/
    public function add_to_cart($guest_id,$status){ 
        return $this->db->insert_id($this->db->query("INSERT INTO carts(guest_id,status,total,created_at) VALUES(?,?,?,NOW())"
        ,array($guest_id,$status,$this->security->xss_clean($this->session->userdata('total_price')))));
    }
    /* INSERT ADDRESS WITH TYPE AND THE STRING TYPE I.E '_ship' and '_bill'
        type 1 will be shipping and 2 for billing */
    public function insert_address($guest_id,$inputs,$type,$type_string){ 
        $query = "INSERT INTO addresses(guest_id,address,second_address,city,state,zip,add_type,created_at) VALUES(?,?,?,?,?,?,?,NOW())";
        $values = array($guest_id,$this->security->xss_clean($inputs['address'.$type_string]),
        $this->security->xss_clean($inputs['address2'.$type_string]),$this->security->xss_clean($inputs['city'.$type_string])
        ,$this->security->xss_clean($inputs['state'.$type_string]),$this->security->xss_clean($inputs['zipcode'.$type_string]),$type);
        return $this->db->query($query,$values);
    }
    /* BILLING VALIDATOR */
    public function billing_validator(){
        $this->load->library("form_validation");
        $this->form_validation->set_rules("first_name_ship", "Shipping First Name", "trim|required");
        $this->form_validation->set_rules("last_name_ship", "Shipping Last Name", "trim|required");
        $this->form_validation->set_rules("address_ship", "Shipping Address", "trim|required");
        $this->form_validation->set_rules("address2_ship", "Shipping  Second Address", "trim|required");
        $this->form_validation->set_rules("city_ship", "Shipping City", "trim|required");
        $this->form_validation->set_rules("state_ship", "Shipping State", "trim|required");
        $this->form_validation->set_rules("zipcode_ship", "Shipping Zip Code", "trim|required");
        //breakline
        if(!$this->form_validation->run()){
            return validation_errors();
        }else{
            return 'success';
        }
    }
    public function shipping_validator(){
        $this->form_validation->set_rules("first_name_bill", "Billing First Name", "trim|required");
        $this->form_validation->set_rules("last_name_bill", "BillingLast Name", "trim|required");
        $this->form_validation->set_rules("address_bill", "BillingAddress", "trim|required");
        $this->form_validation->set_rules("address2_bill", "Billing Second Adress", "trim|required");
        $this->form_validation->set_rules("city_bill", "Billing City", "trim|required");
        $this->form_validation->set_rules("state_bill", "Billing State", "trim|required");
        $this->form_validation->set_rules("zipcode_bill", "Billing Zip Code", "trim|required");
        //breakline
        if(!$this->form_validation->run()){
            return validation_errors();
        }else{
            return 'success';
        }   
    }
    public function card_validator(){
        $this->load->library("form_validation");
        $this->form_validation->set_rules("card_number", "Card Number", "trim|required|min_length[19]|max_length[19]");
        $this->form_validation->set_rules("card_cvc", "Card CVC/Security", "trim|required|min_length[3]|max_length[3]");
        $this->form_validation->set_rules("card_month", "Card Expiration Month", "trim|required");
        $this->form_validation->set_rules("card_year", "Card Expiration Year", "trim|required");
        if(!$this->form_validation->run()){
            return validation_errors();
        }else{
            return 'success';
        }
    }
}