<?php
require_once('class.php');
$objKalp = new Kalp();

if(isset($_POST("add_product"))){
	$objKalp->add_product($_POST['title'], $_POST['description']);
}

?>