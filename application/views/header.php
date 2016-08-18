<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 21/03/15
 * Time: 5:25 PM
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta['meta_description'];?>">
    <meta name="keywords" content="<?php echo $meta['meta_keywords'];?>">
    <title><?php echo $meta['meta_title'];?></title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>extras/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link href="<?php echo base_url();?>extras/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>extras/css/animate.css" rel="stylesheet" />
    <!-- Squad theme CSS -->
    <link href="<?php echo base_url();?>extras/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>extras/color/default.css" rel="stylesheet">
    <link href="<?php echo base_url();?>source/jquery.fancybox.css" rel="stylesheet">
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-67216793-1', 'auto');
        ga('require', 'displayfeatures');
        ga('send', 'pageview');

    </script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">
<!-- Preloader -->
<div id="preloader">
    <div id="load"></div>
</div>

<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="<?php echo site_url();?>">
                <img src="<?php echo base_url();?>extras/img/kalp-logo-small.jpg" />
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo site_url();?>">Home</a></li>
                <li><a href="<?php echo site_url();?>about">About</a></li>
                <!--<li><a href="#service">Service</a></li>-->
                <li><a href="<?php echo site_url();?>contact">Contact</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Products <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php foreach($main_category_menu as $key_main_cat => $val_main_cat){?>
                        <li><a href="<?php echo site_url();?>products/<?php echo $val_main_cat['seo_url'];?>"><?php echo $val_main_cat['title'];?></a></li>
                        <?php }?>

                    </ul>
                </li>
                <li><a href="<?php echo site_url();?>product-gallery">Product Gallery</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>