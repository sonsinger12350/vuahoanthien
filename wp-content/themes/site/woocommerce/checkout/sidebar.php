<?php

// $checkout->get_checkout_fields( 'shipping' );

$discount = 0;
$cart_discount = getCurrentLanguageCode() == 'vi' ? 'Điểm' : 'Point';

$cart_coupons = array();

global $cart_point_reward;
$cart_point_reward = array();
$discount_coupons = array();

$my_coupons = WC()->cart->get_coupons();

foreach( $my_coupons as $code => $coupon ) {
  if( $code == $cart_discount || preg_match('/(cart discount)/i', $code) ) {
    $cart_point_reward[$cart_discount] = $coupon;
    //$cart_coupons[$cart_discount] = $coupon;
  } else if( count($coupon->get_meta_data()) > 0 ) {
    $cart_coupons[$code] = $coupon;
  } else {
    $discount_coupons[$code] = $coupon;
  }
}

if( 0 && $checkout->get_value('shipping_address')!='' ):
  $phone = $checkout->get_value('billing_phone');
  if( $checkout->get_value('shipping_phone')!='' ) {
    $phone = $checkout->get_value('shipping_phone');
  }
?>
<div class=""> <!-- border rounded p-2 mb-3-->
  <div class="d-flex justify-content-between align-items-center py-2">
    <span>Địa chỉ giao hàng</span>
    <a href="#" class="btn btn-primary rounded" data-bs-toggle="modal" data-bs-target="#customer_details">Sửa</a>
  </div>
  <div class="border-top pt-2">
    <div class="fw-bold shipping_name_value"><?php echo $checkout->get_value('shipping_first_name') . ' ' . $checkout->get_value('shipping_last_name');?></div>
    <p class="shipping_address_value"><?php echo $checkout->get_value('shipping_address');?></p>
    <p>Điện thoai: <span class="shipping_phone_value"><?php echo $phone;?></span></p>
  </div>
</div>
<?php endif;?>
<?php /*/ ?>
<div class="border rounded mb-4 p-2">
  <div class="d-flex justify-content-between align-items-center py-2">
    <span>Khuyến mãi</span>
    <div class="">
      <?php foreach ( $cart_coupons as $code => $coupon ) : ?>
      <strong><?php echo esc_attr( $code ); ?></strong>
      [<a class="text-danger" href="<?php echo add_query_arg('remove_coupon', $code );?>" title="Xóa">&times;</a>]
      <?php endforeach; ?>
      <!-- <span>Có thể chọn 0</span>
      <a href="#" data-bs-toggle="tooltip" data-bs-html="false" title=""
        data-bs-original-title="Áp dụng tối đa 1 Mã giảm giá Sản Phẩm và 1 Mã Vận Chuyển">
        <i class="bi bi-exclamation-circle"></i>
      </a> -->
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
  <a href="#" class="text-primary text-hover-underline" data-bs-toggle="modal" data-bs-target="#discountCode">
    <i class="bi bi-ticket-perforated"></i>
    Chọn hoặc nhập Khuyến mãi
  </a>
</div>
<?php /*/ ?>
<?php

$cart_totals = WC()->cart->get_totals();
// $cart_totals['total'];

?>
<div class=""> <!-- border rounded p-2 mb-3  -->
  <div class="d-flex flex-column"> <!--  py-2 -->
    <div class="d-flex justify-content-between align-items-center">
      <div class="">
        <div class="fw-bold">Đơn hàng</div>
        <div>
          <small class="me-1"><?php echo count(WC()->cart->get_cart());?> sản phẩm</small> |
          <a data-bs-toggle="collapse" href="#moreOrderInfo" aria-expanded="false" aria-controls="moreOrderInfo">
            <small>Chi tiết đơn hàng <i class="bi bi-caret-down-fill"></i></small>
          </a>
        </div>
      </div>
      <!-- <button class="btn btn-primary rounded">Sửa</button> -->
    </div>
    <div class="collapse" id="moreOrderInfo">
      <table class="table review-order-detail">
        <tbody>
        <?php 
          // $total = 0;
          foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $sub_total = 0;
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $_product_thumb_link = wp_get_attachment_image_url( $_product->get_image_id(), 'thumbnail' );
            //var_dump($_product_thumb_link);
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) { ?>
          <tr>
            <td class="fs-12">
              <div class="thumbbox">
                <img src="<?php echo $_product_thumb_link; ?>" />
                <small>x<?php echo $cart_item['quantity'];?></small>
              </div>  
            </td>
            <td class="fs-12"><small class="text-limit-3"><?php echo esc_html( $_product->get_name() );?></small></td>
            <td class="fs-12 text-end">
              <?php 
                if ($_product->get_price() > 0) {
                  echo site_wc_price( $sub_total = $_product->get_price() * $cart_item['quantity'] ).'đ'; 
                } else {
                  echo 'Liên hệ';
                }
              ?>  
            </td>
          </tr>
          <?php
              // $total += $sub_total;
            }
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="pt-2">
    <?php if( count($cart_coupons)>0 || count($cart_point_reward)>0):?>
      <div class="d-flex justify-content-between pb-2 border-bottom">
        <small>Tổng</small>
        <small><?php echo site_wc_price( $cart_totals['subtotal'] );?>đ</small>
      </div>
      <?php foreach ( $cart_coupons as $code => $coupon ) : ?>
      <div class="cart_coupon border-bottom pt-2 pb-2 d-flex justify-content-between coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>" 
        data-amount="<?php echo $coupon->amount;?>"
        data-minimum="<?php echo $coupon->minimum_amount;?>"
      >
        <small>Khuyến mãi</small>
        <small><?php echo site_wc_coupon_discount( $coupon ); ?></small>
      </div>
      <?php endforeach; ?>
      <?php foreach ( $cart_point_reward as $code => $coupon ) : ?>
      <div class="cart_coupon border-bottom pt-2 pb-2 d-flex justify-content-between coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>" 
        data-amount="<?php echo $coupon->amount;?>"
        data-minimum="<?php echo $coupon->minimum_amount;?>"
      >
        <small>Điểm tích lũy</small>
        <small><?php echo site_wc_coupon_discount( $coupon ); ?></small>
      </div>
      <?php endforeach; ?>
    <?php endif;?>
    <div class="d-flex justify-content-between py-2">
      <span class="fw-bold">Thành tiền</span> 
      <div class="d-flex flex-column align-items-end">
        <span class="fs-4 fw-bold text-danger"><?php 
                    
          echo site_wc_price( site_wc_cart_get_total() );

        ?>đ</span>
        <!-- <span><i>(Đã bao gồm VAT nếu có)</i></span> -->
      </div>
    </div>
  </div>
</div>
<?php /*/ ?>
<div class="border rounded mb-4 p-2">
  <div class="py-2" data-field="checkout_note_1">
    <?php the_field('checkout_note_1');?>
  </div>
  <div class="border-top pt-2" data-field="checkout_note_2">
    <?php the_field('checkout_note_2');?>
  </div>
</div>
<?php /*/ ?>