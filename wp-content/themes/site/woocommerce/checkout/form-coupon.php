<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

$coupons = site_get_coupons(array('sort'=>'condition_earn'), 'DESC');
$listGift = site_get_checkout_gift();
$total = site_wc_cart_get_total();
$couponsGift = [];
$voucherGift = [];
$couponActive = '';
$voucherActive = '';

foreach ($listGift['coupons'] as $v) {
	$data = $v->get_data();
	$data['img'] = get_the_post_thumbnail( $data['id'], 'full', array( 'alt' => $data['code'] ) );
	$data['condition_earn'] = get_metadata('post', $v->id)['condition_earn'][0];
	if ($data['condition_earn'] <= $total) $couponActive = $data['id'];
	$couponsGift[$data['id']] = $data;
}

foreach ($listGift['vouchers'] as $v) {
	$data = $v->get_data();
	$data['img'] = get_the_post_thumbnail( $data['id'], 'full', array( 'alt' => $data['code'] ) );
	$data['condition_earn'] = get_metadata('post', $v->id)['condition_earn'][0];
	if ($data['condition_earn'] <= $total) $voucherActive = $data['id'];
	$voucherGift[$data['id']] = $data;
}

$my_gift = site_get_my_gift(get_current_user_ID());
$listGiftSelected = !empty(WC()->session->get('order_gift')) ? WC()->session->get('order_gift') : [];
?>
<div class="list-coupons d-none">
	<?php foreach ($coupons as $c): ?>
		<?php
			$condition_earn = get_metadata('post', $c->id)['condition_earn'][0];
		?>
		<div class="coupon-item" data-min="<?= $condition_earn ?>" data-title="<?= $c->code?>"></div>
	<?php endforeach ?>
</div>
<!-- <div class="modal fade modal-voucher d-none" id="discountCode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title border-0" id="discountCodeLabel">Mã khuyến mãi</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php if( empty($args['no_show']) ):?>
					<form method="post">
						<div class="d-flex bg-grey-lightest mb-3 p-4">
							<div class="input-group me-2">
								<span class="input-group-text bg-light" id="basic-addon1">
									<i class="bi bi-ticket-perforated fs-5"></i>
								</span>
								<input type="text" name="coupon_code" class="form-control control-coupon" placeholder="Nhập mã khuyến mãi"
								aria-label="Nhập mã khuyến mãi" aria-describedby="basic-addon1">
								<button type="submit" class="btn btn-primary rounded" style="margin-left: 5px;">Áp dụng</button>
							</div>
						</div>
						<input type="hidden" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
					</form>
				<?php endif;?>
				<?php if( count($my_gift) > 0 ) :?>
					<?php if( empty($args['no_show']) ):?>
						<div class="d-flex justify-content-between mb-3">
							<span class="fw-bold">Mã khuyến mãi</span>
							<small>Áp dụng tối đa 1</small>
						</div>
					<?php endif;?>
					<div class="p-2 list-my-coupons">
						<?php 
							$current_coupon_archived_code = 0;
							$current_coupon_archived_desc = 0;
							$min = 0;

							foreach($my_gift as $coupon): 
								$data = $coupon->get_data();
								$img = get_the_post_thumbnail( $coupon->id, 'full', array( 'alt' => $coupon->get_code() ) );
							?>
							<div class="row coupon-item bg-light mb-2 p-2 shadow rounded align-items-center checked <?=$total < $data['condition_earn'] ? 'disabled' : ''?>" data-min="<?= $data['condition_earn'] ?>" data-title="<?= $coupon->get_code();?>">
								<?php if( $img!='' ): ?>
									<div class="col-12">
										<?php echo $img;?>
										<small class="d-none"><?php echo $coupon->get_code();?></small>
									</div>
								<?php else: ?>
									<div class="col-3">
										<a href="#<?php echo $coupon->get_code();?>" class="d-flex flex-column align-items-center justify-content-center">
											<i class="bi bi-ticket-perforated"></i>
											<small><?php echo $coupon->get_code();?></small>
										</a>
									</div>
									<div class="col-9 border-0 border-dashed border-start">
										<div class="fs-5 fw-bold">Giảm <?php echo site_wc_price($data['amount']);?><sub>đ</sub></div>
										<div>Đơn hàng từ <?php echo site_wc_price($data['condition_earn']);?><sub>đ</sub></div>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div> -->
