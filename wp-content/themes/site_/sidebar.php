<?php



global $sidebar_choose;
global $sidebar_true;

$sidebar_true = true;
if( empty($sidebar_choose) ) $sidebar_choose = [];



$fields = [];

$terms  = [];

$brands = [];



$cat_link = '';

$level    = 0;



$types = array(

  1 => 'Yêu thích',

  2 => 'Hot Deal',

  3 => 'Bán chạy',

);



$sorts = array(

  'discount' => 'Giảm giá nhiều',

  'buy' => 'Bán chạy',

);



$value_prices = site__get( 'prices', array() );

$value_brands = site__get( 'brands', array() );

$value_cats   = site__get( 'cats', array() );

$value_types  = site__get( 'types', array() );

$value_sort   = site__get( 'sort', '' );



$uri = explode('?', $_SERVER['REQUEST_URI']);

$cat = get_queried_object();

// Add to get List Cate
$kw = site__get('s', '');

//$wp_query->query_vars['post_type'] = 'product';
//$wp_query->query_vars['posts_per_page'] = 100;
//var_dump($wp_query);
// $args = array(
//     's' => $kw,
//     'post_type' => 'product',
//     'posts_per_page' => 300,
//     'fields' => 'ids',
    
// );
// $products = new WP_Query( $args );

//$products = wc_get_product();
if($kw) {
  global $wp_query;
  $args = array(
      's' => $kw,
      'post_type' => 'product',
      'posts_per_page' => 300,
  );
  $wp_query = new WP_Query( $args );  
  $product_ids = array();
  // Loop through the products
  if ( have_posts() ) {
      while ( have_posts() ) {
          the_post();
          $product = wc_get_product();
          //var_dump($product);
          $product_id = get_the_ID(); // Get the product ID
          $product_ids[] = $product_id; // Add the product ID to the array
      }
  }  
  //var_dump($products);

  //$product_ids = $products->posts;

  //var_dump($product_ids);

  $all_categories = array(); 
  foreach ( $product_ids as $product_id ) {
      $list_cates = wp_get_post_terms( $product_id, 'product_cat' );
      foreach ( $list_cates as $cate_item ) {
          $all_categories[ $cate_item->term_id ] = $cate_item->name;
      }
  }
  wp_reset_query();
  wp_reset_postdata();
  // end List Cate
}





if( isset($cat->taxonomy) ) {

  $level = site_wc_get_term_level( $cat );

  $cat_link = get_term_link($cat->term_id);



  // $fields = site_acf_get_fields( $cat->taxonomy . ':' . $cat->slug, $group = 1 );

  if( $level != 3 ) {

    $terms = get_terms(array(

      'parent' => $cat->term_id,

      'number' => false,

      'taxonomy' => $cat->taxonomy,

      'hide_empty' => false,

    ));

  } elseif ( $level == 3 ) {
    $cat_id = $cat->term_id;       // Assuming you have the category ID
    $taxonomy = $cat->taxonomy;    // Assuming you have the taxonomy name
    $parent_id = wp_get_term_taxonomy_parent_id($cat_id, $taxonomy);
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'parent'   => $parent_id,
        'number' => false,
        'hide_empty' => false,
    ]);
  } 


  //var_dump($terms);

  $brands = site_the_category_brands( $cat->name );

} else {

  if(isset($_GET['s'])):
    $brands = site_the_category_brands( $all_categories );
    
  else:
     $brands = get_terms(array(

      'number' => false,

      'taxonomy' => 'product-brand',

      // 'hide_empty' => false,

    )); 
  endif;  

  



  if( count($value_brands) ) {

    $items = array();

    foreach( $value_brands as $value ) {

      $items[] = (object) array(

        'term_id' => $value,

        'taxonomy' => 'product-brand'

      );

    }

    //var_dump($items);

    $terms = site_get_terms_level_2_by_terms($items, 'product_cat', $limit = 200 );
    //$terms = site_get_terms_by_terms($items, 'product_cat', $limit = 200 );

    //echo "brand-check";

  }

}
// var_dump($terms); // Show list Category Con (List Phân Loại)
$sale_off_page = get_field('sale_off_page', $cat->taxonomy . '_' . $cat->term_id );
//var_dump($sale_off_page);

