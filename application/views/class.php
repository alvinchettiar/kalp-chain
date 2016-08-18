<?php
class Kalp{

	//saving title and description of the products
	public function addProduct($title, $description){
		$titleEscaped = mysql_real_escape_string($title);
		$descriptionEscaped = mysql_real_escape_string($description);
	}
}