<?php
	global $wp_query;
	$paged = isset($_GET['pi']) ? intval($_GET['pi']) : 1;
	$query = $wp_query->request;
	$query = str_replace('GROUP BY vhd_posts.ID', '', $query);
	$query = str_replace('SELECT SQL_CALC_FOUND_ROWS', 'SELECT DISTINCT ', $query); 
	$wp_query->request = $query;
?>
<?php
if (woocommerce_product_loop()) : ?>
	<div id="list-products">
		<div class="product-list flex-nowrap flex-md-wrap">
			<?php
			//var_dump(wc_get_loop_prop( 'total' ));
			if (wc_get_loop_prop('total')) :
				while (have_posts()) : the_post();
					$product = wc_get_product();
					site_setup_product_data($product);
			?>
					<div class="col-6 col-md-4 col-lg w-lg-20 mb-3 col-product-item">
						<?php wc_get_template_part('archive/product', 'item'); ?>
					</div>
					<?php
				endwhile;
				site_reset_product_data();
			endif;

			if ($paged > 1) {
				for ($i = 2; $i <= $paged; $i++) {
					$the_query = $wp_query;
					$the_query->set('page', $i);
					$the_query->get_posts();
					while ($the_query->have_posts()) : the_post();
						$product = wc_get_product();

						site_setup_product_data($product);
					?>
						<div class="col-6 col-md-4 col-lg w-lg-20 mb-3 col-product-item">
							<?php wc_get_template_part('archive/product', 'item'); ?>
						</div>
			<?php
					endwhile;
					site_reset_product_data();
				}
			}
			?>
		</div>
	</div>
	<?php
	$total = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
	if ($total > 1 && $paged < $total):
		// $uri = explode('?', $_SERVER['REQUEST_URI']);
		$url = esc_url(remove_query_arg('paged'));
	?>
		<div class="row-loadmore text-center mt-4">
			<button class="btn btn-primary rounded btn-loadmore" type="button"
				data-href="<?php echo $url; ?>"
				data-paged="<?php echo $paged; ?>"
				data-total="<?php echo $total; ?>">Xem thêm sản phẩm</button>
		</div>
	<?php endif; ?>
<?php

// wc_get_template_part( 'section/pagination' );
else: ?>
	<div class="noti-text noti-product-notfound">
		<p><img src="<?php site_the_assets(); ?>images/icons/icon-no-product.jpg" atl="Product not Found"></p>
		<p>Xin lỗi chúng tôi không tìm thấy kết quả thỏa điều kiện.</p>
	</div>
<?php endif;?>