//if (is_product_category('gia-soc-hom-nay')) {
if ($sale_off_page == true) {
 //var_dump($cat);
//var_dump($cat->term_id);
    $list_product_byIDs_saleoff =  get_list_product_byIDs_giasohomnay($cat->term_id);
    $list_cate_saleoff = get_categories_except_sale_off($list_product_byIDs_saleoff);
    $list_cate_level01_saleoff = get_level_1_parent_categories($list_cate_saleoff); 
    //var_dump($list_cate_level01_saleoff);

    $terms = $list_cate_level01_saleoff;

} 

?>

<div class="d-none d-lg-block col-lg-2 col-sidebar-product">
   <div class="section-bg"> 
      <?php /* ?>
      <?php if(isset($_GET['s'])): ?>
      <div class="category-list nav-aside bg-light"> <!-- p-2 -->
        <b><a href="">Danh mục sản phẩm</a></b>
        <?php 
          
             ?>
            <div class="cate-list">
              <?php foreach ( $all_categories as $category_id => $category_name ) {
                  echo '<a href="' . get_term_link( $category_id, 'product_cat' ) . '">' . $category_name . '</a><br />';
              } ?>
            </div>
            <?php
            
           
        ?>
      </div>
      <?php endif; ?>
      <?php */ ?>

      <div class="nav-aside bg-light"> <!-- p-2-->

        <form class="sidebar-form" method="get" action="">

          <ul class="nav flex-column">

            <li class="nav-item my-2">

              <b><a href="">Thương hiệu</a></b>

              <?php 
                $txtMainClass = "";
                $txtUlClass = "";
                  if( $brands ): 
                      $number_brands = count($brands);
                      if($number_brands > 7) {
                        $txtUlClass = "collapse";
                      } else {
                        $txtMainClass = "tooShort";
                      }
              ?>
              <div class="explore-more <?php echo $txtMainClass; ?>">  
                <ul class="nav flex-column <?php echo $txtUlClass; ?>"  id="detailMoreBrands">

                  <?php 

                  foreach( $brands as $term ):                

                    $c = '';

                    if( in_array($term->term_id, $value_brands) ) {

                      $sidebar_choose['brands[]='.$term->term_id] = $term->name;

                      $c = 'checked';

                    }


                  ?>

                  <li>

                    <label class="custom-checkbox">

                      <input type="checkbox" name="brands[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                      <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name;?></span></span>

                    </label>

                  </li>

                  <?php endforeach; ?>

                </ul>
                <?php if ($number_brands > 7): ?>
                  <div class="explore-more-action">
                    <a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#detailMoreBrands" role="button"
                      aria-expanded="false" aria-controls="detailMoreBrands">
                      <span class="text-1">Xem thêm</span>
                      <span class="text-2">Rút gọn</span>
                    </a>
                  </div>
                <?php endif ?>
                
              </div>  
              

              <?php endif; ?>

            </li>

            <?php //if( $level <= 3 && (count($terms)>0 || count($all_categories) > 0 ) ): 
                  if( $level <= 3 && ( (count($terms ?? []) > 0) || (count($all_categories ?? []) > 0) ) ): 
                      $list_cate_items = [];
            ?>

              <li class="nav-item my-2">
                <?php //echo $level; 
                    $txtMain_PL_Class = "";
                    $txtUl_PL_Class = "";
                    $number_pl = count($terms);
                    if($number_pl > 7) {
                      $txtUl_PL_Class = "collapse";
                    } else {
                      $txtMain_PL_Class = "tooShort";
                    }
                ?>
                <b><a href="">Phân loại</a></b>
                <div class="explore-more <?php echo $txtMain_PL_Class; ?>">  
                  <ul class="nav flex-column <?php echo $txtUl_PL_Class; ?>" id="detailMoreCate">
                    <?php /* ?>
                    <?php if( $level == 3 ):?>

                      <?php 

                      foreach( $sorts as $value => $name ):

                        $c = '';

                        if( $value == $value_sort ) {

                          $sidebar_choose['sort='.$value] = $name;

                          $c = 'checked';

                        }

                      ?>

                      <li>

                        <label class="custom-checkbox">

                          <input type="radio" name="sort" value="<?php echo $value;?>" <?php echo $c;?> />

                          <span><?php echo $name;?></span>

                        </label>

                      </li>

                      <?php endforeach; ?>

                    <?php elseif( count($terms)>0 ):?>

                    <?php */?>  
                     
                    <?php if( count($terms)>0 && $sale_off_page == false):?>

                        <?php

                        //echo "AAA";

                        foreach( $terms as $term ):

                          // $class = 'level-' . $term->level;
                          $class = 'level-' . $level;

                          // Bỏ qua cat này


                          $c = '';

                          if( in_array($term->term_id, $value_cats) ) {

                            $sidebar_choose['cats[]='.$term->term_id] = $term->name;

                            $c = 'checked';

                          }

                          $category_link = get_category_link($term->term_id);


                          // Edit by binbin

                          if( $sale_off_page == true ) {
                            continue;

                          }



                        ?>

                        <li>

                          <?php if (($level == 1 || $level == 3 || ($value_brands && $level == 0)) ): 
                                   $current_product_category = get_queried_object();
                                   $current_category_link = get_term_link($current_product_category);
                                   if($value_brands) {
                                       $category_link = add_query_arg('brands', $value_brands, $category_link);
                                   }
                          ?>
                              <a class="<?php echo $class; ?> cate_link checkbox-link <?php echo ($current_category_link == $category_link)?"active":""; ?>" href="<?php echo $category_link; ?>"><?php echo $term->name; ?></a>
                          <?php else: ?>
                            <label class="<?php echo $class; ?> custom-checkbox">

                              <input type="checkbox" name="cats[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                              <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name;?></span></span>

                            </label>    
                          <?php endif ?>
                          

                        </li>

                        <?php endforeach; ?>
                    <!-- Phân loại Category cho Giá sốc hôm nay -->
                    <?php elseif (count($terms)>0 && $sale_off_page == true): 
                            //echo "BBB";
                            //var_dump($list_cate_level01_saleoff);


                      ?>
                        <?php foreach ( $list_cate_level01_saleoff as $cate_level01_saleoff_item ): 
                                 $extra_class = "checkbox-link";
                                 $c = '';

                                if( in_array($cate_level01_saleoff_item->term_id, $value_cats) ) {

                                  $sidebar_choose['cats[]='.$cate_level01_saleoff_item->term_id] = $cate_level01_saleoff_item->name;

                                  $c = 'checked';

                                }

                                 //var_dump($cate_level01_saleoff_item); 
                          ?>
                              <li>
                                <label class="<?php echo $class; ?> custom-checkbox">

                                  <input type="checkbox" name="cats[]" value="<?php echo $cate_level01_saleoff_item->term_id; ?>" <?php echo $c;?> />

                                  <!-- <span><?php //echo $cate_level01_saleoff_item->name;?></span> -->

                                  <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $cate_level01_saleoff_item->name; ?></span></span>
   
                                </label>
                              </li>  
                              <?php //echo '<li><a class="cate_link '.$extra_class.'" href="' . get_term_link( $cate_level01_saleoff_item->term_id, 'product_cat' ) . '">' . $cate_level01_saleoff_item->name . '</a></li>'; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php //echo "CCC"; ?>
                        <?php foreach ( $all_categories as $category_id => $category_name ): ?>
                              <?php if(isset($_GET['s'])): 
                                  $extra_class = "checkbox-link";
                              endif; ?>  
                              <?php echo '<li><a class="cate_link '.$extra_class.'" href="' . get_term_link( $category_id, 'product_cat' ) . '">' . $category_name . '</a></li>'; ?>
                        <?php endforeach; ?>

                    <?php endif; ?>



                  </ul>
                  <?php if (count($terms) > 7): ?>
                  <div class="explore-more-action">
                    <a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#detailMoreCate" role="button"
                      aria-expanded="false" aria-controls="detailMoreCate">
                      <span class="text-1">Xem thêm</span>
                      <span class="text-2">Rút gọn</span>
                    </a>
                  </div>
                <?php endif; ?>
                </div>
              </li>

            <?php endif; ?>

            <li class="nav-item my-2">

              <b><a href="">Khoảng giá</a></b>

              <ul class="nav flex-column">

                <?php 

                foreach( site_wc_get_prices_static() as $value => $name ): 

                  $c = '';

                  if( in_array($value, $value_prices) ) {

                    $sidebar_choose['prices[]='.$value] = $name;

                    $c = 'checked';

                  }

                ?>

                <li>

                  <label class="custom-checkbox">

                    <input type="checkbox" name="prices[]" value="<?php echo $value;?>" <?php echo $c;?> />

                    <!-- <span><?php //echo $name;?></span> -->
                    <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $name;?></span></span>

                  </label>


                </li>

                <?php endforeach; ?>

              </ul>

              <?php /* ?>
              <form id="price-filter-form">
                  <div id="price-range-slider"></div>
                  <input type="text" id="min-price" name="min_price" value="">
                  <input type="text" id="max-price" name="max_price" value="">
                  <input type="submit" value="Filter">
              </form>

              <script>
                  jQuery(function($) {
                      // Initialize the price slider with default values
                      var minPrice = 0; // Default minimum price
                      var maxPrice = 1000; // Default maximum price

                      // Function to update the price slider with fetched data
                      function updatePriceSlider(min, max) {
                          $('#price-range-slider').slider('values', [min, max]);
                          $('#min-price').val(min);
                          $('#max-price').val(max);
                      }

                      // Fetch the minimum and maximum prices using AJAX
                      $.ajax({
                          url: '/wp-admin/admin-ajax.php',
                          type: 'POST',
                          data: {
                              action: 'get_min_max_prices' // Create an AJAX action to handle this request
                          },
                          success: function(response) {
                              var data = JSON.parse(response);
                              if (data && data.min_price !== undefined && data.max_price !== undefined) {
                                  minPrice = parseInt(data.min_price);
                                  maxPrice = parseInt(data.max_price);
                                  updatePriceSlider(minPrice, maxPrice);
                              }
                          }
                      });

                      $('#price-range-slider').slider({
                          range: true,
                          min: 0,
                          max: maxPrice, // Set the maximum price based on fetched data
                          values: [minPrice, maxPrice],
                          slide: function(event, ui) {
                              $('#min-price').val(ui.values[0]);
                              $('#max-price').val(ui.values[1]);
                          }
                      });

                      $('#price-filter-form').submit(function() {
                          var minPrice = $('#min-price').val();
                          var maxPrice = $('#max-price').val();

                          // Store the values in session for later use in the query
                          $.ajax({
                              url: '/wp-admin/admin-ajax.php',
                              type: 'POST',
                              data: {
                                  action: 'store_min_max_prices', // Create an AJAX action to handle this request
                                  min_price: minPrice,
                                  max_price: maxPrice
                              },
                              success: function() {
                                  location.reload(); // Trigger the filter
                              }
                          });

                          return false;
                      });
                  });
              </script>
              <?php */ ?>
              <!-- Product Filter Price -->

              <?php if ( is_active_sidebar( 'sidebar-widget-area' ) ) : ?>
                <div id="sidebar-widget-area" class="widget-area sidebar-widget-area">
                    <?php dynamic_sidebar( 'sidebar-widget-area' ); ?>
                </div>
              <?php endif; ?>

              <?php // echo do_shortcode('[wpf-filters id=1]'); ?>

             
            </li>



          </ul>

        </form>

      </div>



      <?php //echo do_shortcode('[searchandfilter id="slice_price"]');  ?>

      <div class="pt-2">

        <a href="<?php echo $uri[0];?>" class="w-100 btn btn-outline-primary px-1 border-2 rounded fw-bold fs-11">Xoá chọn</a>

      </div>


  </div>  
  <!-- end div section-bg  -->

