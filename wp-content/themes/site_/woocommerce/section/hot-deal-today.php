<?php

$cat = get_queried_object();
if( empty($cat->taxonomy) ){
  return;
}

$page_on_front = (int) get_option( 'page_on_front', 0 );
$hot_sale = (array) get_field('hot_sale', $page_on_front);
$category = (array) $hot_sale['category'];


$list = get_posts( array(
  'post_type' => 'product',
  'posts_per_page' => 6,
  // 'order'   => 'ASC',
  'orderby' => 'meta_value_num',
  'meta_key' => 'sale_off',
  'tax_query' => array(
    array(
      'taxonomy' => $cat->taxonomy,
      'field' => 'slug',
      'terms' => array($cat->slug)
    )
  )
));

?>
<section class="my-5">
  <div class="row align-items-center">
    <div class="col-12 col-lg-2 text-center">
      <h4 class="fw-800 text-uppercase">
        Deal Hot hôm nay
      </h4>
      <a class="btn btn-lg btn-primary my-2" href="<?php echo get_term_link($category['term_id']);?>">Mua Ngay</a>
    </div>
    <div class="col-12 col-lg-10 slide-featured-product">
      <?php 
        foreach( $list as $p ): 
          site_setup_product_data( wc_get_product( $p->ID ) ); 
        
          wc_get_template_part( 'archive/product', 'item' );
          
        endforeach; 
        site_reset_product_data();
      ?>
    </div>
  </div>
</section>