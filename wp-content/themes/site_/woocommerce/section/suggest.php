<?php



$link = '';



if( isset($args['hot']) && $args['hot'] == 'deal' ) {

  

  $cat = get_queried_object();

  if( empty($cat->taxonomy) ){

    return;

  }



  $title = 'Deal Hot hôm nay';



  $products = get_posts( array(

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

  

  $cat_hot_sale = site_get_hot_sale_home_page();

  if( isset($cat_hot_sale['term_id']) ) {

    $link = get_term_link($cat_hot_sale['term_id']);

  }



} else if( isset($args['top']) && $args['top'] == 'trend' ) {

  

  $cat = get_queried_object();

  if( empty($cat->taxonomy) ){

    return;

  }



  $title = 'Sản Phẩm Dẫn Đầu Xu Hướng';



  $products = wc_get_products(array(

    'limit' => 6,

    'category' => $cat->name

  ));



  $cat_root = site_wc_get_terms_to_root( $cat, 1 );

  if( isset($cat_root->term_id) ) {

    $link = get_term_link($cat_root->term_id) . '?type=3';

  }

  

} else {

  $title = 'Gợi ý hôm nay';



  $products = wc_get_products(array(

    'limit' => 6,

    'orderby' => 'rand'

  ));

}



?>
<?php if ($products): ?>

<div class="section">
  <div class="section-bg">

  <!-- <div class="container"> -->

    <h2 class="section-header">

      <span><?php echo $title;?></span>

    </h2>

    <div class="slide-multiple slide-product">

      <?php 

        foreach( $products as $product ): 



          if( isset($product->ID) ) {

            $product = wc_get_product( $product->ID ); 

          }



          site_setup_product_data( $product ); 

        

          wc_get_template_part( 'archive/product', 'item' );

          

        endforeach; 

        site_reset_product_data();

      ?>

    </div>

    <?php if( $link != '' ): ?>

    <div class="section-actions text-center mt-3">

      <a href="<?php echo $link;?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>

    </div>

    <?php endif;?>

  <!-- </div> -->
  </div>

</div>
  
<?php endif ?>
