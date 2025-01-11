<?php

/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders);

?>
<h2 class="mb-4 section-header border-0 mg-top-0"><span>Quản lý đơn hàng</span></h2>
<?php if ($has_orders) :

	$tabs = array();

	$statuses = array(
		'processing' => '',
		'shipping' => '',
		'completed' => '',
	);

	foreach (wc_get_order_statuses() as $_key => $name) {
		$key = str_replace('wc-', '', $_key);

		$items = [];
		foreach ($customer_orders->orders as $customer_order) {
			$order = wc_get_order($customer_order);

			// Check if $order is valid
			if (! $order || ! is_a($order, 'WC_Order')) {
				continue; // Skip this iteration if $order is not a valid object
			}

			if ($order->get_status() == $key) {
				$items[] = $order;
			}
		}

		// Sort orders by creation date, newest first
		usort($items, function ($a, $b) {
			return $b->get_date_created()->getTimestamp() - $a->get_date_created()->getTimestamp();
		});

		if ($key == 'shipping') {
			$name = 'Đang vận chuyển';
		} elseif ($key == 'pending') {
			$name = 'Đang xử lý';
			$key = 'processing';
			$_key = 'wc-' . $key;
		}

		if (isset($statuses[$key])) {
			$statuses[$key] = $name;
		}

		if (isset($tabs[$key])) {
			$tabs[$key]['items'] = array_merge($tabs[$key]['items'], $items);
		} else {
			$tabs[$key] = array(
				'name' => $name,
				'items' => $items
			);
		}
	}

