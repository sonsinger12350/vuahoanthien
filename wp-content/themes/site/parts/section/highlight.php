<?php

// $page_on_front = (int) get_option( 'page_on_front', 0 );
// $highlight = get_field('highlight', $page_on_front);
// if( $highlight == '' ) {
//     $highlight = 'Mua hàng trực tuyến giá tốt - miễn phí giao hàng tại thành phố Hồ Chí Minh';
// }

$args = [
	'name'        => 'header-slide',
	'post_type'   => 'wp_block',
	'post_status' => 'publish',
	'numberposts' => 1,
];

$header_slide_post = get_posts($args);
if (empty($header_slide_post[0])) return false;
$post_content = $header_slide_post[0]->post_content;
$slider = [];
preg_match_all('/<img[^>]+src="([^">]+)"[^>]*alt="([^">]*)"[^>]*>/i', $post_content, $matches);

if (!empty($matches[1])) {
	$image_urls = $matches[1];

	foreach ($image_urls as $k => $image_url) {
		$slider[] = [
			"name" => $matches[2][$k],
			"link" => $image_url
		];
	}
}
if (empty($slider)) return null;

$lastElement = end($slider);
$lastElement['name'] = !empty($lastElement) ? explode(' / ', $lastElement['name']) : [];
$slider[array_key_last($slider)] = $lastElement;

$sliderChunk = [
	1 => array_chunk($slider, 1),
	2 => array_chunk($slider, 2),
	3 => array_chunk($slider, 4),
];
?>
<?php foreach ($sliderChunk as $key => $val):?>
	<div id="highlight-carousel-<?= $key ?>" class="highlight carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
		<div class="carousel-inner container">
			<?php foreach ($val as $k => $v): ?>
				<div class="carousel-item <?php echo $k == 0 ? 'active' : ''; ?>">
					<?php foreach ($v as $item): ?>
						<div class="item">
							<img src="<?= $item['link'] ?>" alt="<?= $item['name'] ?>" loading="lazy"/>
							<p class="item-name">
								<?php if (is_array($item['name'])): ?>
									<?php foreach ($item['name'] as $k => $item_value): ?>
										<?= $k != 0 ? ' / ' : '' ?>
										<a href="tel:<?= $item_value ?>"><?= $item_value ?></a>
									<?php endforeach; ?>
								<?php else:?>
									<?= $item['name'] ?>
								<?php endif; ?>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
        <?php if (count($val) > 1): ?>
		<span class="highlight-control carousel-control-prev-icon" aria-hidden="true" data-bs-target="#highlight-carousel-<?= $key ?>" data-bs-slide="prev"></span>
		<span class="highlight-control carousel-control-next-icon" aria-hidden="true" data-bs-target="#highlight-carousel-<?= $key ?>" data-bs-slide="next"></span>
        <?php endif ?>
    </div>
<?php endforeach ?>