<?php

$categories = get_the_terms( get_the_ID(), 'product_cat' );
if( ! is_object($categories[0]) ) {
  return;
}

$cat = $categories[0];

$products = array();

$link = '';

$post_limit = 10;

if( wp_is_mobile() ) {
  $post_limit = 6;  
}

if( isset($args['living']) && $args['living'] == 'space' )
{
  $title = 'Hoàn thiện không gian sống của bạn';

  $cat_root = site_wc_get_terms_to_root( $cat, 1 );


  $params = array(
    // 's' => 'combo',
    'post_type' => 'product',
    'posts_per_page' => $post_limit,
    'tax_query' => array(
      'relation' => 'AND',
      array(
        'taxonomy' => $cat_root->taxonomy,
        'field'    => 'term_id',
        'terms'    => array( $cat_root->term_id ),
      ),
      array(
        'taxonomy' => $cat->taxonomy,
        'field'    => 'term_id',
        'terms'    => array( $cat->term_id ),
        'operator' => 'NOT IN',
      ),
    )
  );

  $products = get_posts( $params );
  if( count($products) == 0 ) {
    // unset($params['s']);
    // $products = get_posts( $params );
  }

  $link = get_term_link($cat_root->term_id);

} else {

  $title = 'Sản phẩm tương tự';

  // $product_ids = wc_get_related_products( get_the_ID(), $limit = 10 );

  $cat_names = array();
  foreach( $categories as $item ) {
    $cat_names[] = $item->slug;
  }
  
  $products = wc_get_products(array(
    'category' => $cat_names,
    'limit' => $post_limit,
    'exclude' => array(get_the_ID())
  ));

  $link = get_term_link( $cat->term_id );
}

?>
<?php if ( count($products)>0 ): ?>
  <div class="section">
    <div class="container">
      <div class="section-bg">
        <h2 class="section-header border-0">
          <span><?php echo $title;?></span>
        </h2>
        <div class="slide-multiple slide-products product-list">
          <?php 
            foreach( $products as $product ): 

              if( isset($product->ID) ) {
                $product = wc_get_product( $product->ID ); 
              }
              site_setup_product_data( $product ); ?>
              <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
                <?php wc_get_template_part( 'archive/product', 'item' ); ?>
              </div>
            <?php endforeach;
            site_reset_product_data();
          ?>
        </div>
        <?php if( $link!='' ):?>
        <div class="section-actions text-center mt-3">
          <a href="<?php echo $link;?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
<?php endif;?>  