<?php
	global $post;
	if ($post->post_type == 'post') return false;

	function fetch_term_by_price($level = 0) {
		$value_prices = site__get('khoang-gia', []);

		if (empty($value_prices)) return [];
		global $wpdb;

		$value_brands = site__get('thuong-hieu', []);
		$exclude_ids = [100, 15, 23, 137];
		$priceArr = explode('-', $value_prices[0]);
		$wherePrice = '';

		// Điều kiện lọc giá
		if (count($priceArr) > 1) {
			$wherePrice = " AND CAST(vhd_postmeta.meta_value AS SIGNED) BETWEEN '".($priceArr[0] * 1000000)."' AND '".($priceArr[1] * 1000000)."'";
		} else {
			$wherePrice = " AND CAST(vhd_postmeta.meta_value AS SIGNED) > '".($priceArr[0] * 1000000)."'";
		}

		$catRoots = $wpdb->get_var("SELECT GROUP_CONCAT(term_id) FROM vhd_term_taxonomy WHERE taxonomy = 'product_cat' AND parent = 0 ");
		$catRoots = explode(',', $catRoots);

		if (!empty($value_brands)) {
			$sql = "SELECT GROUP_CONCAT(DISTINCT vhd_posts.ID)
				FROM vhd_posts
				LEFT JOIN vhd_postmeta ON (vhd_posts.ID = vhd_postmeta.post_id)
				INNER JOIN vhd_term_relationships ON (vhd_posts.ID = vhd_term_relationships.object_id )
				WHERE vhd_posts.post_type = 'product' 
				AND vhd_posts.post_status = 'publish'
				AND vhd_postmeta.meta_key = '_sale_price' 
				AND vhd_term_relationships.term_taxonomy_id  IN (".implode(',', $value_brands).") $wherePrice
			";
		}
		else {
			$sql = "SELECT GROUP_CONCAT(DISTINCT vhd_posts.ID)
				FROM vhd_posts
				LEFT JOIN vhd_postmeta ON (vhd_posts.ID = vhd_postmeta.post_id)
				WHERE vhd_posts.post_type = 'product' 
				AND vhd_posts.post_status = 'publish'
				AND vhd_postmeta.meta_key = '_sale_price' $wherePrice
			";
		}

		$productIds = $wpdb->get_var($sql);
		if (empty($productIds)) return [];

		$allCategories = $wpdb->get_results("
			SELECT DISTINCT vhd_term_taxonomy.term_id, vhd_term_taxonomy.parent
			FROM vhd_term_taxonomy
			INNER JOIN vhd_term_relationships ON (vhd_term_taxonomy.term_taxonomy_id = vhd_term_relationships.term_taxonomy_id)
			WHERE vhd_term_taxonomy.taxonomy = 'product_cat'
			AND vhd_term_relationships.object_id IN ($productIds)
		");

		if (empty($allCategories)) return [];

		$catLv1 = [];
		$catLv2 = [];
		$queryLv1 = [];

		if ($level == 0) {
			foreach ($allCategories as $v) {
				if ($v->parent == 0) $catLv1[] = $v->term_id;
				else if (in_array($v->parent, $catRoots)) $catLv1[] = $v->parent;
				else $catLv2[] = $v->parent;
			}
	
			if (!empty($catLv2)) {
				$queryLv1 = $wpdb->get_var("SELECT GROUP_CONCAT(DISTINCT parent) FROM vhd_term_taxonomy WHERE term_id IN (".implode(',', $catLv2).") ");
				$queryLv1 = !empty($queryLv1) ? explode(',', $queryLv1) : [];
			}
		}
		else if ($level == 1 || $level == 2) {
			foreach ($allCategories as $v) {
				if (!in_array($v->parent, $catRoots)) $catLv1[] = $v->parent;
				else if (in_array($v->parent, $catRoots)) $catLv1[] = $v->term_id;
			}

			if ($level == 2) {
				$catLv2 = [];

				foreach ($allCategories as $v) {
					if (!in_array($v->parent, $catRoots) && in_array($v->parent, $catLv1)) $catLv2[] = $v->term_id;
				}

				$catLv1 = $catLv2;
			}
		}

		$mergeCat = $catLv1;

		if (!empty($queryLv1)) {
			foreach ($queryLv1 as $v) {
				$mergeCat[] = $v;
			}
		}

		$mergeCat = array_unique($mergeCat);

		$final_cat = array_filter($mergeCat, function($value) use ($exclude_ids) {
			if (in_array($value, $exclude_ids)) return false;
			return true;
		});

		if (empty($final_cat)) return [];
		return get_terms(['include' => $final_cat]);
	}

	function fetch_products_by_keyword($keyword) {
		global $wp_query;
		$args = [
			's' => sanitize_text_field($keyword),
			'post_type' => 'product',
			'posts_per_page' => 300,
		];
		$wp_query = new WP_Query($args);
	}

	function fetch_categories_by_product_ids() {
		global $wp_query;
		$product_ids = array_map(function ($post) {
			return $post->ID;
		}, $wp_query->posts);

		
		$categories = [];
		foreach ($product_ids as $product_id) {
			$list_cates = wp_get_post_terms($product_id, 'product_cat');
			foreach ($list_cates as $cate_item) {
				if (!isset($categories[$cate_item->term_id])) {
					$categories[$cate_item->term_id] = $cate_item->name;
				}
			}
		}
		wp_reset_query();
		wp_reset_postdata();
		return $categories;
	}

	function fetch_terms($level, $cat) {
		global $wpdb;
		$value_brands = site__get('thuong-hieu', []);
		$value_prices = site__get('khoang-gia', []);
		$parent_id = $cat->term_id;
		$taxonomy = $cat->taxonomy;
		$child_terms = get_term_children($cat->term_id, $cat->taxonomy);
		$product_ids = [];
		$wherePrice = '';
		$catLv2 = $wpdb->get_var("SELECT GROUP_CONCAT(DISTINCT term_id) FROM vhd_term_taxonomy WHERE parent = $parent_id");
		$catLv2 = !empty($catLv2) ? explode(',', $catLv2) : [];

		if (!empty($value_prices[0])) {
			$priceArr = explode('-', $value_prices[0]);

			// Điều kiện lọc giá
			if (count($priceArr) > 1)
				$where = " AND CAST(vhd_postmeta.meta_value AS SIGNED) BETWEEN '".($priceArr[0] * 1000000)."' AND '".($priceArr[1] * 1000000)."'";
			else
				$where = " AND CAST(vhd_postmeta.meta_value AS SIGNED) > '".($priceArr[0] * 1000000)."'";

			$product_ids = $wpdb->get_var("SELECT GROUP_CONCAT(DISTINCT vhd_posts.ID)
				FROM vhd_posts
				INNER JOIN vhd_postmeta ON(vhd_posts.ID = vhd_postmeta.post_id)
				WHERE vhd_posts.post_type = 'product' AND vhd_posts.post_status = 'publish'
				AND vhd_postmeta.meta_key = '_sale_price' $where
			");
			$wherePrice = " AND vhd_term_relationships.object_id IN ($product_ids)";
			if (empty($product_ids)) return [];
		}

		if (!empty($value_brands)) {
			$sql = "SELECT GROUP_CONCAT(DISTINCT vhd_posts.ID)
				FROM vhd_posts
				INNER JOIN vhd_term_relationships ON (vhd_posts.ID = vhd_term_relationships.object_id )
				WHERE vhd_posts.post_type = 'product' 
				AND vhd_posts.post_status = 'publish'
				AND vhd_term_relationships.term_taxonomy_id  IN (".implode(',', $value_brands).")
			";
			$product_ids_by_brand = $wpdb->get_var($sql);

			if ($level == 1) {
				$sql = "SELECT DISTINCT vhd_term_relationships.term_taxonomy_id, vhd_term_taxonomy.parent
					FROM vhd_term_relationships 
					JOIN vhd_term_taxonomy ON (vhd_term_taxonomy.term_id = vhd_term_relationships.term_taxonomy_id)
					WHERE vhd_term_taxonomy.taxonomy = 'product_cat'
					AND vhd_term_taxonomy.term_id IN (".implode(',', $child_terms).")
					AND vhd_term_relationships.object_id IN ($product_ids_by_brand) $wherePrice
				";

				$taxLv2 = $wpdb->get_results($sql);
				if (empty($taxLv2)) return [];
				$catRoots = $wpdb->get_var("SELECT GROUP_CONCAT(term_id) FROM vhd_term_taxonomy WHERE taxonomy = 'product_cat' AND parent = 0 ");
				$catRoots = explode(',', $catRoots);
				$final_cat = [];

				foreach ($taxLv2 as $v) {
					if (!in_array($v->parent, $catRoots)) $final_cat[] = $v->parent;
					else if (in_array($v->parent, $catRoots)) $final_cat[] = $v->term_taxonomy_id;
				}
			}
			else {
				$allCategories = $wpdb->get_var("
					SELECT GROUP_CONCAT(DISTINCT vhd_term_taxonomy.term_id)
					FROM vhd_term_taxonomy
					INNER JOIN vhd_term_relationships ON (vhd_term_taxonomy.term_taxonomy_id = vhd_term_relationships.term_taxonomy_id)
					WHERE vhd_term_taxonomy.taxonomy = 'product_cat'
					AND vhd_term_relationships.object_id IN ($product_ids_by_brand) $wherePrice
					AND vhd_term_taxonomy.term_id IN (".implode(',', $child_terms).")
				");

				if (empty($allCategories)) return [];

				$exclude_ids = [100, 15, 23, 137, $parent_id];
				$final_cat = array_filter(explode(',', $allCategories), function($value) use ($exclude_ids) {
					if (in_array($value, $exclude_ids)) return false;
					return true;
				});
		
				if (empty($final_cat)) return [];
			}

			return get_terms(['include' => $final_cat]);
		}
		else {
			if (!empty($product_ids)) {
				$sql = "SELECT DISTINCT vhd_term_relationships.term_taxonomy_id, vhd_term_taxonomy.parent
					FROM vhd_term_relationships 
					JOIN vhd_term_taxonomy ON (vhd_term_taxonomy.term_id = vhd_term_relationships.term_taxonomy_id)
					WHERE vhd_term_relationships.term_taxonomy_id IN (".implode(',', $child_terms).") 
					AND vhd_term_relationships.object_id IN ($product_ids)
				";

				$taxLv2 = $wpdb->get_results($sql);

				if (empty($taxLv2)) return [];
				$cat_roots = $wpdb->get_var("SELECT GROUP_CONCAT(term_id) FROM vhd_term_taxonomy WHERE taxonomy = 'product_cat' AND parent = 0 ");
				$cat_roots = explode(',', $cat_roots);
				$cat_lv1 = [];

				foreach ($taxLv2 as $v) {
					if (!in_array($v->parent, $cat_roots)) $cat_lv1[] = $v->parent;
					else if (in_array($v->parent, $cat_roots)) $cat_lv1[] = $v->term_taxonomy_id;
				}

				if ($level == 2) {
					$cat_lv2 = [];

					foreach ($taxLv2 as $v) {
						if (!in_array($v->parent, $cat_roots) && in_array($v->parent, $cat_lv1)) $cat_lv2[] = $v->term_taxonomy_id;
					}

					$cat_lv1 = $cat_lv2;
				}

				return get_terms(['include' => $cat_lv1]);
			}
			else {
				return get_terms([
					'parent' => $parent_id,
					'number' => false,
					'taxonomy' => $taxonomy,
					'hide_empty' => false,
				]);
			}
		}
	}

	function fetch_sibling_terms($term_id, $taxonomy) {
		$parent_id = wp_get_term_taxonomy_parent_id($term_id, $taxonomy);
		return get_terms([
			'taxonomy' => $taxonomy,
			'parent' => $parent_id,
			'number' => false,
			'hide_empty' => false,
		]);
	}

	function fetch_brands($categories, $price) {
		global $wpdb;

		if (!empty($price)) {
			$priceArr = explode('-', $price[0]);
			$wherePrice = '';

			if (count($priceArr) > 1) $wherePrice = " AND CAST(vhd_postmeta.meta_value AS SIGNED) BETWEEN '".($priceArr[0] * 1000000)."' AND '".($priceArr[1] * 1000000)."'";
			else $wherePrice = " AND CAST(vhd_postmeta.meta_value AS SIGNED) > '".($priceArr[0] * 1000000)."'";

			$brandIds = $wpdb->get_var("SELECT GROUP_CONCAT(term_id) 
				FROM vhd_term_taxonomy 
				WHERE taxonomy = 'product-brand'
			");
			$productIds = $wpdb->get_var("SELECT GROUP_CONCAT(DISTINCT vhd_posts.ID)
				FROM vhd_posts
				INNER JOIN vhd_postmeta ON(vhd_posts.ID = vhd_postmeta.post_id)
				WHERE vhd_posts.post_type = 'product' AND vhd_posts.post_status = 'publish'
				AND vhd_postmeta.meta_key = '_sale_price' $wherePrice
			");

			$sql = " SELECT DISTINCT vhd_terms.term_id, vhd_terms.name
				FROM vhd_terms
				LEFT JOIN vhd_term_relationships ON(vhd_terms.term_id = vhd_term_relationships.term_taxonomy_id)
				WHERE vhd_terms.term_id IN ($brandIds) AND vhd_term_relationships.object_id IN ($productIds)
			";

			return $wpdb->get_results($sql);
		}
		else {
			if (isset($_GET['s'])) {
				global $wp_query;

				$query = str_replace('SQL_CALC_FOUND_ROWS DISTINCT vhd_posts.ID', 'GROUP_CONCAT(DISTINCT vhd_posts.ID)', $wp_query->request);
				$query = str_replace('LIMIT 0, 24', ' ', $query);
				$query = str_replace('GROUP BY vhd_posts.ID', ' ', $query);
				$product_ids = $wpdb->get_var($query);

				if (empty($product_ids)) return [];

				$sql = "SELECT DISTINCT vhd_terms.term_id, vhd_term_taxonomy.taxonomy, vhd_terms.name
					FROM vhd_term_taxonomy
					JOIN vhd_terms ON (vhd_term_taxonomy.term_id = vhd_terms.term_id)
					JOIN vhd_term_relationships ON (vhd_terms.term_id = vhd_term_relationships.term_taxonomy_id)
					WHERE vhd_term_taxonomy.taxonomy = 'product-brand' 
					AND object_id IN ($product_ids)
				";
				return $wpdb->get_results($sql);
				// return site_the_category_brands($categories);
			} else {
				return get_terms([
					'number' => false,
					'taxonomy' => 'product-brand',
				]);
			}
		}
		
	}

	function fetch_terms_by_brands() {
		$value_prices = site__get('khoang-gia', []);
		$value_brands = site__get('thuong-hieu', []);
		if (empty($value_brands)) return false;
		if (!empty($value_prices)) return fetch_term_by_price();

		$items = array_map(function ($value) {
			return (object)[
				'term_id' => $value,
				'taxonomy' => 'product-brand',
			];
		}, $value_brands);

		return site_get_terms_level_2_by_terms($items, 'product_cat', 200);
	}

	function fetch_terms_by_keywords() {
		global $wp_query, $wpdb;

		$query = str_replace('SQL_CALC_FOUND_ROWS DISTINCT vhd_posts.ID', 'GROUP_CONCAT(DISTINCT vhd_posts.ID)', $wp_query->request);
		$query = str_replace('LIMIT 0, 24', ' ', $query);
		$query = str_replace('GROUP BY vhd_posts.ID', ' ', $query);

		$product_ids = $wpdb->get_var($query);

		if (empty($product_ids)) return [];

		$sql = "SELECT GROUP_CONCAT(DISTINCT vhd_term_taxonomy.term_id)
			FROM vhd_term_taxonomy
			JOIN vhd_term_relationships ON (vhd_term_taxonomy.term_taxonomy_id = vhd_term_relationships.term_taxonomy_id )
			WHERE vhd_term_relationships.object_id  IN ($product_ids) AND vhd_term_taxonomy.taxonomy = 'product_cat'
		";

		$allCategories = $wpdb->get_var($sql);
		$exclude_ids = [100, 15, 23, 137];
		$final_cat = array_filter(explode(',', $allCategories), function($value) use ($exclude_ids) {
			if (in_array($value, $exclude_ids)) return false;
			return true;
		});

		if (empty($final_cat)) return [];
		return get_terms(['include' => $final_cat]);
	}

	function fetch_saleoff_categories() {
		$list_product_byIDs_saleoff = get_list_product_byIDs_giasohomnay();
		$list_cate_saleoff = get_categories_except_sale_off($list_product_byIDs_saleoff);
		return get_level_1_parent_categories($list_cate_saleoff);
	}

	// Function to fetch all level 1 categories
	function fetch_all_level_1_categories($price) {
		global $wpdb;
		$exclude_slugs = ['gia-soc-hom-nay', 'uncategorized', 'decor']; // Add the slugs of categories you want to exclude

		if (!empty($price)) return fetch_term_by_price();

		$categories = get_terms([
			'taxonomy' => 'product_cat',
			'parent' => 0,
			'hide_empty' => false,
		]);
		// Filter categories to only include those with published products and exclude specific categories
		$categories_with_products = array_filter($categories, function ($category) use ($exclude_slugs, $wpdb) {
			if (in_array($category->slug, $exclude_slugs)) return false;

			$cat_root = site_wc_get_terms_to_root($category, 1);
			$listCat = get_term_children($cat_root->term_id, $cat_root->taxonomy);
			$listCat = !empty($listCat) ? implode(',', $listCat) : 0;
			$haveData = $wpdb->get_var("SELECT COUNT(vhd_posts.ID)
				FROM vhd_posts
				JOIN vhd_term_relationships ON(vhd_posts.ID = vhd_term_relationships.object_id)
				WHERE vhd_posts.post_type = 'product' AND vhd_posts.post_status = 'publish'
				AND vhd_term_relationships.term_taxonomy_id IN (".$listCat.")
			");

			return !empty($haveData) ? true : false;
		});

		return $categories_with_products;
	}

	// Check if current URL has specific query parameters
	function has_specific_query_params() {
		$query_params = ['thuong-hieu', 's']; // Add more if needed , 'min_price', 'max_price'     
		foreach ($query_params as $param) {
			if (isset($_GET[$param])) {
				return true;
			}
		}
		return false;
	}

	function hasChildTerm($catId) {
		global $wpdb;

		$child = $wpdb->get_var("SELECT term_id FROM vhd_term_taxonomy WHERE parent = $catId");

		return !empty($child) ? 1 : 0;
	}

	global $sidebar_choose, $sidebar_true;

	$sidebar_true = true;
	$sidebar_choose = $sidebar_choose ?? [];

	$fields = [];
	$terms  = [];
	$brands = [];
	$all_categories = [];
	$cat_link = '';
	$level = 0;

	$types = [
		1 => 'Yêu thích',
		2 => 'Hot Deal',
		3 => 'Bán chạy',
	];

	$sorts = [
		'discount' => 'Giảm giá nhiều',
		'buy' => 'Bán chạy',
	];

	$value_prices = site__get('khoang-gia', []);
	$value_brands = site__get('thuong-hieu', []);
	$value_cats   = site__get('danh-muc', []);
	$value_types  = site__get('types', []);
	$value_sort   = site__get('sort', '');

	$uri = explode('?', $_SERVER['REQUEST_URI']);
	$cat = get_queried_object();
	$kw = site__get('s', '');

	$action_url = $uri[0]; // Define the $action_url variable with a default value

	if ($kw) {
		fetch_products_by_keyword($kw);
		$all_categories = fetch_categories_by_product_ids();
	}

	if (is_shop() && !has_specific_query_params()) {
		$terms = fetch_all_level_1_categories($value_prices);
	}

	if (isset($cat->taxonomy)) {
		$level = site_wc_get_term_level($cat);
		$cat_link = get_term_link($cat->term_id);
		$brands = getBrandsByCat($cat, $value_cats, $value_prices);
		
		if (!empty($value_brands)) {
			$mapBrand = array_map(function($v) {
				return $v->term_id;
			}, $brands);
			$reload = false;

			foreach ($value_brands as $k => $v) {
				if (!in_array($v, $mapBrand)) {
					$reload = true;
					unset($value_brands[$k]);
				}
			}

			if ($reload) {
				$current_url = home_url(add_query_arg([]));
				$url_parts = wp_parse_url($current_url);
				$query_params = [];
				if (isset($url_parts['query'])) wp_parse_str($url_parts['query'], $query_params);
				$query_params['thuong-hieu'] = $value_brands;
				$new_query = http_build_query($query_params);
				$new_url = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $new_query;
				echo '<script type="text/javascript">
					window.location.href = "' . $new_url . '";
				</script>';
			}
		}

		if (!empty($value_prices)) {
			$terms = fetch_terms($level, $cat);
		}
		else {
			if ($level != 3) $terms = fetch_terms($level, $cat);
			else $terms = fetch_sibling_terms($cat->term_id, $cat->taxonomy);
		}
	} else {
		$brands = fetch_brands($all_categories, $value_prices);

		if (!empty($value_brands)) {
			$terms = fetch_terms_by_brands($value_brands);
		}
		else {
			if (!is_shop() || has_specific_query_params()) $terms = fetch_terms_by_keywords();
		}
	}

	if (is_product_category('gia-soc-hom-nay')) $terms = fetch_saleoff_categories();

	$queried_object = get_queried_object();
	$current_term_link = is_a($queried_object, 'WP_Term') ? get_term_link($queried_object) : null;
?>

<div class="d-none d-lg-block col-lg-2 col-sidebar-product">
	<div class="section-bg">
		<div class="nav-aside bg-light">
			<form class="sidebar-form" method="get" action="<?php echo htmlspecialchars($action_url); ?>">
				<?php if (isset($_GET['dgwt_wcas'])): ?>
					<input type="hidden" name="dgwt_wcas" value="1">
				<?php endif; ?>
				<?php if (isset($_GET['s'])): ?>
					<input type="hidden" name="s" value="<?php echo htmlspecialchars($_GET['s']); ?>">
					<input type="hidden" name="post_type" value="product">
					<?php
						$query_params = $_GET;
						unset($query_params['thuong-hieu'], $query_params['gia-thap-nhat'], $query_params['gia-cao-nhat']);
						$action_url = $uri[0] . '?' . http_build_query($query_params) . '&';
					?>
				<?php endif; ?>

				<ul class="nav flex-column">
					<?php if (!empty($brands)): ?>
						<li class="nav-item my-2">
							<b><a href="">Thương hiệu</a></b>
							<div class="explore-more <?php echo count($brands) > 7 ? '' : 'tooShort'; ?>">
								<ul class="nav <?php echo count($brands) > 7 ? 'collapse' : ''; ?>" id="detailMoreBrands">
									<?php 
										foreach ($brands as $term):
											$checked = in_array($term->term_id, $value_brands) ? 'checked' : '';
									?>
										<li class="<?= $checked ?>">
											<label class="custom-checkbox">
												<input type="checkbox" name="thuong-hieu[]" value="<?= $term->term_id; ?>" <?= $checked ?>>
												<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?= $term->name; ?></span></span>
											</label>
										</li>
										<?php
										// Add to sidebar_choose
										if (in_array($term->term_id, $value_brands)) {
											$sidebar_choose['thuong-hieu[]=' . $term->term_id] = $term->name;
										}
										?>
									<?php endforeach; ?>
								</ul>
								<?php if (count($brands) > 7): ?>
									<div class="explore-more-action">
										<a class="btn btn-outline-primary p-0 border-2 rounded fw-bold btn-viewmore mb-2 w-100" data-bs-toggle="collapse" href="#detailMoreBrands" role="button" aria-expanded="false" aria-controls="detailMoreBrands">
											<span class="text-1">Xem thêm</span>
											<span class="text-2">Rút gọn</span>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</li>
					<?php endif; ?>
					
					<?php if (!empty($terms)): ?>
						<?php if ($level <= 3): ?>
							<li class="nav-item my-2">
								<b><a href="">Phân loại</a></b>
								<div class="explore-more">
									<ul class="nav flex-column colltermsapse" id="detailMoreCate">
										<?php if (!empty($terms)): ?>
											<?php foreach ($terms as $term): ?>
												<li>
													<?php
														$category_link = get_term_link($term->term_id);

														// Check if there are any query parameters in the current URL
														if (!empty($_GET)) {
															// Loop through each query parameter in the $_GET array
															foreach ($_GET as $key => $value) {
																if ($key == 'danh-muc') continue;
																// Check if the parameter is an array (e.g., brands[])
																if (is_array($value)) {
																	// Loop through the array and append each value individually
																	foreach ($value as $val) {
																		$category_link = add_query_arg($key . '[]', $val, $category_link);
																	}
																} else {
																	// Append the single parameter to the category link
																	$category_link = add_query_arg($key, $value, $category_link);
																}
															}
														}

														$hasChildTerm = hasChildTerm($term->term_id);
													?>
													<?php if ((($level == 1 || $level == 3 || ($value_brands && $level == 0)) || is_shop() && !is_product_category('gia-soc-hom-nay')) && $hasChildTerm): ?>
														<a class="cate_link checkbox-link <?php echo $current_term_link == $category_link ? 'active' : ''; ?>" href="<?php echo $category_link; ?>"><?php echo $term->name; ?></a>
													<?php else: ?>
														<label class="custom-checkbox">
															<input type="checkbox" name="danh-muc[]" value="<?php echo $term->term_id; ?>" <?php echo in_array($term->term_id, $value_cats) ? 'checked' : ''; ?>>
															<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name; ?></span></span>
														</label>
														<?php
														// Add to sidebar_choose
														if (in_array($term->term_id, $value_cats)) {
															$sidebar_choose['danh-muc[]=' . $term->term_id] = $term->name;
														}
														?>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										<?php else: ?>
											<?php foreach ($all_categories as $category_id => $category_name): ?>
												<li><a class="cate_link checkbox-link" href="<?php echo get_term_link($category_id, 'product_cat'); ?>"><?php echo $category_name; ?></a></li>
											<?php endforeach; ?>
										<?php endif; ?>
									</ul>
									<div class="explore-more-action">
										<a class="btn btn-outline-primary p-0 border-2 rounded fw-bold btn-viewmore mb-2" data-bs-toggle="collapse" href="#detailMoreCate" role="button" aria-expanded="false" aria-controls="detailMoreCate">
											<span class="text-1">Xem thêm</span>
											<span class="text-2">Rút gọn</span>
										</a>
									</div>
								</div>
							</li>
						<?php endif; ?>
					<?php endif; ?>

					<li class="nav-item my-2">
						<b><a href="">Khoảng giá</a></b>
						<ul class="nav flex-column">
							<?php foreach (site_wc_get_prices_static() as $value => $name): ?>
								<li>
									<label class="custom-checkbox">
										<input type="checkbox" name="khoang-gia[]" value="<?= $value ?>" <?= in_array($value, $value_prices) ? 'checked' : ''; ?>>
										<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?= $name; ?></span></span>
									</label>
									<?php
										// Add to sidebar_choose
										if (in_array($value, $value_prices)) {
											$sidebar_choose['khoang-gia[]=' . $value] = $name;
										}
									?>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				</ul>

				<?php if (is_active_sidebar('sidebar-widget-area')): ?>
					<div id="sidebar-widget-area" class="widget-area sidebar-widget-area">
						<?php dynamic_sidebar('sidebar-widget-area'); ?>
					</div>
				<?php endif; ?>
			</form>
		</div>

		<div class="pt-2">
			<a href="<?php echo $uri[0]; ?>" class="w-100 btn btn-outline-primary px-1 border-2 rounded fw-bold fs-11">Xoá chọn</a>
		</div>
	</div>
</div>


<!-- POPUp Sidebar -->
<div class="modal fade modal-filterSidebar" id="filterSidebar" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Bộ lọc</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="nav-aside p-2 bg-light">
					<form class="sidebar-form" method="get" action="">
						<ul class="nav flex-column">
							<li class="nav-item my-2">
								<b><a href="#">Thương hiệu</a></b>
								<?php if ($brands): ?>
									<ul class="nav flex-column">
										<?php foreach ($brands as $term): ?>
											<li>
												<label class="custom-checkbox">
													<input type="checkbox" name="thuong-hieu[]" value="<?php echo $term->term_id; ?>" <?php echo in_array($term->term_id, $value_brands) ? 'checked' : ''; ?>>
													<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name; ?></span></span>
												</label>
												<?php
												// Add to sidebar_choose
												if (in_array($term->term_id, $value_brands)) {
													$sidebar_choose['thuong-hieu[]=' . $term->term_id] = $term->name;
												}
												?>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>

							<?php if ($level < 3): ?>
								<li class="nav-item my-2">
									<b><a href="#">Phân loại</a></b>
									<ul class="nav flex-column">
										<?php if (!empty($terms)): ?>
											<?php foreach ($terms as $term): ?>
												<?php
												$category_link = get_term_link($term->term_id);
												// Check if there are any query parameters in the current URL
												if (!empty($_GET)) {
													// Loop through each query parameter in the $_GET array
													foreach ($_GET as $key => $value) {
														// Check if the parameter is an array (e.g., brands[])
														if (is_array($value)) {
															// Loop through the array and append each value individually
															foreach ($value as $val) {
																$category_link = add_query_arg($key . '[]', $val, $category_link);
															}
														} else {
															// Append the single parameter to the category link
															$category_link = add_query_arg($key, $value, $category_link);
														}
													}
												}
												?>
												<li>
													<?php if (($level == 1 || $level == 3 || ($value_brands && $level == 0)) || is_shop() && !is_product_category('gia-soc-hom-nay')): ?>
														<a class="cate_link checkbox-link <?php echo $current_term_link == $category_link ? 'active' : ''; ?>" href="<?php echo $category_link; ?>"><?php echo $term->name; ?></a>
													<?php else: ?>
														<label class="custom-checkbox">
															<input type="checkbox" name="danh-muc[]" value="<?php echo $term->term_id; ?>" <?php echo in_array($term->term_id, $value_cats) ? 'checked' : ''; ?>>
															<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name; ?></span></span>
														</label>
														<?php
														// Add to sidebar_choose
														if (in_array($term->term_id, $value_cats)) {
															$sidebar_choose['danh-muc[]=' . $term->term_id] = $term->name;
														}
														?>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										<?php else: ?>
											<?php foreach ($all_categories as $category_id => $category_name): ?>
												<li><a class="cate_link checkbox-link" href="<?php echo get_term_link($category_id, 'product_cat'); ?>"><?php echo $category_name; ?></a></li>
											<?php endforeach; ?>
										<?php endif; ?>
									</ul>
								</li>
							<?php endif; ?>

							<li class="nav-item my-2">
								<b><a href="#">Khoảng giá</a></b>
								<ul class="nav flex-column">
									<?php foreach (site_wc_get_prices_static() as $value => $name): ?>
										<li>
											<label class="custom-checkbox">
												<input type="checkbox" name="khoang-gia[]" value="<?php echo $value; ?>" <?= in_array($value, $value_prices) ? 'checked' : ''; ?>>
												<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $name; ?></span></span>
											</label>
											<?php
												// Add to sidebar_choose
												if (in_array($value, $value_prices)) {
													$sidebar_choose['khoang-gia[]=' . $value] = $name;
												}
											?>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
						</ul>

						<?php if (is_active_sidebar('sidebar-widget-area')): ?>
							<div id="sidebar-widget-area" class="widget-area sidebar-widget-area">
								<?php dynamic_sidebar('sidebar-widget-area'); ?>
							</div>
						<?php endif; ?>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<div class="pt-2">
					<a href="<?php echo $uri[0]; ?>" class="w-100 btn btn-outline-primary px-1 border-2 rounded fw-bold fs-11">Xoá chọn</a>
				</div>
			</div>
		</div>
	</div>
</div>