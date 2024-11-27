<?php
	$categories = get_the_terms(get_the_ID(), 'product_cat');
	if (! is_object($categories[0])) return;
	$cat = $categories[0];
	$products = array();
	$link = '';
	$post_limit = 10;
	if (wp_is_mobile()) $post_limit = 6;

	if (isset($args['living']) && $args['living'] == 'space') {
		$title = 'Hoàn thiện không gian sống của bạn';
		
		$cat_root = site_wc_get_terms_to_root($cat, 1);
		$listCat = get_term_children($cat_root->term_id, $cat_root->taxonomy);
		$listCat = !empty($listCat) ? implode(',', $listCat) : 0;

		$sql = " SELECT DISTINCT vhd_posts.ID
			FROM vhd_posts
			LEFT JOIN vhd_term_relationships ON(vhd_posts.ID = vhd_term_relationships.object_id)
			INNER JOIN vhd_postmeta ON(vhd_posts.ID = vhd_postmeta.post_id)
			WHERE vhd_posts.post_type = 'product' AND vhd_posts.post_status = 'publish'
			AND vhd_term_relationships.term_taxonomy_id IN ($listCat)
			AND vhd_posts.ID NOT IN (SELECT object_id FROM vhd_term_relationships WHERE term_taxonomy_id IN (".$cat->term_id."))
			AND vhd_postmeta.meta_key = '_sale_price' AND CAST(vhd_postmeta.meta_value AS SIGNED) > '0'
			LIMIT 0, 10
		";

		$products = $wpdb->get_results($sql);
		$link = get_term_link($cat_root->term_id);
	} else {
		$title = 'Sản phẩm tương tự';
		$cat_ids = [];

		foreach ($categories as $item) {
			if ($item->slug !== 'gia-soc-hom-nay') $cat_ids[] = $item->term_id;
		}

		$sql = "SELECT DISTINCT vhd_posts.ID
			FROM vhd_posts  LEFT JOIN vhd_term_relationships ON (vhd_posts.ID = vhd_term_relationships.object_id) INNER JOIN vhd_postmeta ON ( vhd_posts.ID = vhd_postmeta.post_id )
			WHERE vhd_posts.ID NOT IN (".get_the_ID().") 
			AND vhd_term_relationships.term_taxonomy_id IN (".implode(',', $cat_ids).")
			AND vhd_postmeta.meta_key = '_sale_price' AND CAST(vhd_postmeta.meta_value AS SIGNED) > '0' 
			AND vhd_posts.post_type = 'product' 
			AND (vhd_posts.post_status = 'publish' OR vhd_posts.post_status = 'acf-disabled')
			LIMIT 0, 10
		";
		$products = $wpdb->get_results($sql);
		$link = get_term_link($cat->term_id);
	}

?>
<?php if (count($products) > 0): ?>
	<div class="section">
		<div class="container">
			<div class="section-bg">
				<h2 class="section-header border-0">
					<span><?php echo $title; ?></span>
				</h2>
				<div class="slide-multiple slide-products product-list">
					<?php
					foreach ($products as $product):

						if (isset($product->ID)) {
							$product = wc_get_product($product->ID);
						}
						site_setup_product_data($product); ?>
						<div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
							<?php wc_get_template_part('archive/product', 'item'); ?>
						</div>
					<?php endforeach;
					site_reset_product_data();
					?>
				</div>
				<?php if ($link != ''): ?>
					<div class="section-actions text-center mt-3">
						<a href="<?php echo $link; ?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>