<?php


$page_on_front = (int) get_option( 'page_on_front', 0 );
$products = get_field('search_top_products', $page_on_front);

?>
<div class="container">
  <div class="section">
    <div class="section-bg">
      <h2 class="section-header">
        <span>Tìm kiếm hàng đầu</span>
      </h2>
      <?php if( $products ):?>
      <div class="product-list flex-nowrap flex-md-wrap">
        <?php 
          foreach( $products as $product ): 

            if( isset($product->ID) ) {
              $product = wc_get_product( $product->ID ); 
            }

            site_setup_product_data( $product ); ?>

            <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
              <?php
                wc_get_template_part( 'archive/product', 'item' );
              ?>
            </div>
          <?php   
          endforeach;
          site_reset_product_data();
        ?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>