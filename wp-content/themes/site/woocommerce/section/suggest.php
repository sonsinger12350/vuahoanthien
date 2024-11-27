<?php
	$link = '';

	if (isset($args['hot']) && $args['hot'] == 'deal') {
		$cat = get_queried_object();
		if (empty($cat->taxonomy)) return;
		$child_categories = get_term_children( $cat->term_id, $cat->taxonomy );
		$child_categories[] = $cat->term_id;
		$title = 'Deal Hot hôm nay';
		$sql = "SELECT DISTINCT vhd_posts.ID
			FROM vhd_posts
			LEFT JOIN vhd_term_relationships ON(vhd_posts.ID = vhd_term_relationships.object_id)
			INNER JOIN vhd_postmeta ON(vhd_posts.ID = vhd_postmeta.post_id)
			INNER JOIN vhd_postmeta AS mt2 ON (vhd_posts.ID = mt2.post_id)
			WHERE vhd_term_relationships.term_taxonomy_id IN(".implode(',', $child_categories).")
				AND vhd_postmeta.meta_key = 'sale_off' 
				AND (mt2.meta_key = '_sale_price' AND CAST(mt2.meta_value AS SIGNED) > '0')
				AND vhd_posts.post_type = 'product'
				AND (vhd_posts.post_status = 'publish' OR vhd_posts.post_status = 'acf-disabled' OR vhd_posts.post_status = 'private')
			LIMIT 0, 6;
		";
		$products = $wpdb->get_results($sql);
		$cat_hot_sale = site_get_hot_sale_home_page();
		if (isset($cat_hot_sale['term_id'])) $link = get_term_link($cat_hot_sale['term_id']);
	} else if (isset($args['top']) && $args['top'] == 'trend') {
		$cat = get_queried_object();
		if (empty($cat->taxonomy)) return;
		$child_categories = get_term_children( $cat->term_id, $cat->taxonomy );
		$child_categories[] = $cat->term_id;

		$title = 'Sản Phẩm Dẫn Đầu Xu Hướng';
		$sql = "SELECT DISTINCT vhd_posts.ID
			FROM vhd_posts
			LEFT JOIN vhd_term_relationships ON(vhd_posts.ID = vhd_term_relationships.object_id)
			INNER JOIN vhd_postmeta ON(vhd_posts.ID = vhd_postmeta.post_id)
			INNER JOIN vhd_postmeta AS mt2 ON (vhd_posts.ID = mt2.post_id)
			WHERE vhd_term_relationships.term_taxonomy_id IN(".implode(',', $child_categories).")
				AND vhd_postmeta.meta_key = 'sale_off' 
				AND (mt2.meta_key = '_sale_price' AND CAST(mt2.meta_value AS SIGNED) > '0')
				AND vhd_posts.post_type = 'product'
				AND (vhd_posts.post_status = 'publish' OR vhd_posts.post_status = 'acf-disabled' OR vhd_posts.post_status = 'private')
			LIMIT 0, 6;
		";
		$products = $wpdb->get_results($sql);
		$cat_root = site_wc_get_terms_to_root($cat, 1);
		if (isset($cat_root->term_id)) $link = get_term_link($cat_root->term_id) . '?type=3';
	} else {
		$title = 'Gợi ý hôm nay';
		$sql = "SELECT DISTINCT vhd_posts.ID
			FROM vhd_posts
			INNER JOIN vhd_postmeta AS mt2 ON (vhd_posts.ID = mt2.post_id)
			WHERE (mt2.meta_key = '_sale_price' AND CAST(mt2.meta_value AS SIGNED) > '0')
				AND vhd_posts.post_type = 'product'
				AND (vhd_posts.post_status = 'publish' OR vhd_posts.post_status = 'acf-disabled' OR vhd_posts.post_status = 'private')
			ORDER BY RAND()
			LIMIT 0, 6;
		";

		$products = get_posts($args);
	}
?>
<?php if ($products): ?>
	<div class="section">
		<div class="section-bg">
			<!-- <div class="container"> -->
			<h2 class="section-header">
				<span><?php echo $title; ?></span>
			</h2>
			<div class="slide-multiple slide-product">
				<?php
				foreach ($products as $product):
					if (isset($product->ID)) $product = wc_get_product($product->ID);
					site_setup_product_data($product);
					wc_get_template_part('archive/product', 'item');
				endforeach;
				site_reset_product_data();
				?>
			</div>
			<?php if ($link != ''): ?>
				<div class="section-actions text-center mt-3">
					<a href="<?php echo $link; ?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
				</div>
			<?php endif; ?>
			<!-- </div> -->
		</div>
	</div>
<?php endif ?>