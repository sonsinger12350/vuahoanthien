<?php 
global $wp_query;

//var_dump($wp_query);

// $paged = isset($_GET['pi']) ? intval($_GET['pi']) : 1;
$paged = 1;

$count_sp = count($wp_query->get_posts());

//var_dump($count_sp); die;
?>
<?php /* ?>
<?php
if (is_product_category('gia-soc-hom-nay')) :
    //echo "aaa";
    // /if (woocommerce_product_loop()) :
        $args = array(
              'post_type' => 'product',
              'posts_per_page' => 60,
              'orderby' => 'meta_value_num',
              'meta_key' => 'sale_off',
              'suppress_filters' => false, // important,
              'meta_query' => array(
                  'relation' => 'AND',
                  array(
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                  ),
                  array(
                    'key' => '_regular_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                  ),
                  array(
                    'key' => '_sale_price',
                    'value' => '_regular_price',
                    'compare' => '>=',
                    'type' => 'DECIMAL(10,2)'
                  )
              )
        );

        $products = new WP_Query($args);
        //var_dump($products);

        if ($products->have_posts()) :
            echo '<div id="list-products">';
              echo '<div class="product-list flex-nowrap flex-md-wrap">';

              while ($products->have_posts()) :
                  $products->the_post();
                  $product = wc_get_product();

                  site_setup_product_data($product);
                  $percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
                  if ( $percentage >= 50 ) : ?>
                    <div class="col-6 col-md-4 col-lg w-lg-20 mb-3 col-product-item">
                        <?php wc_get_template_part('archive/product', 'item'); ?>
                    </div>
              <?php
                  endif;
              endwhile;

              site_reset_product_data();

              echo '</div>';
            echo '</div>';
        endif;

        wp_reset_postdata();
else:
?>
<?php */ ?>
<?php
 
  //var_dump(woocommerce_product_loop());  
  if ( $count_sp >0) :?>
     <?php if ( woocommerce_product_loop() ): ?> 
        <div id="list-products">
          <!-- <div class="row"> -->
          <div class="product-list flex-nowrap flex-md-wrap">
          <?php
            //var_dump(wc_get_loop_prop( 'total' ));
            if ( wc_get_loop_prop( 'total' ) ) :
              while ( have_posts() ) : the_post();
                $product = wc_get_product();

                site_setup_product_data( $product );
            ?>
              <div class="col-6 col-md-4 col-lg w-lg-20 mb-3 col-product-item">
                <?php wc_get_template_part( 'archive/product', 'item' ); ?>
              </div>
            <?php 
              endwhile;

              site_reset_product_data();
            endif;

            if( $paged>1 ) {
              for( $i = 2; $i <= $paged; $i++ ) {
                // The Query.
                // $the_query = new WP_Query([
                //   'posts_per_page' => $wp_query->posts_per_page,
                // ]);

                $the_query = $wp_query;
                
                $the_query->set('page', $i);
                
                $the_query->get_posts();

                while ( $the_query->have_posts() ) : the_post();
                  $product = wc_get_product();
                  
                  site_setup_product_data( $product );
                ?>
                  <div class="pi-product-item col-6 col-md-4 col-lg w-lg-20 mb-3 col-product-item">
                    <?php wc_get_template_part( 'archive/product', 'item' ); ?>
                  </div>
                <?php 
                endwhile;

                site_reset_product_data();

              }
            }
          ?>
          </div>
        </div>
        <?php
        $total = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
        if( $total>1 && $paged<$total ):
          // $uri = explode('?', $_SERVER['REQUEST_URI']);
          $url = esc_url(remove_query_arg(['paged', 'pi']));
        ?>
          <div class="row-loadmore text-center mt-4">
            <button class="btn btn-primary rounded btn-loadmore" type="button" 
              data-href="<?php echo $url;?>"
              data-paged="<?php echo $paged;?>"
              data-total="<?php echo $total;?>"
            >Xem thêm sản phẩm</button>
          </div>
        <?php endif;?>
    <?php endif;?>
    <?php

  // wc_get_template_part( 'section/pagination' );
  else: ?>
    <div class="noti-text noti-product-notfound">  
      <p><img src="<?php site_the_assets();?>images/icons/icon-no-product.jpg" atl="Product not Found"></p>
      <p>Xin lỗi chúng tôi không tìm thấy kết quả thỏa điều kiện.</p>
    </div>
  <?php endif; 
// endif;
?>