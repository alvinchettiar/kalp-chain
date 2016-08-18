<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 29/03/15
 * Time: 7:35 PM
 */

class Admin_controller extends CI_Controller{

    protected $data;
    private $html_wo;

    public function __construct(){

        parent::__construct();
        $this->load->helper('url');
        $this->load->model("admin_model");
        $this->load->model("admin_details");
        $this->load->library('grocery_CRUD');
        $this->load->library('nativesession');
        $this->load->library('uri');
        /*if(!$this->nativesession->getSession('admincred')){
            redirect(base_url()."admin/login", "location");
        }*/
    }



    public function outputView($viewName, $outputData = null){
        $this->load->view($viewName, $outputData);
    }

    public function _example_output($output = null)
    {
        $this->load->view('example.php',$output);
    }

    public function mainCategoryForm(){
        $this->load->view('admin/header');
        $this->load->view('admin/add_main_category');
        $this->load->view('admin/footer');
    }

    public function subCategoryForm(){

        $data['main_category'] = $this->admin_model->getAllValues('main_category');
        $this->load->view('admin/header');
        $this->load->view('admin/add_sub_category', $data);
        $this->load->view('admin/footer');

    }

    public function productForm(){

        $data['sub_category'] = $this->admin_model->getAllValues('sub_category');
        $this->load->view('admin/header');
        $this->load->view('admin/add_product', $data);
        $this->load->view('admin/footer');

    }

    public function productDetailsForm(){

        $data['products'] = $this->admin_model->getAllValues('products');
        $data['error'] = '';
        $this->load->view('admin/header');
        $this->load->view('admin/add_product_details', $data);
        $this->load->view('admin/footer');

    }

    public function addMainCategory(){

        $title = $this->input->post("title");
        $description = $this->input->post("description");

        $data = array(
            "title" => $title,
            "description" => $description
        );
        $main_category_insert = $this->admin_model->insertRecord($data, "main_category");

    }

    public function addSubCategory(){

        $parent_id = $this->input->post("parent_id");
        $title = $this->input->post("title");
        $description = $this->input->post("description");

        $data = array(
            "parent_id" => $parent_id,
            "title" => $title,
            "description" => $description
        );
        $sub_category_insert = $this->admin_model->insertRecord($data, "sub_category");

    }

    public function addProducts(){

        $parent_id = $this->input->post("parent_id");
        $title = $this->input->post("title");
        $description = $this->input->post("description");

        $data = array(
            "parent_id" => $parent_id,
            "title" => $title,
            "description" => $description
        );
        $products_insert = $this->admin_model->insertRecord($data, "products");

    }

    public function addProductDetails(){

        $parent_id = $this->input->post("parent_id");
        $title = $this->input->post("title");
        $description = $this->input->post("description");

        //echo $description;

//        echo FCPATH."<br>";
//        echo APPPATH."<br>";
//        echo __DIR__."<br>";
        $config['upload_path'] = FCPATH . 'uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['remove_spaces'] = TRUE;
        $config['overwrite'] = FALSE;
        $config['file_name'] = strtotime(date('Y-m-d H:i:s'));
        $config['max_size']	= '500';
        /*$config['max_width']  = '1280';
        $config['max_height']  = '800';*/

        $this->load->library('upload', $config);
         //Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
        $this->upload->initialize($config);
        if ( ! $this->upload->do_upload())
        {
            $error = array('error' => $this->upload->display_errors());
            //print_r($error);
//
            $this->load->view('admin/header');
            $this->load->view('admin/add_product_details', $error);
            $this->load->view('admin/footer');
        }
        else {
            $data = array('upload_data' => $this->upload->data());
            /*print_r($data);
            exit;*/
            $data = array(
                "parent_id" => $parent_id,
                "title" => $title,
                "description" => $description,
                "image_link" => "uploads/" . $data['upload_data']['file_name']
            );
            $products_insert = $this->admin_model->insertRecord($data, "product_details");
            //echo "PRODUCT ID " . $products_insert;

            redirect("admin/add-product-details-form");

//            $this->load->view('admin/header');
//            $this->load->view('admin/add_product_details', $data);
//            $this->load->view('admin/footer');

//        }
        }


    }

    public function editMainCat(){

    try{
        $crud = new grocery_CRUD();

        $crud->set_theme('datatables');
        $crud->set_table('main_category');
        $crud->set_subject('MAIN CATEGORY');
        $crud->required_fields('title');
        $crud->columns('id','title', 'seo_url', 'description', 'meta_title', 'meta_description', 'meta_keywords', 'created_date');
        $crud->edit_fields('title', 'seo_url','description', 'meta_title', 'meta_description', 'meta_keywords');

        $crud->callback_field('seo_url', function($post_array){
            $html_elements = '<input type="text" value="'.$post_array.'" id="seo_url" name="seo_url" readonly> ';
            return $html_elements;
        });


        $output = $crud->render();

        $this->outputView('admin/edit_products', $output);
//            $this->_example_output($output);
    }
    catch(Exception $e){
        show_error($e->getMessage().' --- '.$e->getTraceAsString());
    }

    }

    public function editSubCat(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('sub_category');
            $crud->set_subject('SUB CATEGORY');
            $crud->required_fields('parent_id', 'title');
            $crud->columns('id', 'parent_id', 'title','description', 'created_date');
            $crud->edit_fields('parent_id', 'title','description');
            $crud->set_relation('parent_id', 'main_category', 'title');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);
//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editProducts(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('products');
            $crud->set_subject('PRODUCTS');
            $crud->required_fields('parent_id', 'title');
            $crud->columns('id', 'parent_id', 'title','description', 'created_date');
            $crud->edit_fields('parent_id', 'title','description');
            $crud->set_relation('parent_id', 'sub_category', 'title');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);
