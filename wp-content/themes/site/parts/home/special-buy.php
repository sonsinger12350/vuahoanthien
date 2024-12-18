<?php

$page_on_front = (int) get_option( 'page_on_front', 0 );
$hot_sale = (array) get_field('hot_sale', $page_on_front);

$category = (array) $hot_sale['category'];
$cate_id = $category["term_id"];

$post_limit = 10;
if( wp_is_mobile() ) {
  $post_limit = 6;  
}
// if ($args['product_limited']) {
//   $post_limit = $args['product_limited'];
// }

if( empty($category['name']) ) return;

function site_special_buy_filter_post_where( $where) {
  global $wpdb;

  $where .= ' AND ' . $wpdb->posts . '.post_title NOT LIKE \'%combo%\'';

  return $where;
}
add_filter( 'posts_where', $func = 'site_special_buy_filter_post_where' );
// $products = get_posts( array(
//   'post_type' => 'product',
//   'category' => $cate_id,
//   'posts_per_page' => $post_limit,
//   'orderby' => 'meta_value_num',
//   'meta_key' => 'sale_off',
//   'suppress_filters' => false, // important,
//   'meta_query' => array(
//       'relation' => 'AND',
//       array(
//         'key' => '_sale_price',
//         'value' => 0,
//         'compare' => '>',
//         'type' => 'numeric'
//       ),
//       array(
//         'key' => '_regular_price',
//         'value' => 0,
//         'compare' => '>',
//         'type' => 'numeric'
//       ),
//       array(
//         'key' => '_sale_price',
//         'value' => '_regular_price',
//         'compare' => '>=',
//         'type' => 'DECIMAL(10,2)'
//       )
//   )
// ));
$args = array(
    'post_type'      => 'product', // Product type
    'posts_per_page' => $post_limit,        // Number of products to retrieve (-1 to get all)
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_cat', // Product category taxonomy
            'field'    => 'term_id',     // Field to query (term_id is category ID)
            'terms'    => $cate_id,            // Category ID to retrieve products from
        ),
    ),
    'orderby' => 'meta_value_num',

    'meta_key' => 'sale_off',

    'suppress_filters' => false // important
);

// Get the products based on the arguments
$products = get_posts($args);


remove_filter( 'posts_where', $func );


// echo count($products);


?>
<div class="container bg-light">
  <div class="special-buy section">
    <div class="section-bg">
      <div class="special-buy-header">
        <img src="<?php site_the_assets() ?>images/logo/Logo-flashsale.png" width="100" alt="" loading="lazy">
        <div class="special-buy-header-text">
          <?php echo $hot_sale['description'];?>
        </div>
      </div>
      <div class="special-buy-body">
        <div class="special-buy-item-wrapper product-list" id="specialBuy">
          <?php foreach( $products as $p ): 
            $product = wc_get_product( $p->ID );
            site_setup_product_data( $product );
            //$percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
            //if ( $percentage >= 20 ) { ?>
              <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
                <?php
                  wc_get_template_part( 'archive/product', 'item' );
                ?>
              </div>
          <?php // }
          ?>
          
          <?php endforeach; site_reset_product_data(); ?>
        </div>
      </div>
      <?php if( empty($args['no_button']) ): ?>
      <div class="special-actions text-center mt-3">
        <a href="<?php echo get_term_link($category['term_id']);?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm Giá sốc</a>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>