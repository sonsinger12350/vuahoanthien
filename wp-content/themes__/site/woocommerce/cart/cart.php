<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

// $remove_coupon = strtolower( site__get( 'remove_coupon' ) );
// if( $remove_coupon == 'point' ) {
//   WC()->cart->remove_coupon( 'Cart Discount' );
// }

do_action( 'woocommerce_before_cart' );

$link = home_url();
$cat_hot_sale = site_get_hot_sale_home_page();
$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
if( isset($cat_hot_sale['term_id']) ) {
  $link = get_term_link($cat_hot_sale['term_id']);
}

$total = 0;
$cart_coupons = array();
//global $cart_point_reward;
$cart_point_reward = array();
$discount_coupons = array();

$my_coupons = WC()->cart->get_coupons();

// Get Point Discount
$user_id = get_current_user_ID();
if ($user_id) {
  // code...
  $get_user_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
  //$discount_amount = get_points_discount_amount($user_id);
}

// $cart_discount = getCurrentLanguageCode() == 'vi' ? 'Điểm' : 'Point';
$cart_discount = strtolower(__( 'Cart Discount', 'points-and-rewards-for-woocommerce' ));

foreach( $my_coupons as $code => $coupon ) {
  // get_product_ids();
  // is_valid_for_product( $product );
  //var_dump($code);
  if( strtolower($code) == $cart_discount ) {
    $cart_point_reward[$cart_discount] = $coupon;
    //$cart_coupons[$cart_discount] = $coupon;
  } else if( count($coupon->get_meta_data()) > 0 ) {
    $cart_coupons[$code] = $coupon;
  } else {
    $discount_coupons[$code] = $coupon;
  }
}

$currency_symbol = get_woocommerce_currency_symbol();
//$cart_subtotal_redemption = get_option('cart_subtotal_redemption');

//var_dump($cart_coupons);
//var_dump($cart_point_reward);

