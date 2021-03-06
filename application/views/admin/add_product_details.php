<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 01/04/15
 * Time: 1:35 AM
 */
?>
<!-- Section: about -->
<section id="about" class="home-section text-center">
    <div class="heading-about">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="wow bounceInDown" data-wow-delay="0.4s">
                        <div class="section-heading">
                            <div class="clearfix">&nbsp;</div>
                            <div class="clearfix">&nbsp;</div><div class="clearfix">&nbsp;</div>
                            <h3>Add Products Details</h3>
                            <i class="fa fa-2x fa-angle-down"></i>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-lg-8">
                <?php echo $error;?>
                <div class="boxed-grey">
                    <form method="post" enctype="multipart/form-data" action="<?php echo base_url();?>admin/admin_controller/addProductDetails" name="mainCategory" id="contact-form">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="parent_id">Sub Category (Parent)</label>

                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option selected disabled>Select</option>
                                        <?php
                                        foreach($products as $key => $val){
                                            ?>
                                            <option value="<?php echo $val['id'];?>"><?php echo $val['title'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="title">Title</label>

                                    <input type="text" class="form-control" name="title" id="title" value="" required="" />
                                </div>
                                <div>
                                    <label>Description</label>

                                    <textarea name="description" rows="10" cols="100"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="product_img">Product Table Image</label>

                                    <input type="file" name="userfile" id="product_img" size="20" />
                                </div>
                                <div class="form-group">
                                    <label for="product_img">Product Image</label>

                                    <input type="file" name="product_image" id="product_image" size="20" />
                                </div>
                                <div class="form-group">

                                    <input type="submit" name="submitBtn" value="Add Product" class="btn btn-skin pull-right" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</section>
