<?php

$page_on_front = (int) get_option( 'page_on_front', 0 );
$products = get_field('products_suggest', $page_on_front);

if( $products == null || count($products) == 0 ) {
  $tax_query = array();

  if( get_current_user_id()>0 ) {
    $tax = 'product_cat';
   
    $wishlist = YITH_WCWL::get_instance();
    $items = $wishlist->get_products( array('wishlist_id' => 'all' ) );

    if( $items && count($items)>0 ) {
      $term_ids = array();
      
      foreach( $items as $item ) {
        $terms = get_the_terms( $item->get_product_id(), $tax );
        if( $terms && count($terms)>0 ) {
          foreach( $terms as $term ) {
            $term_ids[ $term->term_id ] = $term->term_id;
          }
        }
      }

      if( count($term_ids)>0 ) {
        $tax_query = array(
          'taxonomy' => $tax,
          'field'    => 'term_id',
          'terms'    => $term_ids
        );
      }
    }
  }

  $products = get_posts( array(
    'post_type' => 'product',
    'posts_per_page' => 10,
    'orderby' => 'rand',
    'tax_query' => $tax_query
  ));
}

?>
<div class="container">
  <div class="section section-suggest-on-home">
    <div class="section-bg">
      <h2 class="section-header">
        <span>Dành riêng cho bạn</span>
      </h2>
      <div class="product-list flex-nowrap flex-md-wrap">  <!-- overflow-auto -->
        <?php 
          foreach( $products as $product ):      
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
        <?php endforeach; site_reset_product_data(); ?>
      </div>
      <?php if( isset($category['term_id']) ): ?>
      <div class="section-actions text-center mt-3">
        <a href="<?php echo get_term_link($category['term_id']);?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm các sản phẩm khác</a>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>