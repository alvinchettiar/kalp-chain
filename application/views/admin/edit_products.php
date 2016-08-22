<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
    <style type='text/css'>
        body
        {
            font-family: Arial;
            font-size: 14px;
        }
        a {
            color: blue;
            text-decoration: none;
            font-size: 14px;
        }
        a:hover
        {
            text-decoration: underline;
        }
        input[type=number] {
            font-size: 15px;
            width: 500px;
            height: 20px;
            border: 1px solid #AAA;
            padding: 5px 5px 5px 5px;
            background: #fafafa;
        }
    </style>
    <script>
        $(function(){
            $('#pin_field_box').append('<div id="add_record_button"><input type="button" name="add_record" id="add_record" value="Add Record" /></div>');
            var title = 0;
            var additional_field_count = parseInt($('#new_record_count').val());
//            console.log(additional_field_count);
            $('#add_record').click(function(){
                title++;
                additional_field_count++;
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                $('#add_record_button').append('<br><br><div class="form-input-box"><input type="input" name="title'+title+'" id="new_title'+title+'" placeholder="Title" style="width: 200px; height:23px;" /> <input type="input" name="new_value'+title+'" id="new_value'+title+'" placeholder="Value" style="width: 200px; height:23px;" /> <input type="button" name="add_record" id="add_record" value="X" /></div> <br><br>');

                if(additional_field_count <= 0) {
                    $('#new_record_count').val(title);
                }
                else{
                    $('#new_record_count').val(additional_field_count);
                }

            });

            $('#add_supplier_enquiry_details_record').click(function(){
                title++;
                additional_field_count++;
//                console.log(additional_field_count);
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                $('.supplier_enquiry_details').append('<tr>' +
                '<td><input type="text" value="" name="item'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="number" value="" name="qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" name="unit'+additional_field_count+'" class="suppEnqUnit" style="width:200px;"></td></tr>');

                if(additional_field_count <= 0) {
                    $('#new_record_count').val(title);
                }
                else{
                    $('#new_record_count').val(additional_field_count);
                }
                //console.log(title);

            });



            //script for supplier po
            $('#add_supplier_po_details_record').click(function(){
                title++;
                additional_field_count++;
//                console.log(additional_field_count);
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                $('.supplier_po_details').append('<tr>' +
                '<td><input type="text" name="item'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" class="supp_po_qty" name="qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" pattern="[A-Za-z]{0,50}" title="Only alphabets allowed. Max count is 50" name="unit'+additional_field_count+'" style="width:200px;"></td>'+
                '<td><input type="text" class="supp_po_rate" name="rate'+additional_field_count+'" style="width:200px;"></td></tr>');

                if(additional_field_count <= 0) {
                    $('#new_record_count').val(title);
                }
                else{
                    $('#new_record_count').val(additional_field_count);
                }
                //console.log(title);

            });

            //script for inspection report add dynamic records
            $('#add_inspection_report_details_record').click(function(){
                //alert("HI");
                title++;
                additional_field_count++;
//                console.log(additional_field_count);
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                $('.inspection_report_details').append('<tr>' +
                '<td><input type="text" value="" name="item'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" name="qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="inspection_qty" name="dc_qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="inspection_rcd_qty" name="recd_qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" name="recd_material_dim'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><select id="remarks'+additional_field_count+'" name="remarks'+additional_field_count+'" class="inspection_report_remarks"><option value="">Select</option><option value="0">OK<option value="1">Reject</option></select></td></tr>');


                if(additional_field_count <= 0) {
                    $('#new_record_count').val(title);
                }
                else{
                    $('#new_record_count').val(additional_field_count);
                }
                //console.log(title);

            });

            //script for outsource add dynamic records
            $('#add_outsource_details_record').click(function(){
                //alert("HI");
                title++;
                additional_field_count++;
                //console.log(additional_field_count);
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                var origin = window.location.origin;

                $('.outsource_details').append('<tr>' +
                '<td class="wo'+additional_field_count+'"></td>' +
                '<td><input type="text" value="" class="item_outsource" name="item'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="types_process_parts_outsource" name="types_process_parts'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="dispatched_qty_outsource" name="dispatched_qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="require_qty_outsource" name="require_qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="job_worker_dc_no_outsource" name="job_worker_dc_no'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="job_worker_dc_date_outsource" name="job_worker_dc_date'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="recd_qty_outsource" name="recd_qty'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="require_dim1_outsource" name="require_dim'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="recd_dim1_outsource" name="recd_dim1-'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="recd_dim2_outsource" name="recd_dim2-'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="recd_dim3_outsource" name="recd_dim3-'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="recd_dim4_outsource" name="recd_dim4-'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" value="" class="recd_dim5_outsource" name="recd_dim5-'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><select id="remarks'+additional_field_count+'" name="remarks'+additional_field_count+'" class="outsource_remarks"><option value="">Select</option><option value="0">OK<option value="1">Reject</option></select></td></tr>');

                //fetching all workorders from system and displaying as select box
                $.ajax({
                    url: origin + '/admin/getWorkOrderList',
                    method: 'GET',
                    dataType: 'html',
                    success: function(data, textStatus, jqXHR){
                        //console.log(data);
                        $('.wo'+additional_field_count).html(data);
                        $('.wo_outsource').last().attr('name', 'wo'+additional_field_count);

                    },
                    beforeSend: function(jqXHR, settings){
                        //console.log(settings);
                    }
                })

                if(additional_field_count <= 0) {
                    $('#new_record_count').val(title);
                }
                else{
                    $('#new_record_count').val(additional_field_count);
                }
                //console.log(title);

            });


            //script for adding terms and conditions
            $('#add_terms_record').click(function(){
                title++;
                additional_field_count++;
                //console.log(additional_field_count);
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                $('.terms_n_conditions').append('<tr>' +
                '<td><input type="text" value="" name="terms'+additional_field_count+'" style="width:100%;"></td></tr>');

                if(additional_field_count <= 0) {
                    $('#terms_new_record_count').val(title);
                }
                else{
                    $('#terms_new_record_count').val(additional_field_count);
                }
                //console.log(title);

            });

            $('#add_wo_mis_record').click(function(){
                title++;
                additional_field_count++;
//                console.log(additional_field_count);
                //$('#add_record_button').prepend('<input type="button" name="add_record" id="add_record" value="Add Record" />');
                $('.wo_miscellaneous').append('<tr><td><input type="text" maxlength="200" value="" name="pn'+additional_field_count+'" style="width:200px;"></td>' +
                '<td><input type="text" maxlength="100" value="" name="rq'+additional_field_count+'" style="width:100px;"></td>' +
                '<td><input type="text" maxlength="100" value="" name="pq'+additional_field_count+'" style="width:100px;"></td>' +
                '<td><input type="text" maxlength="100" value="" name="bq'+additional_field_count+'" style="width:100px;"></td></tr>');

                if(additional_field_count <= 0) {
                    $('#new_record_count').val(title);
                }
                else{
                    $('#new_record_count').val(additional_field_count);
                }
                //console.log(title);

            });

            $('#field-title').keyup(function(data){
                //console.log(data);
                var newString = $(this).val().replace(/\(/g, '-');
                var newString = newString.replace(/\)/g, '-');
                var newString = newString.replace(/ /g, '-');
                var newString = newString.replace(/--/g, '-');
                var lastChar = newString.charAt(newString.length -1);
                if(lastChar == '-'){
                    var newString = newString.substring(0, newString.length - 1);
                }
                $('#seo_url').val(newString.toLowerCase());
            });
        });

    </script>

    <script>
        $(document).ready(function(){
            if(!$('#mvat_cst').val()){
               $('#mvat_cst').val('12.5');
            }
            //setting default value for MVAT CST
            //$('#mvat_cst').val('12.5');
            //END setting

            //hiding ADD buttons for below pages
            var btn_txt = $('.ui-button-text').html()
            if(btn_txt == "Add QUOTATION" || btn_txt == "Add WORK ORDER" || btn_txt == "Add INSPECTION REPORT"){
                $('.add_button').hide();
            }
            //alert($('.ui-button-text').html());

            //END hiding

            //setting auto MVAT and CST value based on selection
            $('#mvat_cst_type').on('change', function(e){
//                console.log($(this).val());
                var value = $(this).val();
                switch (value){
                    case "1":
                        $('#mvat_cst').val('12.5');
                        break;
                    case "2":
                        $('#mvat_cst').val('2');
                        break;
                }
            });
            //END setting
        });
    </script>
    <script>
        //for outsource, outsource details section, Job Worker DC Date element
        $(document).mousedown('input.job_worker_dc_date_outsource', function(){    //for dynamically created elements after the the DOM is loaded
            //console.log("Mouse IN");
            //$( ".job_worker_dc_date_outsource" ).datepicker();
            $( '.job_worker_dc_date_outsource' ).datepicker();
        });
    </script>
</head>
<body>
<div>
    <span style="text-align:right; padding: 5px 10px 20px 0px; font-weight: 600; display: block;"><?php echo "Welcome " . $this->nativesession->getSession('admincredfname') . " (" . $this->nativesession->getSession('admincred') . ")"; ?> <a style="text-decoration:underline;" href='<?php echo site_url('admin/logout')?>'>Logout</a></span>
    <?php
     
    switch($this->nativesession->getSession('admincredrole')) {
        case 0:
            ?>
            <strong><a href='<?php echo site_url()?>'>Home</a></strong> |
            <strong><a href='<?php echo site_url('admin/editAbout')?>'>About Us</a></strong> |
            <strong><a href='<?php echo site_url('admin/editMainCat')?>'>Main category</a></strong> |
            <strong><a href='<?php echo site_url('admin/editSubCat')?>'>Sub category</a></strong> |
            <strong><a href='<?php echo site_url('admin/editProducts')?>'>Products</a></strong> |
            <strong><a href='<?php echo site_url('admin/editProductsDetails')?>'>Product Details</a></strong> |
            <strong><a href='<?php echo site_url('admin/editProductGallery')?>'>Product Gallery</a></strong> |
            <strong><a href='<?php echo site_url('admin/editSuppliers')?>'>Suppliers</a></strong> |
            <strong><a href='<?php echo site_url('admin/editClients')?>'>Clients</a></strong> |
            <strong><a href='<?php echo site_url('admin/editContacts')?>'>Contacts</a></strong> |
            <strong><a href='<?php echo site_url('admin/editEnquiry')?>'>Client Enquiry</a></strong> |
            <strong><a href='<?php echo site_url('admin/createQuote')?>'>Quotations</a></strong> |
            <strong><a href='<?php echo site_url('admin/createClientPo')?>'>Client PO</a></strong> |
            <strong><a href='<?php echo site_url('admin/createWO')?>'>Work Order</a></strong> |
            <strong><a href='<?php echo site_url('admin/editSupplierEnquiry')?>'>Supplier Enquiry</a></strong> |
            <strong><a href='<?php echo site_url('admin/createSupplierPo')?>'>Supplier PO</a></strong> |
            <strong><a href='<?php echo site_url('admin/inspectionReport')?>'>Inspection Report</a></strong> |
            <strong><a href='<?php echo site_url('admin/editVendors')?>'>Vendors</a></strong> |
            <strong><a href='<?php echo site_url('admin/outSource')?>'>Outsource</a></strong> |
            <?php
            break;
        case 1:
            ?>
            <strong><a href='<?php echo site_url('admin/editSuppliers')?>'>Suppliers</a></strong> |
            <strong><a href='<?php echo site_url('admin/editClients')?>'>Clients</a></strong> |
            <strong><a href='<?php echo site_url('admin/editContacts')?>'>Contacts</a></strong> |
            <strong><a href='<?php echo site_url('admin/editEnquiry')?>'>Client Enquiry</a></strong> |
            <strong><a href='<?php echo site_url('admin/createQuote')?>'>Quotations</a></strong> |
            <strong><a href='<?php echo site_url('admin/createClientPo')?>'>Client PO</a></strong> |
            <strong><a href='<?php echo site_url('admin/createWO')?>'>Work Order</a></strong> |
            <strong><a href='<?php echo site_url('admin/editSupplierEnquiry')?>'>Supplier Enquiry</a></strong> |
            <strong><a href='<?php echo site_url('admin/createSupplierPo')?>'>Supplier PO</a></strong> |
            <strong><a href='<?php echo site_url('admin/inspectionReport')?>'>Inspection Report</a></strong> |
            <strong><a href='<?php echo site_url('admin/editVendors')?>'>Vendors</a></strong> |
            <strong><a href='<?php echo site_url('admin/outSource')?>'>Outsource</a></strong> |
            <?php
            break;
        case 2:
            ?>
            <strong><a href='<?php echo site_url()?>'>Home</a></strong> |
            <strong><a href='<?php echo site_url('admin/editAbout')?>'>About Us</a></strong> |
            <strong><a href='<?php echo site_url('admin/editMainCat')?>'>Main category</a></strong> |
            <strong><a href='<?php echo site_url('admin/editSubCat')?>'>Sub category</a></strong> |
            <strong><a href='<?php echo site_url('admin/editProducts')?>'>Products</a></strong> |
            <strong><a href='<?php echo site_url('admin/editProductsDetails')?>'>Product Details</a></strong> |
            <strong><a href='<?php echo site_url('admin/editProductGallery')?>'>Product Gallery</a></strong> |
            <?php
            break;
    }
    ?>
    

</div>
<div style='height:20px;'></div>
<div>
    <?php echo $output; ?>
</div>
<script src="<?php echo base_url(); ?>extras/js/jquery.price_format.2.0.min.js" type="text/javascript"></script>    
<script>
    //$(document).on({'change': function(){                 //1st Method:for dynamically created elements after the the DOM is loaded
    $(document).on('change', 'select.inspection_report_remarks, select.outsource_remarks', function(){          //2nd Method:for dynamically created elements after the the DOM is loaded
        //script for inspection report reject reason field
        //$('.inspection_report_remarks').change(function(ea){
            //alert('HI');
            //console.log($(this).val());
            var value = $(this).val();
            var element_id = $(this).attr('id');
            //console.log(element_id);
            switch (value){
                case "1":
                    //$(this).append('<input type="text" value="'+value+'" name="remarks'+element_id+'" style="width:200px;">');
//                    console.log($(this).parent());
                    var parent = $(this).parent();
                    //parent.append('<textarea name="remarks_'+element_id+'" id="remarks_'+element_id+'" style="width:200px;"></textarea>');
                    parent.append(addTextArea(element_id));
                    //$(this).remove();

                    break;
                case "0":
                    hideTextArea('remarks', element_id);
                    //$('#mvat_cst').val('2');
                    break;
            }
        //});

    //}}, 'select' );                    //1st Method:for dynamically created elements after the the DOM is loaded
    });

    function addTextArea(element_id){

        return '<textarea name="remarks_'+element_id+'" id="remarks_'+element_id+'" style="width:200px;"></textarea>';

    }

    function hideTextArea(element_name, element_id){

        $('#'+element_name+"_"+element_id).remove();
        //console.log(element_name+''+element_id);

    }

    /*$(document).on('keyup', '.suppEnqUnit', function() {          //2nd Method:for dynamically created elements after the the DOM is loaded

        //getting value of Supplier Enquiry Unit field from Supplier enquiry details section
        var i = 0;
        var unit_val = $(this).val();
        //var validation_check = /^[a-zA-Z]+$/;             //regular expression for allowing only alphabets/letters
        var validation_check = /[0-9]+$/;             //regular expression for allowing only alphabets/letters
        //console.log(unit_val.search(validation_check));
        //unit_val.replace(/[0-9]/g, '');
        if(unit_val.search(validation_check) >= 0){
            console.log(unit_val.search(validation_check));
            unit_val.replace('aaa', '');
            //var unit_val_length = unit_val.length;
            for(i == unit_val.search(validation_check); i < unit_val.length; i++){
                //console.log(unit_val_length);
                //console.log(unit_val[i]);

                //if(unit_val[i] == unit_val[unit_val.search(validation_check)]){

                //}
            }
//                if()

          //  console.log(unit_val.search(validation_check));
        }
        //console.log($(this).val());

    });*/
$(function(){
   var rate = document.getElementsByName('rate');
//   console.log(rate);
   $('input[name=rate]').priceFormat({
        prefix: '',
        thousandsSeparator: ','
   }); 
   
   $('input[class=supp_po_qty]').priceFormat({
        prefix: '',
        centsLimit: 3,
        thousandsSeparator: ''
   });
   
   $('input[class=supp_po_rate]').priceFormat({
        prefix: '',
        centsLimit: 2,
        thousandsSeparator: ''
   });
   
   $('input[class=inspection_qty]').priceFormat({
        prefix: '',
        centsLimit: 3,
        thousandsSeparator: ''
   });
   
   $('input[class=inspection_rcd_qty]').priceFormat({
        prefix: '',
        centsLimit: 3,
        thousandsSeparator: ''
   });
});

/*
 * Adding script to remove readonly attr from format_no column
 * @author Alvin
 * @date 22/08/2016
 */

$(function(){
   $('#activate_format_no').click(function(data){
       $('input[name=format_no]').removeAttr('readonly');
   });
});
</script>

</body>
</html>
