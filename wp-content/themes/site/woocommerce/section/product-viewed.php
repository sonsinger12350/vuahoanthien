<?php
$ids = array();

// Check if the user is logged in
$user_id = get_current_user_id();

if ($user_id > 0) {
	// Logged-in users: get viewed products from user meta
	$products_meta = get_user_meta($user_id, 'products', true);
	$ids = !empty($products_meta) ? explode(',', $products_meta) : array();
	$ids = array_reverse($ids);
} else {
	// Not logged in: get viewed products from cookies
	$ids = site_get_cookie_array('products');
}

// Return if no products are found
if (count($ids) == 0) return;

$products = wc_get_products(array(
	'limit' => 5,
	'include' => array_slice($ids, 0, 5),
	'orderby' => 'post__in', // Ensures products are shown in the order they appear in $ids
));

// If products exist, proceed
if (count($products)) :
	$link = '';
?>
	<div class="section">
		<div class="containers">
			<div class="section-bg">
				<h2 class="section-header">
					<span>Sản phẩm đã xem</span>
				</h2>
				<div class="slide-multiple-slide-product" id="productRecent">
					<div class="product-list product-viewed-list flex-nowrap flex-md-wrap">
						<?php foreach ($products as $product): site_setup_product_data($product); ?>
							<div class="col-6 col-md-4 col-lg-2 mb-3 col-product-item">
								<?php
								wc_get_template_part('archive/product', 'item');
								?>
							</div>
						<?php endforeach;
						site_reset_product_data(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
endif;
