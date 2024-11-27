<?php

/**
 * Template Name: Compare
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

$ids = explode('-', sanitize_text_field(isset($_GET['ids']) ? $_GET['ids'] : ''));
$error_compare_check = false;

$product_spec_mappings = [
    "bon_cau" => "bon_cau_specs",
    "bon_tam" => "bon_tam_specs",
    "bon_tieu" => "bon_tieu_specs",
    "lavabo" => "lavabo_specs",
    "nap_bon_cau" => "nap_bon_cau_specs",
    "sen_bon_tam" => "sen_bon_tam_specs",
    "sen_tam" => "sen_tam_specs",
    "voi_lavabo" => "voi_lavabo_specs",
    "bep" => "bep_specs",
    "lo" => "lo_specs",
    "cong_tac_o_cam" => "cong_tac_o_cam_specs",
    "o_cam_thong_minh" => "o_cam_thong_minh_specs",
    "thiet_bi_dong_cat" => "thiet_bi_dong_cat_specs",
    "voi_nuoc" => "voi_nuoc_specs",
    "may_bom" => "may_bom_specs",
    "may_say_tay" => "may_say_tay_specs",
    "tu_dien" => "tu_dien_specs",
    "quat_hut" => "quat_hut_specs",
    "khoa_dien_tu" => "khoa_dien_tu_specs",
    "bon_nuoc" => "bon_nuoc_specs",
    "may_nlmt" => "may_nlmt_specs",
    "tay_hoi" => "tay_hoi_specs",
    "tay_nam_cua" => "tay_nam_cua_specs",
    "phu_kien_khoa_dien_tu" => "phu_kien_khoa_dien_tu_specs",
    "led_tuyp" => "led_tuyp_specs",
    "led_ban_nguyet" => "led_ban_nguyet_specs",
    "led_bulb" => "led_bulb_specs",
    "led_downlight" => "led_downlight_specs",
    "led_khan_cap" => "led_khan_cap_specs",
    "phu_kien_tu_bep" => "phu_kien_tu_bep_specs",
    "chau_rua_chen" => "chau_rua_chen_specs",
    "may_hut_mui" => "may_hut_mui_specs",
    "may_rua_chen" => "may_rua_chen_specs",
    "tu_lanh" => "tu_lanh_specs",
    "tu_ruou" => "tu_ruou_specs",
    "gach" => "gach_specs",
    "vat_tu_op_lat" => "vat_tu_op_lat_specs",
    "bo_dieu_khien_trung_tam" => "bo_dieu_khien_trung_tam_specs",
    "cam_bien_thong_minh" => "cam_bien_thong_minh_specs",
    "cong_tac_thong_minh" => "cong_tac_thong_minh_specs",
    "den_thong_minh" => "den_thong_minh_specs",
    "bo_dieu_khien_hong_ngoai" => "bo_dieu_khien_hong_ngoai_specs",
    "pittong_nang_canh_tu" => "pittong_nang_canh_tu_specs",
    "may_giat" => "may_giat_specs",
    "dong_co_rem_thong_minh" => "dong_co_rem_thong_minh_specs",
    "camera_thong_minh" => "camera_thong_minh_specs",
    "may_say" => "may_say_specs",
    "noi_com_dien" => "noi_com_dien_specs",
    "noi_ap_suat" => "noi_ap_suat_specs",
    "may_huy_rac" => "may_huy_rac_specs",
    "may_loc_khong_khi" => "may_loc_khong_khi_specs",
    "may_hut_am" => "may_hut_am_specs",
    "may_loc_khong_khi_xe_hoi" => "may_loc_khong_khi_xe_hoi_specs",
    "quat_tran" => "quat_tran_specs",
    "noi_chien_khong_dau" => "noi_chien_khong_dau_specs",
    "may_nuong_banh_my" => "may_nuong_banh_my_specs",
    "may_xay_ca_phe" => "may_xay_ca_phe_specs",
    "may_pha_ca_phe" => "may_pha_ca_phe_specs",
    "am_sieu_toc" => "am_sieu_toc_specs",
    "may_ep" => "may_ep_specs",
    "may_ep_cham" => "may_ep_cham_specs",
    "may_vat_cam" => "may_vat_cam_specs",
    "may_xay_cam_tay" => "may_xay_cam_tay_specs",
    "may_giat_ket_hop_say" => "may_giat_ket_hop_say_specs",
    "may_xay_sinh_to" => "may_xay_sinh_to_specs",
    "may_nuoc_nong_truc_tiep" => "may_nuoc_nong_truc_tiep_specs",
    "may_nuoc_nong_gian_tiep" => "may_nuoc_nong_gian_tiep_specs",
    "quat_dien" => "quat_dien_specs",
    "quat_sac_gap_gon" => "quat_sac_gap_gon_specs",
    "den_diet_khuan" => "den_diet_khuan_specs",
    "den_led_de_ban" => "den_led_de_ban_specs",
    "set_thiet_bi_thong_minh" => "set_thiet_bi_thong_minh_specs",
    "may_tron_cam_tay" => "may_tron_cam_tay_specs",
    "tay_nam_tu" => "tay_nam_tu_specs",
    "gach_bong_gio" => "gach_bong_gio_specs",
    "thiet_bi_bao_chay" => "thiet_bi_bao_chay_specs",
    "tay_nang_canh_tu" => "tay_nang_canh_tu_specs",
    "ngoi" => "ngoi_specs",
    "may_loc_nuoc" => "may_loc_nuoc_specs",
	"son_nuoc" => "son_nuoc_specs",
	"bot_tret" => "bot_tret_specs",	
	"son_san" => "son_san_specs",
	"chong_tham" => "chong_tham_specs"
];

if (count($ids) > 1) {
	$first_product = wc_get_product($ids[0]);
	$product_type = get_field('product_type', $first_product->get_id());

	if (isset($product_spec_mappings[$product_type])) {
		$specs_slug = $product_spec_mappings[$product_type];
		$specs_s = get_field($specs_slug, $first_product->get_id());
	} else {
		$specs_s = "";
	}

	foreach ($ids as $id) {
		$product = wc_get_product($id);
		$current_product_type = get_field('product_type', $product->get_id());

		if ($current_product_type !== $product_type) {
			$error_compare_check = true;
			break;
		}
	}
}

site_body_class_add('compare-products');
get_header();

$products = wc_get_products([
	'limit' => 4,
	'include' => $ids,
]);

$acf_fields = site_acf_get_fields('Product Fields', $group = 1);

?>
<div class="bg-light">
	<div class="container">
		<div class="section-bg">
			<?php if ($error_compare_check): ?>
				<div class="warning-message"><i class="bi bi-exclamation-triangle"></i> <?php echo esc_html('Những sản phẩm này không cùng loại. Xin vui lòng so sánh những sản phẩm cùng loại với nhau.'); ?></div>
			<?php endif; ?>
			<div class="row row-cols-md-5 mb-3 mt-3" style="row-gap: 16px">
				<div class="col-12 col-md py-3">
					<h2 class="section-header m-0 mb-3 border-0">
						<span>So sánh sản phẩm</span>
					</h2>
				</div>
				<?php foreach ($products as $product): ?>
					<div class="col-6 col-md">
						<div class="card h-100 product-item">
							<a href="<?php echo esc_url($product->get_permalink()); ?>" class="text-decoration-none product-image mb-3">
								<img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'medium'); ?>" class="card-img-top" alt="<?php echo $product->get_title(); ?>" loading="lazy"/>
							</a>
							<div class="card-body p-0">
								<div class="product-title">
									<a href="<?php echo esc_url($product->get_permalink()); ?>" class="text-decoration-none fw-bold">
										<p class="card-text text-dark text-limit-3"><?php echo $product->get_title(); ?></p>
									</a>
								</div>
								<div class="product-rating">
									<div class="d-block fs-5 text starts" style="--rating: <?php site_wc_the_stars_percent($product) ?>%;"></div>
								</div>
								<?php if (site_wc_price($product->get_regular_price()) != null): ?>
									<div class="d-flex flex-column product-price">
										<div class="d-flex flex-row flex-wrap align-items-end justify-content-between">
											<div class="product-price-left">
												<p><small><del><?php echo site_wc_price($product->get_regular_price()); ?><sup class="text">đ</sup></del></small></p>
												<p><b class="me-1 text-danger"><?php echo site_wc_price($product->get_sale_price()); ?><sup class="text-danger fs-12 text">đ</sup></b></p>
											</div>
											<div class="d-flex bg-danger align-items-center justify-content-center border rounded percent-save">
												<span class="fs-11 fw-bold text-light">-<?php site_wc_the_discount_percent($product) ?>%</span>
											</div>
										</div>
									</div>
								<?php endif ?>

							</div>
							<div class="card-footer bg-transparent border-0 p-0 pt-2">
								<button class="btn btn-outline-danger w-100 fw-bold btn-remove-compare rounded" value="<?php echo $product->get_id(); ?>"><i class="bi bi-trash"></i> Xóa</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (!$error_compare_check): ?>
				<h2 class="section-header border-0">
					<span>Thông số kỹ thuật</span>
				</h2>
				<div class="d-none d-md-block">
					<div class="row row-cols-md-5 flex-nowrap">
						<div class="col py-2 border-top product-spec-title bg-grey-lightest">
							Sản phẩm
						</div>
						<?php foreach ($products as $product): ?>
							<div class="col py-2 border-top product-spec-content">
								<?php echo $product->get_title(); ?>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="row row-cols-md-5 flex-nowrap">
						<div class="col py-2 border-top product-spec-title bg-grey-lightest">
							Thương hiệu
						</div>
						<?php foreach ($products as $product): ?>
							<div class="col py-2 border-top product-spec-content">
								<?php site_the_product_brand($product->get_id()); ?>
							</div>
						<?php endforeach; ?>
					</div>
					<?php foreach ($acf_fields as $key => $field):
						$fielditem = acf_get_field($key);
						if ($fielditem['type'] != 'select' && $fielditem['type'] != 'group'):
							if (get_field($field['name'], $product->get_id()) != null): ?>
								<div class="row row-cols-md-5 flex-nowrap">
									<div class="col py-2 border-top product-spec-title bg-grey-lightest">
										<?php echo $field['title']; ?>
									</div>
									<?php foreach ($products as $product): ?>
										<div class="col py-2 border-top product-spec-content">
											<?php the_field($field['name'], $product->get_id()); ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif;
						endif;
						if ($fielditem['type'] == 'group' && $fielditem['name'] == $specs_slug):
							$specs_sp = $fielditem['sub_fields'];
							foreach ($specs_sp as $specs_sp_item): ?>
								<div class="row row-cols-md-5 flex-nowrap">
									<div class="col py-2 border-top product-spec-title bg-grey-lightest">
										<?php echo $specs_sp_item['label']; ?>
									</div>
									<?php 
										foreach ($products as $product) {
											$loai_sp = get_field("product_type", $product->get_id());
											$specs_sps = get_field($product_spec_mappings[$loai_sp], $product->get_id());

											if ($specs_sps) {
												foreach ($specs_sps as $keys => $specs_sp_items) {
													$fielditems = acf_get_field($keys);
													if ($specs_sp_item['label'] == $fielditems['label']) {
														echo '<div class="col py-2 border-top product-spec-content">'.$specs_sp_items.'</div>';
													} 
												}
											}
										} 
									?>
								</div>
					<?php endforeach;
						endif;
					endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="d-md-none d-block">
				<div class="d-flex border-bottom">
					<div class="fw-bold">Sản phẩm</div>
				</div>
				<div class="d-flex mb-3 spec-contents">
					<?php foreach ($products as $i => $product): ?>
						<div class="<?php echo $i < $n - 1 ? 'border-end ' : ''; ?>spec-contents-cell">
							<?php echo $product->get_title(); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="d-flex border-bottom">
					<div class="fw-bold">Thương hiệu</div>
				</div>
				<div class="d-flex mb-3 spec-contents">
					<?php foreach ($products as $i => $product): ?>
						<div class="<?php echo $i < $n - 1 ? 'border-end ' : ''; ?>spec-contents-cell">
							<?php site_the_product_brand($product->get_id()); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php 
					foreach ($acf_fields as $key => $field): 
						$fielditem = acf_get_field($key);
						if ($fielditem['type'] != 'select' && $fielditem['type'] != 'group'):
							if (get_field($field['name'], $product->get_id()) != null): ?>
								<div class="d-flex border-bottom">
									<div class="fw-bold"><?php echo $field['title']; ?></div>
								</div>
								<div class="d-flex mb-3 spec-contents">
									<?php foreach ($products as $i => $product): ?>
										<div class="<?php echo $i < $n - 1 ? 'border-end ' : ''; ?>spec-contents-cell">
											<?php the_field($field['name'], $product->get_id()); ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif;
						endif;
						if ($fielditem['type'] == 'group' && $fielditem['name'] == $specs_slug):
							$specs_sp = $fielditem['sub_fields'];
							foreach ($specs_sp as $specs_sp_item): ?>
								<div class="d-flex border-bottom">
									<div class="fw-bold"><?php echo $specs_sp_item['label']; ?></div>
								</div>
								<div class="d-flex mb-3 spec-contents">
									<?php 
										foreach ($products as $i => $product): 
										$loai_sp = get_field("product_type", $product->get_id());
										$specs_sps = get_field($product_spec_mappings[$loai_sp], $product->get_id());
										if ($specs_sps) {
											foreach ($specs_sps as $keys => $specs_sp_items) {
												$fielditems = acf_get_field($keys);
												if ($specs_sp_item['label'] == $fielditems['label']) {
													echo '<div class="'.($i < $n - 1 ? 'border-end ' : '').' spec-contents-cell">
													'.$specs_sp_items.'
													</div>';
												} 
											}
										}
									?>
									<?php endforeach; ?>
								</div>
					<?php endforeach;
						endif;
						endforeach;
					?>
			</div>
		</div>
	</div>
</div>
<script>
	var cp_ids = [<?php echo implode(',', $ids); ?>];
</script>
<?php

get_footer();