?>
	<ul class="nav navbar-tabs mb-3" id="trackOrder" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="all-orders-tab" data-bs-toggle="pill" data-bs-target="#all-orders"
				type="button" role="tab" aria-controls="all-orders" aria-selected="true">Tất cả đơn</button>
		</li>
		<?php foreach ($tabs as $key => $tab): ?>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="<?php echo $key; ?>-tab" data-bs-toggle="pill" data-bs-target="#<?php echo $key; ?>"
					type="button" role="tab" aria-selected="true"><?php echo $tab['name']; ?></button>
			</li>
		<?php endforeach; ?>
	</ul>
	<form class="track-order-form" method="get">
		<div class="input-group search-box mb-3">
			<input type="text" class="form-control search-input"
				placeholder="Tìm đơn hàng theo Mã đơn hàng" aria-label="Search"
				aria-describedby="button-addon2" name="order_id" value="<?php echo '#'.kiotVietOrderId(str_replace('#', '', site__get('order_id', ''))); ?>">
			<button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
		</div>
	</form>
	<div class="tab-content" id="trackOrderContent">
		<div class="tab-pane fade show active" id="all-orders" role="tabpanel" aria-labelledby="all-orders-tab">
			<?php
			foreach ($tabs as $key => $tab):
				foreach ($tab['items'] as $order):
					$data = $order->get_data();
					$totalDiscount = 0;

					if (!empty($data['fee_lines'])) {
						foreach ($data['fee_lines'] as $fee) {
							$totalDiscount += $fee->get_total();
						}
					}

					$totalDiscount = abs($totalDiscount);

					$list_status = array();
					if (isset($statuses[$key])) {
						foreach ($statuses as $s_key => $name) {
							$list_status[] = '<span' . ($s_key == $key ? ' class="text-warning"' : '') . '>' . $name . '</span>';
						}
					} else {
						$list_status[] = '<span class="text-warning">' . $tab['name'] . '</span>';
					}

					// Get the shipping total
					$shipping_total = $order->get_shipping_total();
			?>
					<div class="card card-body order border-top order-id-<?= kiotVietOrderId($data['id']) ?>" data-status="<?php echo $order->get_status(); ?>">
						<div class="row order-status pb-3">
							<div class="col-md-10">
								Đơn hàng: <strong>#<?= kiotVietOrderId($data['id']) ?></strong>
								| Thời gian đặt hàng: <strong><?php echo $data['date_created']->date('d/m/Y'); ?></strong>
								<br>
								Trạng thái: <?php echo implode(' <i class="bi bi-arrow-right-short"></i> ', $list_status); ?>
							</div>
							<div class="col-md-2 order-actions text-end">
								<a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
							</div>
						</div>
						<div class="order-content">
							<?php
							foreach ($order->get_items() as $item_id => $item):
								// order item data as an array
								//var_dump($item);

								$product = $item->get_product();
								//var_dump($product);

								if ($product == false) {
									continue;
								}

								$item_data = $item->get_data();
								$img = wp_get_attachment_image_url($product->get_image_id());
							?>
								<div class="order-item pb-3 ">
									<div class="order-item-image border-1">
										<?php if ($img): ?>
											<img src="<?php echo $img; ?>" class="img-thumbnail" />
										<?php endif; ?>
										<div class="order-item-quantity">X<?php echo $item_data['quantity']; ?></div>
									</div>
									<div class="order-item-detail">
										<p><?php echo $product->get_name(); ?></p>
									</div>
									<div class="order-item-price text-end">
										<b><?php echo site_wc_price($product->get_price()); ?> <sup class="fs-12">đ</sup></b><br>
									</div>
								</div>
							<?php break;
							endforeach; ?>
						</div>
						<div class="order-footer pt-3">
							<?php
							// Calculate total price before adding shipping fee
							$total_before_shipping = $data['total'] - $shipping_total + $totalDiscount;

							// Display total price before shipping fee
							?>
							<h6 class="text-end"><span class="label text-grey">Thành tiền:</span> <span class="price-number"><?php echo site_wc_price($total_before_shipping); ?><sup class="fs-12">đ</sup></span></h6>
							<?php if (!empty($data['fee_lines'])): ?>
								<?php foreach ($data['fee_lines'] as $v): ?>
									<h6 class="text-end">
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


							<h5 class="text-end"><span class="label text-grey">Tổng cộng:</span> <span class="price-number fw-bold"><?php echo site_wc_price($data['total']); ?><sup class="fs-12">đ</sup></span></h5>
						</div>
					</div>
			<?php
				endforeach;
			endforeach;
			?>
		</div>
		<?php
		foreach ($tabs as $key => $tab):
		?>
			<div class="tab-pane fade" id="<?php echo $key; ?>" role="tabpanel" aria-labelledby="<?php echo $key; ?>-tab">
				<?php
				foreach ($tab['items'] as $order):
					$data = $order->get_data();
					$totalDiscount = 0;

					if (!empty($data['fee_lines'])) {
						foreach ($data['fee_lines'] as $fee) {
							$totalDiscount += $fee->get_total();
						}
					}

					$totalDiscount = abs($totalDiscount);

					$list_status = array();
					if (isset($statuses[$key])) {
						foreach ($statuses as $s_key => $name) {
							$list_status[] = '<span' . ($s_key == $key ? ' class="text-warning"' : '') . '>' . $name . '</span>';
						}
					} else {
						$list_status[] = '<span class="text-warning">' . $tab['name'] . '</span>';
					}
					// Get the shipping total
					$shipping_total = $order->get_shipping_total();
				?>
					<div class="card card-body order border-top order-id-<?= kiotVietOrderId($data['id']) ?>" data-status="<?php echo $order->get_status(); ?>">
						<div class="row order-status  pb-3">
							<div class="col-md-10">
								Đơn hàng: <strong>#<?= kiotVietOrderId($data['id']) ?></strong>
								| Thời gian đặt hàng: <strong><?php echo $data['date_created']->date('d/m/Y'); ?></strong>
								<br>
								Trạng thái: <?php echo implode(' <i class="bi bi-arrow-right-short"></i> ', $list_status); ?>
							</div>
							<div class="col-md-2 order-actions text-end">
								<a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
							</div>
						</div>
						<div class="order-content">
							<?php
							// var_dump( $data );

							foreach ($order->get_items() as $item_id => $item):
								// order item data as an array
								$product = $item->get_product();
								if ($product == false) {
									continue;
								}

								$item_data = $item->get_data();
								$img = wp_get_attachment_image_url($product->get_image_id());
							?>
								<div class="order-item pb-3">
									<div class="order-item-image border-1">
										<?php if ($img): ?>
											<img src="<?php echo $img; ?>" class="img-thumbnail" />
										<?php endif; ?>
										<div class="order-item-quantity">X<?php echo $item_data['quantity']; ?></div>
									</div>
									<div class="order-item-detail">
										<p><?php echo $product->get_name(); ?></p>
									</div>
									<div class="order-item-price text-end">
										<b><?php echo site_wc_price($product->get_price()); ?> <sup class="fs-12">đ</sup></b><br>
									</div>
								</div>
							<?php break;
							endforeach; ?>
						</div>
						<div class="order-footer pt-3">
							<?php
							// Calculate total price before adding shipping fee
							$total_before_shipping = $data['total'] - $shipping_total + $totalDiscount;

							// Display total price before shipping fee
							?>
							<h6 class="text-end"><span class="label text-grey">Thành tiền:</span> <span class="price-number"><?php echo site_wc_price($total_before_shipping); ?><sup class="fs-12">đ</sup></span></h6>
							<?php if (!empty($data['fee_lines'])): ?>
								<?php foreach ($data['fee_lines'] as $v): ?>
									<h6 class="text-end">
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


							<h5 class="text-end"><span class="label text-grey">Tổng cộng:</span> <span class="price-number fw-bold"><?php echo site_wc_price($data['total']); ?><sup class="fs-12">đ</sup></span></h5>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
			<?php esc_html_e('Browse products', 'woocommerce'); ?>
		</a>
		<?php esc_html_e('No order has been made yet.', 'woocommerce'); ?>
	</div>
<?php endif; ?>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>