<?php
	$my_gift = site_get_my_gift(get_current_user_ID());
	$appliedCoupons = WC()->cart->get_applied_coupons();
	$appliedCoupon = !empty($appliedCoupons) ? current($appliedCoupons) : '';
?>
<div class="mb-3 section-bg"> <!-- bg-light border border-0 rounded p-2 -->
	<div class="py-2">
		<span class="fw-bold">Quà tặng hiện có của bạn</span>
		<?php if (is_user_logged_in()): ?>
			<form method="post">
				<div class="d-flex bg-grey-lightest mb-3 mt-3 d-none">
					<div class="input-group me-2">
						<span class="input-group-text bg-light" id="basic-addon1">
							<i class="bi bi-ticket-perforated fs-5"></i>
						</span>
						<input type="text" name="coupon_code" class="form-control control-coupon" placeholder="Nhập mã khuyến mãi" aria-label="Nhập mã khuyến mãi" aria-describedby="basic-addon1">
						<input type="hidden" name="remove_coupon">
						<button type="submit" class="btn btn-primary rounded btn-submit-coupon" style="margin-left: 5px;">Áp dụng</button>
					</div>
				</div>
				<input type="hidden" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
			</form>
			<div class="">
				<?php 
					if (!empty($cart_coupons)):
						foreach ($cart_coupons as $code => $coupon) :

							$data = $coupon->get_data();
							$a_c = '';
							if ($code == $cart_discount) {
								$a_c = ' wps_remove_virtual_coupon';
							}
				?>
					<strong><?php echo esc_attr($code); ?></strong>
					[<a class="text-danger<?php echo $a_c; ?>" href="<?php echo add_query_arg('remove_coupon', urlencode($coupon->get_code())); ?>" title="Xóa">&times;</a>]
				<?php 
						endforeach; 
					endif;
				?>
			</div>
			<?php if (!empty($my_gift)): ?>
				<div class="p-2 list-my-coupons">
					<?php
						foreach($my_gift as $coupon): 
							$data = $coupon->get_data();
							$img = get_the_post_thumbnail( $coupon->id, 'full', array( 'alt' => $coupon->get_code() ) );
						?>
						<label class="row coupon-item align-items-center checked <?=$total < $data['condition_earn'] ? 'disabled' : ''?>" data-min="<?= $data['condition_earn'] ?>" data-title="<?= $coupon->get_code();?>" for="checkbox_coupon_<?= $coupon->code ?>">
							<div class="col-12 d-flex">
								<input type="checkbox" name="coupon_selected" <?= $coupon->code == $appliedCoupon ? 'checked' : ''?> value="<?= $coupon->code ?>" id="checkbox_coupon_<?= $coupon->code ?>">
								<?= $img ?>
							</div>
						</label>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<p class="text-primary fst-italic mt-2">Bạn chưa có quà tặng. Hãy mua sắm để nhận nhiều ưu đãi</p>
			<?php endif ?>
		<?php else: ?>
			<p class="mb-0"> Vui lòng <a href="/dang-nhap/?redirect_to=/cart" class="text-primary">Đăng nhập</a> để sử dụng chức năng này</p>
		<?php endif ?>
	</div>
	<?php
		if (!empty($cart_coupons)):
			foreach ($cart_coupons as $code => $coupon) :
				$img = get_the_post_thumbnail($coupon->id, 'large');
				$post_link = get_metadata('post', $coupon->id)['post_link'][0];
				if ($img != ''):
			?>
					<a class="mb-2 d-block" href="<?= !empty($post_link) ? $post_link : 'javascript:void(0)' ?>" <?= !empty($post_link) ? 'target="_blank"' : '' ?>><?php echo $img; ?></a>
			<?php
				endif;
			endforeach;
		endif;
	?>
</div>