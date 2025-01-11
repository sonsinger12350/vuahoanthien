<?php

global $checkbox_compare_id;
if( empty($checkbox_compare_id) ) {
  $checkbox_compare_id = 0;
}
$checkbox_compare_id++;

// $product = wc_get_product();
global $product;  

$wcwl_count   = 0;
$wcwl_add     = '#modal-noti';
$wcwl_remove  = '';

if( 1 || get_current_user_id()>0 ) {
  $wcwl_count   = site_wcwl_count( $product->get_id() );
  $wcwl_add     = site_add_to_wishlist_url($product->get_id());
  $wcwl_remove  = wp_nonce_url( add_query_arg( 'remove_from_wishlist', $product->get_id(), $product->get_permalink() ), 'remove_from_wishlist' );
}

$like = (int) get_post_meta( $product->get_id(), 'like', true );
// $sold = (int) site_wc_product_sold_count( $product->get_id() );


$product_id = $product->get_id();
$product_price = site_wc_price($product->get_regular_price());
$product_price_sale = site_wc_price($product->get_sale_price());

//echo $saleoff_price = get_post_meta( $product_id, 'sale_off', true );
$virtual_like_number = (int) get_post_meta( $product_id, 'virtual_like_number', true );

$product_available = true;

if($product_price_sale > 0){
  $product_available = true;
} else {
  $product_available = false;
}


// $sale_off = (int) get_post_meta( $product->get_id(), 'sale_off', true );
// if( $sale_off == 100 ) {
//   echo "<!-- $sale_off - updated to 0 -->";
//   update_post_meta( $product->get_id(), 'sale_off', 0 );
// }

$user_count              = yith_wcwl_count_add_to_wishlist( $product_id );
$current_user_count = $user_count ? YITH_WCWL_Wishlist_Factory::get_times_current_user_added_count( $product_id ) : 0;

