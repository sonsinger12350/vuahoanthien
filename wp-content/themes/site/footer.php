<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

global $woocommerce;
global $sidebar_true;

$count = $woocommerce->cart->get_cart_contents_count();

$page_on_front = (int) get_option( 'page_on_front', 0 );
$footer = (array) get_field('footer', $page_on_front);
$social_items = (array) get_field('social_items', $page_on_front);

?>
</main>
<footer class="footer d-flex flex-column">
  <div class="footer-highlight  d-lg-block"> <!-- d-none -->
    <div class="container justify-content-center">
      <?php echo $footer['support'];?>
    </div>
  </div>
  <div class=" d-lg-block"> <!-- d-none -->
    <div class="container footer-nav">
      <div class="row">
        <div class="col-6 col-lg-4 py-1">
          <?php echo $footer['info_1'];?>
        </div>

        <div class="col-6 col-lg-4 py-1">
          <?php echo $footer['info_2'];?>
        </div>

        <div class="col-lg-4 py-1">
          
          <div class="mt-4">
            <div class="footer-logo mb-3">
              <img src="<?php site_the_assets();?>images/bo-cong-thuong.png" loading="lazy"/>
            </div>
            <?php get_template_part( 'parts/section/newsletter'); ?>
          </div>

          <div class="mt-4">
            <?php echo $footer['info_3'];?>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="border-bottom m-auto w-25 my-4"></div>
  
  <div class="container footer-brands">
    <div class="row justify-content-center">
      <div class="col-12 brands-wrapper py-3">
        <?php 
          $tax = 'product-brand';
          $terms = get_terms(array(
            'taxonomy' => $tax,
            'hide_empty' => false,
            'number' => 100,
            'meta_key' => 'order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
          ));

          foreach( $terms as $i => $item ): 
            $key = $tax . '_' . $item->term_id;
            $image = get_field('image', $key);
            $link = wc_get_page_permalink( 'shop' ) . '?thuong-hieu[]=' . $item->term_id;
        ?>
        <a href="<?php echo $link; //the_field( 'link', $key );?>" title="<?php echo $item->name;?>">
          <img src="<?php echo wp_get_attachment_image_url( $image, 'full' );?>" alt="<?php echo $item->name;?>" loading="lazy"/>
        </a>
        <?php 
          //   if( $i == 9 ) {
          //     echo '</div><div class="col-12 brands-wrapper py-3">';
          //   }
          endforeach;
        ?>
      </div>
      <p class="my-4 col-12 col-lg-8 text-center d-lg-block address-footer"> <!-- d-none  -->
        <!-- <small> -->
          <?php echo $footer['address'];?>
        <!-- </small> -->
      </p>
    </div>
  </div>

  <div class="footer-highlight d-block d-none d-lg-none">
    <div class="container">
      <?php echo str_replace('h4','b', $footer['support']);?>
    </div>
  </div>
</footer>

<div class="d-none fixed-bottom w-100 p-4" id="compareFooter">
  <div class="d-flex align-items-center justify-content-center">
    <p>Bạn đã chọn <span class="number">0</span> sản phẩm để so sánh (Tối đa 4 sn phẩm).</p>
    <div class="d-flex" id="compareList"></div>
    <a class="btn btn-primary" data-href="<?php echo site_compare_url();?>">So sánh</a>
    <a class="btn btn-danger btn-reset-compare">Xoá các sản phẩm đã chọn</a>
  </div>
</div>

<div class="<?php // echo $count == 0 ? 'd-none ' : '';?>shop-cart-bottom">
  <div class="fade shop_cart_message">Đang thêm vào giỏ hàng</div>
  <a href="<?php echo site_cart_url();?>">
    <i class="bi bi-cart-check-fill"></i>
    <span class="shop_cart_count"><?php echo $woocommerce->cart->get_cart_contents_count();?></span>
  </a>
</div>

<button class="btn btn-scroll-top hide-on-mobile"><i class="bi bi-arrow-up"></i></button>
<?php if ($social_items): ?>
  <div class="social-box">
    <div class="social-box-inside">
      <?php foreach ($social_items as $i => $item): 
               if($item['name'] == "Phone"):
                  $phoneNums = explode(',', $item["link"] ); ?>
                  <div class="social-item social-item-<?php echo $item['name']; ?>">
                    <div class="item-phone">
                      <?php echo $item['icon']; ?>
                      <div class="phoneNums">
                        <div class="phoneNums-container">
                          <?php foreach ($phoneNums as $j => $phoneNum): ?>
                          <a href="tel:<?php echo str_replace(" ", "", $phoneNum); ?>"><?php echo $item['icon']; ?> <?php echo str_replace(" ", "", $phoneNum); ?></a>  
                          <?php endforeach; ?>  
                        </div>
                        <i class="arrow-down bi bi-caret-down-fill"></i>
                      </div>
                    </div>
                  </div>
               <?php else: ?>
                <div class="social-item social-item-<?php echo $item['name']; ?>">
                  <a href="<?php echo $item["link"]; ?>"><?php echo $item['icon']; ?></a>
                </div>
              <?php endif;
          endforeach; ?>
    </div>
  </div>
<?php endif ?>

<?php if( $sidebar_true == true ): ?>  <!-- is_product_category() || is_product_tag()  -->
  <div class="filter-sidebar-row show-on-mobile">
    <button type="button" class="btn btn-outline-primary btn-lg px-xl-5 rounded" data-bs-toggle="modal" data-bs-target="#filterSidebar"><i class="bi bi-funnel-fill"></i>
 Lọc sản phẩm</button>
  </div>     
<?php endif; ?> 


<?php 
// $pagenames = get_query_var( 'pagename', '' );
// var_dump($pagenames);
//var_dump($sidebar_true);

get_template_part( 'parts/popup/noti' );
$params = array();
if( in_array( get_query_var( 'pagename', '' ), array('gio-hang', 'thanh-toan') ) == false ) {
  $params = array('no_show'=>'input');
}

get_template_part( 'woocommerce/checkout/form', 'coupon', $params );

if( get_query_var( 'pagename', '' ) == 'thanh-toan' ) {
  get_template_part( 'parts/popup/thanks' );
}

if( get_query_var( 'product', '' ) != '' ) {
  get_template_part( 'parts/popup/deal', 'form' );
}

wp_footer(); 

?>

 
</body>
</html> 