<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 01/04/15
 * Time: 1:36 AM
 */
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Forms</title>

</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-custom">
<div class="container">
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
        <ul class="nav navbar-nav">
            <li><strong><a href="<?php echo site_url();?>">Home</a></strong></li>
            <li><strong><a href="<?php echo site_url();?>admin/add-main-category-form">Add Main Category</a></strong></li>
            <li><strong><a href="<?php echo site_url();?>admin/add-sub-category-form">Add Sub Category</a></strong></li>
            <li><strong><a href="<?php echo site_url();?>admin/add-product-form">Add Products</a></strong></li>
            <li><strong><a href="<?php echo site_url();?>admin/add-product-details-form">Add Products Details</a></strong></li>
            <li><a href="<?php echo site_url();?>admin/logout">Logout</a></li>
            <!--<li><a href="#service">Service</a></li>-->

        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>

