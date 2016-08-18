<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 21/03/15
 * Time: 5:26 PM
 */
?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="wow shake" data-wow-delay="0.4s">
                    <div class="page-scroll marginbot-30">
                        <a href="#intro" id="totop" class="btn btn-circle">
                            <i class="fa fa-angle-double-up animated"></i>
                        </a>
                    </div>
                </div>
                <p>&copy;Copyright 2014 - KALP ENGINEERING. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
<?php
/*echo "<pre>";
print_r($aboutus);*/
?>
<!-- Core JavaScript Files -->
<script src="<?php echo base_url();?>extras/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>extras/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>extras/js/jquery.easing.min.js"></script>
<script src="<?php echo base_url();?>extras/js/jquery.scrollTo.js"></script>
<script src="<?php echo base_url();?>extras/js/wow.min.js"></script>
<script src="<?php echo base_url();?>extras/js/handlebars-v3.0.3.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?php echo base_url();?>extras/js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>source/jquery.fancybox.js"></script>
<script>
    $(function(){
        $('.contact_form').submit(function(e){
            var base_url = window.location;
            e.preventDefault();
            $('#result').html('<i class="fa fa-spinner fa-spin fa-3x"></i>');

            $.post("http://" + base_url.host + "/home/saveContactForm", $(this).serialize(), function(data){
                $('#result').html('Thank You. We will get in touch within 24hours.');
            });

        });
    });

    function productDetails(id){
        var base_url = window.location;
        //console.log("ID : " + base_url.host);

        $('html, body').animate({
             scrollTop: $('#product-details').offset().top
        }, 1000);
        $('#product-details').html('<i class="fa fa-spinner fa-spin fa-3x"></i>');
        $.post("http://" + base_url.host + "/products_controller/getProductDetails", {id:id}, function(data){
//        $.post("http://" + base_url.host + "/products_controller/getProductDetails", {id:id}, function(data){
            console.log(data);
            $('#product-details').html(data);
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        /*
         *  Simple image gallery. Uses default settings
         */
        $('.fancybox').fancybox();
    });
</script>
<script>

        var $about = <?php echo $aboutus;?>;
        //console.log($about);
        var context = {about: $about};
        var source = $("#entry-template").html();
        var template = Handlebars.compile(source);
        //console.log(template);
        var html = template(context);
        $('.about').append(html);


</script>

</body>

</html>