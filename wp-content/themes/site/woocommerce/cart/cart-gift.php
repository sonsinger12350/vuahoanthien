<?php
	$listGiftSelected = !empty(WC()->session->get('order_gift')) ? WC()->session->get('order_gift') : [];
	$listGift = site_get_checkout_gift();
	$total = site_wc_cart_get_total();
	$couponsGift = [];
	$voucherGift = [];
	$couponActive = '';
	$voucherActive = [];
	$listEarnableGift = [
		'coupon' => [],
		'voucher' => [],
	];
	$voucherPartners = [];

	foreach ($listGift['coupons'] as $v) {
		$data = $v->get_data();
		$couponMetadata = get_metadata('post', $v->id);
		$data['img'] = get_the_post_thumbnail( $data['id'], 'full' );
		$data['condition_earn'] = $couponMetadata['condition_earn'][0];
		$data['post_link'] = $couponMetadata['post_link'][0];
		if ($data['condition_earn'] <= $total) $couponActive = $data['condition_earn'];
		$couponsGift[$data['id']] = $data;
		$listEarnableGift['coupon'][$data['code']] = $data['condition_earn'];
	}

	foreach ($listGift['vouchers'] as $v) {
		$data = $v->get_data();
		$voucherMetadata = get_metadata('post', $v->id);
		$data['img'] = get_the_post_thumbnail( $data['id'], 'full' );
		$data['condition_earn'] = $voucherMetadata['condition_earn'][0];
		$data['post_link'] = $voucherMetadata['post_link'][0];
		$data['partner_industry'] = $voucherMetadata['partner_industry'][0];
		$partner_industry = sanitize_title($data['partner_industry']);
		if ($data['condition_earn'] <= $total) $voucherActive[$partner_industry] = $data['condition_earn'];
		$voucherGift[$data['partner_industry']][$data['id']] = $data;
		$listEarnableGift['voucher'][$partner_industry][$data['code']] = $data['condition_earn'];
	}
?>
<script>
	var listEarnableGift = <?= json_encode($listEarnableGift) ?>;
