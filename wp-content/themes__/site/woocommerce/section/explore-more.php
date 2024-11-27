<?php

extract(shortcode_atts(array(
  'term' => get_queried_object(),
), (array) $args ));

// $term = get_queried_object();

$term_link = get_term_link($term->term_id);
$fields = site_acf_get_fields( $term->taxonomy . ':' . $term->slug );

$terms = get_terms(array(
  'parent' => $term->term_id,
  'number' => 7,
  'taxonomy' => $term->taxonomy,
  'hide_empty' => false,
));

$brands = get_terms(array(
  // 'parent' => $term->term_id,
  'number' => 7,
  'taxonomy' => 'product-brand',
  'hide_empty' => false,
));

?>
<div class="container pb-5">
  <div class="explore-more">
    <div class="collapse" id="exploreMore">
      <div class="card card-body">
        <h3 class="fw-light">Các sản phẩm khác của VieHome Depot</h3>
        <div class="explore-more-content">
          <ul class="nav flex-column">
            <li class="nav-item my-2">
              <b><a href="">Thương hiệu</a></b>
              <ul class="nav flex-column">
                <?php foreach( $brands as $term ):?>
                <li>
                  <a href="<?php echo $term_link;?>?brand=<?php echo $term->term_id;?>">
                    <?php echo $term->name;?> (<?php echo $term->count;?>)
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </li>
            <?php foreach( $fields as $i => $field ): ?>
            <li class="nav-item my-2">
              <b><a href=""><?php echo $field['title'];?></a></b>
              <ul class="nav flex-column">
                <?php foreach( $field['choices'] as $value => $name ):?>
                <li>
                  <a href="<?php echo $term_link;?>?<?php echo sanitize_title($name);?>=<?php echo $value;?>">
                    <?php echo $name;?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </li>
            <?php endforeach; ?>
            <li class="nav-item my-2">
              <b><a href="">Khoảng giá</a></b>
              <ul class="nav flex-column">
                <?php foreach( site_wc_get_prices_static() as $i => $value ): ?>
                <li>
                  <a href="<?php echo $term_link;?>?price=<?php echo $i;?>">
                    <?php echo $value;?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="explore-more-action">
      <a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#exploreMore" role="button" aria-expanded="false" aria-controls="exploreMore">
        Xem thêm
      </a>
    </div>
  </div>
</div>