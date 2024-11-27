<?php



extract( shortcode_atts(array(

  // 'cat' => get_queried_object(),
  'cat' => '',

  'title' => $lines[0],

  'desc' => $lines[1],

  ''

), (array) $args) );



if( empty($cat) || empty($cat->taxonomy) ) {

  return;

}

//var_dump($cat);


$list = get_posts( array(

  'post_type' => 'product',

  'posts_per_page' => -1,

  'orderby' => 'meta_value_num',

  'meta_key' => 'sale_off',

  //'meta_value' => 70,

  //'meta_compare' => '>=',

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
  ),


  'tax_query' => array(

    array(

      'taxonomy' => $cat->taxonomy,

      'field' => 'slug',

      'terms' => array($cat->slug)

    )

  )

));



?>

<div class="container" id="<?php echo $args['idname']; ?>">

  <h2 class="section-header border-0">

    <span><?php echo $title;?></span>

    <b class="fs-2 fw-800"><?php echo $desc;?></b>

  </h2>

  <div class="product-list flex-nowrap flex-md-wrap">

    <?php

      $saleoff_value = $args['saleoff']; 
      if($saleoff_value > 0) {
        $sale_number = $saleoff_value;
      } else {
        $sale_number = 50;
      }

      foreach( $list as $p ): 
        $product = wc_get_product( $p->ID );

        site_setup_product_data( wc_get_product( $p->ID ) );

        $percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
          if ( $percentage >= $sale_number ) {  

    ?>

              <div class="col-6 col-md-4 col-xl-2 mb-4 col-product-item">

                <?php 

                  wc_get_template_part( 'archive/product', 'item' );

                ?>

              </div>

    <?php
          }
      endforeach; 

      site_reset_product_data();

    ?>

  </div>

</div>