</script>
<div class="section-bg mb-3">
	<div class="d-flex justify-content-between align-items-center">
		<p class="fw-bold mb-2 title-gift">Quà tặng khi mua đơn hàng hiện tại</p>
		<button class="btn btn-primary d-none" id="btn-save-gift" type="button">Lưu quà tặng</button>
	</div>
	<?php if (is_user_logged_in()): ?>
		<input type="hidden" name="order_gift" value="">
		<div class="list-cart-gift list-cart-gift-available">
			<p class="title-coupon mb-1">Vua Hoàn Thiện tặng bạn</p>
			<div class="list-my-gifts list-coupon-available mb-3">
				<?php foreach($couponsGift as $coupon):?>
					<div class="row gift-item checked <?= $coupon['condition_earn'] != $couponActive ? 'd-none' : '' ?>" data-min="<?= $coupon['condition_earn'] ?>" data-title="<?= $coupon['code']; ?>">
						<label class="col-12" for="gift_<?= $coupon['code']; ?>">
							<input type="checkbox" id="gift_<?= $coupon['code']; ?>" name="gift[]" value="<?= $coupon['code'] ?>" <?= in_array($coupon['code'], $listGiftSelected) ? 'checked' : '' ?>>
							<?= $coupon['img'];?>
							<small class="d-none"><?= $coupon['code']; ?></small>
						</label>
						<p class="voucher-link">
							<?php if (!empty($coupon['post_link'])): ?>
								<a class="text-primary" target="_blank" href="<?= $coupon['post_link'] ?>">Chi tiết chương trình</a>
							<?php endif ?>
						</p>
					</div>
				<?php endforeach;?>
			</div>
			<p class="title-voucher mb-1">Đối tác tặng bạn</p>
			<div class="list-industries mb-3">
				<?php 
					$index = 2;
					foreach($voucherGift as $industry => $vouchers):
					if (empty($industry) || empty($vouchers)) continue;
				?>
					<div class="list-industry" style="order: <?= $industry == 'Xây dựng' ? 1 : $index ?>">
						<p class="fw-bold mb-1">Đối tác ngành <span class="text-uppercase"><?= $industry ?></span></p>
						<div class="list-my-gifts list-voucher-available">
							<?php foreach($vouchers as $voucher):?>
								<div class="row gift-item d-block checked <?= !in_array($voucher['condition_earn'], $voucherActive) ? 'd-none' : ''?>" data-min="<?= $voucher['condition_earn'] ?>" data-title="<?= $voucher['code']; ?>" style="order: <?= $voucher['id'] ?>">
									<label class="col-12" for="gift_<?= $voucher['code']; ?>">
										<div class="d-flex align-items-center">
											<input type="checkbox" id="gift_<?= $voucher['code']; ?>" name="gift[]" value="<?= $voucher['code']; ?>" <?= in_array($voucher['code'], $listGiftSelected) ? 'checked' : '' ?>>
											<?= $voucher['img'];?>
											<small class="d-none"><?= $voucher['code']; ?></small>
										</div>
									</label>
									<p class="voucher-link">
										<?php if (!empty($voucher['post_link'])): ?>
											<a class="text-primary" target="_blank" href="<?= $voucher['post_link'] ?>">Chi tiết chương trình</a>
										<?php endif ?>
									</p>
								</div>
							<?php endforeach;?>
						</div>
					</div>
				<?php $index++; ?>
				<?php endforeach;?>
			</div>
		</div>
		<p class="fw-bold title-gift-unavailable mb-2">MUA THÊM SẢN PHẨM ĐỂ NHẬN THÊM NHIỀU QUÀ TẶNG HẤP DẪN KHÁC</p>
		<div class="list-cart-gift list-cart-gift-unavailable">
			<p class="title-coupon mb-1">Vua Hoàn Thiện tặng bạn</p>
			<div class="list-my-gifts list-coupon">
				<?php 
					foreach($couponsGift as $coupon):
					$hide = 0;
					if ($coupon['condition_earn'] == $couponActive) $hide = 1;
					if ($coupon['condition_earn'] < $couponActive) $hide = 1;
				?>
					<div class="row gift-item checked disabled <?= $hide ? 'd-none' : '' ?>" data-min="<?= $coupon['condition_earn'] ?>" data-title="<?= $coupon['code']; ?>">
						<label class="col-12" for="gift_<?= $coupon['code']; ?>">
							<?= $coupon['img'];?>
							<small class="d-none"><?= $coupon['code']; ?></small>
						</label>
						<p class="voucher-link">
							<?php if (!empty($voucher['post_link'])): ?>
								<a class="text-primary" target="_blank" href="<?= $voucher['post_link'] ?>">Chi tiết chương trình</a>
							<?php endif ?>
						</p>
					</div>
				<?php endforeach;?>
			</div>
			<p class="title-voucher mb-1 mt-3">Đối tác tặng bạn</p>
			<div class="list-industries">
				<?php 
					$index = 2;
					foreach($voucherGift as $industry => $vouchers):
					if (empty($industry) || empty($vouchers)) continue;
					$industrySanitize = sanitize_title($industry);
				?>
					<div class="list-industry" data-industry="<?= $industrySanitize ?>" style="order: <?= $industry == 'Xây dựng' ? 1 : $index ?>">
						<p class="fw-bold mb-1">Đối tác ngành <span class="text-uppercase"><?= $industry ?></span></p>
						<div class="list-my-gifts list-voucher">
							<?php 
								foreach($vouchers as $voucher):
								$hide = 0;
								if (in_array($voucher['condition_earn'], $voucherActive)) $hide = 1;
								if ($voucher['condition_earn'] < $voucherActive[$industrySanitize]) $hide = 1;
							?>
								<div class="row gift-item d-block checked disabled <?= $hide ? 'd-none' : '' ?>" data-min="<?= $voucher['condition_earn'] ?>" data-title="<?= $voucher['code']; ?>" style="order: <?= $voucher['id'] ?>">
									<label class="col-12" for="gift_<?= $voucher['code']; ?>">
										<div class="d-flex align-items-center">
											<?= $voucher['img'];?>
											<small class="d-none"><?= $voucher['code']; ?></small>
										</div>
									</label>
									<p class="voucher-link">
										<?php if (!empty($voucher['post_link'])): ?>
											<a class="text-primary" target="_blank" href="<?= $voucher['post_link'] ?>">Chi tiết chương trình</a>
										<?php endif ?>
									</p>
								</div>
							<?php endforeach;?>
						</div>
					</div>
				<?php $index++; ?>
				<?php endforeach;?>
			</div>
		</div>
	<?php else: ?>
		<p class="mb-0"> Vui lòng <a href="/dang-nhap/?redirect_to=/cart" class="text-primary">Đăng nhập</a> để sử dụng chức năng này</p>
	<?php endif ?>
</div>
