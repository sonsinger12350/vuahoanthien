<?php

/**
 * Template Name: Tracking
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

get_header();

$order_id = site__get('ma-don-hang', '');
$order = false;
$status_name = '';

$statuses = array(
	'processing' => '',
	'shipping' => '',
	'completed' => '',
);

if ($order_id != '') {
	$order = wc_get_order(getOrderIdByKiotVietOrder(str_replace('#', '', $order_id)));

	if ($order instanceof WC_Order && $order->get_id() > 0) {
		$status = $order->get_status();
		$steps = array(
			'Đang xử lý' => array('pending', 'processing'),
			'Đang vận chuyển' => array('shipping'),
			'Đã hoàn thành' => array('completed')
		);
	} else {
		$order = false;
	}
	//var_dump($status);
}



?>


<div class="bg-grey-lightest">
	<div class="container">
		<div class="tracking-order-item pb-4 pt-4">
			<div class="section-bg">
				<h2 class="mb-4 border-0 mg-top-0 section-header"><span><?php the_title(); ?></span></h2>
				<p><i>* Nhập mã đơn hàng để kiểm tra trạng thái đơn hàng</i></p>
				<form method="get" action="<?php the_permalink(); ?>">
					<div class="input-group search-box mb-3">
						<input type="text" class="form-control search-input"
							placeholder="Nhập mã đơn hàng" aria-label="Search"
							aria-describedby="button-addon2" name="ma-don-hang" value="<?php echo $order_id; ?>" required>
						<button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
					</div>
				</form>
				<?php if ($order):
					$data = $order->get_data();
					$totalDiscount = 0;

					if (!empty($data['fee_lines'])) {
						foreach ($data['fee_lines'] as $fee) {
							$totalDiscount += $fee->get_total();
						}
					}

					$totalDiscount = abs($totalDiscount);

					//var_dump($data);
					// $list_status = array();
					// // if( isset($statuses[$key]) ) {
					// if( isset($statuses[$data['status']]) ) {
					//   foreach( $statuses as $s_key => $name ) {
					//     // $list_status[] = '<span'.( $s_key == $key ? ' class="text-warning"' : '' ) . '>' . $name .'</span>';
					//     $list_status[] = '<span'.( $s_key == $data['status'] ? ' class="text-warning"' : '' ) . '>' . $name .'</span>';
					//   }
					// } else {
					//   $list_status[] = '<span class="text-warning">' . $status_name .'</span>';
					// }
					//var_dump( $list_status);
				?>
					<div class="tab-content" id="trackOrderContent">
						<div class="tab-pane fade show active" id="all-orders" role="tabpanel" aria-labelledby="all-orders-tab">
							<div class="card card-body order" data-status="<?php echo $order->get_status(); ?>">
								<div class="row order-status border-bottom pb-3">
									<div class="col-md-10">
										Đơn hàng: <strong>#<?= kiotVietOrderId($data['id']) ?></strong>
										| Thời gian đặt hàng: <strong><?php echo $data['date_created']->date('d/m/Y'); ?></strong>
										<br>
										Trạng thái: <span class="steps-list">
											<?php if ($status == "cancelled") {
												echo '<span class="steps"><span class="progress-step text-warning">Đã hủy</span></span>';
											} else {
												foreach ($steps as $step => $step_statuses) {
													$step_class = in_array($status, $step_statuses) ? ' text-warning' : '';
													echo '<span class="steps"><span class="progress-step ' . $step_class . '">' . $step . '</span><i class="bi bi-arrow-right-short"></i></span>';
												}
											} ?>
										</span>


										<?php //echo implode(' <i class="bi bi-arrow-right-short"></i> ', $list_status);
										?>
									</div>
								</div>
								<div class="order-content">
									<?php
									foreach ($order->get_items() as $item_id => $item):
										// order item data as an array
										$product = $item->get_product();

										if ($product == false) continue;

										$item_data = $item->get_data();
										$img = wp_get_attachment_image_url($product->get_image_id());
									?>
										<div class="order-item pb-3 border-bottom">
											<div class="order-item-image border-1">
												<?php if ($img): ?>
													<img src="<?php echo $img; ?>" class="img-thumbnail" />
												<?php endif; ?>
												<div class="order-item-quantity">X<?php echo $item_data['quantity']; ?></div>
											</div>
											<div class="order-item-detail">
												<p><?php echo $product->get_title(); ?></p>
											</div>
											<div class="order-item-price text-end">
												<b><?php echo site_wc_price($product->get_price()); ?><sup class="fs-12">đ</sup></b><br>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="order-footer pt-3 text-end">
									<?php
									$shipping_total = $order->get_shipping_total();
									$total_before_shipping = $data['total'] - $shipping_total + $totalDiscount; // Calculate total before shipping
									?>
									<h6><span class="label text-grey">Thành tiền:</span> <span class="price-number"><?php echo site_wc_price($total_before_shipping); ?><sup class="fs-12">đ</sup></span></h6>
									<?php if (!empty($data['fee_lines'])): ?>
										<?php foreach ($data['fee_lines'] as $v): ?>
											<h6>
												<span class="label text-grey"><?= $v->get_name() ?>:</span>
												<span class="price-number"><?php echo site_wc_price(abs($v->get_total())); ?><sup class="fs-12">đ</sup></span>
											</h6>
										<?php endforeach ?>
									<?php endif ?>
									<h6 class="text-end"><span class="label text-grey">Phí vận chuyển:</span>
										<span class="price-number">
											<?php if ($shipping_total > 0): ?>
												<?php echo site_wc_price($shipping_total); ?><sup class="fs-12">đ</sup>
											<?php else: ?>
												Giao hàng miễn phí
											<?php endif; ?>
										</span>
									</h6>
									<h5><span class="label text-grey">Tổng cộng:</span> <span class="price-number fw-bold"><?php echo site_wc_price($data['total']); ?><sup class="fs-12">đ</sup></span></h5>
								</div>
							</div>
						</div>
					</div>

				<?php else: ?>
					<?php if ($order_id != ''): ?>
						<div class="alert alert-danger" role="alert">
							Không tìm thấy đơn hàng với mã <strong><?php echo $order_id; ?></strong>. Vui lòng kiểm tra lại thông tin đơn hàng.
						</div>
					<?php endif ?>

				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php

get_footer();
