<?php
class Retail extends AppModel {
	var $name = 'Retail';
	
	var $validate = array(
		
	);
	
	var $belongsTo = array(
		'Product' => array(
			'foreign_key' => 'product_id'
		)
	);
	
}
?>