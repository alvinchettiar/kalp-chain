<!-- Section: about -->
    <section id="about" class="home-section text-center">
    	<div class="heading-about">
			<div class="container">
			<div class="row">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p><a href="<?php echo base_url();?>">Home</a> > Product Gallery</p>
				<div class="col-lg-8 col-lg-offset-2">
					<div class="wow bounceInDown" data-wow-delay="0.4s">
                    <div class="section-heading">
					<h1 style="margin-top: 10px;">Types of Industrail Chains</h1>
					<i class="fa fa-2x fa-angle-down"></i>

					</div>
					</div>
				</div>
			</div>
			</div>
		</div>
        <?php
/*        //print_r($product_photos['main_id']);
        $gallery_count = 0;
        foreach($main_category as $key_main_cat => $val_main_cat){ */?><!--
        <div class="service-desc">
            <h5><?php /*echo $val_main_cat['title'];*/?></h5>
        </div>
        <div class="container">
            <?php
/*            if(in_array($val_main_cat['id'], $main_ids)) {
                foreach($product_photos as $key => $val) {
                    if($val['main_id'] == $val_main_cat['id']) {
//                        echo "PRODUCT DETAILS ID : " . $val['prod_d_id'] . "<br>";
//                        echo "PRODUCT DETAILS TITLE : " . $val['prod_d_title'] . "<br>";
                        */?>
                        <a class="fancybox" href="<?php /*echo base_url();*/?>uploads/<?php /*echo $val['image_link'];*/?>"
                           data-fancybox-group="gallery<?php /*echo $gallery_count;*/?>" title="<?php /*echo $val['prod_d_title'];*/?>">
                            <img src="<?php /*echo base_url();*/?>uploads/<?php /*echo $val['image_link'];*/?>" alt="" style="max-width:150px;"/>
                        </a>
                    <?php
/*                        }
                    }
                }
            */?>
        </div>
            --><?php
/*            $gallery_count++;
            }
        */?>
        <div class="container">
        <?php
        foreach($gallery as $key_gallery => $val_gallery) {
        ?>

            <a class="fancybox" href="<?php echo base_url();?>uploads/products-gallery/<?php echo $val_gallery['image_link'];?>"
               data-fancybox-group="gallery" title="<?php echo $val_gallery['title'];?>">
                <img src="<?php echo base_url();?>uploads/products-gallery/<?php echo $val_gallery['image_link'];?>" alt="" style="max-width:150px;"/>

                <!--<span><?php /*echo $val_gallery['title'];*/?></span>-->
            </a>

        <?php
        }
        ?>
        </div>
    </section>