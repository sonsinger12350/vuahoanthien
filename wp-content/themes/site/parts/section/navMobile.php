<?php

global $woocommerce;

$user = wp_get_current_user();

$wishlist = YITH_WCWL::get_instance();

$count = $woocommerce->cart->get_cart_contents_count();

$theme_location = 'primary';

$menu = site_get_menu_items($theme_location, array(
  'menu_item_parent' => 0
));

$allDepartments = array();
$otherItems = array();

$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

if( $user->ID>0 ) {
  $wishlist_url = wc_get_account_endpoint_url( 'wishlist' );
} else {
  $wishlist_url = home_url( 'wishlist' );
}

?>
<nav class="static-top navbar-expand-lg navbar-main">
  <?php
    get_template_part( 'parts/section/highlight');
  ?>
  <div class="nav-wrapper">
    <div class="container">  <!-- pt-2 -->
      <div class="row navbar-main-wrap">
        <div class="col-7 col-lg-3 col-xxl-3 d-flex mobile-pr-0 align-items-center">
          
          <button class="navbar-toggler btn-menu collapsed" id="btnMenu" type="button" data-bs-toggle="collapse" data-bs-target="#allDepartments" data-parent-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation" data-bs-parent="#collapseMenuWrapper">
            <span class="navbar-toggler-icon"></span>
          </button>

          <a class="link-main-logo" href="<?php echo home_url();?>">
            <picture>
              <!-- <source media="(min-width: 640px)" srcset="<?php site_the_assets();?>images/logo/logo-new-mb.png"> -->
              <source media="(min-width: 640px)" srcset="<?php site_the_assets();?>images/logo/VHT-new-logo.svg">
              <source media="(max-width: 575.98px)" srcset="<?php site_the_assets();?>images/logo/VHT-new-logo.svg">
              <img src="<?php site_the_assets();?>images/logo/VHT-new-logo.svg" class="img-fluid w-80" title="Vie Home Depot" loading="lazy">
            </picture>
          </a>

          
        </div>

        <div class="col-5 col-lg-9 col-xxl-9 mobile-pl-0 d-flex align-items-center justify-content-end">
          <div class="search-box">  
            <div class="search-btn-item show-on-mobile">
              <i class="bi bi-search"></i>
              <i class="bi bi-x-lg"></i>
            </div>
            <!-- <form class="input-group" action="<?php //echo $shop_page_url;?>#top">
              <input type="text" name="s" value="<?php //echo get_query_var('s');?>" class="form-control search-input" 
                placeholder="Bạn cần tìm gì..." aria-label="Search" aria-describedby="button-addon2">
              <button class="btn btn-search" type="submot"><i class="bi bi-search"></i></button>
              <input type="hidden" name="post_type" value="product" />
            </form> -->
            
            <?php /*?><form class="input-group" action="<?php echo $shop_page_url;?>#top">
              <input type="text" name="s" value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : '';?>" class="form-control search-input" 
                placeholder="Bạn cần tìm gì..." aria-label="Search" aria-describedby="button-addon2">
              <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
              <input type="hidden" name="post_type" value="product" />
            </form><?php */?>
            <div class="search-box-form">
            <?php 
              //get_search_form(); 
              //echo do_shortcode('[ivory-search id="40148" title="AJAX Search Form for WooCommerce"]');
              echo do_shortcode('[fibosearch]');
            ?>
              <!-- <div class="close-btn"><span>x</span></div> -->
            </div>

          </div>  
          <ul class="nav nav-right">
            <li class="nav-item">
              <span class="nav-link d-flex align-items-center">
                <a class="d-lg-none" data-bs-toggle="collapse" data-bs-target="#profileNav">
                  <i class="d-block bi bi-person"></i>
                </a>
                <i class="d-none d-lg-block bi bi-person"></i>
                <span class="d-none d-xl-inline">
                  <b><?php echo $user->ID>0 ? ucwords($user->display_name) : 'Tài khoản';?></b><br>
                  <small>
                    <?php if( $user->ID>0 ):?>
                    <a href="<?php echo site_account_url();?>">Quản lý</a> / <a href="<?php echo site_login_url();?>?logout=1">Đăng xuất</a>
                    <?php else:?>
                    <a href="<?php echo site_login_url();?>">Đăng nhập</a> / <a href="<?php echo site_register_url();?>">Đăng ký</a>
                    <?php endif;?>
                  </small>
                </span>
              </span>
            </li>
            <li class="nav-item nav-item-shop-cart<?php echo $count>0 ? ' nav-item-has-count' : '';?>">
              <a class="nav-link d-flex align-items-center" href="<?php echo site_cart_url();?>">
                <i class="bi bi-cart"></i>
                <span class="d-none d-xl-inline">
                  <b>Giỏ hàng</b><br>
                  <small><span class="shop_cart_count"><?php echo $count;?></span> sản phẩm</small>
                </span>
                <small class="shop_cart_count cart-number <?php echo ($count > 0)?"show":""; ?>"><?php echo $count;?></small>  
              </a>
            </li>
            <li class="nav-item nav-item-wishlist<?php echo $wishlist->count_products()>0 ? ' nav-item-has-count' : '';?> d-sm-block"> <!-- d-none -->
              <?php
                // Include the custom widget in your template
                if ( is_active_sidebar( 'wishlist-count-widget-area' ) ) :
                    dynamic_sidebar( 'wishlist-count-widget-area' );
                endif;
              ?>
              <?php /* ?>
              <a class="nav-link d-flex align-items-center" href="<?php echo $wishlist_url;?>">
                <i class="bi bi-heart"></i>
                <span class="d-none d-xl-inline">
                  <b>Yêu thích</b><br>
                  <small><span class="wishlist_count"><?php echo $wishlist->count_products();?></span> sản phẩm</small>
                </span>
              </a>
              <?php */ ?>
            </li>
          </ul>
        </div>
      </div>

    </div>

    <div id="mainMenu-wrapper" class="border-bottom">
      <div class="container">
        <div class="row">
          <!-- Profile Menu -->
          <div class="nav-mb-right collapse px-sm-down-0" id="profileNav">
            <ul class="navbar-nav">
              <?php if( $user->ID>0 ):?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo esc_url( site_account_url() ); ?>">
                  <i class="bi bi-person-fill me-2"></i> Thông tin tài khoản
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ));?>">
                  <i class="bi bi-calendar3-range-fill me-2"></i> Quản lý đơn hàng
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ));?>">
                  <i class="bi bi-geo-alt-fill me-2"></i> Sổ địa chỉ
                </a>
              </li>
              <?php /* ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo esc_url( wc_get_account_endpoint_url( 'watched' ));?>">
                  <i class="bi bi-eye-fill me-2"></i> Sản phẩm bạn đã xem
                </a>
              </li>
              <?php */ ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo home_url( 'san-pham-yeu-thich-cua-ban' ).'/?wishlist-action=manage';//esc_url( wc_get_account_endpoint_url( 'wishlist' ) ); ?>">
                  <i class="bi bi-heart-fill me-2"></i> Sản phẩm yêu thích
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo esc_url( wc_get_account_endpoint_url( 'points' ) ); ?>">
                  <i class="bi bi-star-fill me-2"></i> Điểm tích lũy
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo esc_url( wc_get_account_endpoint_url('kho-qua-tang') ); ?>">
                  <i class="bi bi-gift-fill me-2"></i> Kho quà tặng
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_login_url();?>?logout=1">
                  <i class="bi bi-person-circle me-2"></i> Đăng xuất
                </a>
              </li>
              <?php else:?>
                <li class="nav-item">
                <a class="nav-link" href="<?php echo site_login_url();?>">Đăng nhập</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_register_url();?>">Đăng Ký</a>
              </li>
              <?php endif;?>
            </ul>
          </div>
          <!-- End Profile Menu -->
          <!-- Menu Ngang Desktop-->
          <div class="navbar-collapse px-sm-down-0 collapse" id="mainMenu">
            <ul class="navbar-nav">
              <li class="nav-item nav-item-account-inside bg-grey-lighter d-block d-lg-none">
                <a class="nav-link" href="<?php echo site_account_url();?>">
                  <i class="bi bi-person"></i>
                  <span>Tài khoản</span>
                </a>
              </li>
              <li class="nav-item nav-item-wishlist-inside bg-grey-lighter d-block d-lg-none">
                <a class="nav-link" href="<?php echo $wishlist_url;?>">
                  <i class="bi bi-heart"></i>
                  <span>Yêu thích</span>
                </a>
              </li>
              <!-- Menu show on mobile -->
              <?php
              $i = 0;
              foreach( $menu as $menu_item ):

                //var_dump($menu_item)
                $l_c = '';
                $a_c = '';

                $attr = '';
                $bs = get_field('bs_target', $menu_item->ID);

                //var_dump($menu_item->classes); 

                $childs = site_get_menu_items($theme_location, array(
                  'menu_item_parent' => $menu_item->ID
                ));

                if( count($childs)>0 ) {
                  $l_c = ' has-child';
                  if( $i++ == 0 ) {
                    $bs = '#allDepartments';
                    $allDepartments = $childs;
                  } else {
                    $bs = '#otherItems-' . $menu_item->ID; 
                    $otherItems[ 'otherItems-' . $menu_item->ID ] = $childs;
                  }
                } else {
                  $bs = '';

                }

                if( substr($bs,0,1) == '#' ) {
                  if( site_is_mobile() ) {
                    $attr .= 'data-bs-toggle="collapse" ';
                  } else {
                    $attr .= 'data-wp-toggle="collapse" ';
                  }

                  $attr .= 'data-bs-parent="#collapseMenuWrapper" data-bs-target="'. $bs .'"';                  
                }
                
                $icon = '';
                if( get_field('icon', $menu_item->ID ) != '' ) {
                  $icon = '<i class="'. get_field('icon', $menu_item->ID ) .'"></i>';
                  $l_c = ' bg-grey-lighter d-block d-lg-none';
                } else {
                  $a_c = ' fs-lg-5';
                }
              ?>
              <?php /* ?>
              <li class="nav-item<?php echo $l_c;?> <?php echo $menu_item->classes[0]; ?>">
                <a class="nav-link<?php echo $a_c;?>" href="<?php echo $menu_item->url;?>" <?php echo $attr;?>>
                  <?php echo $icon;?><span><?php echo $menu_item->title;?></span>
                </a>
              </li>
              <?php */ ?>
              <?php endforeach;?>
            </ul>
            <!-- End Menu show on mobile -->
            
          </div>
          <!-- End Menu Ngang Desktop-->
        </div>
      </div>
    </div>
  </div>


  <!-- Sub Menu Dropdown (khi hover vào Menu Chính) -->
  <div id="collapseMenuWrapper">
    <div class="collapse-menu-wrapper">
      <div class="collapse-overlay"></div>
      <!-- Sub menu cho Danh mục sản phẩm -->
      <div class="collapse collapse-nav-horizontal" id="allDepartments">
        <!-- <button class="btn btn-back btn-link d-lg-none" data-bs-target="#allDepartments">
          <i class="bi bi-chevron-left"></i> Back
        </button> -->
        <div class="menu-wrapper">
          <ul class="nav nav-main test">
            <li>
              <div class="tracking-order-menu">
                <a class="rounded" href="<?php echo home_url('trang-thai-don-hang');?>">
                  Theo dõi đơn hàng của bạn
                  <img src="<?php site_the_assets();?>images/icons/shipping-car-vuahoanthien.gif" class="img-fluid" title="Vie Home Depot" loading="lazy">
                </a>
              </div>
            </li>
            
            <?php 
            
            $sub_menu = array();
            $menu_brands = array();

            foreach( $allDepartments as $menu_item ):
              
              $childs = site_get_menu_items($theme_location, array(
                'menu_item_parent' => $menu_item->ID
              ));

              //var_dump($menu_item->classes); 
              //var_dump($childs);

              $bs = '';
              $l_c = '';
              if( count($childs)>0 ) {
                $id = 'menu_items_of_'. $menu_item->ID;
                $l_c = ' has-child';
                $sub_menu[ $id ] = array(
                  'menu_parent' => $menu_item,
                  'menu_childs' => $childs,
                );

                if( site_is_mobile() ) {
                  $bs .= 'data-bs-toggle="collapse" ';
                } else {
                  $bs .= 'data-wp-toggle="collapse" ';
                }

                $bs .= 'data-bs-parent="#allDepartments" data-bs-target="#'. $id .'"';
              } 
              else if( $menu_item->post_excerpt == 'show_brands' ) 
              {
                // $menu_brands = get_terms( array(
                //   'taxonomy' => 'product-brand',
                //   // 'hide_empty' => false,
                // ) );

                $menu_brands = get_terms( array(
                    'taxonomy' => 'product-brand',
                    'meta_key' => 'order',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    //'hide_empty' => false,
                ) );

                if( count($menu_brands)>0 ) {
                  $l_c = ' has-child';
                  if( site_is_mobile() ) {
                    $bs .= 'data-bs-toggle="collapse" ';
                  } else {
                    $bs .= 'data-wp-toggle="collapse" ';
                  }

                  $bs .= 'data-bs-parent="#allDepartments" data-bs-target="#menu-show-brands"';
                }
              }
            ?>
            <li class="nav-item text-nowrap nav-item-<?php echo $menu_item->ID;?><?php echo $l_c; ?> <?php echo $menu_item->classes[0]; ?>">
              <a class="nav-link" href="<?php echo $menu_item->url;?>" <?php echo $bs;?>>
                <?php echo $menu_item->title;?>
              </a>
            </li>
            <?php endforeach;?>
            
          </ul>

          <?php if( count($menu_brands)>0 ):?>
          <div class="collapse bg-light" id="menu-show-brands">
            <div class="collapse-panel collapse-category-panel container mobile-px-0">
              <div class="row">
                <button class="btn btn-back btn-link d-lg-none" data-bs-target="#menu-show-brands">
                  <i class="bi bi-chevron-left"></i> Back
                </button>
                <div class="col-12 h-100">
                  <div class="row nav-wrapper-brands py-lg-2">
                    <?php foreach( $menu_brands as $brand ) :
                      $src = wp_get_attachment_image_url( get_field('image', $brand->taxonomy . '_' . $brand->term_id ), 'full' );
                    ?>
                    <div class="col-4 col-lg-2 py-lg-2 nav-brand-item nav-brand-<?php echo $brand->term_id;?>">
                      <a href="<?php echo site_shop_search( array( 'thuong-hieu[]' => $brand->term_id ) );?>" class="border">
                        <img class="img-fluid m-h-30-px" src="<?php echo $src;?>" alt="<?php echo $brand->name;?>" loading="lazy"/>
                      </a>
                    </div>
                    <?php endforeach;?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php 
            $i = 0;
            foreach( $sub_menu as $id => $item ) : $i++; 
            $menu_parent = $item['menu_parent'];

            $c = '';
            // $src = wp_get_attachment_image_url( get_field('image', $menu_parent->ID), 'full' );
            $src = wp_get_attachment_image_url( get_post_meta($menu_parent->ID, '_thumbnail_id'), 'full' );
            if( $src!='' ) :
          ?>
          <div class="collapse" id="<?php echo $id;?>">
            <button class="btn btn-back btn-link d-lg-none" data-bs-target="#<?php echo $id;?>">
              <i class="bi bi-chevron-left"></i> Back
            </button>
            <div class="collapse-panel flex-column flex-lg-row bg-light">
              <ul class="nav flex-grow-1">
                <?php foreach( $item['menu_childs'] as $menu_item ) : ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo $menu_item->url;?>">
                    <?php echo $menu_item->title;?>
                  </a>
                </li>
                <?php endforeach;?>
              </ul>
              <div class="menu-cta d-none d-lg-block">
                <?php if( $src!='' ):?>
                <img src="<?php echo $src;?>" alt="" loading="lazy"/>
                <?php endif;?>
                <div class="menu-cta-wrapper">
                  <h2><?php echo $menu_parent->description;?></h2>
                  <a href="<?php echo $menu_parent->url;?>">Mua ngay</a>
                </div>
              </div>
            </div>
          </div>
          <?php else: ?>
          <div class="collapse bg-light" id="<?php echo $id;?>">
            <div class="collapse-panel collapse-category-panel container mobile-px-0">
              <button class="btn btn-back btn-link d-lg-none btn-<?php echo $id;?>" data-bs-target="#<?php echo $id;?>">
                <i class="bi bi-chevron-left"></i> Back
              </button>
              <div class="row w-100 h-90">
                <div class="col-12 h-100">
                  <ul class="row nav-wrapper">
                    <?php foreach( $item['menu_childs'] as $menu_item ) :
                      
                      $childs = site_get_menu_items($theme_location, array(
                        'menu_item_parent' => $menu_item->ID
                      ));
                    ?>
                    <li class="nav-item col-12 col-lg-3 py-lg-2 <?php echo $menu_item->classes[0]; ?>">
                      <a class="nav-link px-2 p-lg-0" href="<?php echo $menu_item->url;?>">
                        <b><?php echo $menu_item->title;?></b>
                      </a>
                      <?php if( count($childs)>0 ):?>
                      <ul class="ps-lg-2 nav flex-column">
                        <?php foreach( $childs as $child ) :?>
                        <li class="nav-item">
                          <a class="nav-link px-2 p-lg-0" href="<?php echo $child->url;?>">
                            <?php echo $child->title;?>
                          </a>
                        </li>
                        <?php endforeach;?>
                      </ul>
                      <?php endif;?>
                    </li>
                    <?php endforeach;?>
                  </ul>                 
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php endforeach;?>
          
        </div>
        
      </div>
      <!-- End Sub menu cho Danh mục sản phẩm -->
      <!-- Sub menu cho các menu khác của main Menu -->
      <?php foreach( $otherItems as $otherItemID => $homeDecorFurnitureKitchenware):?>
      <div class="collapse collapse-nav-category homeDecorFurnitureKitchenware" id="<?php echo $otherItemID;?>">
        <button class="btn btn-back btn-link d-lg-none" data-bs-target="#<?php echo $otherItemID;?>">
          <i class="bi bi-chevron-left"></i> Back
        </button>
        <div class="menu-wrapper">
          <ul class="nav nav-w-100">
            <?php 
            
            $sub_menu = array();

            foreach( $homeDecorFurnitureKitchenware as $menu_item ):
              
              $childs = site_get_menu_items($theme_location, array(
                'menu_item_parent' => $menu_item->ID
              ));
              
              $bs = '';
              $l_c = '';
              if( count($childs)>0 ) {
                $id = 'menu_items_of_'. $menu_item->ID;
                $l_c = " has-child";
                $sub_menu[ $id ] = array(
                  'menu_parent' => $menu_item,
                  'menu_childs' => $childs,
                );
                
                if( site_is_mobile() ) {
                  $bs .= 'data-bs-toggle="collapse" ';
                } else {
                  $bs .= 'data-wp-toggle="collapse" ';
                }

                $bs .= 'data-bs-parent="#'. $otherItemID .'" data-bs-target="#'. $id .'"';
              }
            ?>
            <li class="nav-item text-nowrap<?php echo $l_c; ?> <?php echo $menu_item->classes[0]; ?>">
              <a class="nav-link" href="<?php echo $menu_item->url;?>" <?php echo $bs;?>>
                <?php echo $menu_item->title;?>
              </a>
            </li>
            <?php endforeach;?>
          </ul>

          <?php foreach( $sub_menu as $id => $item ) : $menu_parent = $item['menu_parent'] ?>
          <div class="collapse bg-light " id="<?php echo $id;?>">
            <div class="collapse-panel collapse-category-panel container mobile-px-0">
              <div class="row w-100 h-100">
                <div class="col-12 d-lg-none">
                  <button class="btn btn-back btn-link" data-bs-target="#<?php echo $id;?>">
                    <i class="bi bi-chevron-left"></i> Back
                  </button>
                </div>
                <div class="col-12 h-100 col-lg-8">
                  <ul class="row nav-wrapper mobile">
                    <?php foreach( $item['menu_childs'] as $menu_item ) :
                      
                      $childs = site_get_menu_items($theme_location, array(
                        'menu_item_parent' => $menu_item->ID
                      ));                    
                    ?>
                    <li class="nav-item col-12 col-lg-4 py-lg-2">
                      <a class="nav-link px-2 p-lg-0" href="<?php echo $menu_item->url;?>">
                        <b title="<?php echo $menu_item->title;?>"></b>
                      </a>
                      <?php if( count($childs)>0 ):?>
                      <ul class="ps-lg-2 nav flex-column">
                        <?php foreach( $childs as $child_item ) :?>
                        <li class="nav-item">
                          <a class="nav-link px-2 p-lg-0" href="<?php echo $child_item->url;?>"><?php echo $child_item->title;?></a>
                        </li>
                        <?php endforeach;?>
                      </ul>
                      <?php endif;?>
                    </li>
                    <?php endforeach;?>
                  </ul>
                </div>
                
                <?php
                $src = wp_get_attachment_image_url( get_post_thumbnail_id($menu_parent->ID), 'full' );
                  // if( $menu_parent->description!='' ) :
                  if( $src !='' ) :
                    // $src = wp_get_attachment_image_url( get_field('image', $menu_parent->ID), 'full' );
                    

                    // Get field from product category
                    $top_brands = get_field('top_brands', $menu_parent->object . '_' . $menu_parent->object_id );
                  ?>
                <div class="d-none d-lg-block p-3 col-lg-4 bg-grey-lighter">
                  <a href="<?php echo $menu_parent->url;?>">
                    <h5><?php echo $menu_parent->title;?></h5>
                    <span><?php echo $menu_parent->description;?></span>
                    <div class="img-wrapper my-3 mx-1">
                      <img src="<?php echo $src;?>" class="img-fluid" loading="lazy"/>
                    </div>
                  </a>

                  <?php /*if( $top_brands ):?>
                  <b>Shop Top Brands</b>
                  <div class="row">
                    <?php foreach( $top_brands as $brand ):
                      $src = wp_get_attachment_image_url( get_field('image', $brand->taxonomy . '_' . $brand->term_id ), 'full' );
                    ?>
                    <a href="" class="col-6 col-lg-4 py-2">
                      <img class="img-fluid m-h-30-px" src="<?php echo $src;?>" alt="<?php echo $brand->name;?>" />
                    </a>
                    <?php endforeach;?>
                  </div>
                  <?php endif; **/?>
                </div>
                <?php endif;?>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <?php endforeach;?>
      <!-- End Sub menu cho các menu khác của main Menu -->
    </div>
  </div>
  <!-- End Sub Menu Dropdown (khi hover vào Menu Chính) -->
</nav>