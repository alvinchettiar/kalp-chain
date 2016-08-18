<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 22/03/15
 * Time: 2:40 PM
 */

class Products_controller extends CI_Controller {

    private $data = array();

    public function __construct(){
        parent::__construct();
        $this->load->model("products");
    }

    public function index(){
        $seo_url = $this->uri->segment(2);
        /*echo $product_id;
        exit;*/
        $main_category = $this->products->getSingleRow("main_category", "seo_url", $seo_url);
        /*echo "<pre>";
        print_r($main_category);
        exit;*/
        $product_id = $main_category[0]['id'];
        $sub_category = $this->products->getSingleRow("sub_category", "parent_id", $product_id);
        $products_array = $this->products->getAllValues("products");

        $main_category_menu = $this->products->getAllValues("main_category");
        $data['main_category'] = $main_category;
        $data['sub_category'] = $sub_category;
        $data['product_id'] = $product_id;
        $data['main_category_menu'] = $main_category_menu;
        $data['products_array'] = $products_array;

        /*echo "<pre>";
        print_r($main_category);
        exit;*/

        $meta_title = $main_category[0]['meta_title'];
        $meta_description = $main_category[0]['meta_description'];
        $meta_keywords = $main_category[0]['meta_keywords'];

        $meta_arr = array(
            "meta_title" => $meta_title,
            "meta_description" => $meta_description,
            "meta_keywords" => $meta_keywords
        );

        $data['meta'] = $meta_arr;

        $this->load->view("header", $data);
        $this->load->view("products", $data);
        $this->load->view("footer");
    }

    public function getProductDetails(){
        $id = $this->input->post("id");
        //echo $id;
        $data['product_details'] = $this->products->getSingleRow("product_details", "parent_id", $id);
        $data['products'] = $this->products->getSingleRow("products", "id", $id);
        $product_details = $this->load->view("product_details_template", $data, true);
        echo $product_details;

    }


} 