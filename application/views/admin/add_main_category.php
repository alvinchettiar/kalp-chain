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
                            <h3>Add Main Category</h3>
                            <i class="fa fa-2x fa-angle-down"></i>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="boxed-grey">
                    <form method="post" action="<?php echo base_url();?>admin/admin_controller/addMainCategory" name="mainCategory" id="contact-form">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Title</label>

                                            <input type="text" class="form-control" name="title" id="title" value="" required="" />
                                        </div>
                                <div class="form-group">
                                    <label for="description">Description</label>

                                            <textarea name="description" id="description" rows="10" cols="100"></textarea>
                                        </div>
                                <div class="form-group">

                                            <input type="submit" name="submitBtn" value="Add Category" class="btn btn-skin pull-right" />
                                    </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>