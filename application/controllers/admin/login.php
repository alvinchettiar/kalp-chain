<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 08/03/15
 * Time: 1:39 AM
 */
ini_set("date.timezone", "Asia/Kolkata");
class Login extends MY_Encrypt{

    function __construct(){
        parent::__construct();
        $this->load->library('log');
        $this->load->library('session');

        //custom library for using php $_SESSION
        $this->load->library('nativesession');
    }

    public function index(){

        if($this->nativesession->getSession('admincred'))
        {
            redirect(base_url()."admin/editMainCat", "location");
        }
        $this->load->view("admin/login");
        $this->load->view("admin/footer");
    }

    /**
     *
     */
    public function checkAdminLogin(){

        $this->load->library('CheckEncryption');        //loading encryption library
        $this->load->model("admin_details");
        $email = $this->input->post("email");
        $password = $this->input->post("password");

        //$check_array = array("email" => $email, "password" => $password);
        $check_array = array("email" => $email);
        $login = $this->admin_details->get_user_columns($check_array, "admin");
        $user_pass = $login[0]['password'];                                         //getting encrypted password from db

        //Instantiating the class
        //$hash_obj = new CheckEncryption();
        $stored_decrypt_result = CheckEncryption::valueDecrypt($password, $user_pass);    //decrypting password and checking if TRUE/FALSE

        if((boolean)$stored_decrypt_result === TRUE){
            $encrypt_admin_session = $this->encrypt($login[0]['email']);
            $encrypt_admin_role_session = $this->encrypt($login[0]['role']);
            $encrypt_admin_fname_session = $this->encrypt($login[0]['first_name']);
            $encrypt_admin_lname_session = $this->encrypt($login[0]['last_name']);
            //$decrypt_admin_session = $this->decrypt($encrypt_admin_session);
            $this->nativesession->setSession('admincred', $this->decrypt($encrypt_admin_session));
            $this->nativesession->setSession('admincredrole', $this->decrypt($encrypt_admin_role_session));
            $this->nativesession->setSession('admincredfname', $this->decrypt($encrypt_admin_fname_session));
            $this->nativesession->setSession('admincredlname', $this->decrypt($encrypt_admin_lname_session));

            $login_json = json_encode(array(
                "role" => (int)$this->nativesession->getSession('admincredrole'),
                "first_name" => (int)$this->nativesession->getSession('admincredfname'),
                "last_name" => (int)$this->nativesession->getSession('admincredlname')
                ));
            
            
            //echo CheckEncryption::valueEncrypt($password, $user_pass);
            echo $login_json;

        }else{
            echo json_encode(array("role" => NULL));
            //print_r($stored_decrypt_result);
            //print_r($login);

            $error_log = log_message("error", json_encode($login));
        }

    }

}