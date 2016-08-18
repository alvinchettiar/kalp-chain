<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Kolkata');
class Home extends CI_Controller {

    //private $data = array();

    public function __construct(){
        parent::__construct();
        $this->load->model("products");

    }
    public function index(){

        $data['main_category'] = $this->homeProductsMain("main_category");
        $data['main_category_menu'] = $this->homeProductsMain("main_category");

        $meta_title = 'Kalp Engineering - Industrial Chain Manufacturers in India';
        $meta_description = 'Kalp Engineering is one of the leading industrial chain manufacturers in India. We roll out all types of transmission & conveyor chains.';
        $meta_keywords = 'kalp chain, kalp engineering, industrial chain manufacturers in india, chain manufacturers in india';

        $meta_arr = array(
            "meta_title" => $meta_title,
            "meta_description" => $meta_description,
            "meta_keywords" => $meta_keywords
        );

        $data['meta'] = $meta_arr;

        $this->load->view('header', $data);
        $this->load->view('home', $data);
        $this->load->view('footer');
    }

    public function about(){
        $data['main_category_menu'] = $this->homeProductsMain("main_category");
        $table_name = 'aboutus';
        $aboutus = $this->products->getAllValues($table_name);
        $data['aboutus'] = json_encode($aboutus[0]['about_content']);

        $meta_title = 'About Kalp Engineering & Rotate - KalpChain.in';
        $meta_description = 'Kalp Engineering is one of the leading industrial chain manufacturers in India. We roll out all types of transmission & conveyor chains as per ISO, BS, ANSI, & Metric standards. Also, customized chains & its related parts in MS, Alloy Steel, SS304/316/430 series, matching with client requirements.';
        $meta_keywords = 'industrial chain manufacturers in india, chain manufacturers in india, kalp chain, kalp engineering, rotate';

        $meta_arr = array(
            "meta_title" => $meta_title,
            "meta_description" => $meta_description,
            "meta_keywords" => $meta_keywords
        );

        $data['meta'] = $meta_arr;

        $this->load->view('header', $data);
        $this->load->view('about');
        $this->load->view('footer', $data);
    }

    public function contact(){
        $data['main_category_menu'] = $this->homeProductsMain("main_category");

        $meta_title = 'Contact Kalp Engineering - KalpChain.in';
        $meta_description = 'Kalp Engineering Mumbai office is located at C/12, Jayshree Indl. Estate, Near H P Gas Godown, Goddev Fatak Rd., Bhayandar (E), Thane - 401105.';
        $meta_keywords = 'kalp engineering address, kalp engineering location, kalp engineering contact';

        $meta_arr = array(
            "meta_title" => $meta_title,
            "meta_description" => $meta_description,
            "meta_keywords" => $meta_keywords
        );

        $data['meta'] = $meta_arr;

        $this->load->view('header', $data);
        $this->load->view('contact');
        $this->load->view('footer', $data);
    }

    public function homeProductsMain($table_name){

        //passing table name to get all rows
        $main_category = $this->products->getAllValues($table_name);
        //print_r($main_category);
        return $main_category;

    }

    public function productGallery(){
        $data['main_category_menu'] = $this->homeProductsMain("main_category");
        $table_name = 'gallery';
        $gallery = $this->products->getAllValues($table_name);
        $data['gallery'] = $gallery;

        $meta_title = 'Kalp Engineering Industrial Chains Product Photos - KalpChain.in';
        $meta_description = 'View Product Photos of all types of Industrail Chains viz. Conveyor Chains & Transmission Chains';
        $meta_keywords = 'kalp engineering, industrail chains';

        $meta_arr = array(
            "meta_title" => $meta_title,
            "meta_description" => $meta_description,
            "meta_keywords" => $meta_keywords
        );

        $data['meta'] = $meta_arr;

        $this->load->view('header', $data);
        $this->load->view('product_gallery', $data);
        $this->load->view('footer');
    }

    public function saveContactForm(){

        $this->load->library('email');
        $this->load->model('site');
        $name = $this->input->post('full_name');
        $email = $this->input->post('email');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');

        $msg = "Name: " . $name . "\n\r";
        $msg .= "Email: " . $email . "\n\r";
        $msg .= "Message: " . $message . "\n\r";

        $form_data = array(
            "name" => $name,
            "email" => $email,
            "subject" => $subject,
            "message" => $message,
        );

        $save_result = $this->site->saveContact($form_data);
        echo $save_result;

        $this->sendemail($subject, $msg);

        //mail('alvin.chettiar@asquarewebstudio.com', 'kalp contact form', 'check message in db');

    }

    public function sendemail($subject, $message){

        $this->load->library('email');
        $config['smtp_host'] = 'smtp.mandrillapp.com';
        $config['smtp_user'] = 'darziwala@gmail.com';
        $config['smtp_pass'] = 'Q6qfz55MBN4YV1UJtq8Lqg';
        $config['smtp_port'] = 587;

        $this->email->initialize($config);

        $this->email->from('admin@kalpchain.in', 'Kalp Engineering');
        $this->email->to('alvin.chettiar@asquarewebstudio.com');

        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();

        echo $this->email->print_debugger();
    }
}
