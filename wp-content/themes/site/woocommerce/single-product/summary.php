<?php

$product = wc_get_product();
$product_id = $product->get_id();

$brand = site_get_product_brand($product->get_id());
$product_available = true;

// Like and Add Favourite Group
$wcwl_count   = 0;
$wcwl_add     = '#modal-noti';
$wcwl_remove  = '';

if (get_current_user_id() > 0) {
	$wcwl_count = site_wcwl_count($product->get_id());
	$wcwl_add     = site_add_to_wishlist_url($product->get_id());
	$wcwl_remove = wp_nonce_url(add_query_arg('remove_from_wishlist', $product->get_id(), $product->get_permalink()), 'remove_from_wishlist');
}

$like = (int) get_post_meta($product->get_id(), 'like', true);
$virtual_like_number = (int) get_post_meta($product_id, 'virtual_like_number', true);

$user_count              = yith_wcwl_count_add_to_wishlist($product_id);
$current_user_count = $user_count ? YITH_WCWL_Wishlist_Factory::get_times_current_user_added_count($product_id) : 0;   // 1 if current user make liked, 0 if none

$available_variations = false;

if (method_exists($product, 'get_available_variations')) {
	$available_variations = $product->get_available_variations();
}

$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();

$final_price = $regular_price;
if ($sale_price > 0) $final_price = $sale_price;
else $product_available = false;
$dinh_muc = '';
$trong_luong = '';

// check for Gạch Product
$loai_sp = get_field("producttype");

if ($loai_sp == "gach") {
	$heso_quydoi = get_field("heso_quydoi");
	$is_tuyp = get_field("tuyp_ky");
	$pricePerM2 = $final_price;
	$perboxPrice = $heso_quydoi * $final_price;
	$unit_heso = "thùng";
	if ($is_tuyp == true) $unit_heso = "tuýp (kg)";
	$field_title = [
		'Số m2/thùng',
		'Đơn giá thùng',
		'Số thùng cần'
	];
	$unit1 = 'm<sup>2</sup>';
	$unit2 = 'đ';
}
else {
	$don_vi_tinh = get_field("don_vi_tinh");
	$loai_dong_goi = get_field("loai_dong_goi");

	if (in_array($loai_sp, ['bao', 'kg'])) {
		$heso_quydoi = get_field("dinh_muc_kg");
		$trong_luong = get_field("trong_luong_bao");

		$don_vi_tinh = !empty($don_vi_tinh) ? $don_vi_tinh : 'kg';
		$loai_dong_goi = !empty($loai_dong_goi) ? $loai_dong_goi : 'bao';

		$field_title = [
			"Định mức ($don_vi_tinh/m<sup>2</sup>)",
			"Trọng lượng ($don_vi_tinh/$loai_dong_goi)",
			"Số $loai_dong_goi cần",
		];

		if ($loai_sp == 'kg') $field_title[] = "Đơn giá $loai_dong_goi";

		$unit = $don_vi_tinh;
		$unit2 = $don_vi_tinh;
	}
	elseif ($loai_sp == 'thung') {
		$heso_quydoi = get_field("dinh_muc_vien");
		$trong_luong = get_field("so_vien_thung");

		$don_vi_tinh = !empty($don_vi_tinh) ? $don_vi_tinh : 'viên';
		$loai_dong_goi = !empty($loai_dong_goi) ? $loai_dong_goi : 'thùng';

		$field_title = [
			"Định mức ($don_vi_tinh/m<sup>2</sup>)",
			"Số $don_vi_tinh/$loai_dong_goi",
			"Số $loai_dong_goi cần",
			"Đơn giá $loai_dong_goi"
		];
		$unit = $don_vi_tinh;
		$unit2 = $don_vi_tinh;
	}
}


$productType = get_field('product_type', $product_id);

