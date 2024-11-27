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

$coupons = site_get_coupons(array('sort'=>'minimum_amount'));
$total = site_wc_cart_get_total();
?>
<div class="modal fade modal-voucher" id="discountCode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
				<?php if( count($coupons)>0 ) :?>
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

					foreach( $coupons as $coupon ): 
						$data = $coupon->get_data(); 
						$img = get_the_post_thumbnail( $coupon->id, 'full', array( 'alt' => $coupon->get_code() ) );
						$min = (int) $data['minimum_amount'];
						if ($min <= $total) {
							$current_coupon_archived_amount = $data['minimum_amount'];
			              	$current_coupon_archived_code = $data['code'];
			            }
					?>
					<div class="row coupon-item bg-light mb-2 p-2 shadow rounded align-items-center <?php echo ($current_coupon_archived_amount == $data['minimum_amount'])?"checked":""; ?>" data-min="<?php echo $data['minimum_amount'];?>" data-title="<?php echo $coupon->get_code();?>">
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
							<div>Đơn hàng từ <?php echo site_wc_price($data['minimum_amount']);?><sub>đ</sub></div>
						</div>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>