</div>



<!-- POPUp Sidebar -->

<div class="modal fade modal-filterSidebar" id="filterSidebar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bộ lọc</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="nav-aside p-2 bg-light">
          <?php /* ?>
          <?php if(isset($_GET['s'])): ?>
          <div class="category-list">
            <b><a href="">Danh mục sản phẩm</a></b>
            <?php 
              
                $kw = $_GET['s'];
                global $wp_query;
                //$wp_query->query_vars['post_type'] = 'product';
                //$wp_query->query_vars['posts_per_page'] = 100;
                //var_dump($wp_query);
                // $args = array(
                //     's' => $kw,
                //     'post_type' => 'product',
                //     'posts_per_page' => 300,
                //     'fields' => 'ids',
                    
                // );
                // $products = new WP_Query( $args );

                //$products = wc_get_product();

                $args = array(
                    's' => $kw,
                    'post_type' => 'product',
                    'posts_per_page' => 300,
                );
                $wp_query = new WP_Query( $args );

                $product_ids = array();
                // Loop through the products
                  if ( have_posts() ) {
                      while ( have_posts() ) {
                          the_post();
                          $product = wc_get_product();
                          //var_dump($product);
                          $product_id = get_the_ID(); // Get the product ID
                          $product_ids[] = $product_id; // Add the product ID to the array
                      }
                  }  
                //var_dump($products);

                //$product_ids = $products->posts;

                //var_dump($product_ids);

                $all_categories = array(); 
                foreach ( $product_ids as $product_id ) {
                    $list_cates = wp_get_post_terms( $product_id, 'product_cat' );
                    foreach ( $list_cates as $cate_item ) {
                        $all_categories[ $cate_item->term_id ] = $cate_item->name;
                    }
                } ?>
                <div class="cate-list">
                  <?php foreach ( $all_categories as $category_id => $category_name ) {
                      echo '<a href="' . get_term_link( $category_id, 'product_cat' ) . '">' . $category_name . '</a><br />';
                  } ?>
                </div>
                <?php
                wp_reset_query();
                wp_reset_postdata();
               
            ?>
          </div>
          <?php endif; ?><?php */ ?>

          <form class="sidebar-form" method="get" action="">

            <ul class="nav flex-column">

              <li class="nav-item my-2">
                <?php if( $brands ): ?>

                <b><a href="">Thương hiệu</a></b>


                <ul class="nav flex-column">

                  <?php 

                  foreach( $brands as $term ):                

                    $c = '';

                    if( in_array($term->term_id, $value_brands) ) {

                      $sidebar_choose['brands[]='.$term->term_id] = $term->name;

                      $c = 'checked';

                    }

                  ?>

                  <li>

                    <label class="custom-checkbox">

                      <input type="checkbox" name="brands[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                      <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name;?></span></span>

                    </label>

                  </li>

                  <?php endforeach; ?>

                </ul>

                <?php endif; ?>

              </li>

              <?php if( $level < 3 ):?>

              <li class="nav-item my-2">

                <b><a href="">Phân loại</a></b>

                <ul class="nav flex-column">

                  <?php if( $level == 3 ):?>

                    <?php 

                    foreach( $sorts as $value => $name ):

                      $c = '';

                      if( $value == $value_sort ) {

                        $sidebar_choose['sort='.$value] = $name;

                        $c = 'checked';

                      }

                    ?>

                    <li>

                      <label class="custom-checkbox">

                        <input type="radio" name="sort" value="<?php echo $value;?>" <?php echo $c;?> />

                        <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $name;?></span></span>

                      </label>

                    </li>

                    <?php endforeach; ?>

                  <?php elseif( count($terms)>0 ):?>

                    <?php

                    foreach( $terms as $term ):



                      // Bỏ qua cat này

                      if( $term->slug == 'gia-soc-hom-nay' ) {

                        continue;

                      }



                      $c = '';

                      if( in_array($term->term_id, $value_cats) ) {

                        $sidebar_choose['cats[]='.$term->term_id] = $term->name;

                        $c = 'checked';

                      }
                       $category_link = get_category_link($term->term_id);
                    ?>

                    <li>

                      <?php if (($level == 1 || $level == 3 || ($value_brands && $level == 0)) && is_product_category('gia-soc-hom-nay')==false): 
                                 $current_product_category = get_queried_object();
                                 $current_category_link = get_term_link($current_product_category);
                                 if($value_brands) {
                                     $category_link = add_query_arg('brands', $value_brands, $category_link);
                                 }
                        ?>
                            <a class="cate_link checkbox-link <?php echo ($current_category_link == $category_link)?"active":""; ?>" href="<?php echo $category_link; ?>"><?php echo $term->name; ?></a>
                        <?php else: ?>
                          <label class="<?php echo $class; ?> custom-checkbox">

                            <input type="checkbox" name="cats[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                            <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $term->name;?></span></span>

                          </label>    
                        <?php endif ?>
                     <!--  <label>

                        <input type="checkbox" name="cats[]" value="<?php //echo $term->term_id;?>" <?php echo $c;?> />

                        <span><?php //echo $term->name;?></span>

                      </label> -->

                    </li>

                    <?php endforeach; ?>

                  <?php else: ?>

                    <?php foreach ( $all_categories as $category_id => $category_name ): ?>
                          <?php if(isset($_GET['s'])): 
                              $extra_class = "checkbox-link";
                          endif; ?>  
                          <?php echo '<li><a class="cate_link '.$extra_class.'" href="' . get_term_link( $category_id, 'product_cat' ) . '">' . $category_name . '</a></li>'; ?>
                    <?php endforeach; ?>

                    <?php /*

                    foreach( $types as $i => $name ):

                      $c = '';

                      if( in_array($i, $value_types) ) {

                        $sidebar_choose['types[]='.$i] = $name;

                        $c = 'checked';

                      }

                    ?>

                    <li>

                      <label class="custom-checkbox">

                        <input type="checkbox" name="types[]" value="<?php echo $i;?>" <?php echo $c;?> />

                        <span><?php echo $name;?></span>

                      </label>

                    </li>

                    <?php endforeach; */?>

                  <?php endif; ?>

                </ul>

              </li>

              <?php endif; ?>

              <li class="nav-item my-2">

                <b><a href="">Khoảng giá</a></b>

                <ul class="nav flex-column">

                  <?php 

                  foreach( site_wc_get_prices_static() as $value => $name ): 

                    $c = '';

                    if( in_array($value, $value_prices) ) {

                      $sidebar_choose['prices[]='.$value] = $name;

                      $c = 'checked';

                    }

                  ?>

                  <li>

                    <label class="custom-checkbox">

                      <input type="checkbox" name="prices[]" value="<?php echo $value;?>" <?php echo $c;?> />

                      <span class="custom-checkbox-field"><span class="checkbox-item"></span> <span class="field-name"><?php echo $name;?></span></span>

                    </label>

                  </li>

                  <?php endforeach; ?>

                </ul>

                <?php if ( is_active_sidebar( 'sidebar-widget-area' ) ) : ?>
                <div id="sidebar-widget-area" class="widget-area sidebar-widget-area">
                    <?php dynamic_sidebar( 'sidebar-widget-area' ); ?>
                </div>
              <?php endif; ?>



              </li>

            </ul>

          </form>

        </div>
      </div>
      <div class="modal-footer">
         <div class="pt-2">

            <a href="<?php echo $uri[0];?>" class="w-100 btn btn-outline-primary px-1 border-2 rounded fw-bold fs-11">Xoá chọn</a>

          </div>
      </div>
    </div>
  </div>
</div>


  