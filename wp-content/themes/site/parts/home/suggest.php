<?php
$page_on_front = (int) get_option('page_on_front', 0);
$terms = get_field('categories_suggest', $page_on_front);

if ($terms == false || count($terms) == 0) {
	$terms = get_categories(array(
		'taxonomy' => 'product_cat',
	));
}

$all_products = array();

if (count($terms) > 0) {
    $term_slugs = array_map(function($v) {
		return $v->slug;
	},$terms);
	$cache_key = 'random_products_' . implode('_', $term_slugs);
	$use_cache = true; // Set to false to turn off caching

	if ($use_cache) $cached_products = get_transient($cache_key);
	else $cached_products = false; // Skip cache retrieval

	if ($cached_products === false) {
		$term_ids = $wpdb->get_var("SELECT GROUP_CONCAT(`term_id`) `id`
			FROM `vhd_terms` 
			WHERE `slug` IN ('".implode("','", $term_slugs)."')
		");

		$sql = "
			SELECT p.ID
			FROM vhd_posts p
			INNER JOIN vhd_postmeta pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_sale_price' AND pm2.meta_value > 0
			WHERE p.post_type = 'product'
			AND p.post_status = 'publish'
			AND EXISTS (
				SELECT 1
				FROM vhd_term_relationships tr
				INNER JOIN vhd_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
				AND tt.term_id IN ($term_ids)
				AND tr.object_id = p.ID
			)
			LIMIT 10;
		";
		$posts = $wpdb->get_results($sql);

		if ($use_cache) set_transient($cache_key, $posts, HOUR_IN_SECONDS);
		$cached_products = $posts;
	}

	$products = $cached_products;
}

?>
<div class="container">
	<div class="section section-suggest-on-home">
		<div class="section-bg">
			<h2 class="section-header">
				<span>Gợi ý hôm nay</span>
			</h2>
			<div class="product-list flex-nowrap flex-md-wrap"> <!-- overflow-auto -->
				<?php foreach ($products as $product):
					if (isset($product->ID)) {
						$product = wc_get_product($product->ID);
					}
					site_setup_product_data($product);
				?>
					<div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
						<?php
						wc_get_template_part('archive/product', 'item');
						?>
					</div>
				<?php endforeach;
				site_reset_product_data(); ?>
			</div>
			<?php if (isset($category['term_id'])): ?>
				<div class="section-actions text-center mt-3">
					<a href="<?php echo get_term_link($category['term_id']); ?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>