<?php
	if (!defined('ABSPATH')) exit;

	if (empty($products)) {
		echo '';exit;
	}

	foreach ($products as $product) {
		site_setup_product_data($product);
		wc_get_template_part( 'archive/product', 'item' );
	} 
?>