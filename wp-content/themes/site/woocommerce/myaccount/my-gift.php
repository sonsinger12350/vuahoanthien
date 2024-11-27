<?php
	$site_name = get_bloginfo('name');
	$myGifts = site_get_my_gift(get_current_user_ID(), true);
	$listVoucher = [];

	foreach ($myGifts['vouchers'] as $v) {
		$voucherMetadata = get_metadata('post', $v->ID);
		$listVoucher[$voucherMetadata['partner_industry'][0]][] = $v;
	}
?>

<div class="my-gift-page">
	<h2 class="mb-4 section-header border-0 mg-top-0"><span>Kho quà tặng</span></h2>

	<div class="list-coupon mb-4">
		<h4 class="mb-2 border-0 mg-top-0"><span><?= $site_name ?> tặng bạn</span></h3>
		<div class="list-gift">
			<?php if (!empty($myGifts['coupons'])): ?>
				<?php foreach ($myGifts['coupons'] as $coupon): ?>
					<?php
						$post_link = get_metadata('post', $coupon->ID)['post_link'][0];
					?>
					<div class="item d-flex flex-column align-items-end">
						<span class="total <?= $coupon->total <=1 ? 'd-none' : ''?>">x<?= $coupon->total?></span>
						<img src="<?= $coupon->img ?>" alt="<?= $coupon->post_title?>">
						<?php if (!empty($post_link)): ?>
							<a href="<?= $post_link ?>" target="_blank" class="text-primary">Chi tiết chương trình</a>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			<?php else: ?>
				<p class="text-left">Bạn chưa có quà tặng.</p>
			<?php endif ?>
		</div>
	</div>

	<div class="list-voucher">
		<h4 class="mb-4 border-0 mg-top-0"><span>Quà tặng từ đối tác</span></h4>
		
			<?php if (!empty($listVoucher)): ?>
				<?php foreach ($listVoucher as $industry => $vouchers): ?>
					<div class="list-industry mb-3">
						<h6 class="mb-0">Đối tác ngành <span class="text-uppercase"><?= $industry ?></span></h6>
						<div class="list-gift">
							<?php foreach ($vouchers as $voucher): ?>
								<?php
									$post_link = get_metadata('post', $voucher->ID)['post_link'][0];
								?>
								<div class="item d-flex flex-column align-items-end">
									<span class="total <?= $voucher->total <=1 ? 'd-none' : ''?>">x<?= $voucher->total?></span>
									<img src="<?= $voucher->img ?>" alt="<?= $voucher->post_title?>">
									<?php if (!empty($post_link)): ?>
										<a href="<?= $post_link ?>" target="_blank" class="text-primary">Chi tiết chương trình</a>
									<?php endif ?>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				<?php endforeach ?>
			<?php else: ?>
				<p class="text-left">Bạn chưa có quà tặng.</p>
			<?php endif ?>
		
	</div>
</div>