?>
<form class="cart-form" id="cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post"> <!--container-->
  <div class="row">
    <div class="col-12 col-lg-9 col-cart">
      <div class="section-bg mb-3">  
        <!-- <h2 class="section-header border-0"><span>Giỏ hàng</span></h2> -->

        <div class="shop_table cart">
          <div class="cart-inside">      
            <div class="cart-header bg-light row mb-3 py-3 rounded">
              <div class="col-1">STT</div>
              <div class="col-10 col-md-4 d-md-flex justify-content-center">Sản phẩm</div>
              <div class="col-2 d-none d-md-flex justify-content-center">Đơn giá</div>
              <div class="col-2  d-md-flex justify-content-center">Số lượng</div>
              <div class="col-2  d-md-flex justify-content-center">Thành tiền</div>
              <div class="col-1  d-md-flex"></div>
            </div>
            <div class="cart-body">
              <?php
              $i = 1;
              foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                  $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

                  $price = $_product->get_price();
                  $discount = 0;

                  foreach( $discount_coupons as $coupon ) {
                    $data = $coupon->get_data();
                    if( in_array( $_product->get_id(), $data['product_ids'] ) ) {
                      $discount = site_wc_coupon_discount_amount($coupon) / $cart_item['quantity'];
                      
                      if( $discount > 0 ) {
                        $price -= $discount;
                      }
                    }
                  }
                  
                  $subtotal = $price * $cart_item['quantity'];
                  $total += $subtotal;
              ?>
              <div class="bg-light row mb-3 py-3 fs-11 bt-1 cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                <div class="col-1 col-stt">
                  <span><?php echo $i++;?></span>
                </div>
                <div class="col-10 col-md-4 col-product-desc">
                  <div class="d-flex">
                    <div class="product-image">
                      <a href="<?php echo $product_permalink;?>">
                        <img src="<?php echo wp_get_attachment_image_url( $_product->get_image_id(), 'full' );?>"
                          class="img-fluid rounded-start me-2" alt="">
                      </a>
                    </div>
                    <div class="product-title">
                      <span class="text-limit-2"><?php echo $_product->get_name();?></span>
                      <div class="d-md-none d-block product-price">
                        <div>
                          <?php if ($price > 0): ?>
                            <span class="cart-item-price text-danger">
                              <?php echo site_wc_price($price);?><span><?php echo $currency_symbol; ?></span>
                            </span>
                            <span class="text-decoration-line-through fs-122"><?php echo site_wc_price($_product->get_regular_price());?><span><?php echo $currency_symbol; ?></span></span>
                          <?php else: ?>
                            <span class="cart-item-price">Giá liên hệ</span>
                          <?php endif ?>  
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-2 col-price d-none d-md-flex justify-content-center flex-wrap p-0">
                  <div class="inside">
                    <?php if ($price > 0): ?>
                      <span class="text-decoration-line-through price-normal">
                        <?php echo site_wc_price($_product->get_regular_price());?><ins class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol; ?></ins>
                      </span>
                      <span class="cart-item-price price-forsale fw-bold text-danger"><?php echo site_wc_price($price);?><ins class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol; ?></ins></span>
                    <?php else: ?>
                      <span class="cart-item-price">Giá liên hệ</span>
                    <?php endif ?>
                      
                  </div>
                </div>
                <div class="col-2 col-amount d-md-flex justify-content-center">
                  <div class="d-flex flex-row">
                    <label class="show-on-mobile">Số lượng: </label>
                    <div class="product-quantity d-inline-flex quantity-input-group mx-2" data-price="<?php echo $price;?>">
                      <button type="button" class="btn btn-outline-dark btn-decrease btn-change border fw-bold fs-4">-</button>
                      <?php
          						if ( $_product->is_sold_individually() ) {
          							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
          						} else {
          							$product_quantity = woocommerce_quantity_input(
          								array(
          									'input_name'   => "cart[{$cart_item_key}][qty]",
          									'input_value'  => $cart_item['quantity'],
          									'max_value'    => $_product->get_max_purchase_quantity(),
          									'min_value'    => '0',
          									'product_name' => $_product->get_name(),
          								),
          								$_product,
          								false
          							);
          						}

          						$input = apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.

                      echo str_replace(
                        array( $a = 'quantity', $b = 'input-text' ), 
                        array( $a . '-box', 'form-control text-center fw-bold border border-end-0 border-start-0 rounded-0 quantity ' . $b ),
                        $input
                      );

          						?>            
                      <button type="button" class="btn btn-outline-dark btn-increase btn-change border fw-bold fs-4">+</button>
                    </div>
                  </div>
                </div>
                <div class="col-2 col-price-total d-md-flex justify-content-center text-danger">
                  <?php if ($subtotal > 0): ?>
                      <label class="show-on-mobile">Thành tiền: </label>
                      <span class="cart_sub_total fw-bold"><?php echo site_wc_price( $subtotal ); ?><ins class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol; ?></ins></span>
                  <?php else: ?>
                    <label class="show-on-mobile">Thành tiền: </label>
                    <span class="cart_sub_total">Liên hệ</span>
                  <?php endif; ?>  
                    
                </div>
                <?php /* ?>
                <div class="col-1 col-delete d-md-flex">
                  <?php
                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                      'woocommerce_cart_item_remove_link',
                      sprintf(
                        '<a href="%s" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="bi bi-trash text-grey-lightest"></i></a>',
                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                        esc_html__( 'Remove this item', 'woocommerce' ),
                        esc_attr( $product_id ),
                        esc_attr( $_product->get_sku() )
                      ),
                      $cart_item_key
                    );
                  ?>
                </div>
                <?php */ ?>
                <div class="col-1 col-delete d-md-flex">
                      <div class="delete-product" data-cart-item-key="<?php echo $cart_item_key; ?>">
                          <?php
                              echo apply_filters(
                                  'woocommerce_cart_item_remove_link',
                                  sprintf(
                                      '<a class="delete-link" href="%s" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="bi bi-trash text-grey-lightest"></i></a>',
                                      esc_url(wc_get_cart_remove_url($cart_item_key)),
                                      esc_html__('Remove this item', 'woocommerce'),
                                      esc_attr($product_id),
                                      esc_attr($_product->get_sku())
                                  ),
                                  $cart_item_key
                              );
                          ?>
                      </div>
                  </div>

                  <!-- Modal HTML -->
                  <div id="deleteModal" class="modal fade" id="modal-message" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="icon-heading"><i class="bi bi-exclamation-triangle"></i></div>
                        <div class="modal-header">
                          <h5 class="modal-title">Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng ?</h5>
                          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                        </div>
                        <div class="modal-body">
                          <div class="links-row">
                            <a id="confirmDelete" href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" class="confirm-link btn btn-primary rounded" data-removal-url="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>">Vẫn muốn xóa</a>
                            <a class="btn btn-outline-dark rounded" href="#" id="cancelDelete">Hủy</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
              </div>
              <?php
                }
              } ?>
            </div>
          </div>
        </div>
      </div>      
      <?php if( count($cart_coupons) == 0 ): ?>
      <div class="section-bg">    
        <div class="cart-footer">  
          <?php 
            $need_amount = 0;
            $min = 0;
            $minimum_amount = 0;
            $coupon_archived = false;
            $current_coupon_archived = "";
            $current_coupon_archived_desc = "";
            $current_coupon_archived_code = 0;

            $coupons = site_get_coupons(array('sort'=>'minimum_amount'));
            $coupon_descs = [];
            $i = 0;

            foreach( $coupons as $index => $coupon){
              $data = $coupon->get_data();
              $min = (int) $data['minimum_amount'];
              $coupon_descs[ $data['minimum_amount'] ] = [
                  'description' => $data['description'],
                  'coupon_code' => $data['code']
              ];
              if( $need_amount == 0 && $min > $total ) {
                $need_amount = $min - $total;
                $minimum_amount = $data['minimum_amount'];
              }
              if ($min <= $total) {
                $coupon_archived = true;  
                $current_coupon_archived = $data;
                //var_dump($current_coupon_archived);
                $current_coupon_archived_desc = $data['description'];
                $current_coupon_archived_code = $data['code'];
              }
            }
            //echo $minimum_amount;
            //var_dump($current_coupon_archived);
            //echo $need_amount;
          ?>
          <div class="row-need-amount<?php echo ($need_amount == 0 && $need_amount != null) ? ' d-none' : '';?>"> <!-- bg-light row mb-3 py-3 rounded -->
            <div class="col-md-12 text-center">
              <!-- Mua thêm <span class="need_amount"><?php //echo site_wc_price($need_amount);?></span><sup>đ</sup> để được khuyến mãi! -->
              <p class="cart_coupon_warning">
              <?php if ($coupon_archived): // Add this check ?>
                Chúc mừng bạn đã đạt điều kiện sử dụng khuyến mãi <b style="text-transform: uppercase;"><?php echo $current_coupon_archived_code; ?></b> là <?php echo $current_coupon_archived_desc; ?> <b>Vui lòng nhấn chọn mã khuyến mãi để sử dụng</b>.
              <?php endif; ?>
              </p>
              <p class="line-1 <?php echo ($need_amount==0 )?'d-none':''; ?>">
              <?php //if ($need_amount != null ): ?>
                Chỉ cần mua thêm <b><span class="need_amount" data-min="<?php echo $min;?>"><?php echo site_wc_price($need_amount);?></span><span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol; ?></span></b> để nhận thêm ưu đãi 
              <?php //endif ?>
                <?php 
                  $coupon_current_class = "";
                  foreach( $coupon_descs as $amount => $desc ) {
                    //echo $amount;
                    //var_dump($coupon_descs);
                    //var_dump($current_coupon_archived["minimum_amount"]);
                    echo '<span class="minimum-amount '.  ( $amount == $current_coupon_archived["minimum_amount"] ? 'current-coupon ' : '' ) . $amount . ( $amount == $minimum_amount ? '' : ' d-none' ) .'"data-desc="'.$desc["description"].'" data-coupon="'.$desc["coupon_code"].'"><b style="text-transform: uppercase;">'.$desc["coupon_code"].'</b> là ' . $desc["description"] . '</span>';
                  }
                ?></p>
              
              <!-- <p class="cart_coupon_warning"></p>   -->
              <!-- <p class="line-2 d-none">Bạn đã có thể dùng mã ưu đãi.</p> -->
              <a href="<?php echo $shop_page_url; ?>" class="btn btn-primary rounded rounded">Mua thêm sản phẩm khác</a>
            </div>
          </div>
         </div>
      </div>  
      <?php 
      endif;
      ?>

      <!-- <div class="bg-light row mb-3 py-3 rounded">
        <div class="col-md-12 text-end">
          
        </div>
      </div> -->
      <input type="hidden" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" />
      <?php // do_action( 'woocommerce_cart_actions' ); ?>
      <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
       
    </div>
    
    <!-- Cart Sidebar -->
    <div class="col-12 col-lg-3 cart-infomation">
      <div class="cart-infomation-inside">
        <?php 
          wc_get_template_part( 'cart/cart-points' );
          
          if ( wc_coupons_enabled() ) :
        ?>
        <div class="mb-3 section-bg"> <!-- bg-light border border-0 rounded p-2 -->
          <div class="d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold">Khuyến mãi</span>
            <div class="">
              <?php foreach ( $cart_coupons as $code => $coupon ) : 
              
                $data = $coupon->get_data();          
                $a_c = '';
                if( $code == $cart_discount ) {
                  $a_c = ' wps_remove_virtual_coupon';
                }
              ?>
              <strong><?php echo esc_attr( $code ); ?></strong>
              [<a class="text-danger<?php echo $a_c;?>" href="<?php echo add_query_arg('remove_coupon', urlencode($coupon->get_code()) );?>" title="Xóa">&times;</a>]
              <?php endforeach; ?>
            </div>
          </div>
          <?php 
            foreach ( $cart_coupons as $code => $coupon ) : 
              $img = get_the_post_thumbnail( $coupon->id, 'large' );
              if( $img!='' ):
            ?>
            <div class="mb-2"><?php echo $img;?></div>
            <?php 
              endif;
            endforeach;
          ?>
          <a href="#" class="text-primary text-hover-underline coupon-popup-btn" data-bs-toggle="modal" data-bs-target="#discountCode">
            <i class="bi bi-ticket-perforated"></i>
            Chọn hoặc nhập Khuyến mãi
          </a>
        </div>
        <?php endif;?>
        
        <div class="mb-3 section-bg cart-infomation-summary"> <!-- bg-light p-2 rounded -->
          <?php if( count($cart_coupons)>0 || count($cart_point_reward)>0):?>
          <div class="d-flex justify-content-between pb-2 border-bottom">
            <small>Tổng</small>
            <small><?php echo site_wc_price( $total );?><?php echo $currency_symbol; ?></small>
          </div>
          <?php foreach ( $cart_coupons as $code => $coupon ) : ?>
          <div class="cart_coupon border-bottom pt-2 pb-2 d-flex justify-content-between coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>" 
            data-amount="<?php echo $coupon->amount;?>"
            data-minimum="<?php echo $coupon->minimum_amount;?>"
          >
            <small>Khuyến mãi</small>
            <small><?php echo site_wc_coupon_discount( $coupon ); ?> [<a class="text-danger<?php echo $a_c;?>" href="<?php echo add_query_arg('remove_coupon', urlencode($coupon->get_code()) );?>" title="Xóa">&times;</a>]</small>
          </div>
          <?php endforeach; ?>
          <?php foreach ( $cart_point_reward as $code => $coupon ) : ?>
          <div class="cart_coupon border-bottom pt-2 pb-2 d-flex justify-content-between coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>" 
            data-amount="<?php echo $coupon->amount;?>"
            data-minimum="<?php echo $coupon->minimum_amount;?>"
          >
            <small>Điểm tích lũy</small>
            <small><?php echo site_wc_coupon_discount( $coupon ); ?> [<a id="remove_point_coupon" class="wps_remove_virtual_coupon text-danger<?php echo $a_c;?>" href="<?php echo add_query_arg('remove_coupon', urlencode($coupon->get_code()) );?>" title="Xóa">&times;</a>]</small>
          </div>
          <?php endforeach; ?>
          <?php endif;?>
          <div class="d-flex justify-content-between py-2 cart-total-final">
            <span class="fw-bold">Thành tiền</span>
            <div class="d-flex flex-column align-items-end">
              <?php if (site_wc_price( site_wc_cart_get_total() ) > 0): ?>
                <span class="fs-4 fw-bold text-danger">
                  <span class="cart_total" data-value="<?php echo site_wc_cart_get_total();?>">
                    <?php echo site_wc_price( site_wc_cart_get_total() ); ?>
                  </span>
                  <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                </span>
                <!-- <span><i>(Đã bao gồm VAT nếu có)</i></span> -->
              <?php else: ?>
                <span class="fs-4 fw-bold text-danger">Liên hệ</span>
              <?php endif; ?>            
            </div>
          </div>
        </div>
        <a href="<?php echo wc_get_checkout_url();?>" class="btn btn-danger rounded w-100">Mua hàng</a>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript">
  const removeCouponLink = document.getElementById('remove_point_coupon');
  // const inputElement = document.getElementById('wps_cart_points');
  // const resultElement = document.getElementById('result');
  // const remainPointElement = document.getElementById('point-remaining');
  // console.log(wps_cart_points);

  removeCouponLink.addEventListener('click', function (event) {
    event.preventDefault();

    
    // Clear the value in localStorage
    localStorage.setItem('wps_cart_points', 0);
    localStorage.setItem('wps_cart_remain_points', 0);
    // Clear the displayed result
    resultElement.textContent = '';
    remainPointElement.textContent = '';
    // Reset the input field
  });
</script>

<?php
/*
$points = false;

if( class_exists('Points_Rewards_For_Woocommerce') ) {
  $points = new Points_Rewards_For_Woocommerce();
}
*/

if( site__get('test', '') == 'abs' ) {
  echo '<p style="display: none;">';
  var_dump( $my_coupons );
  echo '</p>';
}