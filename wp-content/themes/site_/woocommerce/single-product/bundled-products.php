<?php


// $products = array();

// $categories = get_the_terms( get_the_ID(), 'product_cat' );
// if( is_object($categories[0]) ) 
// {
//   $cat = $categories[0];

//   $cat_root = site_wc_get_terms_to_root( $cat, 1 );
  
//   $exclude = array();

//   if( $cat->parent != $cat_root->term_id ) {
//     $exclude[] = $cat->parent;
//   } else {
//     $exclude[] = $cat->term_id;
//   }

//   $terms = get_terms( array(
//     'taxonomy'=> $cat_root->taxonomy,
//     'parent'  => $cat_root->term_id,
//     'exclude' => $exclude
//   ) );
  
//   $limit = 6;

//   foreach( $terms as $term ) {
//     $list = wc_get_products(array(
//       'category' => array( $term->name ),
//       'limit' => 1
//     ));

//     if( count($list)>0 ) {
//       $products[] = $list[0];
//     }

//     if( count($products)>=$limit ) {
//       break;
//     }
//   }
// }

$products = array();

$current_product_id = get_the_ID();
$current_product_brands = wp_get_post_terms( $current_product_id, 'product-brand', array( 'fields' => 'names' ) );

$categories = get_the_terms( $current_product_id, 'product_cat' );
if ( is_object( $categories[0] ) ) {
    $cat = $categories[0];

    $cat_root = site_wc_get_terms_to_root( $cat, 1 );

    $exclude = array();

    if ( $cat->parent != $cat_root->term_id ) {
        $exclude[] = $cat->parent;
    } else {
        $exclude[] = $cat->term_id;
    }

    // Get terms of category that has the same parent category with the current category
    $terms = get_terms( array(
        'taxonomy' => $cat_root->taxonomy,
        'parent'   => $cat_root->term_id,
        'exclude'  => $exclude
    ) );

    $limit = 12; // Set limit of product to display

    //var_dump($cat_root);

    //$limit_per_category = ceil($limit / count($terms));
    //var_dump(count($terms));
    //echo $limit_per_cate;

    foreach ( $terms as $term ) {
        $args = array(
            'category' => array( $term->name ),
            // 'limit'    => $limit_per_category,
            'limit'    => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product-brand',
                    'field'    => 'slug',
                    'terms'    => $current_product_brands,  // Get product with the Same Brands
                ),
            ),
        );
        $product = wc_get_products( $args );
        if ( count( $product ) > 0 ) {
            // $product = $list[0];
            // $products[] = $product;
            foreach ($product as $item) {
                $cate[] = $item;
            }
        }
    }    
    
    $limit_per_cate = 1; // Set lemit of product each category to display

    //var_dump(count($terms));
    if($cate):
        //var_dump(count($cate));
        if(count($cate)<5) {
            $limit_per_cate = 6 - count($cate);
        } 
    endif;    

    //echo $limit_per_cate;

    foreach ( $terms as $term ) {
        $args = array(
            'category' => array( $term->name ),
            // 'limit'    => $limit_per_category,
            'limit'    => $limit_per_cate,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product-brand',
                    'field'    => 'slug',
                    'terms'    => $current_product_brands,  // Get product with the Same Brands
                ),
            ),
        );

        $list = wc_get_products( $args );

        //var_dump($list);

        if ( count( $list ) > 0 ) {
            // $product = $list[0];
            // $products[] = $product;
            foreach ($list as $product) {
                $products[] = $product;
            }
        }

        if ( count( $products ) >= $limit ) {
            break;
        }
    }
}

?>
<?php if ( count($products)>0 ): ?>
<div class="section list-product-buy-with">
  <div class="container">
    <div class="section-bg">
        <h2 class="section-header border-0">
          <span>Sản phẩm thường mua kèm</span>
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
    </div>
  </div>
</div>
<?php endif ?>
