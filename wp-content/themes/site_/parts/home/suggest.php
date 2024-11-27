<?php

$page_on_front = (int) get_option( 'page_on_front', 0 );
$terms = get_field('categories_suggest', $page_on_front);
$hot_sale = (array) get_field('hot_sale', $page_on_front);
$category = (array) $hot_sale['category'];

/*
if( $terms == false || count($terms) == 0 ) {
  $terms = get_categories(array(
    'number' => 6,
    'taxonomy' => 'product_cat',
  ));
}

$products = array();

if( count($terms)>0 ) {
  foreach( $terms as $term ) {
    $posts = get_posts( array(
      'post_type' => 'product',
      'posts_per_page' => 1,
      'tax_query' => array(
        array(
          'taxonomy' => 'product_cat',
          'field' => 'slug',
          'terms' => array($term->slug)
        )
      )
    ));

    if( count($posts)>0 ) {
      $products[] = $posts[0];
    }
  }
}
*/

$products = wc_get_products(array(
  'limit' => 20,
  'orderby' => 'rand',
));

?>
<div class="container">
  <div class="section section-suggest-on-home">
    <div class="section-bg">
      <h2 class="section-header">
        <span>Gợi ý hôm nay</span>
      </h2>
      <div class="product-list flex-nowrap flex-md-wrap"> <!-- overflow-auto -->
          <?php foreach( $products as $product ):
            if( isset($product->ID) ) {
              $product = wc_get_product( $product->ID ); 
            }
            site_setup_product_data( $product );
          ?>
          <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
            <?php
              wc_get_template_part( 'archive/product', 'item' );
            ?>
          </div>
          <?php endforeach; site_reset_product_data();?>
      </div>
      <?php if( isset($category['term_id']) ): ?>
      <div class="section-actions text-center mt-3">
        <a href="<?php echo get_term_link($category['term_id']);?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>