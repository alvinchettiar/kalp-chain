<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 03/04/15
 * Time: 4:33 PM
 */

?>

<?php
//print_r($products);
if(!empty($products)){
    if($products[0]['description'] != "") {
        ?>
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <hr class="marginbot-50">
            </div>
        </div>
        <div class="container">
        <div class="col-sm-6 col-md-12">

            <h4>
                <?php
                echo $products[0]['title'];
                ?>
            </h4>

            <p style="text-align: justify;">
                <?php
                echo $products[0]['description'];
                ?>
            </p>
        </div>
    <?php
    }
}
foreach($product_details as $key => $val){
?>
    <div class="row">
        <div class="col-lg-2 col-lg-offset-5">
            <hr class="marginbot-50">
        </div>
    </div>
    <div class="container">
    <?php /*if($val['description']){
        */?>
        <div class="col-sm-6 col-md-12">
            <h4>
                <?php
                echo $val['title'];
                ?>
            </h4>
            <p style="text-align: justify;">
                <?php
                echo $val['description'];
                ?>
            </p>
        </div>
        <?php
/*    }
    */?>

<div class="col-sm-6 col-md-12">

</div>
<div class="col-sm-6 col-md-12">
    <?php
    if($val['product_image'] != "NULL" && $val['product_image'] != ""){
        ?>
        <img src="<?php echo base_url();?>uploads/product_image/<?php echo $val['product_image']; ?>" style="max-width:900px;" />
        <div class="clearfix">&nbsp;</div>
        <?php
    }
    if($val['image_link'] != "NULL" && $val['image_link'] != ""){
    ?>
    <img src="<?php echo base_url();?>uploads/<?php echo $val['image_link']; ?>" style="max-width:900px;" />
    <?php
    }
    ?>
</div>
    </div>

<?php
}
?>