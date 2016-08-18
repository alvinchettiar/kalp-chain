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
    private $terms_n_cond;

    public function __construct(){

        parent::__construct();
        $this->load->helper('url');
        $this->load->model("admin_model");
        $this->load->model("admin_details");
        $this->load->library('grocery_CRUD');
        $this->load->library('nativesession');
        $this->load->library('uri');
        if(!$this->nativesession->getSession('admincred')){
            redirect(base_url()."admin/login", "location");
        }



        /*echo $this->nativesession->getSession('admincredrole');
        echo $this->nativesession->getSession('admincredfname');
        echo $this->nativesession->getSession('admincredlname');*/
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

    public function editVendors(){

        try{
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('vendors');
            $crud->set_subject('VENDOR LIST');
            $crud->required_fields('vendor_name');
            $crud->columns('vendor_name', 'address', 'district', 'pincode', 'website', 'contact_person', 'cp_mobile', 'email', 'std_code', 'tel_no1', 'tel_no2', 'tel_no3', 'vat', 'cst', 'pan', 'excise_no', 'service_tax_no', 'lbt_no');
            $crud->edit_fields('vendor_name', 'address', 'district', 'pincode', 'website', 'contact_person', 'cp_mobile', 'email', 'std_code', 'tel_no1', 'tel_no2', 'tel_no3', 'vat', 'cst', 'pan', 'excise_no', 'service_tax_no', 'lbt_no');

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
            $crud->columns('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->add_fields('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->edit_fields('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->required_fields('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
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
            $this->data['enquiry_qty'] = @$this->data['enquiry_details'][0]['qty'];
            $this->data['enquiry_moc'] = @$this->data['enquiry_details'][0]['MOC'];
            $this->data['enquiry_pitch'] = @$this->data['enquiry_details'][0]['pitch'];

            /*echo "<pre>";
            print_r($this->data['enquiry_details']);
            exit;*/


            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('quotation');
            $crud->set_subject('QUOTATION');
            $crud->columns('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
            $crud->add_fields('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date', 'pitch', 'moc', 'link', 'attachment', 'roller', 'wip', 'bush', 'pin', 'qty', 'rate', 'pf', 'discount', 'taxes', 'mvat_cst', 'delivery', 'weight', 'notes');
            $crud->edit_fields('quote_edit', 'enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date', 'pitch', 'moc', 'link', 'attachment', 'roller', 'wip', 'bush', 'pin', 'qty', 'rate', 'pf', 'discount', 'taxes', 'mvat_cst', 'delivery', 'weight', 'notes');
            $crud->required_fields('enquiry_for', 'client_id', 'enquiry_no', 'enquiry_date', 'quotation_no', 'date');
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

            /*$crud->callback_field('date', function($post_array){
                return '<input type="date" maxlength="200" value="'.$post_array.'" name="date">';
            });*/

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
                    $this->data['link3'] = $this->data['quotation_miscellaneous'][0]['link3'];
                    $this->data['attachment'] = $this->data['quotation_miscellaneous'][0]['attachment'];
                    $this->data['attachment_details'] = $this->data['quotation_miscellaneous'][0]['attachment_details'];
                    $this->data['attachment2'] = $this->data['quotation_miscellaneous'][0]['attachment2'];
                    $this->data['roller'] = $this->data['quotation_miscellaneous'][0]['roller'];
                    $this->data['roller2'] = $this->data['quotation_miscellaneous'][0]['roller2'];
                    $this->data['roller_details'] = $this->data['quotation_miscellaneous'][0]['roller_details'];
                    $this->data['wip'] = $this->data['quotation_miscellaneous'][0]['wip'];
                    $this->data['wip2'] = $this->data['quotation_miscellaneous'][0]['wip2'];
                    $this->data['bush'] = $this->data['quotation_miscellaneous'][0]['bush'];
                    $this->data['bush2'] = $this->data['quotation_miscellaneous'][0]['bush2'];
                    $this->data['bush3'] = $this->data['quotation_miscellaneous'][0]['bush3'];
                    $this->data['bush_type'] = $this->data['quotation_miscellaneous'][0]['bush_type'];
                    $this->data['pin'] = $this->data['quotation_miscellaneous'][0]['pin'];
                    $this->data['pin2'] = $this->data['quotation_miscellaneous'][0]['pin2'];
                    $this->data['pin3'] = $this->data['quotation_miscellaneous'][0]['pin3'];
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
                    $this->data['delivery_type'] = $this->data['quotation_miscellaneous'][0]['delivery_type'];
                    $this->data['weight'] = $this->data['quotation_miscellaneous'][0]['weight'];
                    $this->data['weight_type'] = $this->data['quotation_miscellaneous'][0]['weight_type'];
                    $this->data['notes'] = $this->data['quotation_miscellaneous'][0]['notes'];
                    $this->data['additional_fields'] = $this->data['quotation_miscellaneous'][0]['additional_fields'];
                }
            }

            //print_r($this->data['quotation_miscellaneous']);

            $crud->callback_field('pitch', function ($post_array) {

                /*$pitch = (isset($this->data['pitch'])) ? $this->data['pitch'] : $post_array;
                $pitch_details = (isset($this->data['pitch_details'])) ? $this->data['pitch_details'] : '';*/

                $html_elements = '<input type="text" maxlength="200" value="' . $this->data['enquiry_pitch'] . '" name="pitch" style="width:200px;"> ';

                /*$pitch_types = $this->getQuoteMiscellaneous('pitch');

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
                $html_elements .= ' <input type="text" maxlength="200" value="' . $pitch_details . '" name="pitch_details" style="width:200px;"> ';*/

                return $html_elements;
            });

            $crud->callback_field('moc', function ($post_array) {

                /*$moc = (isset($this->data['moc'])) ? $this->data['moc'] : $post_array;*/
                $html_elements = '<input type="text" maxlength="200" value="' . $this->data['enquiry_moc'] . '" name="moc" style="width:200px;"> ';

                /*$pitch_types = $this->getQuoteMiscellaneous('moc');

                $html_elements .= '<select name="moc_type" style="width:100px;">';
                foreach ($pitch_types as $key => $val) {
                    if(@$this->data['moc_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                }
                $html_elements .= '</select>';*/

                return $html_elements;
            });

            $crud->callback_field('link', function ($post_array) {
                $link = (isset($this->data['link'])) ? $this->data['link'] : $post_array;
                $link2 = (isset($this->data['link2'])) ? $this->data['link2'] : '';
                $link3 = (isset($this->data['link2'])) ? $this->data['link3'] : '';
                $html_elements = '<input type="text" maxlength="200" value="' . $link . '" name="link" style="width:200px;"> <input type="text" maxlength="200" value="' . $link2 . '" name="link2" style="width:200px;"> mm '
                        . ' - <input type="text" maxlength="200" value="' . $link3 . '" name="link3" style="width:200px;">  ';

                return $html_elements;
            });

            $crud->callback_field('attachment', function ($post_array) {
                $attachment = (isset($this->data['attachment'])) ? $this->data['attachment'] : $post_array;
                $attachment2 = (isset($this->data['attachment2'])) ? $this->data['attachment2'] : $post_array;
                $attachment_details = (isset($this->data['attachment_details'])) ? $this->data['attachment_details'] : '';
                $html_elements = '<input type="text" maxlength="200" value="' . $attachment . '" name="attachment" style="width:200px;"> mm thick';
                $html_elements .= ' <input type="text" maxlength="200" value="' . $attachment_details . '" name="attachment_details" style="width:200px;">';
                $html_elements .= ' - <input type="text" maxlength="200" value="' . $attachment2 . '" name="attachment2" style="width:200px;">';
                return $html_elements;
            });

            $crud->callback_field('roller', function ($post_array) {
                $roller = (isset($this->data['roller'])) ? $this->data['roller'] : $post_array;
                $roller2 = (isset($this->data['roller2'])) ? $this->data['roller2'] : '';
                $roller_details = (isset($this->data['roller_details'])) ? $this->data['roller_details'] : '';

                $html_elements = '<input type="text" maxlength="200" value="' . $roller . '" name="roller" style="width:200px;"> mm Dia X <input type="text" maxlength="200" value="' . $roller2 . '" name="roller2" style="width:200px;"> mm long';
                $html_elements .= ' - <input type="text" maxlength="200" value="' . $roller_details . '" name="roller_details" style="width:200px;">';
                return $html_elements;
            });

            $crud->callback_field('wip', function ($post_array) {
                $wip = (isset($this->data['wip'])) ? $this->data['wip'] : $post_array;
                $wip2 = (isset($this->data['wip2'])) ? $this->data['wip2'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $wip . '" name="wip" style="width:200px;"> mm';
                $html_elements .= ' - <input type="text" maxlength="200" value="' . $wip2 . '" name="wip2" style="width:200px;">';
                return $html_elements;
            });

            $crud->callback_field('bush', function ($post_array) {
                $bush = (isset($this->data['bush'])) ? $this->data['bush'] : $post_array;
                $bush2 = (isset($this->data['bush2'])) ? $this->data['bush2'] : '';
                $bush3 = (isset($this->data['bush3'])) ? $this->data['bush3'] : '';

                $html_elements = '<input type="text" maxlength="200" value="' . $bush . '" name="bush" style="width:200px;"> mm Dia X <input type="text" maxlength="200" value="' . $bush2 . '" name="bush2" style="width:200px;"> mm long ';
                $html_elements .= ' - <input type="text" maxlength="200" value="' . $bush3 . '" name="bush3" style="width:200px;">';

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
                $pin3 = (isset($this->data['pin3'])) ? $this->data['pin3'] : '';

                //checking for additional fields
                $additional_fields = @json_decode($this->data['additional_fields'], true);
                $additional_field_count = count($additional_fields);
                $html_elements = '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" /><input type="text" maxlength="200" value="' . $pin . '" name="pin" style="width:200px;"> mm Dia X <input type="text" maxlength="200" value="' . $pin2 . '" name="pin2" style="width:200px;"> mm long ';


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
                $html_elements .= ' - <input type="text" maxlength="200" value="' . $pin3 . '" name="pin3" style="width:200px;">';

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
                /*$qty = (isset($this->data['qty'])) ? $this->data['qty'] : $post_array;*/

                $html_elements = '<input type="text" maxlength="200" value="' . $this->data['enquiry_qty'] . '" name="qty" style="width:200px;"> ';

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
                        /*else{
                            $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                        }*/
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
                $selected_yes = '';
                $selected_no = '';
                if($taxes === 'yes')
                { 
                    $selected_yes = "selected"; 
                }
                if($taxes === 'no')
                { 
                    $selected_no = "selected"; 
                }
                //$html_elements = '<input type="text" maxlength="200" value="" name="taxes" style="width:200px;" > % (Excise)';
               
                $html_elements = '<select name="taxes" id="taxes">'
                        . '<option value="yes" '.$selected_yes.'>Yes</option><option value="no" '.$selected_no.'>No</option></select> (Excise)';

                return $html_elements;
            });

            $crud->callback_field('mvat_cst', function ($post_array) {
                $mvat_cst = (isset($this->data['mvat_cst'])) ? $this->data['mvat_cst'] : $post_array;

                $html_elements = '<input type="text" maxlength="200" value="' . $mvat_cst . '" name="mvat_cst" id="mvat_cst" style="width:200px;" readonly> ';

                $pitch_types = $this->getQuoteMiscellaneous('mvat_cst');

                $html_elements .= '<select name="mvat_cst_type" id="mvat_cst_type" style="width:100px;">';
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

                $html_elements .= '<select name="delivery_type" style="width:100px;">';
                foreach ($delivery_types as $key => $val) {

                    if(@$this->data['delivery_type']==$val['id']) {
                        //echo "IN IF";
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        //echo "IN else";
                        $default = ($val['type']=='Weeks') ? "selected='selected'" : "";
                        $html_elements .= '<option value="' . $val['id'] . '" '.$default.'>' . $val['type'] . '</option>';
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

                $html_elements = '<div class="form-input-box" id="notes_input_box">
                                    <textarea id="field-notes" name="notes" cols="100" class="texteditor" rows="50" style="resize: none;">'.$notes.'</textarea>
                                  </div>';

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
                $crud->add_action('Create PO', '', '', 'ui-icon-plus', array($this, 'generateClientPo'));
            //}

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }


    }

    public function generateClientPo($primary_key, $row){
        /*echo "<pre>";
        print_r($row);*/
        return site_url('admin/createClientPo/add/').'?quotation_no='.$row->quotation_no;
    }

    public function saveQuotation($post_array){

            /*echo "<pre>";
            print_r($post_array);
            exit;*/
            $quotation_no = $post_array['quotation_no'];
            $client_id = $post_array['client_id'];
            $enquiry_no = $post_array['enquiry_no'];
            $enquiry_date = $post_array['enquiry_date'];
            $date = date('Y-m-d', strtotime($post_array['date']));
            $enquiry_for = $post_array['enquiry_for'];



            $pitch = $post_array['pitch'];
//            $pitch_details = $post_array['pitch_details'];
            $moc = $post_array['moc'];
            $attachment = $post_array['attachment'];
            $attachment2 = $post_array['attachment2'];
            $attachment_details = $post_array['attachment_details'];
            $roller = $post_array['roller'];
            $roller2 = $post_array['roller2'];           
            $roller_details = $post_array['roller_details'];
            $wip = $post_array['wip'];
            $wip2 = $post_array['wip2'];
            $qty = $post_array['qty'];
            $rate = $post_array['rate'];
            $pf = $post_array['pf'];
            $discount = $post_array['discount'];
            $taxes = $post_array['taxes'];
            $mvat_cst = $post_array['mvat_cst'];
            $delivery = $post_array['delivery'];
            $delivery_type = $post_array['delivery_type'];
            $weight = $post_array['weight'];
            $notes = $post_array['notes'];            
            $bush = $post_array['bush'];
            $bush2 = $post_array['bush2'];
            $bush3 = $post_array['bush3'];
            $link = $post_array['link'];
            $link2 = $post_array['link2'];
            $link3 = $post_array['link3'];
            $pin = $post_array['pin'];
            $pin2 = $post_array['pin2'];
            $pin3 = $post_array['pin3'];

//            $pitch_type = $post_array['pitch_type'];
//            $moc_type = $post_array['moc_type'];
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
            'date' => $date,
            'enquiry_for' => $enquiry_for

        );

        $quotation_miscellaneous_vals_add =  array(
            "quotation_no" => addslashes($quotation_no),
            'pitch' => addslashes($pitch),
//            'pitch_details' => $pitch_details,
            'moc' => $moc,
            'attachment' => $attachment,
            'attachment2' => $attachment2,
            'attachment_details' => $attachment_details,
            'roller' => $roller,
            'roller2' => $roller2,           
            'roller_details' => $roller_details,
            'wip' => $wip,
            'wip2' => $wip2,
            'qty' => $qty,
            'rate' => $rate,
            'pf' => $pf,
            'discount' => $discount,
            'taxes' => $taxes,
            'mvat_cst' => $mvat_cst,
            'delivery' => $delivery,
            'delivery_type' => $delivery_type,
            'weight' => $weight,
            'notes' => addslashes(htmlentities($notes)),            
            'link' => $link,
            'link2' => $link2,
            'link3' => $link3,
            'bush' => $bush,
            'bush2' => $bush2,
            'bush3' => $bush3,
            'pin' => $pin,
            'pin2' => $pin2,
            'pin3' => $pin3,
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
//            'pitch_details' => $pitch_details,
            'moc' => $moc,
            'attachment' => $attachment,
            'attachment2' => $attachment2,
            'attachment_details' => $attachment_details,
            'roller' => $roller,
            'roller2' => $roller2,
            'roller_details' => $roller_details,
            'wip' => $wip,
            'wip2' => $wip2,
            'qty' => $qty,
            'rate' => $rate,
            'pf' => $pf,
            'discount' => $discount,
            'taxes' => $taxes,
            'mvat_cst' => $mvat_cst,
            'delivery' => $delivery,
            'delivery_type' => $delivery_type,
            'weight' => $weight,
            'notes' => htmlentities($notes),           
            'link' => $link,
            'link2' => $link2,
            'link3' => $link3,
            'bush' => $bush,
            'bush2' => $bush2,
            'bush3' => $bush3,
            'pin' => $pin,
            'pin2' => $pin2,
            'pin3' => $pin3,
            'bush_type' => $bush_type,
            'pin_type' => $pin_type,
            'qty_type' => $qty_type,
            'rate_type' => $rate_type,
            'mvat_cst_type' => $mvat_cst_type,
            'weight_type' => $weight_type,
            'additional_fields' => @json_encode($additional_fields)

        );

        /*echo "<pre>";
        print_r($quotation_miscellaneous_vals_edit);
        exit;*/

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
        $this->data['delivery_type'] = $this->data['quote_miscellaneous'][0]['delivery_type'];
        $this->data['delivery_type_name'] = $this->admin_details->get_user_column_one('id', $this->data['delivery_type'], 'delivery');

        /*echo "<pre>";
        print_r($this->data['quote_miscellaneous']);
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

            //getting quotation id from URI
            $client_po_id = $this->uri->segment(4);

            //getting enquiry number from the URL
            $this->data['client_po'] = $this->admin_details->get_user_column_one('id', $client_po_id, 'po_client');

            $this->data['quotation_no'] = ($this->input->get('quotation_no')) ? @$this->input->get('quotation_no') : @$this->data['client_po'][0]['quotation_id'];

            //checking if quotation no is quotation id
            if(stripos($this->data['quotation_no'], 'KALPQUOTE') === false){
                $this->data['quotation_details'] = @$this->admin_details->get_user_column_one('id', $this->data['quotation_no'], 'quotation');
                $this->data['quotation_id'] = @$this->data['quotation_details'][0]['id'];
                $this->data['quotation_no'] = @$this->data['quotation_details'][0]['quotation_no'];
                $this->data['enquiry_no'] = @$this->data['quotation_details'][0]['enquiry_no'];
                $this->data['enquiry_details'] = @$this->admin_details->get_user_column_one('enquiry_no', $this->data['enquiry_no'], 'enquiry');
                $this->data['client_details'] = @$this->admin_details->get_user_column_one('id', $this->data['enquiry_details'][0]['client_id'], 'clients');
                $this->data['enquiry_qty'] = @$this->data['enquiry_details'][0]['qty'];
                $this->data['enquiry_qty_type'] = @$this->data['enquiry_details'][0]['qty_type'];
                $this->data['enquiry_moc'] = @$this->data['enquiry_details'][0]['MOC'];
                $this->data['enquiry_pitch'] = @$this->data['enquiry_details'][0]['pitch'];
            }
            else
            {
                $this->data['quotation_details'] = @$this->admin_details->get_user_column_one('quotation_no', $this->data['quotation_no'], 'quotation');
                $this->data['quotation_id'] = @$this->data['quotation_details'][0]['id'];
                $this->data['enquiry_no'] = @$this->data['quotation_details'][0]['enquiry_no'];
                $this->data['enquiry_details'] = @$this->admin_details->get_user_column_one('enquiry_no', $this->data['enquiry_no'], 'enquiry');
                $this->data['client_details'] = @$this->admin_details->get_user_column_one('id', $this->data['enquiry_details'][0]['client_id'], 'clients');
                $this->data['enquiry_qty'] = @$this->data['enquiry_details'][0]['qty'];
                $this->data['enquiry_qty_type'] = @$this->data['enquiry_details'][0]['qty_type'];
                $this->data['enquiry_moc'] = @$this->data['enquiry_details'][0]['MOC'];
                $this->data['enquiry_pitch'] = @$this->data['enquiry_details'][0]['pitch'];
            }

            /*echo "<pre>";
            print_r($this->data);
            exit;*/


            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('po_client');
            $crud->set_subject('PURCHASE ORDER - CLIENT');
            $crud->columns('client_id', 'quotation_id', 'po_no', 'po_date', 'wo_no', 'wo_date', 'pitch', 'moc', 'qty', 'qty_type', 'delivery_date', 'delivery_place');
            $crud->add_fields('client_id', 'quotation_id', 'po_no', 'po_date', 'wo_no', 'wo_date', 'pitch', 'moc', 'qty', 'qty_type', 'delivery_date', 'delivery_place');
            $crud->edit_fields('client_id', 'quotation_id', 'po_no', 'po_date', 'wo_no', 'wo_date', 'pitch', 'moc', 'qty', 'qty_type', 'delivery_date', 'delivery_place');
            $crud->required_fields('client_id', 'po_no', 'po_date', 'wo_no', 'wo_date');
            $crud->set_relation('client_id', 'clients', 'client_name');
            /*$crud->set_relation('pitch_type', 'pitch', 'type');
            $crud->set_relation('moc_type', 'moc', 'type');*/
            $crud->set_relation('qty_type', 'qty', 'type');
            $crud->set_relation('quotation_id', 'quotation', 'quotation_no');
            $crud->add_action('CREATE WO', '', '', 'ui-icon-plus', array($this, 'generateWO'));
            $crud->callback_add_field('wo_no', array($this, 'generateWorkOrderNo'));

            $crud->callback_field('client_id', function(){
                return '<input type="text" maxlength="200" value="'.@$this->data['enquiry_details'][0]['client_id'].'" name="client_id" readonly> | Client Name : '.@$this->data['client_details'][0]['client_name'];
            });

            $crud->callback_field('quotation_id', function($post_array){
                $quotation = ($this->data['quotation_id']) ? $this->data['quotation_id'] : $post_array;
                return '<input type="hidden" maxlength="200" value="'.$quotation.'" name="quotation_id"> Quotation No. ' . $this->data['quotation_no'];
            });

            $crud->callback_field('pitch', function($post_array){
                return '<input type="text" maxlength="200" value="'.$this->data['enquiry_pitch'].'" name="pitch">';
            });
            $crud->callback_field('moc', function($post_array){
                return '<input type="text" maxlength="200" value="'.$this->data['enquiry_moc'].'" name="moc">';
            });
            /*
            $crud->callback_field('qty', function($post_array){
                $qty = ($post_array) ? $post_array : $this->data['enquiry_qty'];
                return '<input type="text" maxlength="200" value="'.$qty.'" name="qty">';
            });
            */

            /*
            $crud->callback_field('qty_type', function($post_array){
                $this->data['qty_type'] = $this->admin_details->get_user_column_one('id', $this->data['enquiry_qty_type'], 'qty');
                /*$html_elements = '<select name="qty_type" style="width:100px;">';

                        $html_elements .= '<option value="' . $this->data['enquiry_qty_type'] . '" selected>' . $this->data['qty_type'][0]['type'] . '</option>';

                $html_elements .= '</select>';*/

            /*
                $pitch_types = $this->getQuoteMiscellaneous('qty');

                $html_elements = '<select name="qty_type" style="width:100px;" >';
                foreach ($pitch_types as $key => $val) {
                    /*if(@$this->data['qty_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{*/
                    /*if(@$this->data['enquiry_qty_type']==$val['id']) {
                        $html_elements .= '<option value="' . $val['id'] . '" selected>' . $val['type'] . '</option>';
                    }
                    else{
                        $html_elements .= '<option value="' . $val['id'] . '">' . $val['type'] . '</option>';
                    }
                    //}
                }
                $html_elements .= '</select>';

                return $html_elements;
            });*/

            $crud->callback_insert(array($this, 'saveClientPO'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function saveClientPO($post_array){

        $wo_no = $post_array['wo_no'];
        $wo_date = $post_array['wo_date'];

        $wo_array = array(
            "wo_no" => $wo_no,
            "wo_date" => $wo_date
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
            $wo_no = $this->input->get('wo_no');
           
            //getting enquiry number from the URL
            try{
                if($wo_no) {
                    $add_new_record = $this->uri->segment(3);
                    $this->data['wo_details'] = $this->admin_details->get_user_column_one('wo_no', $wo_no, 'workorder');
                }
                else
                {
                    $wo_id = $this->uri->segment(4);
                    $this->data['wo_details'] = $this->admin_details->get_user_column_one('id', $wo_id, 'workorder');
                }
            }
            catch(Exception $e){
                show_error($e->getMessage(), '---', $e->getTraceAsString());
            }

            $this->data['wo_no'] = ($this->input->get('wo_no')) ? @$this->input->get('wo_no') : @$this->data['wo_details'][0]['wo_no'];
            $this->data['wo_date'] = (@$this->data['wo_details'][0]['wo_date']) ? @$this->data['wo_details'][0]['wo_date'] : NULL;
            $this->data['wo_miscellaneous'] = (@$this->data['wo_details'][0]['wo_miscellaneous']) ? @$this->data['wo_details'][0]['wo_miscellaneous'] : NULL;
            $this->data['lengths'] = (@$this->data['wo_details'][0]['lengths']) ? @$this->data['wo_details'][0]['lengths'] : NULL;
            $this->data['total_bags'] = (@$this->data['wo_details'][0]['total_bags']) ? @$this->data['wo_details'][0]['total_bags'] : '';
            $this->data['dispatch_through'] = (@$this->data['wo_details'][0]['dispatch_through']) ? @$this->data['wo_details'][0]['dispatch_through'] : NULL;
            $this->data['weight'] = (@$this->data['wo_details'][0]['weight']) ? @$this->data['wo_details'][0]['weight'] : NULL;
            $this->data['dc_no'] = (@$this->data['wo_details'][0]['dc_no']) ? @$this->data['wo_details'][0]['dc_no'] : NULL;
            $this->data['lr_no'] = (@$this->data['wo_details'][0]['lr_no']) ? @$this->data['wo_details'][0]['lr_no'] : NULL;
            $this->data['dc_date'] = (@$this->data['wo_details'][0]['dc_date']) ? @$this->data['wo_details'][0]['dc_date'] : NULL;
            $this->data['lr_date'] = (@$this->data['wo_details'][0]['lr_no']) ? @$this->data['wo_details'][0]['lr_no'] : NULL;
            $this->data['format_no'] = (@$this->data['wo_details'][0]['format_no']) ? @$this->data['wo_details'][0]['format_no'] : NULL;

            /*echo "<pre>";
            print_r($this->data['wo_details']);
            exit;*/

            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('workorder');
            $crud->set_subject('WORK ORDER');
            $crud->columns('wo_no', 'wo_date', 'lengths', 'total_bags', 'dispatch_through', 'weight', 'dc_no', 'lr_no', 'remarks', 'dc_date', 'lr_date', 'format_no');
            $crud->add_fields('wo_no', 'wo_date', 'wo_details', 'lengths', 'total_bags', 'dispatch_through', 'weight', 'dc_no', 'lr_no', 'remarks', 'dc_date', 'lr_date', 'format_no');
            $crud->edit_fields('wo_no', 'wo_date', 'wo_details', 'lengths', 'total_bags', 'dispatch_through', 'weight', 'dc_no', 'lr_no', 'remarks', 'dc_date', 'lr_date', 'format_no');
            $crud->required_fields('wo_no', 'wo_date');
            //$crud->set_relation('client_id', 'clients', 'client_name');
            //$crud->unset_add();

            $crud->callback_field('wo_no', function($post_array){
                $wo_no = (isset($this->data['wo_no'])) ? $this->data['wo_no'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$wo_no.'" name="wo_no" style="width:200px;" readonly>';
            });

            $crud->callback_field('wo_date', function($post_array){
                $wo_date = (isset($this->data['wo_date'])) ? date('d-m-Y', strtotime($this->data['wo_date'])) : date('d-m-Y', strtotime($post_array));
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
            $crud->callback_field('format_no', function($post_array){
                $format_no = (isset($this->data['format_no'])) ? $this->data['format_no'] : $post_array;
                return '<input type="text" maxlength="200" value="'.$format_no.'" name="format_no" style="width:200px;">';
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
                $additional_field_count = (count($additional_fields) == 0) ? 1 : count($additional_fields);
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
            if(isset($post_array['pn' . $i]) && $post_array['pn' . $i] != '') {
                $additional_fields[] = array(
                    "pn" => $post_array['pn' . $i],
                    "rq" => $post_array['rq' . $i],
                    "pq" => $post_array['pq' . $i],
                    "bq" => $post_array['bq' . $i]
                );
            }
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
            "dc_date" => date('Y-m-d', strtotime($post_array['dc_date'])),
            "lr_date" => date('Y-m-d', strtotime($post_array['lr_date'])),
            "format_no" => $post_array['format_no']

        );

        //print_r($update_wo_array);

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
        //echo "ENQUIRY NO : " . $enquiry_no."<br>";
        $this->data['item'] = $this->data['enquiry'][0]['item'];
        //echo "ITEM : " . $this->data['item']."<br>";


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

        $this->data['delivery_type'] = $this->data['quote_miscellaneous'][0]['delivery_type'];
        $this->data['delivery_type_name'] = $this->admin_details->get_user_column_one('id', $this->data['delivery_type'], 'delivery');

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

            $supplier_enquiry_id = $this->uri->segment(4);
            //echo "SEGMENT : " . $supplier_enquiry_id;
            $this->data['supplier_enquiry'] = $this->admin_details->get_user_column_one('id', $supplier_enquiry_id, 'supplier_enquiry');
            //print_r($this->data['supplier_enquiry'][0]['supplier_enquiry_details']);


            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('supplier_enquiry');
            $crud->set_subject('SUPPLIER ENQUIRY');
            $crud->columns('enquiry_no', 'supplier_id', 'date', 'supplier_enquiry_details', 'remarks');
            //$crud->add_fields('enquiry_no', 'supplier_id', 'date', 'wo_no', 'material', 'material_type', 'size_round', 'size_round_type', 'size_thickness', 'size_thickness_type', 'size_width', 'size_width_type', 'qty', 'qty_type', 'delivery_period', 'taxes');
            $crud->add_fields('enquiry_no', 'supplier_id', 'date', 'supplier_enquiry_details', 'remarks', 'add_record_flag');
            $crud->edit_fields('enquiry_no', 'supplier_id', 'date', 'supplier_enquiry_details', 'remarks');
            $crud->required_fields('enquiry_no', 'supplier_id', 'date');
            $crud->set_relation('supplier_id', 'suppliers', 'supplier_name');
            $crud->set_relation('wo_no', 'workorder', 'wo_no');
            //$crud->set_relation('material_type', 'materials', 'type');
            //$crud->set_relation('size_round_type', 'size', 'wo_no');
            //$crud->set_relation('qty_type', 'qty', 'type');
            $crud->add_action('Create Supplier PO', '', '', 'ui-icon-plus', array($this, 'generateSupplierPO'));
            $crud->add_action('Print Preview', '', 'admin/supplierEnquiryPrintPreview', 'ui-icon-plus');

            $crud->callback_add_field('add_record_flag', function(){
                return '<input type="hidden" name="add_record_flag" value="new_record" />';
            });

            $crud->callback_add_field('enquiry_no', array($this, 'generateSupplierEnquiryNo'));
            $crud->callback_edit_field('enquiry_no', function($post_array){
                return '<input type="text" maxlength="200" value="'.$post_array.'" name="enquiry_no" readonly>';
            });

            $this->html_wo .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 800px;" class="supplier_enquiry_details">';
            $this->html_wo .= '<th>Item</th>';
            $this->html_wo .= '<th>Qty.</th>';
            $this->html_wo .= '<th>Unit</th>';

            $crud->callback_field('supplier_enquiry_details', function ($post_array) {
                $supplier_enquiry_details = (isset($this->data['supplier_enquiry_details'])) ? $this->data['supplier_enquiry_details'] : $post_array;

                //$this->html_wo .= '<tr ><td><input type="text" maxlength="200" value="' . $delivery . '" name="delivery" style="width:200px;"> </td>';
                //$this->html_wo .= '<input type="hidden" name="new_record_count" value="0" id="new_record_count" />';

                //checking for additional fields
                $additional_fields = @json_decode($this->data['supplier_enquiry'][0]['supplier_enquiry_details'], true);
                $additional_field_count = (count($additional_fields) == 0) ? 1 : count($additional_fields);
                $this->html_wo .= '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" />';

                //adding additional fields to the edit form
                if(!empty($additional_fields)){

                    foreach($additional_fields as $key => $val){
                        $key++;
                        $item = $val['item'];
                        $qty = $val['qty'];
                        $unit = $val['unit'];


                        $this->html_wo .= '<tr>';

                        $this->html_wo .= '<td><input type="text" value="'.$item.'" name="item'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="number" value="'.$qty.'" name="qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$unit.'" name="unit'.$key.'" class="suppEnqUnit" style="width:200px;"></td></tr>';


                    }
                    //$this->html_wo .= '</table>';
                }
                else {

                    $this->html_wo .= '<tr>';

                    $this->html_wo .= '<td><input type="text"  value="" name="item1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="number" value="" name="qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text"  value="" name="unit1" class="suppEnqUnit" style="width:200px;"></td></tr>';


                }
                $this->html_wo .= '<tr><td colspan="7"><input type="button" name="add_supplier_enquiry_details" id="add_supplier_enquiry_details_record" value="Add Record" /> </td></tr>';
                $this->html_wo .= '</table>';

                return $this->html_wo;
            });

            /*$crud->callback_field('size_round_type', function($post_array){
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
            });*/

            $crud->callback_insert(array($this, 'saveSupplierEnquiry'));
            $crud->callback_update(array($this, 'saveSupplierEnquiry'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function saveSupplierEnquiry($post_array)
    {
        try {

            $enquiry_no = $post_array['enquiry_no'];

            //looping/collecting dynamically generated data
            $new_record_count = $post_array['new_record_count'];
            for ($i = 1; $i <= $new_record_count; $i++) {
                if (isset($post_array['item' . $i]) && $post_array['item' . $i] != '') {
                    $additional_fields[] = array(
                        "item" => $post_array['item' . $i],
                        "qty" => $post_array['qty' . $i],
                        "unit" => $post_array['unit' . $i]
                    );
                }
            }

            $supplier_enquiry_details = json_encode($additional_fields);

            $update_supplier_enquiry_array = array(
                "enquiry_no" => $enquiry_no,
                "supplier_id" => $post_array['supplier_id'],
                "date" => date('Y-m-d', strtotime($post_array['date'])),
                "supplier_enquiry_details" => $supplier_enquiry_details,
                "remarks" => $post_array['remarks']
            );

            //print_r($update_wo_array);
            if($post_array['add_record_flag']){
                $update_result = $this->admin_model->insertRecord($update_supplier_enquiry_array, 'supplier_enquiry');
            }
            else{
                $update_result = $this->admin_model->updateRecord($update_supplier_enquiry_array, 'supplier_enquiry', 'enquiry_no', $enquiry_no);
            }

            /*echo $update_result;

            exit;*/
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function supplierEnquiryPrintPreview(){

        $supplier_enq_id = $this->uri->segment(3);
        $this->data['print_id'] = $supplier_enq_id;
        $this->data['supplier_enq'] = $this->admin_details->get_user_column_one('id', $supplier_enq_id, 'supplier_enquiry');
        $supplier_id = $this->data['supplier_enq'][0]['supplier_id'];
        $this->data['supplier_details'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        //print_r($this->data['supplier_details']);
        $enquiry_no = $this->data['supplier_enq'][0]['enquiry_no'];
        $this->data['print_func'] = 'printSupplierEnquiry';

        $this->data['render_template'] = $this->load->view('admin/client_enq_print_template', $this->data, true);
        $this->data['footer'] = $this->load->view('admin/print_preview_footer', $this->data, true);
        $this->load->view('admin/print_preview', $this->data);

    }

    public function printSupplierEnquiry(){
        $this->load->library('pdf');
        $supplier_enq_id = $this->uri->segment(3);
        $this->data['print_id'] = $supplier_enq_id;
        $this->data['supplier_enq'] = $this->admin_details->get_user_column_one('id', $supplier_enq_id, 'supplier_enquiry');
        $supplier_id = $this->data['supplier_enq'][0]['supplier_id'];
        $this->data['supplier_details'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        $enquiry_no = $this->data['supplier_enq'][0]['enquiry_no'];

        //$wo_id = $this->data['supplier_enq'][0]['wo_no'];
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        /*echo "<pre>";
        print_r($this->admin_details->getQuoteMiscellaneous($quotation_no));
        exit;*/
        //unset($this->data['quote_miscellaneous'][0]['quotation_no']);

        /*$material_type = $this->data['supplier_enq'][0]['material_type'];
        $qty_type = $this->data['supplier_enq'][0]['qty_type'];*/

        //creating query conditions to get values by passing id in where clause
        /*$query_array = array(
            array(
                "table_name" => "materials",
                "id" => $material_type
            ),
            array(
                "table_name" => "qty",
                "id" => $qty_type
            )
        );*/

        //getting types by passing query conditions
        /*$this->data['types'] = $this->admin_details->getMultipleSelect($query_array);*/

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
        $data = 'KALPSUPENQ'.date('my').str_pad($last_enq_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="enquiry_no" readonly>';
    }

    public function createSupplierPo(){
        try{

            $supplier_po_id = $this->uri->segment(4);
            $this->data['supplier_po'] = $this->admin_details->get_user_column_one('id', $supplier_po_id, 'po_supplier');
            
            /*
            if($this->data['supplier_po_id']){
                $this->data['supplier_po'] = $this->admin_details->get_user_column_one('id', $this->data['supplier_po_id'], 'po_supplier');
                $this->data['supplier_po_no'] = $this->data['supplier_po'][0]['po_no'];
                $this->data['supplier_id'] = $this->data['supplier_po'][0]['supplier_id'];
                $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $this->data['supplier_id'], 'suppliers');
                $this->data['supplier_name'] = $this->data['suppliers'][0]['supplier_name'];
            }
            */
            
            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('po_supplier');
            $crud->set_subject('SUPPLIER PO');
            $crud->columns('supplier_id', 'po_no', 'po_date', 'supplier_po_details','remarks');
            //$crud->add_fields('supplier_id', 'quotation_id', 'po_no', 'po_date', 'wo_id', 'material', 'material_type', 'size_round', 'size_round_type', 'size_thickness', 'size_thickness_type', 'size_width', 'size_width_type', 'qty', 'qty_type', 'rate', 'rate_type', 'delivery_period', 'delivery_type', 'taxes', 'payment_terms', 'transport_through');
            $crud->add_fields('supplier_id', 'quotation_no', 'po_no', 'po_date', 'supplier_po_details','remarks', 'terms_n_cond', 'format_no', 'add_record_flag');
            $crud->edit_fields('supplier_id', 'quotation_no', 'po_no', 'po_date', 'supplier_po_details', 'remarks', 'terms_n_cond', 'format_no');
            $crud->required_fields('supplier_id', 'quotation_no', 'po_no', 'po_date');
            $crud->set_relation('supplier_id', 'suppliers', 'supplier_name');
            //$crud->set_relation('wo_id', 'workorder', 'wo_no');
            //$crud->set_relation('quotation_id', 'quotation', 'quotation_no');
            //$crud->set_relation('material_type', 'materials', 'type');
            //$crud->set_relation('size_round_type', 'size', 'wo_no');
            //$crud->set_relation('qty_type', 'qty', 'type');
            //$crud->set_relation('rate_type', 'rate', 'type');
            //$crud->set_relation('delivery_type', 'delivery', 'type');
            $crud->add_action('Print Preview', '', 'admin/printPreviewSuppPO', 'ui-icon-plus');
            $crud->add_action('Create Inspection Report', '', '', 'ui-icon-plus', array($this, 'generateInspectionReport'));

            if($this->input->get('supplier_enq_id'))
            {
                $supplier_enq_id = $this->input->get('supplier_enq_id');
                $this->data['supplier_enquiry'] = $this->admin_details->get_user_column_one('id', $supplier_enq_id, 'supplier_enquiry');
                $supplier_id = $this->data['supplier_enquiry'][0]['supplier_id'];
                $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
                
                $crud->callback_add_field('supplier_id', function(){
                    return '<input type="hidden" name="supplier_id" id="supplier_id" value="'.$this->data['suppliers'][0]['id'].'" />' . $this->data['suppliers'][0]['supplier_name'];
                });
            }
            
            $crud->callback_add_field('po_no', array($this, 'generateSupplierPONo'));
            $crud->callback_add_field('quotation_no', array($this, 'generateSupplierPOQuoteNo'));

            $crud->callback_add_field('add_record_flag', function(){
               return '<input type="hidden" name="add_record_flag" value="new_record" />';
            });
            /*$crud->callback_field('size_round_type', function($post_array){
                $this->html_wo = '<select name="size_round_type">';
                $this->html_wo .= '<option value="mm">mm</option>';
                $this->html_wo .= '<option value="inch">inch</option>';
                $this->html_wo .= '</select>';

                return $this->html_wo;
            });*/

            $this->html_wo .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 800px;" class="supplier_po_details">';
            $this->html_wo .= '<th>Item</th>';
            $this->html_wo .= '<th>Qty.</th>';
            $this->html_wo .= '<th>Unit</th>';
            $this->html_wo .= '<th>Rate</th>';

            $crud->callback_field('supplier_po_details', function ($post_array) {
                $supplier_po_details = (isset($this->data['supplier_po_details'])) ? $this->data['supplier_po_details'] : $post_array;

                //$this->html_wo .= '<tr ><td><input type="text" maxlength="200" value="' . $delivery . '" name="delivery" style="width:200px;"> </td>';
                //$this->html_wo .= '<input type="hidden" name="new_record_count" value="0" id="new_record_count" />';

                //checking for additional fields
                $additional_fields = @json_decode($this->data['supplier_po'][0]['supplier_po_details'], true);
                $additional_field_count = (count($additional_fields) == 0) ? 1 : count($additional_fields);
                $this->html_wo .= '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" />';

                //adding additional fields to the edit form
                if(!empty($additional_fields)){

                    foreach($additional_fields as $key => $val){
                        $key++;
                        $item = $val['item'];
                        $qty = $val['qty'];
                        $unit = $val['unit'];
                        $rate = $val['rate'];


                        $this->html_wo .= '<tr>';

                        $this->html_wo .= '<td><input type="text" value="'.$item.'" name="item'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$qty.'" name="qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$unit.'" name="unit'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$rate.'" name="rate'.$key.'" style="width:200px;"></td></tr>';


                    }
                    //$this->html_wo .= '</table>';
                }
                else {

                    $this->html_wo .= '<tr>';

                    $this->html_wo .= '<td><input type="text"  value="" name="item1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" value="" name="qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text"  value="" name="unit1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text"  value="" name="rate1" style="width:200px;"></td></tr>';


                }
                $this->html_wo .= '<tr><td colspan="7"><input type="button" name="add_supplier_po_details" id="add_supplier_po_details_record" value="Add Record" /> </td></tr>';
                $this->html_wo .= '</table>';

                return $this->html_wo;
            });

            $crud->callback_field('terms_n_cond', function($post_array){

              return $this->createTerms('terms_n_cond', $post_array, $this->data['supplier_po']);

            });

            /*$crud->callback_field('size_thickness_type', function($post_array){
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
            });*/

            $crud->callback_insert(array($this, 'saveSupplierPO'));
            $crud->callback_update(array($this, 'saveSupplierPO'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function generateInspectionReport($primary_key, $row){
        /*echo "<pre>";
        print_r($row);*/
        return site_url('admin/inspectionReport/add/').'?supplier_po_id='.$row->id;
    }
    
    public function generateSupplierPO($primary_key, $row){
        /*echo "<pre>";
        print_r($row);*/
        return site_url('admin/createSupplierPo/add/').'?supplier_enq_id='.$row->id;
    }

    public function saveSupplierPO($post_array)
    {

        $po_no = $post_array['po_no'];

        //looping/collecting dynamically generated data
        $new_record_count = $post_array['new_record_count'];
        for ($i = 1; $i <= $new_record_count; $i++) {
            if(isset($post_array['item' . $i]) && $post_array['item' . $i] != '') {
                $additional_fields[] = array(
                    "item" => $post_array['item' . $i],
                    "qty" => $post_array['qty' . $i],
                    "unit" => $post_array['unit' . $i],
                    "rate" => $post_array['rate' . $i]
                );
            }
        }

        //looping/collecting dynamically generated terms and condition
        $terms_new_record_count = $post_array['terms_new_record_count'];
        for ($i = 1; $i <= $terms_new_record_count; $i++) {
            if(isset($post_array['terms' . $i]) && $post_array['terms' . $i] != '') {
                $additional_fields_terms[] = array(
                    "terms" => $post_array['terms' . $i]
                );
            }
        }

        $supplier_po_details = json_encode($additional_fields);
        $terms_n_cond = json_encode($additional_fields_terms);

        $update_supplier_po_array = array(
            "supplier_id" => $post_array['supplier_id'],
            "quotation_no" => $post_array['quotation_no'],
            "po_no" => $po_no,
            "po_date" => $post_array['po_date'],
            "supplier_po_details" => $supplier_po_details,
            "remarks" => $post_array['remarks'],
            "format_no" => mysql_real_escape_string($post_array['format_no']),
            "terms_n_cond" => $terms_n_cond
        );

        //print_r($update_supplier_po_array);


        if($post_array['add_record_flag']){
            $result = $this->admin_model->insertRecord($update_supplier_po_array, 'po_supplier');
        }
        else {
            $result = $this->admin_model->updateRecord($update_supplier_po_array, 'po_supplier', 'po_no', $po_no);
        }

        /*echo $result;

        exit;*/
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

    /**
     * @return string
     */
    public function generateSupplierPOQuoteNo(){
        $last_enquiry_details = $this->admin_model->getLastId('po_supplier');
        $last_enq_id = (!empty($last_enquiry_details)) ? $last_enquiry_details[0]['id'] + 1 : 1;
        $data = 'KALPSUPQUOTE'.date('my').str_pad($last_enq_id,3,'0', STR_PAD_LEFT);
        return '<input type="text" maxlength="200" value="'.$data.'" name="quotation_no" readonly>';
    }

    public function printPreviewSuppPO(){
        $supplier_po_id = $this->uri->segment(3);
        $this->data['print_id'] = $supplier_po_id;
        $this->data['supplier_po'] = $this->admin_details->get_user_column_one('id', $supplier_po_id, 'po_supplier');
        $supplier_id = $this->data['supplier_po'][0]['supplier_id'];
        $quotation_no = $this->data['supplier_po'][0]['quotation_no'];
        $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        //$this->data['quotation'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
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
        $this->data['created_by'] = $this->nativesession->getSession('admincredfname');

        //creating query conditions to get values by passing id in where clause
        /*$query_array = array(
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
        );*/

        $this->data['print_func'] = 'printSupplierPO';

        //getting types by passing query conditions
        //$this->data['types'] = $this->admin_details->getMultipleSelect($query_array);
        $this->data['render_template'] = $this->load->view('admin/supplier_po_print_template', $this->data, true);
        $this->data['footer'] = $this->load->view('admin/print_preview_footer', $this->data, true);
        $this->load->view('admin/print_preview', $this->data);
    }

    public function printSupplierPO(){
        $this->load->library('pdf');
        $supplier_po_id = $this->uri->segment(3);
        $this->data['print_id'] = $supplier_po_id;
        $this->data['supplier_po'] = $this->admin_details->get_user_column_one('id', $supplier_po_id, 'po_supplier');
        $supplier_id = $this->data['supplier_po'][0]['supplier_id'];
        //$quotation_id = $this->data['supplier_po'][0]['quotation_id'];
        $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        //$this->data['quotation'] = $this->admin_details->get_user_column_one('id', $quotation_id, 'quotation');
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
        $this->data['created_by'] = htmlspecialchars($this->nativesession->getSession('admincredfname'));

        //getting types by passing query conditions
        //$this->data['types'] = $this->admin_details->getMultipleSelect($query_array);

        $this->data['render_template'] = $this->load->view('admin/supplier_po_print_template', $this->data, true);
        $this->pdf->load_view('admin/supplier_po_print_preview', $this->data);
        $this->pdf->set_paper("a4", "portrait");
        $this->pdf->render();
        $this->pdf->stream($po_no.".pdf");

    }

    public function inspectionReport(){
        try{

            $inspection_report_id = $this->uri->segment(4);
            $this->data['inspection_report'] = $this->admin_details->get_user_column_one('id', $inspection_report_id, 'inspection_report');
            $this->data['supplier_po_id'] = ($this->input->get('supplier_po_id')) ? @$this->input->get('supplier_po_id') : @$this->data['inspection_report'][0]['supplier_po_id'];

            if($this->data['supplier_po_id']){
                $this->data['supplier_po'] = $this->admin_details->get_user_column_one('id', $this->data['supplier_po_id'], 'po_supplier');
                $this->data['supplier_po_no'] = $this->data['supplier_po'][0]['po_no'];
                $this->data['supplier_id'] = $this->data['supplier_po'][0]['supplier_id'];
                $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $this->data['supplier_id'], 'suppliers');
                $this->data['supplier_name'] = $this->data['suppliers'][0]['supplier_name'];
            }

            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('inspection_report');
            $crud->set_subject('INSPECTION REPORT');
            $crud->columns('supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name', 'created_date');
            //$crud->add_fields('supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name', 'lr_no', 'lr_date', 'dc_qty', 'received_qty', 'received_qty_type', 'remarks', 'rejection_reason', 'wo_id', 'dimension', 'dimension_type', 'dimension_width', 'dimension_length');
            $crud->add_fields('supplier_po_id', 'supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name', 'lr_no', 'lr_date', 'inspection_report_details', 'remarks', 'format_no', 'created_date', 'add_record_flag');
            $crud->edit_fields('supplier_po_id', 'supplier_id', 'supplier_dc_no', 'supplier_dc_date', 'transporter_name', 'lr_no', 'lr_date', 'inspection_report_details', 'remarks', 'format_no', 'created_date');
            $crud->required_fields('supplier_id', 'supplier_dc_no', 'supplier_dc_date');
            $crud->set_relation('supplier_id', 'suppliers', 'supplier_name');
            //$crud->set_relation('wo_id', 'workorder', 'wo_no');
            //$crud->set_relation('quotation_id', 'quotation', 'quotation_no');
            //$crud->set_relation('size_round_type', 'size', 'wo_no');
            //$crud->set_relation('received_qty_type', 'qty', 'type');
            //$crud->set_relation('dimension_type', 'dimension', 'type');

            $crud->callback_field('supplier_id', function(){
                return '<input type="hidden" name="supplier_id" value="'.$this->data['supplier_id'].'" >'.$this->data['supplier_name'];
            });

            $crud->callback_field('supplier_po_id', function(){
                return '<input type="hidden" name="supplier_po_id" value="'.$this->data['supplier_po_id'].'" >' . $this->data['supplier_po_no'];
            });

            $crud->callback_field('created_date', function(){
                $created_date = ($this->data['inspection_report'][0]['created_date']!= NULL) ? $this->data['inspection_report'][0]['created_date'] : date('d-m-Y');
                return '<input type="hidden" name="created_date" value="'.$created_date.'" />'.date('d-m-Y', strtotime($created_date));
            });

            $crud->callback_add_field('add_record_flag', function(){
                return '<input type="hidden" name="add_record_flag" value="new_record" />';
            });

            $this->html_wo .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 800px;" class="inspection_report_details">';
            $this->html_wo .= '<th>Item</th>';
            $this->html_wo .= '<th>DC Qty.</th>';
            $this->html_wo .= '<th>Recd Qty</th>';
            $this->html_wo .= '<th>Recd Material Dimention</th>';
            $this->html_wo .= '<th>Remarks</th>';

            $crud->callback_field('inspection_report_details', function ($post_array) {
                //$supplier_po_details = (isset($this->data['supplier_po_details'])) ? $this->data['supplier_po_details'] : $post_array;

                //$this->html_wo .= '<tr ><td><input type="text" maxlength="200" value="' . $delivery . '" name="delivery" style="width:200px;"> </td>';
                //$this->html_wo .= '<input type="hidden" name="new_record_count" value="0" id="new_record_count" />';

                //checking for additional fields
                $additional_fields = @json_decode($this->data['inspection_report'][0]['inspection_report_details'], true);
                $additional_field_count = (count($additional_fields) == 0) ? 1 : count($additional_fields);
                $this->html_wo .= '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" />';

                //adding additional fields to the edit form
                if(!empty($additional_fields)){

                    foreach($additional_fields as $key => $val){
                        $key++;
                        $item = $val['item'];
                        $dc_qty = $val['dc_qty'];
                        $recd_qty = $val['recd_qty'];
                        $recd_material_dim = $val['recd_material_dim'];
                        $remarks = $val['remarks'];
                        $remarks_remarks = $val['remarks_remarks'];
                        //echo "$key :" . $remarks."<br>";
                        if($remarks == '0'){
                            $selected_ok = 'selected';
                        }
                        else if($remarks == '1'){

                            $selected_reject = 'selected';

                        }
                        $ok = '0';
                        $reject = '1';


                        $this->html_wo .= '<tr>';

                        $this->html_wo .= '<td><input type="text" value="'.$item.'" name="item'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$dc_qty.'" name="dc_qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$recd_qty.'" name="recd_qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" value="'.$recd_material_dim.'" name="recd_material_dim'.$key.'" style="width:200px;"></td>';
                        //$this->html_wo .= '<td><input type="text" value="'.$remarks.'" name="remarks'.$key.'" style="width:200px;"></td></tr>';
                        $this->html_wo .= '<td><select id="remarks'.$key.'" name="remarks'.$key.'" class="inspection_report_remarks"><option value="'.$ok.'" '.@$selected_ok.'>OK</option><option value="'.$reject.'" '.@$selected_reject.'>Reject</option></select>';
                        if($remarks=='1'){
                            $this->html_wo .= '<textarea name="remarks_remarks'.$key.'" id="remarks_remarks'.$key.'" style="width:200px;">'.$remarks_remarks.'</textarea>';
                        }


                        $this->html_wo .= '</td></tr>';

                        //unsetting remarks variable
                        unset($selected_ok);
                        unset($selected_reject);


                    }
                    //$this->html_wo .= '</table>';
                }
                else {

                    $this->html_wo .= '<tr>';

                    $this->html_wo .= '<td><input type="text"  value="" name="item1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" value="" name="dc_qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text"  value="" name="recd_qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text"  value="" name="recd_material_dim1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><select id="remarks1" name="remarks1" class="inspection_report_remarks"><option value="">Select</option><option value="0">OK</option><option value="1">Reject</option></select></td></tr>';


                }
                $this->html_wo .= '<tr><td colspan="7"><input type="button" name="add_inspection_report_details" id="add_inspection_report_details_record" value="Add Record" /> </td></tr>';
                $this->html_wo .= '</table>';

                return $this->html_wo;
            });

            /*$crud->callback_field('size_thickness_type', function($post_array){
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
            });*/

            //$crud->callback_insert(array($this, 'saveSupplierPO'));
            //$crud->callback_update(array($this, 'saveSupplierPO'));

            /*$crud->callback_field('size_round_type', function($post_array){
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
            });*/

            $crud->callback_insert(array($this, 'saveInspectionReport'));
            $crud->callback_update(array($this, 'saveInspectionReport'));

            $crud->add_action('Print Preview', '', 'admin/previewInspectionReport', 'ui-icon-plus');

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function saveInspectionReport($post_array)
    {

        $id = $this->uri->segment(4);

        //print_r($id);

        //looping/collecting dynamically generated data
        $new_record_count = $post_array['new_record_count'];
        for ($i = 1; $i <= $new_record_count; $i++) {
            if(isset($post_array['item' . $i]) && $post_array['item' . $i] != '') {
                $additional_fields[] = array(
                    "item" => $post_array['item' . $i],
                    "dc_qty" => $post_array['dc_qty' . $i],
                    "recd_qty" => $post_array['recd_qty' . $i],
                    "recd_material_dim" => $post_array['recd_material_dim' . $i],
                    "remarks" => $post_array['remarks' . $i],
                    "remarks_remarks" => ($post_array['remarks_remarks' . $i]) ? $post_array['remarks_remarks' . $i] : ''
                );
            }
        }

        $inspection_report_details = json_encode($additional_fields);

        //echo $inspection_report_details;

        $update_inspection_report_array = array(
            "id" => $id,
            "supplier_id" => $post_array['supplier_id'],
            "supplier_po_id" => $post_array['supplier_po_id'],
            "supplier_dc_no" => $post_array['supplier_dc_no'],
            "supplier_dc_date" => $post_array['supplier_dc_date'],
            "transporter_name" => $post_array['transporter_name'],
            "lr_no" => $post_array['lr_no'],
            "lr_date" => $post_array['lr_date'],
            "inspection_report_details" => $inspection_report_details,
            "remarks" => $post_array['remarks'],
            "format_no" => mysql_real_escape_string($post_array['format_no']),
            "created_date" => $post_array['created_date']
        );

        /*print_r($update_inspection_report_array);
        exit;*/


        if($post_array['add_record_flag']){
            $result = $this->admin_model->insertRecord($update_inspection_report_array, 'inspection_report');
        }
        else {
            $result = $this->admin_model->updateRecord($update_inspection_report_array, 'inspection_report', 'id', $id);
        }

        /*echo $result;

        exit;*/
    }

    public function previewInspectionReport(){
        $inspection_report_id = $this->uri->segment(3);
        $this->data['print_id'] = $inspection_report_id;
        $this->data['inspection_report'] = $this->admin_details->get_user_column_one('id', $inspection_report_id, 'inspection_report');
        $supplier_id = $this->data['inspection_report'][0]['supplier_id'];
        $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        $this->data['print_func'] = 'printInspectionReport';

        //getting types by passing query conditions
        $this->data['render_template'] = $this->load->view('admin/inspection_report_print_template', $this->data, true);
        $this->data['footer'] = $this->load->view('admin/print_preview_footer', $this->data, true);
        $this->load->view('admin/print_preview', $this->data);
    }

    public function printInspectionReport(){
        $this->load->library('pdf');
        $inspection_report_id = $this->uri->segment(3);
        $this->data['print_id'] = $inspection_report_id;
        $this->data['inspection_report'] = $this->admin_details->get_user_column_one('id', $inspection_report_id, 'inspection_report');
        $supplier_id = $this->data['inspection_report'][0]['supplier_id'];
        $this->data['suppliers'] = $this->admin_details->get_user_column_one('id', $supplier_id, 'suppliers');
        /*$supplier_name = $this->data['suppliers'][0]['supplier_name'];
        $supplier_name = $this->data['suppliers'][0]['supplier_name'];*/
        //$this->data['quote_miscellaneous'] = $this->admin_details->get_user_column_one('quotation_no', $quotation_no, 'quotation_miscellaneous');
        /*echo "<pre>";
        print_r($this->admin_details->getQuoteMiscellaneous($quotation_no));
        exit;*/

        $this->data['print_func'] = 'printInspectionReport';

        //getting types by passing query conditions
        //$this->data['types'] = $this->admin_details->getMultipleSelect($query_array);
        $this->data['render_template'] = $this->load->view('admin/inspection_report_print_template', $this->data, true);
        $this->pdf->load_view('admin/print_preview', $this->data);
        $this->pdf->set_paper("a4", "portrait");
        $this->pdf->render();
        $this->pdf->stream("KALPINSREPORT".$inspection_report_id.".pdf");

    }

    public function outSource(){
        try{

            //$_GET id from URL
            $outsource_id = $this->uri->segment(4);
            //create an array of all the columns from outsource table

            if ($outsource_id) {
                $this->data['outsource'] = $this->admin_details->get_user_column_one('id', $outsource_id, 'outsource');
            }

            $crud = new grocery_CRUD();

            $crud->set_theme('datatables');
            $crud->set_table('outsource');
            $crud->set_subject('OUTSOURCE');
            $crud->columns('wo_id', 'vendor_id', 'labour_job_challan_no', 'lic_date');
            $crud->add_fields('wo_id', 'vendor_id', 'labour_job_challan_no', 'lic_date', 'outsource_details', 'add_record_flag');
            //$crud->edit_fields('wo_id', 'vendor_id', 'labour_job_chain_no', 'lic_date', 'material_details', 'sent_qty', 'what_to_make', 'supplier_dc_no', 'supplier_dc_date', 'received_qty_dc', 'qty_type');
            $crud->edit_fields('wo_id', 'vendor_id', 'labour_job_challan_no', 'lic_date', 'outsource_details');
            $crud->required_fields('vendor_id', 'supplier_dc_no', 'supplier_dc_date');
            //$crud->set_relation('wo_id', 'suppliers_id', 'supplier_dc_no', 'supplier_dc_date');
            $crud->set_relation('wo_id', 'workorder', 'wo_no');
            $crud->set_relation('vendor_id', 'vendors', 'vendor_name');
            //$crud->set_relation('qty_type', 'qty', 'type');
            $crud->add_action('Print Preview', '', 'admin/outsourcePrintPreview', 'ui-icon-plus');

            $crud->callback_add_field('add_record_flag', function(){
                return '<input type="hidden" name="add_record_flag" value="new_record" />';
            });
            //creating table structure for outsource miscellaneous details
            $this->html_wo .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 800px;" class="outsource_details">';
            $this->html_wo .= '<th>Work Order</th>';
            $this->html_wo .= '<th>Items</th>';
            $this->html_wo .= '<th>Type of Process / parts</th>';
            $this->html_wo .= '<th>Dispatched Qty</th>';
            $this->html_wo .= '<th>Require Qty</th>';
            $this->html_wo .= '<th>Job Worker DC No</th>';
            $this->html_wo .= '<th>Job Worker DC Date</th>';
            $this->html_wo .= '<th>Received Qty</th>';
            $this->html_wo .= '<th>Require Dimension</th>';
            $this->html_wo .= '<th>Recd Dim 1</th>';
            $this->html_wo .= '<th>Recd Dim 2</th>';
            $this->html_wo .= '<th>Recd Dim 3</th>';
            $this->html_wo .= '<th>Recd Dim 4</th>';
            $this->html_wo .= '<th>Recd Dim 5</th>';
            $this->html_wo .= '<th>Remarks</th>';

            $crud->callback_field('outsource_details', function ($post_array) {
                //checking for additional fields
                $additional_fields = @json_decode($this->data['outsource'][0]['outsource_details'], true);
                $additional_field_count = (count($additional_fields) == 0) ? 1 : count($additional_fields);
                $this->html_wo .= '<input type="hidden" name="new_record_count" value="'.$additional_field_count.'" id="new_record_count" />';

                //getting all work order records
                $wo_details = $this->admin_model->getAllValues('workorder');

                $wo_arr = array_map(function($data_wo_id, $data_wo_no){
                            return array("id" => $data_wo_id['id'], "no" => $data_wo_no['wo_no']);
                        }, $wo_details, $wo_details);

                //adding additional fields to the edit form
                if(!empty($additional_fields)){

                    foreach($additional_fields as $key => $val){
                        $key++;
                        $wo = $val['wo'];
                        $item = $val['item'];
                        $types_process_parts = $val['types_process_parts'];
                        $dispatched_qty = $val['dispatched_qty'];
                        $require_qty = $val['require_qty'];
                        $job_worker_dc_no = $val['job_worker_dc_no'];
                        $job_worker_dc_date = $val['job_worker_dc_date'];
                        $recd_qty = $val['recd_qty'];
                        $require_dim = $val['require_dim'];
                        $require_dim1 = $val['recd_dim1-'.$key];
                        $require_dim2 = $val['recd_dim2-'.$key];
                        $require_dim3 = $val['recd_dim3-'.$key];
                        $require_dim4 = $val['recd_dim4-'.$key];
                        $require_dim5 = $val['recd_dim5-'.$key];
                        $remarks = $val['remarks'];
                        $remarks_remarks = $val['remarks_remarks'];
                        //echo "$key :" . $remarks."<br>";
                        if($remarks == '0'){
                            $selected_ok = 'selected';
                        }
                        else if($remarks == '1'){

                            $selected_reject = 'selected';

                        }
                        $ok = '0';
                        $reject = '1';

                        //generating select list from wo_details array
                        $select_list = '<select class="wo_outsource" name="wo'.$key.'">';
                        foreach($wo_arr as $key_wo => $val_wo){
                            $wo_id = $val_wo['id'];
                            $wo_no = $val_wo['no'];
                            if($wo_id == $wo) { $selected = 'selected="selected"'; } else {$selected = '';}
                            $select_list .= '<option value="'.$wo_id.'" ' .$selected. '>'.$wo_no.'</option>';
                        }
                        $select_list .= '</select>';

                        $this->html_wo .= '<tr>';

                        $this->html_wo .= '<td>'.$select_list.'</td>';

                        $this->html_wo .= '<td><input type="text" class="item_outsource" value="'.$item.'" name="item'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="types_process_parts_outsource" value="'.$types_process_parts.'" name="types_process_parts'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="dispatched_qty_outsource" value="'.$dispatched_qty.'" name="dispatched_qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="require_qty_outsource" value="'.$require_qty.'" name="require_qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="job_worker_dc_no_outsource" value="'.$job_worker_dc_no.'" name="job_worker_dc_no'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="job_worker_dc_date_outsource" value="'.$job_worker_dc_date.'" name="job_worker_dc_date'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="recd_qty_outsource" value="'.$recd_qty.'" name="recd_qty'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="require_dim1_outsource" value="'.$require_dim.'" name="require_dim'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="recd_dim1_outsource" value="'.$require_dim1.'" name="recd_dim1-'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="recd_dim2_outsource" value="'.$require_dim2.'" name="recd_dim2-'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="recd_dim3_outsource" value="'.$require_dim3.'" name="recd_dim3-'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="recd_dim4_outsource" value="'.$require_dim4.'" name="recd_dim4-'.$key.'" style="width:200px;"></td>';
                        $this->html_wo .= '<td><input type="text" class="recd_dim5_outsource" value="'.$require_dim5.'" name="recd_dim5-'.$key.'" style="width:200px;"></td>';
                        //$this->html_wo .= '<td><input type="text" value="'.$remarks.'" name="remarks'.$key.'" style="width:200px;"></td></tr>';
                        $this->html_wo .= '<td><select id="remarks'.$key.'" name="remarks'.$key.'" class="outsource_remarks"><option value="'.$ok.'" '.@$selected_ok.'>OK</option><option value="'.$reject.'" '.@$selected_reject.'>Reject</option></select>';
                        if($remarks=='1'){
                            $this->html_wo .= '<textarea name="remarks_remarks'.$key.'" id="remarks_remarks'.$key.'" style="width:200px;">'.$remarks_remarks.'</textarea>';
                        }


                        $this->html_wo .= '</td></tr>';

                        //unsetting remarks variable
                        unset($selected_ok);
                        unset($selected_reject);


                    }
                    //$this->html_wo .= '</table>';
                }
                else {
                    //generating select list from wo_details array
                    $select_list = '<select class="wo_outsource" name="wo">';
                    foreach($wo_arr as $key_wo => $val_wo){
                        $wo_id = $val_wo['id'];
                        $wo_no = $val_wo['no'];
                        $select_list .= '<option value="'.$wo_id.'">'.$wo_no.'</option>';
                    }
                    $select_list .= '</select>';

                    $this->html_wo .= '<tr>';
                    $this->html_wo .= '<td>'.$select_list.'</td>';
                    $this->html_wo .= '<td><input type="text" class="item_outsource" value="" name="item1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="types_process_parts_outsource" value="" name="types_process_parts1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="dispatched_qty_outsource" value="" name="dispatched_qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="require_qty_outsource" value="" name="require_qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="job_worker_dc_no_outsource" value="" name="job_worker_dc_no1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="job_worker_dc_date_outsource" value="" name="job_worker_dc_date1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="recd_qty_outsource" value="" name="recd_qty1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="require_dim1_outsource" value="" name="require_dim1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="recd_dim1_outsource" value="" name="recd_dim1-1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="recd_dim2_outsource" value="" name="recd_dim2-1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="recd_dim3_outsource" value="" name="recd_dim3-1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="recd_dim4_outsource" value="" name="recd_dim4-1" style="width:200px;"></td>';
                    $this->html_wo .= '<td><input type="text" class="recd_dim5_outsource" value="" name="recd_dim5-1" style="width:200px;"></td>';

                    $this->html_wo .= '<td><select id="remarks1" name="remarks1" class="outsource_remarks"><option value="">Select</option><option value="0">OK</option><option value="1">Reject</option></select></td></tr>';


                }
                $this->html_wo .= '<tr><td colspan="7"><input type="button" name="add_outsource_details" id="add_outsource_details_record" value="Add Record" /> </td></tr>';
                $this->html_wo .= '</table>';

                return $this->html_wo;
            });

            $crud->callback_insert(array($this, 'saveOutsource'));
            $crud->callback_update(array($this, 'saveOutsource'));

            $output = $crud->render();

            $this->outputView('admin/edit_products', $output);

//            $this->_example_output($output);
        }
        catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    public function saveOutsource($post_array)
    {

        try {
            $id = $this->uri->segment(4);

            //print_r($id);

            //looping/collecting dynamically generated data
            $new_record_count = $post_array['new_record_count'];
            for ($i = 1; $i <= $new_record_count; $i++) {
                if (isset($post_array['item' . $i]) && $post_array['item' . $i] != '') {
                    $additional_fields[] = array(
                        "wo" => $post_array['wo' . $i],
                        "item" => $post_array['item' . $i],
                        "types_process_parts" => $post_array['types_process_parts' . $i],
                        "recd_qty" => $post_array['recd_qty' . $i],
                        "dispatched_qty" => $post_array['dispatched_qty' . $i],
                        "require_qty" => $post_array['require_qty' . $i],
                        "job_worker_dc_no" => $post_array['job_worker_dc_no' . $i],
                        "job_worker_dc_date" => $post_array['job_worker_dc_date' . $i],
                        "require_dim" => $post_array['require_dim' . $i],
                        "recd_dim1-".$i => mysql_real_escape_string($post_array['recd_dim1-' . $i]),
                        "recd_dim2-".$i => mysql_real_escape_string($post_array['recd_dim2-' . $i]),
                        "recd_dim3-".$i => mysql_real_escape_string($post_array['recd_dim3-' . $i]),
                        "recd_dim4-".$i => mysql_real_escape_string($post_array['recd_dim4-' . $i]),
                        "recd_dim5-".$i => mysql_real_escape_string($post_array['recd_dim5-' . $i]),
                        "remarks" => $post_array['remarks' . $i],
                        "remarks_remarks" => ($post_array['remarks_remarks' . $i]) ? $post_array['remarks_remarks' . $i] : ''
                    );
                }
            }

            $outsource_details = json_encode($additional_fields);

            //echo $inspection_report_details;

            $inspection_report_array = array(
                "id" => $id,
                "wo_id" => $post_array['wo_id'],
                "vendor_id" => $post_array['vendor_id'],
                "labour_job_challan_no" => $post_array['labour_job_challan_no'],
                "lic_date" => date('Y-m-d', strtotime($post_array['lic_date'])),
                "outsource_details" => $outsource_details,
                "remarks" => $post_array['remarks'],
                "created_date" => date('Y-m-d H:m:i')
            );

            /*echo "<pre>";
            print_r($inspection_report_array);
            print_r($additional_fields);
            exit;*/


            if ($post_array['add_record_flag']) {
                $result = $this->admin_model->insertRecord($inspection_report_array, 'outsource');
            } else {
                unset($inspection_report_array['created_date']);
                $inspection_report_array['updated_date'] = date('Y-m-d H:m:i');
                $result = $this->admin_model->updateRecord($inspection_report_array, 'outsource', 'id', $id);
            }
        }
        catch(Exception $e){
            show_error($e->getMessage() . " -- " . $e->getTraceAsString());
        }

        /*echo $result;

        exit;*/
    }

    public function outsourcePrintPreview(){
        $outsource_id = $this->uri->segment(3);
        $this->data['print_id'] = $outsource_id;
        $this->data['outsource'] = $this->admin_details->get_user_column_one('id', $outsource_id, 'outsource');
        //getting workorder no from workorder ids
        $wo_data = array();
        foreach(json_decode($this->data['outsource'][0]['outsource_details'], true) as $key => $value){
            $wo_id = $value['wo'];
            $wo_data[$wo_id] = $this->admin_details->get_user_column_one('id', $wo_id, 'workorder');
        }
        $this->data['wo_data'] = $wo_data;
        $vendor_id = $this->data['outsource'][0]['vendor_id'];
        $this->data['vendor'] = $this->admin_details->get_user_column_one('id', $vendor_id, 'vendors');
        $this->data['print_func'] = 'printOutsource';

        //getting types by passing query conditions
        $this->data['render_template'] = $this->load->view('admin/outsource_print_template', $this->data, true);
        $this->data['footer'] = $this->load->view('admin/print_preview_footer', $this->data, true);
        $this->load->view('admin/print_preview', $this->data);
    }

    public function printOutsource(){
        $this->load->library('pdf');
        $outsource_id = $this->uri->segment(3);
        $this->data['print_id'] = $outsource_id;
        $this->data['outsource'] = $this->admin_details->get_user_column_one('id', $outsource_id, 'outsource');
        //getting workorder no from workorder ids
        $wo_data = array();
        foreach(json_decode($this->data['outsource'][0]['outsource_details'], true) as $key => $value){
            $wo_id = $value['wo'];
            $wo_data[$wo_id] = $this->admin_details->get_user_column_one('id', $wo_id, 'workorder');
        }
        $this->data['wo_data'] = $wo_data;
        $vendor_id = $this->data['outsource'][0]['vendor_id'];
        $this->data['vendor'] = $this->admin_details->get_user_column_one('id', $vendor_id, 'vendors');
        $this->data['print_func'] = 'printOutsource';

        //getting types by passing query conditions
        $this->data['render_template'] = $this->load->view('admin/outsource_print_template', $this->data, true);
        $this->pdf->load_view('admin/print_preview', $this->data);
        $this->pdf->set_paper("a4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("KALPOUTSOURCE".$outsource_id.".pdf");
    }

    private function createTerms($column_name,$post_array,$current_record_array)
    {

        try {
            $terms = (isset($this->data[$column_name])) ? $this->data[$column_name] : $post_array;

            //checking for additional fields
            $additional_fields = @json_decode($terms, true);
            //print_r($additional_fields);

            $additional_field_count = (count($additional_fields) == 0) ? 1 : count($additional_fields);
            $this->terms_n_cond = '<input type="hidden" name="terms_new_record_count" value="' . $additional_field_count . '" id="terms_new_record_count" />';

            $this->terms_n_cond .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width: 800px;" class="terms_n_conditions">';
            $this->terms_n_cond .= '<th>Terms & Conditions</th>';

            //adding additional fields to the edit form
            if (!empty($additional_fields)) {

                foreach ($additional_fields as $key => $val) {
                    $key++;
                    $termsncond = $val['terms'];

                    $this->terms_n_cond .= '<tr>';
                    $this->terms_n_cond .= '<td><input type="text" value="' . $termsncond . '" name="terms' . $key . '" style="width:100%;"></td></tr>';


                }
            } else {

                $this->terms_n_cond .= '<tr>';
                $this->terms_n_cond .= '<td><input type="text"  value="" name="terms1" style="width:100%;"></td></tr>';


            }
            $this->terms_n_cond .= '<tr><td colspan="7"><input type="button" name="add_terms" id="add_terms_record" value="Add Terms" /> </td></tr>';
            $this->terms_n_cond .= '</table>';

            return $this->terms_n_cond;

        } catch (Exception $e) {
            log_error("Create Terms Error Msg - " . $e->getMessage());
            log_error("Create Terms Error Line No. - " . $e->getLine());
            log_error("Create Terms Code - " . $e->getCode());
        }
    }

    public function getWorkOrderList(){
        $wo_details = $this->admin_model->getAllValues('workorder');

        //generating select list from wo_details array
        $wo_arr = array_map(function($data_wo_id, $data_wo_no){
            return array("id" => $data_wo_id['id'], "no" => $data_wo_no['wo_no']);
        }, $wo_details, $wo_details);

        $select_list = '<select class="wo_outsource" name="wo">';
        foreach($wo_arr as $key_wo => $val_wo){
            $wo_id = $val_wo['id'];
            $wo_no = $val_wo['no'];
            $select_list .= '<option value="'.$wo_id.'">'.$wo_no.'</option>';
        }
        $select_list .= '</select>';

        echo $select_list;
    }

}