<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model{
    /* GET USER ID */
    public function get_ID($id){
        return $this->db->query("SELECT * FROM users WHERE id= ?"
        ,array($this->security->xss_clean($id)))->row_array();
    }
    public function getEmail($query,$input){
        return $this->db->query("SELECT * FROM users WHERE $query= ? ",array($input))->result_array();
    }
     /* SET ADMIN IF YOU WANT TO BE AN ADMIN: DEFAULT ADMIN IS ID = 1 IN USER  */
     public function setAdmin($id){
        return $this->db->query("INSERT INTO admins(user_id,created_at) VALUES(?,?)",array($id,date("Y-m-d, H:i:s")));
    }

    /* GET ADMIN ID IF IT'S MATCH THEN PROCEED TO THE LOGIN PROCESS FOR VALIDATION */
    public function getAdmin($id){
        return $this->db->query("SELECT * FROM admins INNER JOIN users
        ON users.id = admins.user_id WHERE admins.id = ?
        ",array($id))->result_array();
    }
    /* LOGIN PROCESS THIS IS WHERE THE LOGIN STARTS */
    public function login_user($input){
        return $this->db->query(
        "SELECT * FROM users WHERE contact = ? OR email = ?",array( 
            $this->security->xss_clean($input),
            $this->security->xss_clean($input)))->row_array();
            //  return $this->db->query($query, $values);
    }
     /* LOGIN PROCESS WHERE AFTER LOGIN USER FETCH DATA IT WILL PROCEED TO VALIDATION AND FILTER WHOS
    ADMIN AND WHOS NOT */
    public function login_process($input){
        $data = $this->login_user($this->security->xss_clean($input['email_contact_number']));
        $encrypted =  md5($this->security->xss_clean($input['password']) .''. $data['salt']);
        if($data['password'] == $encrypted){
            $arr = array(
                'id'=> $data['id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email']
            );
            $this->session->set_userdata('curr_user',$arr);
            $this->session->set_userdata('logged_in',TRUE);
            if($this->getAdmin($data['id'])){
                $this->session->set_userdata('is_admin',TRUE);
                return 'admin';
            }else{
                return 'user';
            }
        }
    }
    
    /* INSERT NEW ACCOUNT - There's an error on insert_id but it's working (if error is showing only), I
        just ignore it*/
    public function insert_user($user){
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
        $encrypted_password =  md5($this->security->xss_clean($user['password']) .''. $salt);
        $query = "INSERT INTO users(first_name,last_name,contact,email,password,salt,created_at) VALUES (?,?,?,?,?,?,?)";
        $values = array(
            $this->security->xss_clean($user['first_name']),
            $this->security->xss_clean($user['last_name']),
            $this->security->xss_clean($user['contact']),
            $this->security->xss_clean($user['email']),
            $encrypted_password,$salt,
            date("Y-m-d, H:i:s")); 
            
         return $this->db->insert_id($this->db->query($query, $values));
        //return var_dump($query,$values);
    }
    /* AUTO LOG IN AFTER REGISTRATION */
    public function setUser($data,$input){
        $arr = array(
            'id'=> $data,
            'first_name' =>  $this->security->xss_clean($input['first_name']),
            'last_name' =>  $this->security->xss_clean($input['last_name']),
            'contact' =>  $this->security->xss_clean($input['contact'])
        );
        $this->session->set_userdata('curr_user',$arr);
        $this->session->set_userdata('logged_in',TRUE);
    }
     /* AND ALSO YOU NEED TO CHECK IF THE SIGN IN INPUTS ARE FILLED OR NOT */
     public function login_validator(){
        $this->load->library("form_validation");
        $this->form_validation->set_rules("email_contact_number", "Contact Number or Email", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required");
        if(!$this->form_validation->run()){
            return validation_errors();
        }else{
            return 'success';
        }
    }

     /* BUT BEFORE YOU ENTER THE DATA TO DATABASE YOU NEED TO VALIDATE IT FIRST! */
    public function create_validator($email,$contact){
        $this->load->library("form_validation");
        $this->form_validation->set_rules("first_name", "First Name", "trim|required");
        $this->form_validation->set_rules("last_name", "Last Name", "trim|required");
        $this->form_validation->set_rules("email", "Email", "trim|required|valid_email");
        $this->form_validation->set_rules("contact", "Contact Number", "trim|required|min_length[11]|max_length[11]");
        $this->form_validation->set_rules("password", "Password", "trim|required|min_length[8]");
        $this->form_validation->set_rules("confirm_password", "Confirm Password", "trim|required|matches[password]");
        if(!$this->form_validation->run()){
            return validation_errors();
        }else if($this->getEmail('email',$email) == TRUE){
            return 'email';
        }else if($this->getEmail('contact',$contact) == TRUE){
            return 'contact';
        }else{
            return 'success';
        }
    }
        /* VALIDATE THE USER WHETHER ADMIN,USER, AND GUEST */
    public function validate_users(){
        $curr_session = $this->session->userdata('logged_in');
        $admin = $this->session->userdata('is_admin');
        if($curr_session && $admin){
            return 'admin';
		}else if($curr_session && !$admin || $curr_session){
            return 'user';
		}else{
            return 'guest';
        }
    }

}