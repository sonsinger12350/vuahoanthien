<?php
	$products = array();
	$current_product_id = get_the_ID();
	$current_product_brands = wp_get_post_terms($current_product_id, 'product-brand');
	$current_product_brands = !empty($current_product_brands[0]) ? $current_product_brands[0]->term_id : 0;
	$categories = get_the_terms($current_product_id, 'product_cat');

	if (!empty($categories) && is_object($categories[0])) {
		$cat = $categories[0];
		$cat_root = site_wc_get_terms_to_root($cat, 1);
		$exclude = array();
		if ($cat->parent != $cat_root->term_id) $exclude[] = $cat->parent;
		else $exclude[] = $cat->term_id;
		$terms = get_terms(array(
			'taxonomy' => $cat_root->taxonomy,
			'parent'   => $cat_root->term_id,
			'exclude'  => $exclude
		));
		$limit = 15;
		$products_per_term = array();
		$terms_with_products = 0;

		foreach ($terms as $term) {
			$args = array(
				'taxonomy'   => $term->taxonomy,
				'parent'     => $term->term_id,
				'fields'     => 'ids',
				'hide_empty' => false,
			);

			$child_terms = get_terms($args);
			$current_term = implode(',', array_merge($child_terms, [$term->term_id]));

			$sql = "SELECT DISTINCT vhd_posts.ID
				FROM vhd_posts LEFT JOIN vhd_term_relationships ON (vhd_posts.ID = vhd_term_relationships.object_id)
				LEFT JOIN vhd_term_relationships AS tt1 ON (vhd_posts.ID = tt1.object_id)
				INNER JOIN vhd_postmeta ON ( vhd_posts.ID = vhd_postmeta.post_id )
				WHERE vhd_term_relationships.term_taxonomy_id IN ($current_term) 
				AND tt1.term_taxonomy_id = '$current_product_brands'
				AND vhd_postmeta.meta_key = '_sale_price' 
				AND CAST(vhd_postmeta.meta_value AS SIGNED) > '0'  
				AND vhd_posts.post_type = 'product'
				AND vhd_posts.post_status = 'publish'
				LIMIT 0, $limit
			";
			$list = $wpdb->get_results($sql);

			if (count($list) > 0) {
				$terms_with_products++;
				$limit_per_cate = ($terms_with_products < 5) ? 5 : 1;
				$products_per_term[$term->name] = array_slice($list, 0, $limit_per_cate);
				if (count($products) + count($products_per_term[$term->name]) > $limit) break;
				$products = array_merge($products, $products_per_term[$term->name]);
			}

			if (count($products) >= $limit) break;
		}

		// Shuffle the products array to randomize the order
		shuffle($products);

		// Limit the final product array to the required limit
		$products = array_slice($products, 0, $limit);
	}
?>
<?php if (count($products) > 0): ?>
	<div class="section list-product-buy-with">
		<div class="container">
			<div class="section-bg">
				<h2 class="section-header border-0">
					<span>Sản phẩm thường mua kèm</span>
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
			</div>
		</div>
	</div>
<?php endif ?>