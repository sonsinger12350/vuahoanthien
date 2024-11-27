<?php



// $brands = get_categories(array(

//   'number' => 1,

//   'taxonomy' => 'product-brand',

// ));



$brands = get_the_terms( get_the_ID(), 'product-brand' );



if( !$brands || count($brands) == 0 ) return;


$post_limit = 10;

if( wp_is_mobile() ) {
  $post_limit = 6;  
}

$list = get_posts( array(

  'post_type' => 'product',

  'posts_per_page' => $post_limit,

  'exclude' => array(get_the_ID()),

  'tax_query' => array(

    array(

      'taxonomy' => $brands[0]->taxonomy,

      'field' => 'slug',

      'terms' => array($brands[0]->slug)

    )

  )

));



$categories = get_the_terms( get_the_ID(), 'product_cat' );



?>

<div class="rows">

  <div class="section">

    <div class="container">
        <div class="section-bg">

          <h2 class="section-header border-0">

            <span>Sản Phẩm Khác Của <?php echo $brands[0]->name;?></span>

          </h2>

          <div class="slide-multiple slide-products product-list">

            <?php 

              foreach( $list as $p ): 

                site_setup_product_data( wc_get_product( $p->ID ) ); ?>

              
                <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
                  <?php wc_get_template_part( 'archive/product', 'item' ); ?>
                </div>  
                

              <?php endforeach; 

              site_reset_product_data();

            ?>

          </div>

          <?php if( isset($categories[0]) ):?>

          <div class="section-actions text-center mt-3">

            <a href="<?php echo get_term_link($categories[0]->term_id);?>?brands[]=<?php echo $brands[0]->term_id;?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem thêm sản phẩm</a>

          </div>

          <?php endif;?>

      </div>
    </div>

  </div>

</div>