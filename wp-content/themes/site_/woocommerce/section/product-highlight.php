<?php

$cat = get_queried_object();

$tax_query = array();

if( isset($cat->taxonomy) ) {
  $tax_query = array(
    array(
      'taxonomy' => $cat->taxonomy,
      'field' => 'slug',
      'terms' => array($cat->slug)
    )
  );
}

// $products = wc_get_products(array(
//   'category' => array( $cat->name ),
//   'limit' => 5,
// ));

$list = get_posts(array(
  'post_type' => 'product',
  'posts_per_page' => 6,
  'meta_key' => 'type',
  'meta_value' => 3,
  'tax_query' => $tax_query
));

?>
<div class="product-highlight my-4">
  <div class="product-highlight-title">
    <h2>Sản phẩm bán chạy</h2>
  </div>
  <div class="product-highlight-content container">
    <?php if( count($list)>0 ) :?>
    <div class="row mx-0 bg-light pt-3">
      <?php foreach( $list as $p ): site_setup_product_data( wc_get_product( $p->ID ) ); ?>
      <div class="col-6 col-md-4 col-lg-2 mb-3">
        <?php
          wc_get_template_part( 'archive/product', 'item' );
        ?>
      </div>
      <?php endforeach; site_reset_product_data(); ?>
    </div>
    <?php endif;?>
  </div>
</div>