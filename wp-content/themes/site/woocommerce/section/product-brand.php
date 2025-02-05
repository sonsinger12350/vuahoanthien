<?php
	$brands = get_the_terms(get_the_ID(), 'product-brand');
	if (!$brands || count($brands) == 0) return;
	$post_limit = 10;
	if (wp_is_mobile()) $post_limit = 6;
	$current_post_id = get_the_ID();

	$sql = " SELECT DISTINCT vhd_posts.ID
		FROM vhd_posts
		LEFT JOIN vhd_term_relationships ON (vhd_posts.ID = vhd_term_relationships.object_id)
		INNER JOIN vhd_postmeta ON ( vhd_posts.ID = vhd_postmeta.post_id )
		WHERE vhd_posts.ID != $current_post_id
		AND vhd_term_relationships.term_taxonomy_id = ".$brands[0]->term_id."
		AND vhd_postmeta.meta_key = '_sale_price' AND CAST(vhd_postmeta.meta_value AS SIGNED) > '0'
		AND vhd_posts.post_type = 'product'
		AND vhd_posts.post_status = 'publish'
		LIMIT 0, 10
	";

	$list = $wpdb->get_results($sql);
	$categories = get_the_terms(get_the_ID(), 'product_cat');
?>

<?php if (count($list) > 0): ?>
	<div class="section">
		<div class="container">
			<div class="section-bg">
				<h2 class="section-header border-0">
					<span>Sản Phẩm Khác Của <?php echo $brands[0]->name; ?></span>
				</h2>
				<div class="slide-multiple slide-products product-list">
					<?php
						foreach ($list as $p):
							site_setup_product_data(wc_get_product($p->ID)); ?>
							<div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
								<?php wc_get_template_part('archive/product', 'item'); ?>
							</div>
						<?php endforeach;
						site_reset_product_data();
					?>
				</div>
				<?php if (isset($categories[0])): ?>
					<div class="section-actions text-center mt-3">
						<a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>?thuong-hieu[]=<?php echo $brands[0]->term_id; ?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif ?>