?>
<div class="card h-100 product-item">
  <?php if( get_field('type', 'product_' . $product->get_id() )!='' ):?>
  <span class="position-absolute top-0 start-0 p-1 bg-primary text-light text-small"><?php the_field('type', 'product_' . $product->get_id() )?></span>
  <?php endif;?>
  <div class="position-absolute top-0 end-0 btn-favorite">
    <?php /* * ?>
    <a href="<?php echo $wcwl_add;?>" class="fs-3 text-primary favorite-link<?php echo $wcwl_count>0?' favorited':'';?>" data-remove="<?php echo $wcwl_remove;?>" data-id="<?php echo $product->get_id();?>" >
      <?php if( $like>0 ):?>
      <sup class="like-count"><?php echo $like;?></sup>
      <?php endif;?>
      <i class="bi bi-suit-heart<?php echo $wcwl_count>0?'-fill':'';?>"></i>
    </a>
    <a href="javascript:void(0)" class="d-none fs-3 text-primary">
      <i class="bi bi-suit-heart-fill"></i>
    </a>
    <?php /* */?>
    <?php if( $user_count>0 || $virtual_like_number>0 ):?>
      <sup class="like-count"><?php echo formatNumber($user_count + $virtual_like_number);//wp_kses_post( yith_wcwl_get_count_text( $product_id ) );//$user_count;//$like;?></sup>
    <?php endif;?>
    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
  </div>  
  <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-decoration-none product-image mb-3" title="<?php echo $product->get_title(); ?>" style="background-image:url(<?php echo wp_get_attachment_image_url( $product->get_image_id(), 'full' );?>);">
    <img src="<?php echo wp_get_attachment_image_url( $product->get_image_id(), 'full' );?>" class="card-img-top"
      alt="<?php echo $product->get_title(); ?>" loading="lazy"/>
      <!-- medium -->
  </a>
  <div class="card-body p-0">
    <div class="product-title">
      <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-decoration-none" title="<?php echo $product->get_title(); ?>"> <!-- fw-bold -->
        <p class="card-text text-dark text-limit-3"><?php echo $product->get_title(); ?></p>
      </a>
    </div>  
    <div class="product-rating">
      <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-decoration-none style-none" title="<?php echo $product->get_title(); ?>">
        <div class="d-inline-block fs-5 text starts" style="--rating: <?php site_wc_the_stars_percent( $product )?>%;"></div>
        <?php if( $product->get_review_count()>0 ):?> <span class="sepa-col">|</span> <?php echo $product->get_review_count(); ?>
        <?php endif;?>
      </a>  
    </div>
    <?php if ($product->get_regular_price() > 0 && $product_available == true): ?>
    <div class="d-flex flex-column product-price">
      <div class="d-flex flex-row flex-wrap align-items-end justify-content-between">
        <div class="product-price-left">
          <?php if ($product_price_sale>0): ?>
            <p><small><del><?php echo $product_price; ?><sup class="text">đ</sup></del></small></p>
            <p><b class="me-1 text-danger"><?php echo $product_price_sale; ?><sup class="text">đ</sup></b></p>
          <?php else: ?>
            <p><small>&nbsp;</small></p>
            <p><b class="me-1 text-danger"><?php echo $product_price; ?><sup class="text">đ</sup></b></p>
          <?php endif ?>
          
        </div>
        <?php if ($product_price != null && $product_price_sale != null ): ?>
          <div class="d-flex bg-danger align-items-center justify-content-center border rounded percent-save">
            <span class="fs-11 fw-bold text-light">-<?php site_wc_the_discount_percent( $product )?>%</span>
          </div>
        <?php endif ?>
      </div>
    </div>  
    <?php else: ?>
    <div class="d-flex flex-column product-price">
      <div class="d-flex flex-row flex-wrap align-items-end justify-content-between">
        <div class="product-price-left">
          <p><small>&nbsp;</small></p>
          <p class="contact-txt"><b class="me-1 text-danger"><?php echo custom_replace_contact_text('Liên hệ'); ?></b></p>
        </div>
       </div>
    </div>  
    <?php endif; ?>
  </div>
  <?php 
  if( empty($args['no_footer']) ):
          
    $quantity = 0;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      if( $cart_item['product_id'] == $product->get_id() ) {
        $quantity = $cart_item['quantity'];
        break;
      }
    }

  ?>
  <div class="card-footer bg-transparent border-0 p-0 pt-2">
    <a href="<?php echo $product->add_to_cart_url();?>" class="<?php echo $quantity > 0 ? 'd-none ' : '' ;?>w-100 btn btn-outline-primary px-1 border-2 rounded fw-bold fs-10 <?php echo ($product_available==false)?"disabled":""; ?>">Chọn mua</a>
    <div class="<?php echo $quantity == 0 ? 'd-none ' : '' ;?>product-item-quantity d-inline-flex" data-href="<?php echo esc_url( $product->get_permalink() ); ?>">
      <button type="button" class="btn btn-outline-dark btn-cart-decrease btn-change border rounded fw-bold fs-4">-</button>
      <input type="number" class="form-control text-center fw-bold border border-end-0 border-start-0 rounded-0 quantity input-text qty text" step="1" min="1" max="" value="<?php echo $quantity;?>" inputmode="numeric" autocomplete="off">
      <button type="button" class="btn btn-outline-dark btn-cart-increase btn-change border rounded fw-bold fs-4">+</button>
    </div>
    <div class="d-flex compare-row">
      <div class="form-check mx-auto mt-2">
        <input class="form-check-input input-compare" type="checkbox" value="<?php echo $product->get_id();?>" id="cp-<?php echo $checkbox_compare_id . '-' . $product->get_id();?>">
        <label class="form-check-label text-hover-primary fs-11" for="cp-<?php echo $checkbox_compare_id . '-' . $product->get_id();?>">So sánh</label>
      </div>
    </div>
  </div>
  <?php endif;?>
</div>