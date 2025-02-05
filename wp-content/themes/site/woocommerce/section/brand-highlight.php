<?php
	$cat = get_queried_object();
	if (empty($cat->name)) return;
	
	$list_brands = getBrandsByCat($cat);

	// usort($list_brands, function ($a, $b) {
	// 	$order_a = get_term_meta($a->term_id, 'order', true);
	// 	$order_b = get_term_meta($b->term_id, 'order', true);
	// 	if ($order_a == $order_b) return 0;
	// 	return ($order_a < $order_b) ? -1 : 1;
	// });

	if ($list_brands && count($list_brands) > 0):
?>
	<div class="section">
		<div class="section-bg">
			<div class="brand-highlight row">
				<?php
					$i = 0;
					foreach ($list_brands as $item):
						if ($i++ > 20) break;
						$image = get_field('image', $item->taxonomy . '_' . $item->term_id);
						$link = get_term_link($cat->term_id, $cat->taxonomy) . '?thuong-hieu[]=' . $item->term_id;
				?>
					<div class="col-4 col-lg-2 py-2 brand-item">
						<a href="<?php echo esc_url($link); //$url;
									?>#top" class="card justify-content-center">
							<div class="p-0 text-center">
								<img src="<?php echo wp_get_attachment_image_url($image, 'medium'); ?>" alt="<?php echo $item->name; ?>" class="img-fluid m-h-30-px" loading="" />
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>