<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kalp Chain - Admin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

    <!-- jQuery -->
    <script src="<?php echo base_url();?>extras/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>extras/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>extras/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url();?>extras/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script>
        function checkFormEmpty(formName)
        {
            $("#result_save").html("");
            var elementId = "";
            var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            $('#'+formName+' :text, :password, textarea, select').each(function(){
                var elementId = $(this).attr("id");
                var elementVal = $('#' + elementId).val();
                $('#' + elementId).next().remove();
                //console.log("Element ID : " + elementId);

                if($('#' + elementId).attr('type')=='text' || $('#' + elementId).attr('type')=='password' || $('#' + elementId).is('textarea'))
                {
                    if($.trim($('#' + elementId).val()).length < 1){

                        $('#' + elementId).after("<label></label>");
                        $('#' + elementId).next().css("color", "#D74C49");
                        $('#' + elementId).next().css("font-style", "italic");
                        $('#' + elementId).next().text("Cannot be empty");
                        flag = 0;
                        return false;
                    }
                    else{
                        $('#' + elementId).next().remove;
                        $('#' + elementId).next().text("");
                        flag = 1;
                    }
                }

                if(elementId == 'email'){

                    if(!filter.test(elementVal))
                    {
                        //console.log("Inside Filter : " + elementId);
                        $('#' + elementId).after("<label></label>");
                        $('#' + elementId).next().css("color", "#D74C49");
                        $('#' + elementId).next().css("font-style", "italic");
                        $('#' + elementId).next().text("Enter valid email id");
                        flag = 0;
                        return false;
                    }
                }

                if($('#' + elementId).is('select'))
                {
                    if($('#' + elementId)[0].selectedIndex == ''){

                        $('#' + elementId).after("<label></label>");
                        $('#' + elementId).next().css("color", "#D74C49");
                        $('#' + elementId).next().css("font-style", "italic");
                        $('#' + elementId).next().text("Cannot be empty");
                        flag = 0;
                        return false;
                    }
                    else{
                        $('#' + elementId).next().remove;
                        $('#' + elementId).next().text("");
                        flag = 1;

                    }
                }

            });

            if(flag == 0){
                return false;
            }
            else{
                return true;
            }

        }

        //checking if last element in the form is filled once other fileds are validated to true
        function checkFormSubmitAjaxPost(formName, submit_url)
        {
            var baseUrl = window.location.host;

            if($('#'+formName+' :text, :password, textarea, select').last().val()!=''){
                $('#result_save').html('<i class="fa fa-spinner fa-spin fa-3x"></i>');
                $.post("http://"+baseUrl+submit_url, $('#'+formName).serialize(), function(data){

                        //checking role of logged in user
                        var user_details = JSON.parse(data);

                        var user_role = user_details['role'];
                        if( user_role == 0){
                            window.location.href = "editMainCat";
                        }
                        else if(user_role == 1){
                            window.location.href = "editSuppliers";
                        }
                        else if(user_role == 2){
                            window.location.href = "editAbout";
                        }
                        else{
                            $('#result_save').html('Invalid Login Credentials');
                        }

                });
            }
        }

        $(function(){
            //validating & submitting add measurements form
            $('#login-form').submit(function(event){
                event.preventDefault();

                if(checkFormEmpty($(this).attr('id')) === true){
//                    console.log("Form Reply : " + checkFormEmpty($(this).attr('id')));
                    var submit_url = "/admin/login/checkAdminLogin";
                    checkFormSubmitAjaxPost($(this).attr('id'), submit_url);
                }
            });

        });

    </script>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">

                    <form name="form" id="login-form">
                        <fieldset>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="E-mail" id="email" name="email" autofocus>

                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" value="">

                            </div>
                            <div class="form-group">
                                <div id="result_save" class="text-center"></div>
                            </div>
                            <!--                                <div class="checkbox">-->
                            <!--                                    <label>-->
                            <!--                                        <!--                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me-->
                            <!--                                    </label>-->
                            <!---->
                            <!--                                </div>-->

                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Sign In" />
                            <input type="button" class="btn btn-lg btn-success btn-block" value="Reset" />

                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>