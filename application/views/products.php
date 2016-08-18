<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 29/03/15
 * Time: 3:27 PM
 */

//print_r($main_category);
?>
<!-- Section: main cat info -->
<section id="about" class="home-section text-center">
<div class="heading-about" id="intro">
    <div class="container">
        <div class="row">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p><a href="<?php echo base_url();?>">Home</a> > Products > <?php echo $main_category[0]['title']; ?></p>
            <div class="col-lg-8 col-lg-offset-2">
                <div class="wow bounceInDown" data-wow-delay="0.4s">
                    <div id="product_body_head" class="section-heading">
                        <h1 style="margin-top: 30px;"><?php echo $main_category[0]['title'];?></h1>
                        <i class="fa fa-2x fa-angle-down"></i>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">

    <div class="row">
        <div class="col-lg-2 col-lg-offset-5">
            <hr class="marginbot-50">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="wow bounceInUp" data-wow-delay="0.2s">
                <p><?php echo $main_category[0]['description'];?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-lg-offset-5">
            <hr class="marginbot-50">
        </div>
    </div>
</div>
    <?php
    if(!empty($sub_category)){
    ?>
    <div class="heading-about">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="wow bounceInDown" data-wow-delay="0.4s">
                        <div class="section-heading">
                            <h4>Products</h4>
                            <i class="fa fa-2x fa-angle-down"></i>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <hr class="marginbot-50">
            </div>
        </div>

        <div class="row">
            <?php foreach($sub_category as $key_main_cat => $val_main_cat){?>
                <div class="col-sm-6 col-md-6">
                    <div class="wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="service-box">
                            <div class="service-icon">
                                <!--                                <img src="--><?php //echo base_url();?><!--extras/img/icons/service-icon-1.png" alt="" />-->
                            </div>
                            <div id="product_body_middle" class="service-desc">
                                <h2><?php echo $val_main_cat['title'];?></h2>
                                <p><?php echo $val_main_cat['description'];?></p>
                                <p>
                                   <ul class="nav">
                                    <?php foreach($products_array as $key_products => $val_products){
                                        if($val_main_cat['id'] == $val_products['parent_id']){
                                    ?>
                                        <li><h3><a href="javascript:;" onclick="productDetails(<?php echo $val_products['id'];?>);"><?php echo $val_products['title'];?></a></h3></li>
                                    <?php
                                        }
                                    }
                                    ?>

                                   </ul>
<!--                                    <a href="--><?php //echo site_url();?><!--products/--><?php //echo $val_main_cat['id'];?><!--">Read More</a>-->
                                </p>



                            </div>
                            <div class="clearfix">&nbsp;</div>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>
        <div class="row" id="product-details">


        </div>
    </div>
    <?php
    }
    ?>
</section>
<!-- /Section: about -->