//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editProductsDetails(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('product_details');
            $crud->set_subject('PRODUCT DETAILS');
            $crud->required_fields('parent_id', 'title');
            $crud->columns('id', 'parent_id', 'title', 'description', 'image_link', 'product_image', 'created_date');
            $crud->edit_fields('parent_id', 'title','description', 'image_link', 'product_image');
            $crud->set_relation('parent_id', 'products', 'title');
            $crud->set_field_upload('image_link', 'uploads/');
            $crud->set_field_upload('product_image', 'uploads/product_image/');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
            log_message('error', $e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editProductGallery(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('gallery');
            $crud->set_subject('PRODUCT GALLERY');
            $crud->required_fields('title', 'image_link');
            $crud->columns('id', 'title', 'image_link', 'created_date');
            $crud->edit_fields('title', 'image_link');
            $crud->set_field_upload('image_link', 'uploads/products-gallery');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editSuppliers(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('suppliers');
            $crud->set_subject('SUPPLIERS LIST');
            $crud->required_fields('supplier_name');
            $crud->columns('supplier_name', 'address', 'district', 'pincode', 'website', 'contact_person', 'cp_mobile', 'email', 'std_code', 'tel_no1', 'tel_no2', 'tel_no3', 'vat', 'cst', 'pan', 'excise_no', 'service_tax_no', 'lbt_no');
            $crud->edit_fields('supplier_name', 'address', 'district', 'pincode', 'website', 'contact_person', 'cp_mobile', 'email', 'std_code', 'tel_no1', 'tel_no2', 'tel_no3', 'vat', 'cst', 'pan', 'excise_no', 'service_tax_no', 'lbt_no');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editClients(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('clients');
            $crud->set_subject('CLIENTS LIST');
            $crud->required_fields('client_name');
            $crud->columns('client_name', 'address', 'district', 'pincode', 'website', 'contact_person', 'cp_mobile', 'email', 'std_code', 'tel_no1', 'tel_no2', 'tel_no3', 'vat', 'cst', 'pan', 'excise_no');
            $crud->edit_fields('client_name', 'address', 'district', 'pincode', 'website', 'contact_person', 'cp_mobile', 'email', 'std_code', 'tel_no1', 'tel_no2', 'tel_no3', 'vat', 'cst', 'pan', 'excise_no');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editContacts(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('contacts');
            $crud->set_subject('CONTACTS LIST');
            $crud->required_fields('type');
            $crud->columns('type', 'first_name', 'last_name', 'area_code', 'tel_no1', 'tel_no2', 'tel_no3', 'mobile_1', 'mobile_2');
            $crud->edit_fields('type', 'first_name', 'last_name', 'area_code', 'tel_no1', 'tel_no2', 'tel_no3', 'mobile_1', 'mobile_2');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }

    }

    public function editAbout(){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('aboutus');
            $crud->set_subject('ABOUT US');
            $crud->required_fields('abount_content');
            $crud->columns('about_content', 'created_date', 'updated_date');
            $crud->edit_fields('about_content');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function editEnquiry(){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('enquiry');
            $crud->set_subject('INQUIRY');
            $crud->columns('enquiry_no', 'client_id', 'date', 'item', 'pitch', 'qty', 'qty_type', 'MOC');
            $crud->add_fields('enquiry_no', 'client_id', 'date', 'item', 'pitch', 'qty', 'qty_type', 'MOC');
            $crud->edit_fields('client_id', 'date', 'item', 'pitch', 'qty', 'qty_type', 'MOC');
            $crud->required_fields('enquiry_no', 'client_id', 'date', 'item', 'pitch', 'qty', 'qty_type', 'MOC');
            $crud->set_relation('client_id', 'clients', 'client_name');
            $crud->set_relation('qty_type', 'qty', 'type');
            $crud->add_action('Create Quotation', '', '', 'ui-icon-plus', array($this, 'generateQuote'));

            $crud->callback_add_field('enquiry_no', array($this, 'generateEnquiryNo'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function generateQuote($primary_key, $row){
        return site_url('admin/createQuote/add/').'?enquiry_no='.$row->enquiry_no;
    }

    public function editQuotation($primary_key, $row){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->where('enquiry_no', $row->enquiry_no);
            $crud->set_table('quotation');
            $crud->set_subject('QUOTATION');
            $crud->columns('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->add_fields('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->edit_fields('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->required_fields('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->set_relation('client_id', 'clients', 'client_name');
            $crud->set_relation('enquiry_no', 'enquiry', 'enquiry_no');

            $callback_response = $crud->callback_add_field('enquiry_no', array($this, 'generateEnquiryNo'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    //creating quotation for selected Enquiry
    public function createQuote(){
        try{

            //getting quotation id from URI
            $quote_id = $this->uri->segment(4);

            //getting enquiry number from the URL
            $this->data['quote'] = $this->admin_details->get_user_column_one('id', $quote_id, 'quotation');
            $this->data['enquiry_no'] = ($this->input->get('enquiry_no')) ? @$this->input->get('enquiry_no') : @$this->data['quote'][0]['enquiry_no'];
            $this->data['enquiry_details'] = @$this->admin_details->get_user_column_one('enquiry_no', $this->data['enquiry_no'], 'enquiry');
            $this->data['client_details'] = @$this->admin_details->get_user_column_one('id', $this->data['enquiry_details'][0]['client_id'], 'clients');
            $this->data['enquiry_qty_type'] = @$this->data['enquiry_details'][0]['qty_type'];

            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('quotation');
            $crud->set_subject('QUOTATION');
            $crud->columns('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->add_fields('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date', 'pitch', 'moc', 'link', 'attachment', 'roller', 'wip', 'bush', 'pin', 'qty', 'rate', 'pf', 'discount', 'taxes', 'mvat_cst', 'delivery', 'weight', 'notes');
            $crud->edit_fields('quote_edit', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date', 'pitch', 'moc', 'link', 'attachment', 'roller', 'wip', 'bush', 'pin', 'qty', 'rate', 'pf', 'discount', 'taxes', 'mvat_cst', 'delivery', 'weight', 'notes');
            $crud->required_fields('client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->set_relation('client_id', 'clients', 'client_name');
            //$crud->set_relation('enquiry_no', 'enquiry', 'enquiry_no');


            //getting values of current quotation from quotation_miscellaneous table if record exists

            if(!empty($this->data['enquiry_details'])) {
                $this->data['quotation_details'] = $this->admin_details->get_user_column_one('enquiry_no', $this->data['enquiry_no'], 'quotation');
                if(!empty($this->data['quotation_details'])) {
                    $this->data['quotation_no'] = $this->data['quotation_details'][0]['quotation_no'];
                }
            }

            $crud->callback_field('enquiry_no', function($post_array){

                $enq_no = ($this->data['enquiry_no']) ? $this->data['enquiry_no'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$enq_no.'" name="enquiry_no" readonly>';
            });

            $crud->callback_field('client_id', function(){
                return '<input type="text" maxlength="200" value="'.@$this->data['enquiry_details'][0]['client_id'].'" name="client_id" readonly> | Client Name : '.@$this->data['client_details'][0]['client_name'];
            });
            $crud->callback_field('enquiry_date', function(){
                return '<input type="text" maxlength="50" value="'.@$this->data['enquiry_details'][0]['date'].'" name="enquiry_date" readonly>';
            });

            $crud->callback_field('quotation_no', function($post_array){
                return '<input type="text" maxlength="200" value="'.$post_array.'" name="quotation_no" readonly>';
            });

            /*foreach($this->data['all_miscellaneous_fields'] as $this->data['key_mis'] => $this->data['val_mis']) {
                echo $this->data['val_mis'];
                $val_mis =  $this->data['val_mis'];
                $crud->callback_field($this->data['all_miscellaneous_fields'][$this->data['key_mis']], function ($post_array) {

                    if(in_array($this->data['val_mis'], $this->data['single_input_fields'])) {
                        $html_elements = '<input type="text" maxlength="200" value="' . $post_array . '" name="'.$this->data['val_mis'].'" style="width:200px;"> ';
                    }
                    else{
                        $html_elements = '<input type="text" maxlength="200" value="' . $post_array . '" name="'.$this->data['val_mis'].'" style="width:200px;"> <input type="text" maxlength="200" value="' . $post_array . '" name="'.$this->data['key_mis'].'" style="width:200px;">';
                    }

                    $pitch_types = $this->getQuoteMiscellaneous('pitch');

                    $html_elements .= '<select name="pitch_type" style="width:100px;">';
                    foreach ($pitch_types as $key => $val) {
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                    $html_elements .= '</select>';

                    return $html_elements;
                });

            }*/

            //checking if quotation exists in db related to the current enquiry number. if exists generating quotation miscellaneous values
            if(isset($this->data['quotation_no'])){
                $this->data['quotation_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $this->data['quotation_no'], 'quotation_miscellaneous');
                if(!empty($this->data['quotation_miscellaneous'])) {
                    $this->data['pitch'] = $this->data['quotation_miscellaneous'][0]['pitch'];
                    $this->data['pitch_type'] = $this->data['quotation_miscellaneous'][0]['pitch_type'];
                    $this->data['pitch_details'] = $this->data['quotation_miscellaneous'][0]['pitch_details'];
                    $this->data['moc'] = $this->data['quotation_miscellaneous'][0]['moc'];
                    $this->data['moc_type'] = $this->data['quotation_miscellaneous'][0]['moc_type'];
                    $this->data['link'] = $this->data['quotation_miscellaneous'][0]['link'];
                    $this->data['link2'] = $this->data['quotation_miscellaneous'][0]['link2'];
                    $this->data['attachment'] = $this->data['quotation_miscellaneous'][0]['attachment'];
                    $this->data['attachment_details'] = $this->data['quotation_miscellaneous'][0]['attachment_details'];
                    $this->data['roller'] = $this->data['quotation_miscellaneous'][0]['roller'];
                    $this->data['roller2'] = $this->data['quotation_miscellaneous'][0]['roller2'];
                    $this->data['roller_details'] = $this->data['quotation_miscellaneous'][0]['roller_details'];
                    $this->data['wip'] = $this->data['quotation_miscellaneous'][0]['wip'];
                    $this->data['bush'] = $this->data['quotation_miscellaneous'][0]['bush'];
                    $this->data['bush2'] = $this->data['quotation_miscellaneous'][0]['bush2'];
                    $this->data['bush_type'] = $this->data['quotation_miscellaneous'][0]['bush_type'];
                    $this->data['pin'] = $this->data['quotation_miscellaneous'][0]['pin'];
                    $this->data['pin2'] = $this->data['quotation_miscellaneous'][0]['pin2'];
                    $this->data['pin_type'] = $this->data['quotation_miscellaneous'][0]['pin_type'];
                    $this->data['qty'] = $this->data['quotation_miscellaneous'][0]['qty'];
                    $this->data['qty_type'] = $this->data['quotation_miscellaneous'][0]['qty_type'];
                    $this->data['rate'] = $this->data['quotation_miscellaneous'][0]['rate'];
                    $this->data['rate_type'] = $this->data['quotation_miscellaneous'][0]['rate_type'];
                    $this->data['pf'] = $this->data['quotation_miscellaneous'][0]['pf'];
                    $this->data['discount'] = $this->data['quotation_miscellaneous'][0]['discount'];
                    $this->data['taxes'] = $this->data['quotation_miscellaneous'][0]['taxes'];
                    $this->data['mvat_cst'] = $this->data['quotation_miscellaneous'][0]['mvat_cst'];
                    $this->data['mvat_cst_type'] = $this->data['quotation_miscellaneous'][0]['mvat_cst_type'];
                    $this->data['delivery'] = $this->data['quotation_miscellaneous'][0]['delivery'];
                    $this->data['weight'] = $this->data['quotation_miscellaneous'][0]['weight'];
                    $this->data['weight_type'] = $this->data['quotation_miscellaneous'][0]['weight_type'];
                    $this->data['notes'] = $this->data['quotation_miscellaneous'][0]['notes'];
                    $this->data['additional_fields'] = $this->data['quotation_miscellaneous'][0]['additional_fields'];
                }
            }

            //print_r($this->data['quotation_miscellaneous']);

            $crud->callback_field('pitch', function ($post_array) {

                $pitch = (isset($this->data['pitch'])) ? $this->data['pitch'] : $post_array;
                $pitch_details = (isset($this->data['pitch_details'])) ? $this->data['pitch_details'] : '';

                $html_elements = '<input type="text" maxlength="200" value="' . $pitch . '" name="pitch" style="width:200px;"> ';

                $pitch_types = $this->getQuoteMiscellaneous('pitch');

                $html_elements .= '<select name="pitch_type" style="width:100px;">';
                foreach ($pitch_types as $key => $val) {
                    if(@$this->data['pitch_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select>';
                $html_elements .= ' <input type="text" maxlength="200" value="' . $pitch_details . '" name="pitch_details" style="width:200px;"> ';

                return $html_elements;
            });

            $crud->callback_field('moc', function ($post_array) {

                $moc = (isset($this->data['moc'])) ? $this->data['moc'] : $post_array;
                $html_elements = '<input type="text" maxlength="200" value="' . $moc . '" name="moc" style="width:200px;"> ';

                $pitch_types = $this->getQuoteMiscellaneous('moc');

                $html_elements .= '<select name="moc_type" style="width:100px;">';
                foreach ($pitch_types as $key => $val) {
                    if(@$this->data['moc_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select>';

                return $html_elements;
            });

            $crud->callback_field('link', function ($post_array) {
                $link = (isset($this->data['link'])) ? $this->data['link'] : $post_array;
                $link2 = (isset($this->data['link2'])) ? $this->data['link2'] : '';
                $html_elements = '<input type="text" maxlength="200" value="' . $link . '" name="link" style="width:200px;"> <input type="text" maxlength="200" value="' . $link2 . '" name="link2" style="width:200px;"> mm';

                return $html_elements;
            });

            $crud->callback_field('attachment', function ($post_array) {
                $attachment = (isset($this->data['attachment'])) ? $this->data['attachment'] : $post_array;
                $attachment_details = (isset($this->data['attachment_details'])) ? $this->data['attachment_details'] : '';
                $html_elements = '<input type="text" maxlength="200" value="' . $attachment . '" name="attachment" style="width:200px;"> mm thick';
                $html_elements .= ' <input type="text" maxlength="200" value="' . $attachment_details . '" name="attachment_details" style="width:200px;">';
                return $html_elements;
            });

            $crud->callback_field('roller', function ($post_array) {
                $roller = (isset($this->data['roller'])) ? $this->data['roller'] : $post_array;
                $roller2 = (isset($this->data['roller2'])) ? $this->data['roller2'] : '';
                $roller_details = (isset($this->data['roller_details'])) ? $this->data['roller_details'] : '';

                $html_elements = '<input type="text" maxlength="200" value="' . $roller . '" name="roller" style="width:200px;"> mm <img src="'.base_url().'extras/img/dia.png" align="center" /> X <input type="text" maxlength="200" value="' . $roller2 . '" name="roller2" style="width:200px;"> mm long';
                $html_elements .= ' <input type="text" maxlength="200" value="' . $roller_details . '" name="roller_details" style="width:200px;">';
                return $html_elements;
            });

            $crud->callback_field('wip', function ($post_array) {
                $wip = (isset($this->data['wip'])) ? $this->data['wip'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $wip . '" name="wip" style="width:200px;"> mm';
                return $html_elements;
            });

            $crud->callback_field('bush', function ($post_array) {
                $bush = (isset($this->data['bush'])) ? $this->data['bush'] : $post_array;
                $bush2 = (isset($this->data['bush2'])) ? $this->data['bush2'] : '';

                $html_elements = '<input type="text" maxlength="200" value="' . $bush . '" name="bush" style="width:200px;"> mm <img src="'.base_url().'extras/img/dia.png" align="center" /> X <input type="text" maxlength="200" value="' . $bush2 . '" name="bush2" style="width:200px;"> mm long ';

                $pitch_types = $this->getQuoteMiscellaneous('bush');

                $html_elements .= '<select name="bush_type" style="width:100px;">';
                foreach ($pitch_types as $key => $val) {
                    if(@$this->data['bush_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select>';

                return $html_elements;
            });

            $crud->callback_field('pin', function ($post_array) {
                $pin = (isset($this->data['pin'])) ? $this->data['pin'] : $post_array;
                $pin2 = (isset($this->data['pin2'])) ? $this->data['pin2'] : '';

                //checking for additional fields
                $additional_fields = @json_decode($this->data['additional_fields'], true);
                $additional_field_count = count($additional_fields);
                $html_elements = '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" /><input type="text" maxlength="200" value="' . $pin . '" name="pin" style="width:200px;"> mm <img src="'.base_url().'extras/img/dia.png" align="center" /> X <input type="text" maxlength="200" value="' . $pin2 . '" name="pin2" style="width:200px;"> mm long ';


                $pitch_types = $this->getQuoteMiscellaneous('pin');

                $html_elements .= '<select name="pin_type" style="width:100px;">';
                foreach ($pitch_types as $key => $val) {
                    if(@$this->data['pin_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select><br><br>';

                //adding additional fields to the edit form
                if(!empty($additional_fields)){

                    foreach($additional_fields as $key => $val){
                        $key++;
                        $title = $val['title'];
                        $value = $val['value'];
                        $html_elements .= '<input type="text" maxlength="200" value="' . $title . '" name="title'.$key.'" id="new_title'.$key.'" style="width:200px;"> <input type="text" maxlength="200" value="' . $value . '" name="new_value'.$key.'" id="new_value'.$key.'"  style="width:200px;"><br><br>';

                    }
                }

                return $html_elements;
            });

            $crud->callback_field('qty', function ($post_array) {

                //$client_enquiry_qty = $this->admin_details->get_user_column_one('qty', 'Rs/Ft', 'rate');
                $qty = (isset($this->data['qty'])) ? $this->data['qty'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $qty . '" name="qty" style="width:200px;"> ';

                $pitch_types = $this->getQuoteMiscellaneous('qty');

                $html_elements .= '<select name="qty_type" style="width:100px;" >';
                foreach ($pitch_types as $key => $val) {
                    /*if(@$this->data['qty_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{*/
                        if(@$this->data['enquiry_qty_type']==$val['id']) {
                            $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                        }
                        else{
                            $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                        }
                    //}
                }
                $html_elements .= '</select>';

                return $html_elements;
            });

            $crud->callback_field('rate', function ($post_array) {
                $rate = (isset($this->data['rate'])) ? $this->data['rate'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $rate . '" name="rate" style="width:200px;"> ';

                $pitch_types = $this->getQuoteMiscellaneous('rate');
                $qty_types = $this->getQuoteMiscellaneous('qty');
                $html_elements .= '<select name="rate_type" style="width:100px;">';
                foreach ($qty_types as $key => $val) {
                    if(@$this->data['enquiry_qty_type']==$val['id']){
                        switch($val['type']){
                            case 'Ft':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Rs/Ft', 'rate');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                            case 'Kgs':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Rs/Kgs', 'rate');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                            case 'Mtr':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Rs/Mtr', 'rate');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                            case 'no':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Rs/No', 'rate');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                        }
                    }
                    /*if(@$this->data['rate_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }*/
                }
                $html_elements .= '</select> Ex-Factory';

                return $html_elements;
            });

            $crud->callback_field('pf', function ($post_array) {
                $pf = (isset($this->data['pf'])) ? $this->data['pf'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $pf . '" name="pf" style="width:200px;"> Extra';

                return $html_elements;
            });

            $crud->callback_field('discount', function ($post_array) {
                $discount = (isset($this->data['discount'])) ? $this->data['discount'] : $post_array;
                $html_elements = '<input type="text" maxlength="200" value="' . $discount . '" name="discount" style="width:200px;"> (in %)';
                return $html_elements;
            });

            $crud->callback_field('taxes', function ($post_array) {
                $taxes = (isset($this->data['taxes'])) ? $this->data['taxes'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="12.5" name="taxes" style="width:200px;" readonly> %';

                return $html_elements;
            });

            $crud->callback_field('mvat_cst', function ($post_array) {
                $mvat_cst = (isset($this->data['mvat_cst'])) ? $this->data['mvat_cst'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $mvat_cst . '" name="mvat_cst" style="width:200px;"> ';

                $pitch_types = $this->getQuoteMiscellaneous('mvat_cst');

                $html_elements .= '<select name="mvat_cst_type" style="width:100px;">';
                foreach ($pitch_types as $key => $val) {

                    if(@$this->data['mvat_cst_type']==$val['id']) {
                        //echo "IN IF";
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        //echo "IN else";
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select>';

                return $html_elements;
            });

            $crud->callback_field('delivery', function ($post_array) {
                $delivery = (isset($this->data['delivery'])) ? $this->data['delivery'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $delivery . '" name="delivery" style="width:200px;"> ';
                $delivery_types = $this->getQuoteMiscellaneous('delivery');

                $html_elements .= '<select name="mvat_cst_type" style="width:100px;">';
                foreach ($delivery_types as $key => $val) {

                    if(@$this->data['delivery_type']==$val['id']) {
                        //echo "IN IF";
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        //echo "IN else";
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select>';

                return $html_elements;
            });

            $crud->callback_field('weight', function ($post_array) {
                $weight = (isset($this->data['weight'])) ? $this->data['weight'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $weight . '" name="weight" style="width:200px;"> ';
                $pitch_types = $this->getQuoteMiscellaneous('weight');
                $qty_types = $this->getQuoteMiscellaneous('qty');
                $html_elements .= '<select name="weight_type" style="width:100px;">';
                foreach ($qty_types as $key => $val) {
                    if(@$this->data['enquiry_qty_type']==$val['id']){
                        switch($val['type']){
                            case 'Ft':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Kgs/Ft', 'weight');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                            case 'Kgs':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Kgs/Kgs', 'weight');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                            case 'Mtr':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Kgs/Mtr', 'weight');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                            case 'no':
                                $rate_details = $this->admin_details->get_user_column_one('type', 'Kgs/No', 'weight');
                                $html_elements .= '<option value="' . @$rate_details[0]['id'] . '" selected>' . @$rate_details[0]['type'] . '</option>';
                                break;
                        }
                    }
                }
                /*foreach ($pitch_types as $key => $val) {
                    if(@$this->data['weight_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }*/
                $html_elements .= '</select>';
                return $html_elements;
            });

            $crud->callback_field('notes', function ($post_array) {
                $notes = (isset($this->data['notes'])) ? $this->data['notes'] : $post_array;

                $html_elements = '<textarea name="notes" cols="100" rows="50" style="resize: none;">'.$notes.'</textarea>';

                return $html_elements;
            });

            $crud->callback_edit_field('quote_edit', function(){
                return '<input type="hidden" value="editform" name="quote_edit">';
            });
            $crud->callback_add_field('quotation_no', array($this, 'generateQuotationNo'));

            $crud->callback_insert(array($this, 'saveQuotation'));
            $crud->callback_update(array($this, 'saveQuotation'));

            //adding a print view button
            //if($this->uri->segment(3) == 'edit') {
                $crud->add_action('Print Preview', '', 'admin/printPreview', 'ui-icon-plus');
            //}

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }


    }

    public function saveQuotation($post_array){

            /*echo "<pre>";
            print_r($post_array);
            exit;*/
            $quotation_no = $post_array['quotation_no'];
            $client_id = $post_array['client_id'];
            $enquiry_no = $post_array['enquiry_no'];
            $enquiry_date = $post_array['enquiry_date'];
            $date = $post_array['date'];



            $pitch = $post_array['pitch'];
            $pitch_details = $post_array['pitch_details'];
            $moc = $post_array['moc'];
            $attachment = $post_array['attachment'];
            $attachment_details = $post_array['attachment_details'];
            $roller = $post_array['roller'];
            $roller2 = $post_array['roller2'];
            $roller_details = $post_array['roller_details'];
            $wip = $post_array['wip'];
            $qty = $post_array['qty'];
            $rate = $post_array['rate'];
            $pf = $post_array['pf'];
            $discount = $post_array['discount'];
            $taxes = $post_array['taxes'];
            $mvat_cst = $post_array['mvat_cst'];
            $delivery = $post_array['delivery'];
            $weight = $post_array['weight'];
            $notes = $post_array['notes'];
            $bush = $post_array['bush'];
            $bush2 = $post_array['bush2'];
            $link = $post_array['link'];
            $link2 = $post_array['link2'];
            $pin = $post_array['pin'];
            $pin2 = $post_array['pin2'];

            $pitch_type = $post_array['pitch_type'];
            $moc_type = $post_array['moc_type'];
            $bush_type = $post_array['bush_type'];
            $pin_type = $post_array['pin_type'];
            $qty_type = $post_array['qty_type'];
            $rate_type = $post_array['rate_type'];
            $mvat_cst_type = $post_array['mvat_cst_type'];
            $weight_type = $post_array['weight_type'];

        //looping/collecting dynamically generated data
        $new_record_count = $post_array['new_record_count'];
        for($i = 1; $i <= $new_record_count; $i++){
            $additional_fields[] = array("title" => $post_array['title'.$i], "value" => $post_array['new_value'.$i]);
        }

        //print_r();
        //exit;

        $quotation_vals = array(
            'quotation_no' => $quotation_no,
            'client_id' => $client_id,
            'enquiry_no' => $enquiry_no,
            'enquiry_date' => $enquiry_date,
            'date' => $date

        );

        $quotation_miscellaneous_vals_add =  array(
            "quotation_no" => $quotation_no,
            'pitch' => $pitch,
            'pitch_details' => $pitch_details,
            'moc' => $moc,
            'attachment' => $attachment,
            'attachment_details' => $attachment_details,
            'roller' => $roller,
            'roller2' => $roller2,
            'roller_details' => $roller_details,
            'wip' => $wip,
            'qty' => $qty,
            'rate' => $rate,
            'pf' => $pf,
            'discount' => $discount,
            'taxes' => $taxes,
            'mvat_cst' => $mvat_cst,
            'delivery' => $delivery,
            'weight' => $weight,
            'notes' => $notes,
            'link' => $link,
            'link2' => $link2,
            'bush' => $bush,
            'bush2' => $bush2,
            'pin' => $pin,
            'pin2' => $pin2,
            'pitch_type' => $pitch_type,
            'moc_type' => $moc_type,
            'bush_type' => $bush_type,
            'pin_type' => $pin_type,
            'qty_type' => $qty_type,
            'rate_type' => $rate_type,
            'mvat_cst_type' => $mvat_cst_type,
            'weight_type' => $weight_type,
            'additional_fields' => @json_encode($additional_fields)

        );

        $quotation_miscellaneous_vals_edit =  array(
            'pitch' => $pitch,
            'pitch_details' => $pitch_details,
            'moc' => $moc,
            'attachment' => $attachment,
            'attachment_details' => $attachment_details,
            'roller' => $roller,
            'roller2' => $roller2,
            'roller_details' => $roller_details,
            'wip' => $wip,
            'qty' => $qty,
            'rate' => $rate,
            'pf' => $pf,
            'discount' => $discount,
            'taxes' => $taxes,
            'mvat_cst' => $mvat_cst,
            'delivery' => $delivery,
            'weight' => $weight,
            'notes' => $notes,
            'link' => $link,
            'link2' => $link2,
            'bush' => $bush,
            'bush2' => $bush2,
            'pin' => $pin,
            'pin2' => $pin2,
            'pitch_type' => $pitch_type,
            'moc_type' => $moc_type,
            'bush_type' => $bush_type,
            'pin_type' => $pin_type,
            'qty_type' => $qty_type,
            'rate_type' => $rate_type,
            'mvat_cst_type' => $mvat_cst_type,
            'weight_type' => $weight_type,
            'additional_fields' => @json_encode($additional_fields)

        );

        if(isset($post_array['quote_edit'])){
            $this->db->where('quotation_no', $quotation_no);
            $this->db->update('quotation', $quotation_vals);
            $this->db->where('quotation_no', $quotation_no);
            $this->db->update('quotation_miscellaneous', $quotation_miscellaneous_vals_edit);
        }
        else {
            $this->db->insert('quotation', $quotation_vals);
            $this->db->insert('quotation_miscellaneous', $quotation_miscellaneous_vals_add);
        }

        /*echo "<pre>";
        print_r($result_quote);
        print_r($result_quote_miscellaneous);*/

    }

    protected function getQuoteMiscellaneous($type){

        $this->data['miscellaneous_type'] = $this->admin_model->getAllValues($type);
        return $this->data['miscellaneous_type'];
        /*print_r($this->data['main_category']);
        exit;*/

    }

    /**
     * @return string
     */
    public function generateEnquiryNo(){
        $last_enquiry_details = $this->admin_model->getLastId('enquiry');
        $last_enq_id = (!empty($last_enquiry_details)) ? $last_enquiry_details[0]['id'] + 1 : 1;
        $data = 'KALPENQ'.date('my').str_pad($last_enq_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="enquiry_no" readonly>';
    }

    /**
     * @return string
     */
    public function generateWorkOrderNo(){
        $last_enquiry_details = $this->admin_model->getLastId('po_client');
        $last_enq_id = (!empty($last_enquiry_details)) ? $last_enquiry_details[0]['id'] + 1 : 1;
        $data = 'KALPWO'.date('my').str_pad($last_enq_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="wo_no" readonly>';
    }

    /**
     * @return string
     */
    public function generateQuotationNo(){
        $last_quote_details = $this->admin_model->getLastId('quotation');
        $last_quote_id = (!empty($last_quote_details)) ? $last_quote_details[0]['id'] + 1 : 1;
        $data = 'KALPQUOTE'.date('my').str_pad($last_quote_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="quotation_no" readonly>';
    }

    public function printPreview(){
        $quotation_id = $this->uri->segment(3);
        $this->data['print_id'] = $quotation_id;
        $this->data['print_func'] = 'printQuote';
        $this->data['quote'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
        $quotation_no = $this->data['quote'][0]['quotation_no'];
        $enquiry_no = $this->data['quote'][0]['enquiry_no'];
        $this->data['enquiry'] = $this->admin_details->get_user_column_one('enquiry_no', $enquiry_no, 'enquiry');
        $client_details = $this->admin_details->get_user_column_one('id', $this->data['quote'][0]['client_id'], 'clients');
        $client_name = $client_details[0]['client_name'];
        array_push( $this->data['quote'], array("client_name" => $client_name) );
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        $this->data['quote_miscellaneous'] = $this->admin_details->getQuoteMiscellaneous($quotation_no);
        /*echo "<pre>";
        print_r($this->admin_details->getQuoteMiscellaneous($quotation_no));
        exit;*/
         unset($this->data['quote_miscellaneous'][0]['quotation_no']);

        $this->data['render_template'] = $this->load->view('admin/quote_print_template', $this->data, true);
        $this->data['footer'] = $this->load->view('admin/print_preview_footer', $this->data, true);
        $this->load->view('admin/print_preview', $this->data);

        /*echo "<pre>";
        //print_r($this->data['quote_template']);
        print_r($this->data['quote_miscellaneous']);*/

    }

    public function supplierEnquiry(){

        //try catch block
        try{
            $crud = new Grocery_CRUD();
            $crud->set_theme('datatables');
            $crud->set_table('');
            $crud->set_subject('');
            $crud->columns();

        }
        catch(Exception $e){

        }
    }

    public function createClientPO()
    {

        try {

            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('po_client');
            $crud->set_subject('PURCHASE ORDER - CLIENT');
            $crud->columns('client_id', 'quotation_id', 'po_no', 'po_date', 'wo_no', 'wo_date', 'pitch', 'pitch_type', 'moc', 'moc_type', 'qty', 'qty_type', 'delivery_date', 'delivery_place');
            $crud->add_fields('client_id', 'quotation_id', 'po_no', 'po_date', 'wo_no', 'wo_date', 'pitch', 'pitch_type', 'moc', 'moc_type', 'qty', 'qty_type', 'delivery_date', 'delivery_place');
            $crud->edit_fields('client_id', 'quotation_id', 'po_no', 'po_date', 'wo_no', 'wo_date', 'pitch', 'pitch_type', 'moc', 'moc_type', 'qty', 'qty_type', 'delivery_date', 'delivery_place');
            $crud->required_fields('client_id', 'po_no', 'po_date', 'wo_no', 'wo_date');
            $crud->set_relation('client_id', 'clients', 'client_name');
            $crud->set_relation('pitch_type', 'pitch', 'type');
            $crud->set_relation('moc_type', 'moc', 'type');
            $crud->set_relation('qty_type', 'qty', 'type');
            $crud->set_relation('quotation_id', 'quotation', 'quotation_no');
            $crud->add_action('CREATE WO', '', '', 'ui-icon-plus', array($this, 'generateWO'));
            $crud->callback_add_field('wo_no', array($this, 'generateWorkOrderNo'));

            $crud->callback_insert(array($this, 'saveClientPO'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function saveClientPO($post_array){


        $wo_array = array(
            "wo_no" => $post_array['wo_no'],
            "wo_date" => $post_array['wo_date']
        );
        $wo_result = $this->admin_model->insertRecord($wo_array, 'workorder');

        if($wo_result > 0) {
            $po_client_result = $this->admin_model->insertRecord(array_filter($post_array), 'po_client');
        }


    }

    public function generateWO($primary_key, $row){
        return site_url('admin/createWO/add/').'?wo_no='.$row->wo_no;
    }

    //creating quotation for selected Enquiry
    /**
     *
     */
    public function createWO(){
        try {

            //getting quotation id from URI
            $wo_no = $this->uri->segment(5);
            //echo $wo_no;
            //exit;

            //getting enquiry number from the URL
            try{
                $this->data['wo_details'] = $this->admin_details->get_user_column_one('wo_no', $wo_no, 'workorder');
            }
            catch(Exception $e){
                show_error($e->getMessage(), '---', $e->getTraceAsString());
            }

            $this->data['wo_no'] = ($this->input->get('wo_no')) ? @$this->input->get('wo_no') : @$this->data['wo_details'][0]['wo_no'];
            $this->data['wo_date'] = (@$this->data['wo_details'][0]['wo_date']) ? @$this->data['wo_details'][0]['wo_date'] : '0000-00-00 00:00:00';
            $this->data['wo_miscellaneous'] = (@$this->data['wo_details'][0]['wo_miscellaneous']) ? @$this->data['wo_details'][0]['wo_miscellaneous'] : '';
            $this->data['lengths'] = (@$this->data['wo_details'][0]['lengths']) ? @$this->data['wo_details'][0]['lengths'] : 0;
            $this->data['total_bags'] = (@$this->data['wo_details'][0]['total_bags']) ? @$this->data['wo_details'][0]['total_bags'] : 0;
            $this->data['dispatch_through'] = (@$this->data['wo_details'][0]['dispatch_through']) ? @$this->data['wo_details'][0]['dispatch_through'] : '';
            $this->data['weight'] = (@$this->data['wo_details'][0]['weight']) ? @$this->data['wo_details'][0]['weight'] : 0;
            $this->data['dc_no'] = (@$this->data['wo_details'][0]['dc_no']) ? @$this->data['wo_details'][0]['dc_no'] : 0;
            $this->data['lr_no'] = (@$this->data['wo_details'][0]['lr_no']) ? @$this->data['wo_details'][0]['lr_no'] : 0;
            $this->data['dc_date'] = (@$this->data['wo_details'][0]['dc_date']) ? @$this->data['wo_details'][0]['dc_date'] : '';
            $this->data['lr_date'] = (@$this->data['wo_details'][0]['lr_no']) ? @$this->data['wo_details'][0]['lr_no'] : 0;

            /*echo "<pre>";
            print_r($this->data['wo_details']);
            exit;*/

            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('workorder');
            $crud->set_subject('WORK ORDER');
            $crud->columns('wo_no', 'wo_date', 'lengths', 'total_bags', 'dispatch_through', 'weight', 'dc_no', 'lr_no', 'remarks', 'dc_date', 'lr_date');
            $crud->add_fields('wo_no', 'wo_date', 'wo_details', 'lengths', 'total_bags', 'dispatch_through', 'weight', 'dc_no', 'lr_no', 'remarks', 'dc_date', 'lr_date');
            $crud->edit_fields('wo_no', 'wo_date', 'wo_details', 'lengths', 'total_bags', 'dispatch_through', 'weight', 'dc_no', 'lr_no', 'remarks', 'dc_date', 'lr_date');
            $crud->required_fields('wo_no', 'wo_date');
            //$crud->set_relation('client_id', 'clients', 'client_name');
            //$crud->unset_add();

            $crud->callback_field('wo_no', function($post_array){
                $wo_no = (isset($this->data['wo_no'])) ? $this->data['wo_no'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$wo_no.'" name="wo_no" style="width:200px;" readonly>';
            });

            $crud->callback_field('wo_date', function($post_array){
                $wo_date = (isset($this->data['wo_date'])) ? $this->data['wo_date'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$wo_date.'" name="wo_date" style="width:200px;" readonly>';
            });

            $crud->callback_field('lengths', function($post_array){
                $lengths = (isset($this->data['lengths'])) ? $this->data['lengths'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$lengths.'" name="lengths" style="width:200px;">';
            });
            $crud->callback_field('total_bags', function($post_array){
                $total_bags = (isset($this->data['total_bags'])) ? $this->data['total_bags'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$total_bags.'" name="total_bags" style="width:200px;">';
            });
            $crud->callback_field('dispatch_through', function($post_array){
                $dispatch_through = (isset($this->data['dispatch_through'])) ? $this->data['dispatch_through'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$dispatch_through.'" name="dispatch_through" style="width:200px;">';
            });
            $crud->callback_field('weight', function($post_array){
                $weight = (isset($this->data['weight'])) ? $this->data['weight'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$weight.'" name="weight" style="width:200px;">';
            });
            $crud->callback_field('dc_no', function($post_array){
                $dc_no = (isset($this->data['dc_no'])) ? $this->data['dc_no'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$dc_no.'" name="dc_no" style="width:200px;">';
            });
            $crud->callback_field('lr_no', function($post_array){
                $lr_no = (isset($this->data['lr_no'])) ? $this->data['lr_no'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$lr_no.'" name="lr_no" style="width:200px;">';
            });
            $crud->callback_field('remarks', function($post_array){
                $remarks = (isset($this->data['remarks'])) ? $this->data['remarks'] : $post_array;
                return '<textarea name="remarks" class="texteditor" style="resize: none;">'.$remarks.'</textarea>';
            });
            /*$crud->callback_field('dc_date', function($post_array){
                $dc_date = (isset($this->data['dc_date'])) ? $this->data['dc_date'] : $post_array;
                return '<input id="field-dc_date" type="text" maxlength="200" value="'.$dc_date.'" name="dc_date" style="width:200px;" class="datepicker-input form-control hasDatepicker">';
            });
            $crud->callback_field('lr_date', function($post_array){
                $lr_date = (isset($this->data['lr_date'])) ? $this->data['lr_date'] : $post_array;
                return '<input id="field-lr_date" type="text" maxlength="200" value="'.$lr_date.'" name="lr_date" style="width:200px;" class="datepicker-input form-control hasDatepicker">';
            });*/


            $this->html_wo .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 800px;" class="wo_miscellaneous">';
            $this->html_wo .= '<th>Parts Name</th>';
            $this->html_wo .= '<th>Required Qty.</th>';
            $this->html_wo .= '<th>Production Qty.</th>';
            $this->html_wo .= '<th>Balance Qty.</th>';

            $crud->callback_field('wo_details', function ($post_array) {
                $wo_details = (isset($this->data['wo_details'])) ? $this->data['wo_details'] : $post_array;

                //$this->html_wo .= '<tr ><td><input type="text" maxlength="200" value="' . $delivery . '" name="delivery" style="width:200px;"> </td>';
                //$this->html_wo .= '<input type="hidden" name="new_record_count" value="0" id="new_record_count" />';

                //checking for additional fields
                $additional_fields = @json_decode($this->data['wo_miscellaneous'], true);
                $additional_field_count = count($additional_fields);
                $this->html_wo .= '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" />';

                //adding additional fields to the edit form
                if(!empty($additional_fields)){

                    foreach($additional_fields as $key => $val){
                        $key++;
                        $pn = $val['pn'];
                        $rq = $val['rq'];
                        $pq = $val['pq'];
                        $bq = $val['bq'];


                        $this->html_wo .= '<tr><td><input type="text" maxlength="200" value="'.$pn.'" name="pn'.$key.'" style="width:200px;"> </td>';

                        $this->html_wo .= '<td><input type="text" maxlength="100" value="'.$rq.'" name="rq'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" maxlength="100" value="'.$pq.'" name="pq'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" maxlength="100" value="'.$bq.'" name="bq'.$key.'" style="width:200px;"></td></tr>';


                    }
                    //$this->html_wo .= '</table>';
                }
                else {

                    $this->html_wo .= '<tr><td><input type="text" maxlength="200" value="" name="pn1" style="width:200px;"> </td>';

                    $this->html_wo .= '<td><input type="text" maxlength="100" value="" name="rq1" style="width:100px;"></td>';
                    $this->html_wo .= '<td><input type="text" maxlength="100" value="" name="pq1" style="width:100px;"></td>';
                    $this->html_wo .= '<td><input type="text" maxlength="100" value="" name="bq1" style="width:200px;"></td></tr>';


                }
                $this->html_wo .= '<tr><td colspan="7"><input type="button" name="add_wo_mis" id="add_wo_mis_record" value="Add Record" /> </td></tr>';
                $this->html_wo .= '</table>';

                return $this->html_wo;
            });

            $crud->callback_insert(array($this, 'saveWO'));
            $crud->callback_update(array($this, 'saveWO'));

            //printing WO
            $crud->add_action('Print Preview', '', 'admin/woPrintPreview', 'ui-icon-plus');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }

    public function saveWO($post_array)
    {

        $wo_no = $post_array['wo_no'];

        //looping/collecting dynamically generated data
        $new_record_count = $post_array['new_record_count'];
        for ($i = 1; $i <= $new_record_count; $i++) {
            $additional_fields[] = array(
                "pn" => $post_array['pn' . $i],
                "rq" => $post_array['rq' . $i],
                "pq" => $post_array['pq' . $i],
                "bq" => $post_array['bq' . $i]
            );
        }

        $wo_miscellaneous = json_encode($additional_fields);

        $update_wo_array = array(

            "wo_miscellaneous" => $wo_miscellaneous,
            "lengths" => $post_array['lengths'],
            "total_bags" => $post_array['total_bags'],
            "dispatch_through" => $post_array['dispatch_through'],
            "weight" => $post_array['weight'],
            "dc_no" => $post_array['dc_no'],
            "lr_no" => $post_array['lr_no'],
            "remarks" => $post_array['remarks'],
            "dc_date" => $post_array['dc_date'],
            "lr_date" => $post_array['lr_date']

        );
        /*echo "<pre>";
        print_r($additional_fields);*/

        $update_result = $this->admin_model->updateRecord($update_wo_array, 'workorder', 'wo_no', $wo_no);
        /*echo $update_result;

        exit;*/
    }

    public function woPrintPreview(){
        $wo_id = $this->uri->segment(3);
        $this->data['print_id'] = $wo_id;
        $this->data['print_func'] = 'printWO';
        $this->data['wo_details'] = $this->admin_details->get_user_column_one('id', $wo_id, 'workorder');
        $wo_no = $this->data['wo_details'][0]['wo_no'];
        $this->data['po_client'] = $this->admin_details->get_user_column_one('wo_no', $wo_no, 'po_client');
        $client_details = $this->admin_details->get_user_column_one('id', $this->data['po_client'][0]['client_id'], 'clients');
        $client_name = $client_details[0]['client_name'];
        $quotation_id = $this->data['po_client'][0]['quotation_id'];
        $this->data['quotation'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
        $enquiry_no = $this->data['quotation'][0]['enquiry_no'];
        $this->data['enquiry'] = $this->admin_details->get_user_column_one('enquiry_no', $enquiry_no, 'enquiry');

        $this->data['item'] = $this->data['enquiry'][0]['item'];

        array_push( $this->data['wo_details'], array("client_name" => $client_name) );
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        //$this->data['quote_miscellaneous'] = $this->admin_details->getQuoteMiscellaneous($quotation_no);

        $this->data['wo_miscellaneous'] = json_decode($this->data['wo_details'][0]['wo_miscellaneous'], true);

        /*echo "<pre>";
        print_r($this->data['wo_miscellaneous']);
        exit;*/
        //unset($this->data['quote_miscellaneous'][0]['quotation_no']);
        $this->data['render_template'] = $this->load->view('admin/wo_print_template', $this->data, true);
        $this->data['footer'] = $this->load->view('admin/print_preview_footer', $this->data, true);
        $this->load->view('admin/print_preview', $this->data);

        /*echo "<pre>";
        //print_r($this->data['quote_template']);
        print_r($this->data['quote_miscellaneous']);*/

    }

    public function printQuote(){
        $this->load->library('pdf');
        $quotation_id = $this->uri->segment(3);
        $this->data['print_id'] = $quotation_id;
        $this->data['quote'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
        $quotation_no = $this->data['quote'][0]['quotation_no'];
        $enquiry_no = $this->data['quote'][0]['enquiry_no'];
        $this->data['enquiry'] = $this->admin_details->get_user_column_one('enquiry_no', $enquiry_no, 'enquiry');
        $client_details = $this->admin_details->get_user_column_one('id', $this->data['quote'][0]['client_id'], 'clients');
        $client_name = $client_details[0]['client_name'];
        array_push( $this->data['quote'], array("client_name" => $client_name) );
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        $this->data['quote_miscellaneous'] = $this->admin_details->getQuoteMiscellaneous($quotation_no);
        /*echo "<pre>";
        print_r($this->admin_details->getQuoteMiscellaneous($quotation_no));
        exit;*/
        unset($this->data['quote_miscellaneous'][0]['quotation_no']);
        $this->data['render_template'] = $this->load->view('admin/quote_print_template', $this->data, true);
        $this->pdf->load_view('admin/print_preview', $this->data);
        $this->pdf->set_paper("a4", "portrait");
        $this->pdf->render();
        $this->pdf->stream($quotation_no.".pdf");
    }

    public function printWO()
    {
        $this->load->library('pdf');
        $wo_id = $this->uri->segment(3);

        $this->data['wo_details'] = $this->admin_details->get_user_column_one('id', $wo_id, 'workorder');
        $wo_no = $this->data['wo_details'][0]['wo_no'];
        $this->data['po_client'] = $this->admin_details->get_user_column_one('wo_no', $wo_no, 'po_client');
        $client_details = $this->admin_details->get_user_column_one('id', $this->data['po_client'][0]['client_id'], 'clients');
        $client_name = $client_details[0]['client_name'];
        $quotation_id = $this->data['po_client'][0]['quotation_id'];
        $this->data['quotation'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
        $enquiry_no = $this->data['quotation'][0]['enquiry_no'];
        $this->data['enquiry'] = $this->admin_details->get_user_column_one('enquiry_no', $enquiry_no, 'enquiry');

        $this->data['item'] = $this->data['enquiry'][0]['item'];

        array_push( $this->data['wo_details'], array("client_name" => $client_name) );
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        //$this->data['quote_miscellaneous'] = $this->admin_details->getQuoteMiscellaneous($quotation_no);

        $this->data['wo_miscellaneous'] = json_decode($this->data['wo_details'][0]['wo_miscellaneous'], true);

        /*echo "<pre>";
        print_r($this->data['wo_miscellaneous']);
        exit;*/
        //unset($this->data['quote_miscellaneous'][0]['quotation_no']);
        $this->data['render_template'] = $this->load->view('admin/wo_print_template', $this->data, true);
        //$this->load->view('admin/print_preview', $this->data);
        $this->pdf->load_view('admin/wo_print_preview', $this->data);
        $this->pdf->set_paper("a4", "portrait");
        $this->pdf->render();
        $this->pdf->stream($wo_no.".pdf");
    }

    //supplier enquiry section
    public function editSupplierEnquiry(){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('supplier_enquiry');
            $crud->set_subject('SUPPLIER ENQUIRY');
            $crud->columns('enquiry_no', 'supplier_id', 'date', 'wo_no');
            $crud->add_fields('enquiry_no', 'supplier_id', 'date', 'wo_no', 'material', 'material_type', 'size_round', 'size_round_type', 'size_thickness', 'size_thickness_type', 'size_width', 'size_width_type', 'qty', 'qty_type', 'delivery_period', 'taxes');
            $crud->edit_fields('supplier_id', 'date', 'wo_no', 'material', 'material_type', 'size_round', 'size_round_type', 'size_thickness', 'size_thickness_type', 'size_width', 'size_width_type', 'qty', 'qty_type', 'delivery_period', 'taxes');
            $crud->required_fields('enquiry_no', 'supplier_id', 'date', 'wo_no');
            $crud->set_relation('supplier_id', 'suppliers', 'supplier_name');
            $crud->set_relation('wo_no', 'workorder', 'wo_no');
            $crud->set_relation('material_type', 'materials', 'type');
            //$crud->set_relation('size_round_type', 'size', 'wo_no');
            $crud->set_relation('qty_type', 'qty', 'type');
            $crud->add_action('Print Preview', '', 'admin/printSupplierEnquiry', 'ui-icon-plus');

            $crud->callback_add_field('enquiry_no', array($this, 'generateSupplierEnquiryNo'));
            $crud->callback_field('size_round_type', function($post_array){
                $this->html_wo = '<select name="size_round_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_thickness_type', function($post_array){
                $this->html_wo = '<select name="size_thickness_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_width_type', function($post_array){
                $this->html_wo = '<select name="size_width_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function printSupplierEnquiry(){
        $this->load->library('pdf');
        $supplier_enq_id = $this->uri->segment(3);
        $this->data['print_id'] = $supplier_enq_id;
        $this->data['supplier_enq'] = $this->admin_details->get_user_column_one('id', $supplier_enq_id, 'supplier_enquiry');
        $enquiry_no = $this->data['supplier_enq'][0]['enquiry_no'];
        $wo_id = $this->data['supplier_enq'][0]['wo_no'];
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        /*echo "<pre>";
        print_r($this->admin_details->getQuoteMiscellaneous($quotation_no));
        exit;*/
        //unset($this->data['quote_miscellaneous'][0]['quotation_no']);

        $material_type = $this->data['supplier_enq'][0]['material_type'];
        $qty_type = $this->data['supplier_enq'][0]['qty_type'];

        //creating query conditions to get values by passing id in where clause
        $query_array = array(
            array(
                "table_name" => "materials",
                "id" => $material_type
            ),
            array(
                "table_name" => "qty",
                "id" => $qty_type
            )
        );

        //getting types by passing query conditions
        $this->data['types'] = $this->admin_details->getMultipleSelect($query_array);

        $this->data['render_template'] = $this->load->view('admin/client_enq_print_template', $this->data, true);
        $this->pdf->load_view('admin/print_preview', $this->data);
        $this->pdf->set_paper("a4", "portrait");
        $this->pdf->render();
        $this->pdf->stream($enquiry_no.".pdf");
    }

    /**
     * @return string
     */
    public function generateSupplierEnquiryNo(){
        $last_enquiry_details = $this->admin_model->getLastId('supplier_enquiry');
        $last_enq_id = (!empty($last_enquiry_details)) ? $last_enquiry_details[0]['id'] + 1 : 1;
        $data = 'KALPSUPENQ'.date('dmy').str_pad($last_enq_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="enquiry_no" readonly>';
    }

    public function createSupplierPo(){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('po_supplier');
            $crud->set_subject('SUPPLIER PO');
            $crud->columns('supplier_id', 'po_no', 'po_date', 'wo_id');
            $crud->add_fields('supplier_id', 'quotation_id', 'po_no', 'po_date', 'wo_id', 'material', 'material_type', 'size_round', 'size_round_type', 'size_thickness', 'size_thickness_type', 'size_width', 'size_width_type', 'qty', 'qty_type', 'rate', 'rate_type', 'delivery_period', 'delivery_type', 'taxes', 'payment_terms', 'transport_through');
            $crud->edit_fields('supplier_id', 'quotation_id', 'po_no', 'po_date', 'wo_id', 'material', 'material_type', 'size_round', 'size_round_type', 'size_thickness', 'size_thickness_type', 'size_width', 'size_width_type', 'qty', 'qty_type', 'rate', 'rate_type', 'delivery_period', 'delivery_type', 'taxes', 'payment_terms', 'transport_through');
            $crud->required_fields('supplier_id', 'quotation_id', 'po_no', 'po_date', 'wo_id');
            $crud->set_relation('supplier_id', 'suppliers', 'supplier_name');
            $crud->set_relation('wo_id', 'workorder', 'wo_no');
            $crud->set_relation('quotation_id', 'quotation', 'quotation_no');
            $crud->set_relation('material_type', 'materials', 'type');
            //$crud->set_relation('size_round_type', 'size', 'wo_no');
            $crud->set_relation('qty_type', 'qty', 'type');
            $crud->set_relation('rate_type', 'rate', 'type');
            $crud->set_relation('delivery_type', 'delivery', 'type');
            $crud->add_action('Print Preview', '', 'admin/printSupplierPO', 'ui-icon-plus');

            $crud->callback_add_field('po_no', array($this, 'generateSupplierPONo'));
            $crud->callback_field('size_round_type', function($post_array){
                $this->html_wo = '<select name="size_round_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_thickness_type', function($post_array){
                $this->html_wo = '<select name="size_thickness_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_width_type', function($post_array){
                $this->html_wo = '<select name="size_width_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /**
     * @return string
     */
    public function generateSupplierPONo(){
        $last_enquiry_details = $this->admin_model->getLastId('po_supplier');
        $last_enq_id = (!empty($last_enquiry_details)) ? $last_enquiry_details[0]['id'] + 1 : 1;
        $data = 'KALPSUPPO'.date('my').str_pad($last_enq_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="po_no" readonly>';
    }

    public function printSupplierPO(){
        $this->load->library('pdf');
        $supplier_po_id = $this->uri->segment(3);
        $this->data['print_id'] = $supplier_po_id;
        $this->data['supplier_po'] = $this->admin_details->get_user_column_one('id', $supplier_po_id, 'po_supplier');
        $supplier_id = $this->data['supplier_po'][0]['supplier_id'];
        $quotation_id = $this->data['supplier_po'][0]['quotation_id'];
        $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        $this->data['quotation'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
        /*$supplier_name = $this->data['suppliers'][0]['supplier_name'];
        $supplier_name = $this->data['suppliers'][0]['supplier_name'];*/
        $po_no = $this->data['supplier_po'][0]['po_no'];
        $wo_id = $this->data['supplier_po'][0]['wo_id'];
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        /*echo "<pre>";
        print_r($this->admin_details->getQuoteMiscellaneous($quotation_no));
        exit;*/
        //unset($this->data['quote_miscellaneous'][0]['quotation_no']);

        $material_type = $this->data['supplier_po'][0]['material_type'];
        $qty_type = $this->data['supplier_po'][0]['qty_type'];
        $rate_type = $this->data['supplier_po'][0]['rate_type'];
        $delivery_type = $this->data['supplier_po'][0]['delivery_type'];

        //creating query conditions to get values by passing id in where clause
        $query_array = array(
            array(
                "table_name" => "materials",
                "id" => $material_type
            ),
            array(
                "table_name" => "qty",
                "id" => $qty_type
            ),
            array(
                "table_name" => "rate",
                "id" => $rate_type
            ),
            array(
                "table_name" => "delivery",
                "id" => $delivery_type
            ),
        );

        //getting types by passing query conditions
        $this->data['types'] = $this->admin_details->getMultipleSelect($query_array);

        $this->data['render_template'] = $this->load->view('admin/supplier_po_print_template', $this->data, true);
        $this->pdf->load_view('admin/print_preview', $this->data);
        $this->pdf->set_paper("a4", "portrait");
        $this->pdf->render();
        $this->pdf->stream($po_no.".pdf");
    }

    public function inspectionReport(){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('inspection_report');
            $crud->set_subject('INSPECTION REPORT');
            $crud->columns('supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name');
            $crud->add_fields('supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name', 'lr_no', 'lr_date', 'dc_qty', 'received_qty', 'received_qty_type', 'remarks', 'rejection_reason', 'wo_id', 'dimension', 'dimension_type', 'dimension_width', 'dimension_length');
            $crud->edit_fields('supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name', 'lr_no', 'lr_date', 'dc_qty', 'received_qty', 'received_qty_type', 'remarks', 'rejection_reason', 'wo_id', 'dimension', 'dimension_type', 'dimension_width', 'dimension_length');
            $crud->required_fields('supplier_id', 'supplier_dc_no', 'supplier_dc_date');
            $crud->set_relation('supplier_id', 'suppliers', 'supplier_name');
            $crud->set_relation('wo_id', 'workorder', 'wo_no');
            //$crud->set_relation('quotation_id', 'quotation', 'quotation_no');
            //$crud->set_relation('size_round_type', 'size', 'wo_no');
            $crud->set_relation('received_qty_type', 'qty', 'type');
            $crud->set_relation('dimension_type', 'dimension', 'type');
            $crud->add_action('Print Preview', '', 'admin/printSupplierPO', 'ui-icon-plus');

            $crud->callback_add_field('po_no', array($this, 'generateSupplierPONo'));
            $crud->callback_field('size_round_type', function($post_array){
                $this->html_wo = '<select name="size_round_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_thickness_type', function($post_array){
                $this->html_wo = '<select name="size_thickness_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_width_type', function($post_array){
                $this->html_wo = '<select name="size_width_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function outSource(){
        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('outsource');
            $crud->set_subject('OUTSOURCE');
            $crud->columns('wo_id', 'suppliers_id', 'labour_job_chain_no', 'lic_date', 'material_details', 'sent_qty', 'what_to_make', 'supplier_dc_no', 'supplier_dc_date', 'received_qty_dc', 'qty_type');
            $crud->add_fields('wo_id', 'suppliers_id', 'labour_job_chain_no', 'lic_date', 'material_details', 'sent_qty', 'what_to_make', 'supplier_dc_no', 'supplier_dc_date', 'received_qty_dc', 'qty_type');
            $crud->edit_fields('wo_id', 'suppliers_id', 'labour_job_chain_no', 'lic_date', 'material_details', 'sent_qty', 'what_to_make', 'supplier_dc_no', 'supplier_dc_date', 'received_qty_dc', 'qty_type');
            $crud->required_fields('suppliers_id', 'supplier_dc_no', 'supplier_dc_date');
            $crud->set_relation('wo_id', 'suppliers_id', 'supplier_dc_no', 'supplier_dc_date');
            $crud->set_relation('wo_id', 'workorder', 'wo_no');
            $crud->set_relation('suppliers_id', 'suppliers', 'supplier_name');
            $crud->set_relation('qty_type', 'qty', 'type');
            $crud->add_action('Print Preview', '', 'admin/printSupplierPO', 'ui-icon-plus');

            $crud->callback_add_field('po_no', array($this, 'generateSupplierPONo'));
            $crud->callback_field('size_round_type', function($post_array){
                $this->html_wo = '<select name="size_round_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_thickness_type', function($post_array){
                $this->html_wo = '<select name="size_thickness_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });
            $crud->callback_field('size_width_type', function($post_array){
                $this->html_wo = '<select name="size_width_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

}