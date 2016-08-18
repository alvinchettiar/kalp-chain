<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_controller extends CI_Controller {

    //private $data = array();

    public function __construct(){
        parent::__construct();
        $this->load->model("Products");

    }
    public function index(){

        $data['main_category'] = $this->homeProductsMain("main_category");
        $data['main_category_menu'] = $this->homeProductsMain("main_category");

        $this->load->view('header', $data);
        $this->load->view('home', $data);
        $this->load->view('footer');
    }

    public function about(){
        $data['main_category_menu'] = $this->homeProductsMain("main_category");
        $this->load->view('header', $data);
        $this->load->view('about');
        $this->load->view('footer');
    }

    public function homeProductsMain($table_name){

        //passing table name to get all rows
        $main_category = $this->Products->getAllValues($table_name);
        //print_r($main_category);
        return $main_category;

    }
}
