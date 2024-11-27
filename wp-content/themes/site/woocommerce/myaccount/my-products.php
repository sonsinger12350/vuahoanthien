<?php
global $wp_query;

$products = array();
$title  = '';
$page   = '';

if( isset( $wp_query->query['watched'] ) ) {
  $title = 'Sản phẩm bạn đã xem';

  $products = wc_get_products(array(
    'include' => site_get_products_viewed(),
  ));

} else if( isset( $wp_query->query['later'] ) ) {
  $title = 'Sản phẩm mua sau';

  $products = wc_get_products(array(
    'include' => site_get_cookie_array('products'),
  ));

} else if( isset( $wp_query->query['wishlist'] ) ) {
  $title = 'Danh sách sản phẩm yêu thích';

  $page = 'wishlist';
  $my_postid = 11;
  $content_post = get_post($my_postid);
  $desc = $content_post->post_content;

  // $items = site_wcwl_get_products();
  // foreach( $items as $item ) {
  //   $product = wc_get_product( $item->get_product_id() );
    
  //   // $product->get_remove_url = $item->get_remove_url();

  //   $products[] = $product;
  // }

  // $item->get_remove_url();
  // wp_nonce_url( add_query_arg( 'remove_from_wishlist', $this->get_product_id(), $base_url ), 'remove_from_wishlist' );
}

if( $title == '' ) return;
?>
<div class="<?php echo $page;?>-page">
  <h2 class="mb-4 section-header border-0 mg-top-0"><span><?php echo $title;?></span></h2>
    <div id="list-products" class="my-<?php echo $page;?>-products">
    <!-- <div class="row flex-nowrap flex-md-wrap overflow-auto"> -->
      <div class="product-list product-wishlist flex-nowrap flex-md-wrap">
        <?php echo do_shortcode('[yith_wcwl_wishlist]'); ?>
      </div>
    </div>
  
  
  
</div>