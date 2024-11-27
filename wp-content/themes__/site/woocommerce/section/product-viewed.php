<?php

$ids = site_get_cookie_array('products');

if( count($ids) == 0 ) return;

$products = wc_get_products(array(
  'limit' => 5,
  'include' => $ids,
));

if( count($products) ):
  $link = '';

  $category = site_get_hot_sale_home_page();
  if( isset($category['term_id']) ) {
    $link = get_term_link($category['term_id']);
  }
?>
<div class="section">
  <div class="section-bg">    
  <!-- <div class="container"> -->
    <h2 class="section-header">
      <span>Sản phẩm đã xem</span>
    </h2>    
    <div class="slide-multiple-slide-product" id="productRecent">
      <div class="product-list product-viewed-list flex-nowrap flex-md-wrap">
        <?php foreach( $products as $product ): site_setup_product_data( $product ); ?>
        <div class="col-6 col-md-4 col-lg-2 mb-3 col-product-item">
          <?php
            wc_get_template_part( 'archive/product', 'item' );
          ?>
        </div>
        <?php endforeach; site_reset_product_data(); ?>
      </div>
    </div>
    <?php if( $link != '' ): ?>
    <div class="section-actions text-center mt-3">
      <a href="<?php echo $link;?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>
    </div>
    <?php endif;?>
  <!-- </div> -->
  </div>
</div>
<?php
endif;
