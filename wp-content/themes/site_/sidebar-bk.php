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

  }

  

  $brands = site_the_category_brands( $cat->name );

} else {

  $brands = get_terms(array(

    'number' => false,

    'taxonomy' => 'product-brand',

    // 'hide_empty' => false,

  ));



  if( count($value_brands) ) {

    $items = array();

    foreach( $value_brands as $value ) {

      $items[] = (object) array(

        'term_id' => $value,

        'taxonomy' => 'product-brand'

      );

    }

    

    $terms = site_get_terms_by_terms($items, 'product_cat', $limit = 10 );

  }

}



?>

<div class="d-none d-lg-block col-lg-2">

  <div class="nav-aside p-2 bg-light">

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

                <label>

                  <input type="checkbox" name="brands[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                  <span><?php echo $term->name;?></span>

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

        <?php if( $level < 3 ): 
                  $list_cate_items = [];
        ?>

        <li class="nav-item my-2">

          <b><a href="">Phân loại</a></b>
          <div class="explore-more">  
            <ul class="nav flex-column collapse"  id="detailMoreCate">

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

                  <label>

                    <input type="radio" name="sort" value="<?php echo $value;?>" <?php echo $c;?> />

                    <span><?php echo $name;?></span>

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

                ?>

                <li>

                  <label>

                    <input type="checkbox" name="cats[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                    <span><?php echo $term->name;?></span>

                  </label>

                </li>

                <?php endforeach; ?>

              <?php else:?>

                <?php 

                foreach( $types as $i => $name ):

                  $c = '';

                  if( in_array($i, $value_types) ) {

                    $sidebar_choose['types[]='.$i] = $name;

                    $c = 'checked';

                  }

                ?>

                <li>

                  <label>

                    <input type="checkbox" name="types[]" value="<?php echo $i;?>" <?php echo $c;?> />

                    <span><?php echo $name;?></span>

                  </label>

                </li>

                <?php endforeach; ?>

                <?php endif; ?>

            </ul>
            <div class="explore-more-action">
              <a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#detailMoreCate" role="button"
                aria-expanded="false" aria-controls="detailMoreCate">
                <span class="text-1">Xem thêm</span>
                <span class="text-2">Rút gọn</span>
              </a>
            </div>
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

              <label>

                <input type="checkbox" name="prices[]" value="<?php echo $value;?>" <?php echo $c;?> />

                <span><?php echo $name;?></span>

              </label>

            </li>

            <?php endforeach; ?>

          </ul>            

        </li>

      </ul>

    </form>

  </div>

  <div class="pt-2">

    <a href="<?php echo $uri[0];?>" class="w-100 btn btn-outline-primary px-1 border-2 rounded fw-bold fs-11">Xoá chọn</a>

  </div>

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

          <form class="sidebar-form" method="get" action="">

            <ul class="nav flex-column">

              <li class="nav-item my-2">

                <b><a href="">Thương hiệu</a></b>

                <?php if( $brands ): ?>

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

                    <label>

                      <input type="checkbox" name="brands[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                      <span><?php echo $term->name;?></span>

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

                      <label>

                        <input type="radio" name="sort" value="<?php echo $value;?>" <?php echo $c;?> />

                        <span><?php echo $name;?></span>

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

                    ?>

                    <li>

                      <label>

                        <input type="checkbox" name="cats[]" value="<?php echo $term->term_id;?>" <?php echo $c;?> />

                        <span><?php echo $term->name;?></span>

                      </label>

                    </li>

                    <?php endforeach; ?>

                  <?php else:?>

                    <?php 

                    foreach( $types as $i => $name ):

                      $c = '';

                      if( in_array($i, $value_types) ) {

                        $sidebar_choose['types[]='.$i] = $name;

                        $c = 'checked';

                      }

                    ?>

                    <li>

                      <label>

                        <input type="checkbox" name="types[]" value="<?php echo $i;?>" <?php echo $c;?> />

                        <span><?php echo $name;?></span>

                      </label>

                    </li>

                    <?php endforeach; ?>

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

                    <label>

                      <input type="checkbox" name="prices[]" value="<?php echo $value;?>" <?php echo $c;?> />

                      <span><?php echo $name;?></span>

                    </label>

                  </li>

                  <?php endforeach; ?>

                </ul>            

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


  