<!-- <div class="modal fade modal-gift" id="checkoutGift"tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title border-0" id="discountCodeLabel">Danh sách quà tặng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php if( !empty($couponsGift) ) :?>
					<div class="d-flex justify-content-between mb-3 mt-3">
						<span class="fw-bold">Mã khuyến mãi</span>
					</div>
					<div class="list-my-gifts list-coupon p-2">
						<?php foreach($couponsGift as $coupon):?>
							<div class="row gift-item bg-light mb-2 p-2 shadow rounded align-items-center checked <?= $coupon['id'] != $couponActive ? 'disabled' : ''?>" data-min="<?= $coupon['condition_earn'] ?>" data-title="<?= $coupon['code']; ?>">
								<?php if( $coupon['img'] != '' ): ?>
								<label class="col-12 d-flex" for="gift_<?= $coupon['code']; ?>">
									<input type="checkbox" id="gift_<?= $coupon['code']; ?>" name="gift[]" value="<?= $coupon['code'] ?>" <?= in_array($coupon['code'], $listGiftSelected) ? 'checked' : '' ?>>
									<?= $coupon['img'];?>
									<small class="d-none"><?= $coupon['code']; ?></small>
								</label>
								<?php else: ?>
								<div class="col-3">
									<a href="#<?= $coupon['code']; ?>" class="d-flex flex-column align-items-center justify-content-center">
										<i class="bi bi-ticket-perforated"></i>
										<small><?= $coupon['code']; ?></small>
									</a>
								</div>
								<div class="col-9 border-0 border-dashed border-start">
									<div class="fs-5 fw-bold">Giảm <?= site_wc_price($coupon['amount']);?><sub>đ</sub></div>
									<div>Đơn hàng từ <?= site_wc_price($coupon['condition_earn']);?><sub>đ</sub></div>
								</div>
								<?php endif; ?>
							</div>
						<?php endforeach;?>
					</div>
				<?php endif;?>

				<?php if( !empty($voucherGift) ) :?>
					<div class="d-flex justify-content-between mb-3 mt-3">
						<span class="fw-bold">Voucher</span>
					</div>
					<div class="list-my-gifts list-voucher p-2">
						<?php foreach($voucherGift as $voucher):?>
							<div class="row gift-item bg-light mb-2 p-2 shadow rounded align-items-center checked <?= $voucher['id'] != $voucherActive ? 'disabled' : ''?>" data-min="<?= $voucher['condition_earn'] ?>" data-title="<?= $voucher['code']; ?>">
								<?php if( $voucher['img'] != '' ): ?>
									<label class="col-12 d-flex" for="gift_<?= $voucher['code']; ?>">
										<input type="checkbox" id="gift_<?= $voucher['code']; ?>" name="gift[]" value="<?= $voucher['code']; ?>" <?= in_array($voucher['code'], $listGiftSelected) ? 'checked' : '' ?>>
										<?= $voucher['img'];?>
										<small class="d-none"><?= $voucher['code']; ?></small>
									</label>
								<?php else: ?>
									<div class="col-3">
										<a href="#<?= $voucher['code']; ?>" class="d-flex flex-column align-items-center justify-content-center">
											<i class="bi bi-ticket-perforated"></i>
											<small><?= $voucher['code']; ?></small>
										</a>
									</div>
									<div class="col-9 border-0 border-dashed border-start">
										<div class="fs-5 fw-bold">Giảm <?= site_wc_price($voucher['amount']);?><sub>đ</sub></div>
										<div>Đơn hàng từ <?= site_wc_price($voucher['condition_earn']);?><sub>đ</sub></div>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach;?>
					</div>
				<?php endif;?>
			</div>
			<div class="modal-footer">
				<div class="d-flex justify-content-between align-items-center w-100">
					<a href="<?=wc_get_account_endpoint_url('kho-qua-tang')?>" target="_blank" class="text-primary">Xem quà tặng của bạn</a>
					<button class="btn btn-primary btn-save-gift">Lưu quà tặng</button>
				</div>
			</div>
		</div>
	</div>
</div> -->