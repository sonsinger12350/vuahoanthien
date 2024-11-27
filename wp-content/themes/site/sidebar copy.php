<?php

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

	$value_prices = site__get('prices', []);
	$value_brands = site__get('brands', []);
	$value_cats   = site__get('cats', []);
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

	if (isset($cat->taxonomy)) {
		$level = site_wc_get_term_level($cat);
		$cat_link = get_term_link($cat->term_id);

		if ($level != 3) {
			$terms = fetch_terms($cat->term_id, $cat->taxonomy);
		}
		else {
			$terms = fetch_sibling_terms($cat->term_id, $cat->taxonomy);
		}

		$brands = getBrandsByCat($cat);
	} else {
		$brands = fetch_brands($all_categories);
		$terms = fetch_terms_by_brands($value_brands);
	}

	if (is_product_category('gia-soc-hom-nay')) {
		$terms = fetch_saleoff_categories();
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

	function fetch_terms($parent_id, $taxonomy) {
		return get_terms([
			'parent' => $parent_id,
			'number' => false,
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
		]);
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

	function fetch_brands($categories) {
		if (isset($_GET['s'])) {
			return site_the_category_brands($categories);
		} else {
			return get_terms([
				'number' => false,
				'taxonomy' => 'product-brand',
			]);
		}
	}

	function fetch_terms_by_brands($value_brands) {
		if (count($value_brands)) {
			$items = array_map(function ($value) {
				return (object)[
					'term_id' => $value,
					'taxonomy' => 'product-brand',
				];
			}, $value_brands);
			return site_get_terms_level_2_by_terms($items, 'product_cat', 200);
		}
		return [];
	}

	function fetch_saleoff_categories() {
		$list_product_byIDs_saleoff = get_list_product_byIDs_giasohomnay();
		$list_cate_saleoff = get_categories_except_sale_off($list_product_byIDs_saleoff);
		return get_level_1_parent_categories($list_cate_saleoff);
	}

	// Function to fetch all level 1 categories
	function fetch_all_level_1_categories() {
		$exclude_slugs = ['gia-soc-hom-nay', 'uncategorized', 'decor']; // Add the slugs of categories you want to exclude

		$categories = get_terms([
			'taxonomy' => 'product_cat',
			'parent' => 0,
			'hide_empty' => false,
		]);

		// Filter categories to only include those with published products and exclude specific categories
		$categories_with_products = array_filter($categories, function ($category) use ($exclude_slugs) {
			if (in_array($category->slug, $exclude_slugs)) return false;

			$args = [
				'post_type' => 'product',
				'post_status' => 'publish',
				'tax_query' => [
					[
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => $category->term_id,
					],
				],
			];
			$query = new WP_Query($args);
			return $query->have_posts();
		});

		return $categories_with_products;
	}

	// Check if current URL has specific query parameters
	function has_specific_query_params() {
		$query_params = ['brands', 's']; // Add more if needed , 'min_price', 'max_price'     
		foreach ($query_params as $param) {
			if (isset($_GET[$param])) {
				return true;
			}
		}
		return false;
	}

?>

<div class="d-none d-lg-block col-lg-2 col-sidebar-product">
	<div class="section-bg">
		<div class="nav-aside bg-light">
			<form class="sidebar-form" method="get" action="<?php echo htmlspecialchars($action_url); ?>">
				<?php if (isset($_GET['s'])): ?>
					<input type="hidden" name="s" value="<?php echo htmlspecialchars($_GET['s']); ?>">
					<input type="hidden" name="post_type" value="product">
					<?php
					$query_params = $_GET;
					unset($query_params['brands'], $query_params['min_price'], $query_params['max_price']);
					$action_url = $uri[0] . '?' . http_build_query($query_params) . '&';
					?>
				<?php endif; ?>

				<ul class="nav flex-column">
					<li class="nav-item my-2">
						<b><a href="">Thương hiệu</a></b>
						<?php if ($brands): ?>
							<div class="explore-more <?php echo count($brands) > 7 ? '' : 'tooShort'; ?>">
								<ul class="nav <?php echo count($brands) > 7 ? 'collapse' : ''; ?>" id="detailMoreBrands">
									<?php 
										foreach ($brands as $term):
											$checked = in_array($term->term_id, $value_brands) ? 'checked' : '';
									?>
										<li class="<?= $checked ?>">
											<label class="custom-checkbox">
												<input type="checkbox" name="brands[]" value="<?= $term->term_id; ?>" <?= $checked ?>>
												<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?= $term->name; ?></span></span>
											</label>
										</li>
										<?php
										// Add to sidebar_choose
										if (in_array($term->term_id, $value_brands)) {
											$sidebar_choose['brands[]=' . $term->term_id] = $term->name;
										}
										?>
									<?php endforeach; ?>
								</ul>
								<?php if (count($brands) > 7): ?>
									<div class="explore-more-action">
										<a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#detailMoreBrands" role="button" aria-expanded="false" aria-controls="detailMoreBrands">
											<span class="text-1">Xem thêm</span>
											<span class="text-2">Rút gọn</span>
										</a>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</li>

					<?php if (is_shop() && !has_specific_query_params()) {
						$terms = fetch_all_level_1_categories();
					} ?>
					<?php if ($level <= 3): ?>
						<li class="nav-item my-2">
							<b><a href="">Phân loại</a></b>
							<div class="explore-more">
								<ul class="nav flex-column colltermsapse" id="detailMoreCate">
									<?php //var_dump($terms); 
									?>
									<?php if (count($terms) > 0): ?>
										<?php foreach ($terms as $term): ?>
											<li>
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
												<?php if (($level == 1 || $level == 3 || ($value_brands && $level == 0)) || is_shop() && !is_product_category('gia-soc-hom-nay')): ?>
													<a class="cate_link checkbox-link <?php echo get_term_link(get_queried_object()) == $category_link ? 'active' : ''; ?>" href="<?php echo $category_link; ?>"><?php echo $term->name; ?></a>
												<?php else: ?>
													<label class="custom-checkbox">
														<input type="checkbox" name="cats[]" value="<?php echo $term->term_id; ?>" <?php echo in_array($term->term_id, $value_cats) ? 'checked' : ''; ?>>
														<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name; ?></span></span>
													</label>
													<?php
													// Add to sidebar_choose
													if (in_array($term->term_id, $value_cats)) {
														$sidebar_choose['cats[]=' . $term->term_id] = $term->name;
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
									<a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#detailMoreCate" role="button" aria-expanded="false" aria-controls="detailMoreCate">
										<span class="text-1">Xem thêm</span>
										<span class="text-2">Rút gọn</span>
									</a>
								</div>
							</div>
						</li>
					<?php endif; ?>

					<li class="nav-item my-2">
						<b><a href="">Khoảng giá</a></b>
						<ul class="nav flex-column">
							<?php foreach (site_wc_get_prices_static() as $value => $name): ?>
								<li>
									<label class="custom-checkbox">
										<input type="checkbox" name="prices[]" value="<?php echo $value; ?>" <?php echo in_array($value, $value_prices) ? 'checked' : ''; ?>>
										<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $name; ?></span></span>
									</label>
									<?php
									// Add to sidebar_choose
									if (in_array($value, $value_prices)) {
										$sidebar_choose['prices[]=' . $value] = $name;
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
													<input type="checkbox" name="brands[]" value="<?php echo $term->term_id; ?>" <?php echo in_array($term->term_id, $value_brands) ? 'checked' : ''; ?>>
													<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name; ?></span></span>
												</label>
												<?php
												// Add to sidebar_choose
												if (in_array($term->term_id, $value_brands)) {
													$sidebar_choose['brands[]=' . $term->term_id] = $term->name;
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
										<?php if (is_shop() && !has_specific_query_params()) {
											$terms = fetch_all_level_1_categories();
										} ?>
										<?php if (count($terms) > 0): ?>
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
														<a class="cate_link checkbox-link <?php echo get_term_link(get_queried_object()) == $category_link ? 'active' : ''; ?>" href="<?php echo $category_link; ?>"><?php echo $term->name; ?></a>
													<?php else: ?>
														<label class="custom-checkbox">
															<input type="checkbox" name="cats[]" value="<?php echo $term->term_id; ?>" <?php echo in_array($term->term_id, $value_cats) ? 'checked' : ''; ?>>
															<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name; ?></span></span>
														</label>
														<?php
														// Add to sidebar_choose
														if (in_array($term->term_id, $value_cats)) {
															$sidebar_choose['cats[]=' . $term->term_id] = $term->name;
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
												<input type="checkbox" name="prices[]" value="<?php echo $value; ?>" <?php echo in_array($value, $value_prices) ? 'checked' : ''; ?>>
												<span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $name; ?></span></span>
											</label>
											<?php
											// Add to sidebar_choose
											if (in_array($value, $value_prices)) {
												$sidebar_choose['prices[]=' . $value] = $name;
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