?>
<div class="row <?php echo ($loai_sp == "gach") ? 'product-gach' : ''; ?>" id="productSummary"> <!-- mb-3  border-top -->
	<div class="col-12 col-md-12 col-lg-12 py-2"> <!--py-3 -->
		<div class="section-bg">
			<?php if (get_field('type', 'product_' . $product->get_id()) != ''): ?>
				<div class="d-flex align-items-center">
					<span class="bg-primary p-1 text-light fs-12"><?php the_field('type', 'product_' . $product->get_id()); ?></span>
				</div>
			<?php endif; ?>
			<div class="main-info "> <!--mb-3 -->
				<?php /*?>
				<a><?php   
						if( $brand ) { 
							echo $brand->name . ' - ';
						}
						echo $product->get_sku();
				?></a>
				<?php */ ?>
				<div class="title-box">
					<h5><?php the_title(); ?></h5>
				</div>

				<div class="d-flex row-reviews-links">
					<a href="#customerReviews" class="fw-bold me-1 text-primary text-hover-underline"><?php echo number_format($product->get_average_rating(), 1); ?></a>
					<a href="#customerReviews">
						<div class="d-block fs-5 text starts" style="--rating: <?php site_wc_the_stars_percent($product->get_average_rating()) ?>%;"></div>
					</a>
					<span class="ms-3 ps-3 border-start border-start-mb me-3 pe-3 border-end">
						<a class="text-hover-underline" href="#customerReviews">
							<b><?php echo $product->get_review_count(); ?></b>

							Đánh giá</a>
					</span>
					<div class="social-link">
						<?php /* ?>
						<a title="Yêu thích" href="<?php echo site_add_to_wishlist_url($product->get_id());?>" class="fs-3 text-primary favorite-link<?php echo $wcwl_count?' favorited':'';?>" data-remove="<?php echo $wcwl_remove;?>" data-id="<?php echo $product->get_id();?>" >
							<i class="bi bi-suit-heart<?php echo $wcwl_count?'-fill':'';?>"></i>
						</a>
						<?php */ ?>
						<a title="Chia sẻ" href="<?php echo site_wc_fb_share_url($product); ?>" class="fs-3 text-primary btn-facebook">
							<i class="bi bi-facebook"></i>
						</a>
						<a title="Copy link" href="#" class="text-primary btn-copy">
							<i class="bi bi-link-45deg"></i>
						</a>
						<span class="btn-favorite">
							<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
							<?php if ($user_count > 0 || $virtual_like_number > 0): ?>
								<span class="like-count d-none"><?php echo '<span class="user_count">' . formatNumber($user_count + $virtual_like_number) . '</span> người đã thích sản phẩm này'; //.wp_kses_post( yith_wcwl_get_count_text( $product_id ) );//$user_count;//$like;
																?></span>
							<?php endif; ?>
						</span>
						<a href="javascript:void(0)" data-id="<?= $product_id ?>" class="btn-compare d-flex align-items-center text-primary gap-2 ms-3">
							<i class="fa fa-plus-circle" aria-hidden="true"></i> So sánh
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-7 col-lg-7 py-2">
		<div class="section-bg">

			<?php
			wc_get_template_part('single-product/slider-images');
			?>
		</div>
	</div>
	<div class="col-12 col-md-5 col-lg-5 productSummary-col-right"> <!-- mobile-px-0 py-2 -->
		<!-- <div class="section-bg">   -->
		<div class="py-2">
			<div class="section-bg">
				<div class="row">
					<div class="d-flex flex-column bg-light"> <!--  mb-2 -->
						<div class="d-flex flex-row align-items-center justify-content-between row-price-discount">
							<?php if ($regular_price > 0 && $product_available == true): ?>

								<div class="row-price">
									<?php if ($sale_price > 0): ?>
										<span class="d-block fs-11">Giá thị trường: <span class="text-decoration-line-through product_regular_price"><?php echo site_wc_price($regular_price); ?><sup class="unit-price-symbol text">đ</sup></span></span>
										<span class="d-block fs-11">Giá ưu đãi: <span class="fw-bold fs-3 text-danger product_sale_price"><?php echo site_wc_price($sale_price); ?><sup class="unit-price-symbol text-danger text">đ</sup></span>
										<?php else: ?>
											<span class="d-block fs-11">Giá ưu đãi: <span class="fw-bold fs-3 text-danger product_sale_price"><?php echo site_wc_price($final_price); ?><sup class="unit-price-symbol text-danger text">đ</sup></span>
											<?php endif ?>
											</span>
								</div>
								<?php if (site_wc_get_discount_percent($product) > 0): ?>
									<div class="row-discount">
										<span class="d-block p-2 bg-blue rounded fs-3 text-yellow discount-number">Tiết kiệm <b><?php site_wc_the_discount_percent($product) ?>%</b></span>
									</div>
								<?php endif; ?>
							<?php else:
								$product_available = false;
								//var_dump($product_available);
							?>
								<div class="row-price">
									<span class="d-block">Giá: <span class="fw-bold fs-3 text-danger product_sale_price"><?php echo custom_replace_contact_text('Liên hệ'); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="py-2">
			<div class="section-bg">
				<div class="row">
					<?php
					do_action('woocommerce_before_add_to_cart_form');
					?>
					<div class="bg-light product_short_description"> <!-- my-2 -->
						<?php
						// echo $product->get_short_description(); 

						$page_on_front = (int) get_option('page_on_front', 0);
						the_field('product_short_description', $page_on_front);
						?>
					</div>
				</div>
			</div>
		</div>
		<?php if ($product_available == true): ?>
			<div class="py-2">
				<div class="section-bg">
					<?php if (!empty($loai_sp) && in_array($loai_sp, ['gach', 'bao', 'kg', 'thung'])): ?>
						<div class="row row-remake-price">
							<h3>Dự toán sản phẩm</h3>
							<div class="remake-price-box">
								<div class="remake-price-box">
									<div class="remake-price-row">
										<div class="remake-price-row-left">
											<label for="m2input">Số m<sup>2</sup> cần:</label>
											<input type="m2input" name="m2input" id="m2input">
										</div>
										<div class="remake-price-row-right">
											<label><?= @$field_title[0] ?>:</label>
											<div class="m2perbox"><strong><?= $heso_quydoi.' '.$unit ?> </strong></div>
										</div>
									</div>
									<div class="remake-price-row">
										<div class="remake-price-row-left">
											<label><?= $field_title[1] ?>:</label>
											<div class="priceperbox">
												<strong><span id="perboxPrice"><?= $loai_sp == 'gach' ? number_format(round($perboxPrice), 0, '.', ',') : $trong_luong  ?></span> <?= $unit2 ?></strong>
											</div>
										</div>
										<div class="remake-price-row-right">
											<label><?= $field_title[2] ?>	</label>
											<?php if ($loai_sp == 'gach'): ?>
												<div id="amountbox" class="amountbox"><strong>0</strong></div>
											<?php else: ?>
												<input type="number" id="amountbox">
											<?php endif ?>
										</div>
									</div>
									<?php if (!empty($field_title[3])): ?>
										<div class="remake-price-row">
											<div class="remake-price-row-left">
												<label><?= $field_title[3] ?>:</label>
												<div>
													<strong><span id="unit_price"><?php echo number_format(round($final_price*$trong_luong), 0, '.', ','); ?></span> </strong>
												</div>
											</div>
										</div>
									<?php endif ?>
									<div class="remake-price-row border-top pt-3">
										<label>Thành tiền:</label>
										<div class="final-price text-danger">
											<strong><span id="finalPrice">00.00</span> đ</strong>
										</div>
									</div>
								</div>
							</div>
						</div>
						<script type="text/javascript">
							function calculateTotalPrice() {
								let productType = '<?= $loai_sp ?>';
								let weight = <?= !empty($trong_luong) ? $trong_luong : 0 ?>;
								let price = <?= $loai_sp == 'gach' ? $perboxPrice : $final_price ?>;
								let amount = $('#amountbox').val();
								let total = 0;

								if (['thung', 'kg'].indexOf(productType) != -1) price = price * weight;
								if (productType == 'gach') amount = $('#amountbox').text();
								else amount = $('#amountbox').val();
								amount = parseFloat(amount);

								if (!isNaN(amount) && amount !== '') total = Math.round(price*amount).toLocaleString();
								$('#finalPrice').text(total);
							}

							$(document).ready(function() {
								$('body').on('input', '#m2input', function() {
									let userInput = $(this).val();
									let productType = '<?= $loai_sp ?>';
									let rateNumber = <?= $heso_quydoi ?>;
									let weight = <?= !empty($trong_luong) ? $trong_luong : 0 ?>;

									if (!isNaN(userInput) && userInput !== '') {
										let resultAmount;

										if (productType == 'gach') {
											resultAmount = Math.ceil(parseFloat(userInput) / rateNumber);
											$('#amountbox').text(resultAmount);
										}
										else {
											resultAmount = Math.ceil((parseFloat(userInput) * rateNumber) / weight);
											$('#amountbox').val(resultAmount);
										}

										$('#quantityProduct').val(resultAmount);
									}
									else {
										if (productType == 'gach') $('#amountbox').text(0);
										else $('#amountbox').val(0);
										$('#quantityProduct').val(1);
									}

									calculateTotalPrice();
								});

								$('body').on('input', '#amountbox', function() {
									let val = $(this).val();
									let rateNumber = <?= $heso_quydoi ?>;
									let weight = <?= !empty($trong_luong) ? $trong_luong : 0 ?>;
									let amount = '';

									if (val) amount = parseFloat(Number((parseFloat(val)*weight)/rateNumber).toFixed(2));
									$('#m2input').val(amount);
									calculateTotalPrice();
								});
							});
						</script>
					<?php endif; ?>
					<div class="row row-addcart-buttons">
						<div class="bg-light"> <!-- mt-2  -->
							<div class="row-bottons">
								<?php
								if ($available_variations) {

									wp_enqueue_script('wc-add-to-cart-variation');

									// Load the template.
									wc_get_template(
										'single-product/add-to-cart/variable.php',
										array(
											'available_variations' => $available_variations,
											'attributes'           => $product->get_variation_attributes(),
											'selected_attributes'  => $product->get_default_attributes(),
										)
									);
								} else {
									wc_get_template_part('single-product/add-to-cart/simple');
								}
								?>
							</div>
						</div>
						<div class="bg-light"> <!--mt-3 -->
							<div class="deal-price-box">
								<p>Vua Hoàn Thiện mong muốn mang đến Quý Khách hàng giá cả kèm chất lượng dịch vụ tốt nhất. Quý Khách vui lòng trả giá hoặc đề xuất mức giá mà Quý Khách thấy hợp lý. Hãy Trả Giá Ngay!</p>
								<a href="#" class="btn btn-success rounded me-1 p-2 fw-normal flex-grow-1 btn-deal-price"
									data-bs-toggle="modal" data-bs-target="#modal-deal">
									Trả giá ngay
								</a>
							</div>
							<?php /*?><div class="social-link">
								<a href="<?php echo site_add_to_wishlist_url($product->get_id());?>" class="fs-3 text-primary favorite-link<?php echo $wcwl_count?' favorited':'';?>" data-remove="<?php echo $wcwl_remove;?>" data-id="<?php echo $product->get_id();?>" >
									<i class="bi bi-suit-heart<?php echo $wcwl_count?'-fill':'';?>"></i>
								</a>
								<a href="<?php echo site_wc_fb_share_url($product);?>" class="fs-3 text-primary" target="_blank">
									<i class="bi bi-facebook"></i>
								</a>
								<a href="#" class="fs-3 text-primary btn-copy">
									<i class="bi bi-link-45deg"></i>
								</a>
							</div> <?php */ ?>
						</div>
						<?php ?><input class="data-copy" value="<?php echo esc_url($product->get_permalink()); ?>" style="opacity: 0; height: 0; padding: 0; margin: 0;" /><?php ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<!-- </div> -->
	</div>
</div>

<div class="modal fade modal-compare-product" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title border-0" id="discountCodeLabel">So sánh sản phẩm</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="list-product">
					<?php for ($i = 1; $i < 5; $i++):?>
						<div class="item" data-number="<?= $i ?>">
							<span class="remove-product"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
							<div class="product">
								<div class="empty" data-bs-toggle="tooltip" data-bs-placement="top" title="Vui lòng nhập tên sản phẩm vào ô bên dưới để thêm sản phẩm so sánh">
									<div class="add-item">+</div>
								</div>
							</div>
						</div>
					<?php endfor ?>
				</div>
				<div class="form-search">
					<p class="text-center mb-1">Nhập tên sản phẩm / mã sản phẩm để tìm kiếm</p>
					<div class="input-group">
						<span class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></span>
						<input name="keyword" type="text" placeholder="Bạn cần tìm gì" class="form-control">
						<input type="hidden" name="product_type" value="<?=$productType?>">
					</div>
					<div class="result-search"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary rounded btn-compare-product">So sánh</button>
				<a href="javascript:void(0)" class="remove-all text-danger">Xóa các sản phẩm đã chọn</a>
			</div>
		</div>
	</div>
</div>