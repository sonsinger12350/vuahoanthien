<?php

$product = wc_get_product();
$product_id = $product->get_id();

$brand = site_get_product_brand( $product->get_id() );
$product_available = true;

$wcwl_count = 0;
$wcwl_remove = '#';

if( get_current_user_id()>0 ) {
  $wcwl_count = site_wcwl_count( $product->get_id() );
  $wcwl_remove = wp_nonce_url( add_query_arg( 'remove_from_wishlist', $product->get_id(), $product->get_permalink() ), 'remove_from_wishlist' );
}

// Get Available variations?
// $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
// $available_variations = $get_variations ? $product->get_available_variations() : false;
// $available_variations = $product->get_available_variations();

$available_variations = false;

if( method_exists($product, 'get_available_variations') ) {
  $available_variations = $product->get_available_variations();
}

$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();

$final_price = $regular_price;
if($sale_price > 0){
  $final_price = $sale_price;
} else {
  $product_available = false;
}

// check for Gạch Product
$loai_sp = get_field("producttype"); 
if($loai_sp == "gach"): 
    $heso_quydoi = get_field("heso_quydoi");  
    $is_tuyp = get_field("tuyp_ky");  
    $pricePerM2 = $final_price;
    $perboxPrice = $heso_quydoi * $final_price;
    $unit_heso = "thùng (vỉ)";
    if($is_tuyp == true){
      $unit_heso = "tuýp (kg)";
    }
endif;    

// if (isset($product->custom_prices)) {
//   // Access the old and new prices from the custom_prices variable
//   var_dump($product->custom_prices);
//   $old_price = $product->custom_prices['old'];
//   $new_price = $product->custom_prices['new'];
//   if($old_price) {
//     $sale_price = $old_price;
//   } 
// }


// if( $available_variations ) {
//   $prices = explode('&ndash;', str_replace([".", ' ', '₫'], '', trim( strip_tags( $product->get_price_html() ) ) ) );
//   $regular_price = (int) $prices[0];
//   $sale_price = (int) $prices[1];
// }
$currency_symbol = get_woocommerce_currency_symbol();
$loai_sp = get_field("producttype");

?>
<div class="row <?php echo ($loai_sp == "gach")?'specialProduct':''; ?>" id="productSummary"> <!-- mb-3  border-top -->
  <div class="col-12 col-md-12 col-lg-12 py-2"> <!--py-3 -->
    <div class="section-bg">
      <?php if( get_field('type', 'product_' . $product->get_id() )!='' ):?>
      <div class="d-flex align-items-center">
        <span class="bg-primary p-1 text-light fs-12"><?php the_field('type', 'product_' . $product->get_id() );?></span>
      </div>
      <?php endif;?>
      <div class="main-info "> <!--mb-3 -->
        <?php /*?>
        <a><?php 
            if( $brand ) {
              echo $brand->name . ' - ';
            }
            echo $product->get_sku();
        ?></a>
        <?php */?>
        <div class="title-box">
          <h5><?php the_title();?></h5>
        </div>  
          
        <div class="d-flex row-reviews-links">
          <a href="#customerReviews" class="fw-bold me-1 text-primary text-hover-underline"><?php echo number_format($product->get_average_rating(), 1); ?></a>
          <a href="#customerReviews"><div class="d-block fs-5 text starts" style="--rating: <?php site_wc_the_stars_percent( $product->get_average_rating() )?>%;"></div></a>
          <span class="ms-3 ps-3 border-start border-start-mb me-3 pe-3 border-end">
            <a class="text-hover-underline" href="#customerReviews">
              <b><?php echo $product->get_review_count(); ?></b>
            
            Đánh giá</a>
          </span>
          <div class="social-link">
            <?php /* ?>
            <a title="Yêu thích" href="<?php echo site_add_to_wishlist_url($product->get_id());?>" class="fs-3 text-primary favorite-link<?php echo $wcwl_count?' favorited':'';?>" data-remove="<?php echo $wcwl_remove;?>" data-id="<?php echo $product->get_id();?>" >
              <i class="bi bi-suit-heart<?php echo $wcwl_count?'-fill':'';?>"></i>
            </a>
            <?php */ ?>
            <a title="Chia sẻ" href="<?php echo site_wc_fb_share_url($product);?>" class="fs-3 text-primary">
              <i class="bi bi-facebook"></i>
            </a>
            <a title="Copy link" href="#" class="fs-3 text-primary btn-copy">
              <i class="bi bi-link-45deg"></i>
            </a>
            <div class="btn-favorite">
              <?php 
                  $virtual_like_number = (int) get_post_meta( $product_id, 'virtual_like_number', true );
                  $user_count              = yith_wcwl_count_add_to_wishlist( $product_id );
                  $current_user_count = $user_count ? YITH_WCWL_Wishlist_Factory::get_times_current_user_added_count( $product_id ) : 0;

                  $virtual_count  = $user_count + $virtual_like_number;

              ?>
              <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
              <?php if( $virtual_count>0 ):?>
                <sup class="like-count d-none"><?php echo $virtual_count;//wp_kses_post( yith_wcwl_get_count_text( $product_id ) );//$user_count;//$like;?> <span class="hide-on-mobile">người đã thích</span></sup>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  
  <div class="col-12 col-md-7 col-lg-7 py-2 productSummary-col-left"> 
    <div class="section-bg">
      
      <?php
        wc_get_template_part( 'single-product/slider-images' );
      ?>
    </div>
  </div>
  <div class="col-12 col-md-5 col-lg-5 productSummary-col-right"> <!-- mobile-px-0 py-2 -->
    <div class="col-inside">  
      <div class="py-2">
        <div class="section-bg">
          <div class="row">
            <div class="d-flex flex-column bg-light"> <!--  mb-2 -->
              <div class="d-flex flex-row align-items-center justify-content-between row-price-discount">
                <?php if ($regular_price > 0 && $product_available == true): ?>
                                
                  <div class="row-price">
                    <?php if ($sale_price > 0): ?>
                      <span class="d-block fs-11">Giá thị trường: <span class="text-decoration-line-through product_regular_price"><?php echo site_wc_price(  $regular_price );?><span class="currency-txt"><?php echo $currency_symbol; ?></span></span></span>
                      <span class="d-block">Giá ưu đãi: <span class="fw-bold fs-3 text-danger product_sale_price"><?php echo site_wc_price( $sale_price );?><span class="currency-txt"><?php echo $currency_symbol; ?></span></span>
                    <?php else: ?> 
                      <span class="d-block">Giá ưu đãi: <span class="fw-bold fs-3 text-danger product_sale_price"><?php echo site_wc_price( $final_price );?> VNĐ</span>
                    <?php endif ?>
                    </span>
                  </div>
                  <?php if (site_wc_get_discount_percent( $product ) > 0): ?>
                    <div class="row-discount">
                      <span class="d-block p-2 bg-blue rounded fs-3 text-yellow discount-number">Tiết kiệm <b><?php site_wc_the_discount_percent( $product ) ?>%</b></span>
                    </div>  
                  <?php endif; ?>
                <?php else: 
                        $product_available = false;
                        //var_dump($product_available);
                  ?>
                    <div class="row-price">
                      <span class="d-block">Giá: <span class="fw-bold fs-3 text-danger product_sale_price"><?php echo custom_replace_contact_text('Liên hệ'); ?></span>
                    </div>
                <?php endif; ?>  
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="py-2">  
        <div class="section-bg">
          <div class="row">  
            <?php 
              do_action( 'woocommerce_before_add_to_cart_form' );
            ?>
            <div class="bg-light product_short_description"> <!-- my-2 -->
              <?php 
                // echo $product->get_short_description(); 
              
                $page_on_front = (int) get_option( 'page_on_front', 0 );
                the_field('product_short_description', $page_on_front);
              ?>
            </div>
          </div>
        </div>  
      </div>  
      <?php if($product_available == true): ?>
      <div class="py-2">  
        <div class="section-bg">
          <?php if($loai_sp == "gach"): 
            $heso_quydoi = get_field("heso_quydoi");  
            // $perboxPrice = $heso_quydoi * $final_price;
            //$perboxPrice = $new_price;
          ?>
            <div class="row row-remake-price">
              <h3>Dự toán sản phẩm</h3>
              <div class="remake-price-box">
                <div class="remake-price-box">
                  <div class="remake-price-row">
                    <div class="remake-price-row-left">
                      <label for="m2input">Số m<sup>2</sup> cần:</label>
                      <input type="m2input" name="m2input" id="m2input" >
                    </div>
                    <div class="remake-price-row-right">
                      <label>Số m<sup>2</sup>/<?php echo $unit_heso; ?>:</label>
                      <div class="m2perbox"><strong><?php echo $heso_quydoi; ?> m<sup>2</sup></strong></div>
                    </div>
                  </div>
                  <div class="remake-price-row">
                    <div class="remake-price-row-left">
                      <label>Đơn giá <?php echo $unit_heso; ?>:</label>
                      <div class="priceperbox">
                        <strong><span id="perboxPrice"><?php echo number_format(round($perboxPrice), 0, '.', ','); ?></span><span class="currency-txt"><?php echo $currency_symbol; ?></span></strong>
                      </div>
                    </div>
                    <div class="remake-price-row-right">
                      <label>Số <?php echo $unit_heso; ?> cần:</label>
                      <div id="amountbox" class="amountbox"><strong>0</strong></div>
                    </div>
                  </div>
                  <div class="remake-price-row border-top pt-3">
                    <label>Thành tiền:</label>
                    <div class="final-price text-danger">
                      <strong><span id="finalPrice">00.00</span><span class="currency-txt"><?php echo $currency_symbol; ?></span></strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
          <script type="text/javascript">
            document.getElementById('m2input').addEventListener('input', function() {
              // Get the user input
              let userInput = document.getElementById('m2input').value;
              const rateNumber = <?php echo $heso_quydoi; ?>;
              const regularPrice = <?php echo $final_price; ?>;
              const perboxPrice = <?php echo $perboxPrice; ?>;
              
              // Convert the input to a number and check if it's a valid number
              if (!isNaN(userInput) && userInput !== '') {
                  // Multiply the input by 10
                  let resultAmount = Math.ceil(parseFloat(userInput) / rateNumber);
                  let resultPrice = resultAmount * perboxPrice;
                  
                  // Update the value of the input field with the multiplied result
                  document.getElementById('amountbox').textContent = resultAmount;
                  document.getElementById('quantityProduct').value = resultAmount;
                  document.getElementById('finalPrice').textContent = Math.round(resultPrice).toLocaleString();
              } else {
                  // If the input is not a valid number or empty, clear the result
                  document.getElementById('amountbox').textContent = 0;
                  document.getElementById('quantityProduct').value = 1;
                  document.getElementById('finalPrice').textContent = 0;
              }
            });
          </script>  
          <?php endif; ?>
          <div class="row row-addcart-buttons">  
            <div class="bg-light"> <!-- mt-2  -->
              <div class="row-bottons">  
                <?php
                  if( $available_variations ) {          

                    wp_enqueue_script( 'wc-add-to-cart-variation' );

                    // Load the template.
                    wc_get_template(
                      'single-product/add-to-cart/variable.php',
                      array(
                        'available_variations' => $available_variations,
                        'attributes'           => $product->get_variation_attributes(),
                        'selected_attributes'  => $product->get_default_attributes(),
                      )
                    );
                  } else {
                    wc_get_template_part( 'single-product/add-to-cart/simple' ); 
                  }
                ?>
              </div>
            </div>
            <div class="bg-light"> <!--mt-3 -->
              <div class="deal-price-box">
                <p>Vua Hoàn Thiện mong muốn mang đến Quý Khách hàng giá cả kèm chất lượng dịch vụ tốt nhất. Quý Khách vui lòng trả giá hoặc đề xuất mức giá mà Quý Khách thấy hợp lý. Hãy Trả Giá Ngay!</p>
                <a href="#" class="btn btn-success rounded me-1 p-2 fw-normal flex-grow-1 btn-deal-price"
                  data-bs-toggle="modal" data-bs-target="#modal-deal">
                  Trả giá ngay          
                </a>
              </div>
              <?php /*?><div class="social-link">
                <a href="<?php echo site_add_to_wishlist_url($product->get_id());?>" class="fs-3 text-primary favorite-link<?php echo $wcwl_count?' favorited':'';?>" data-remove="<?php echo $wcwl_remove;?>" data-id="<?php echo $product->get_id();?>" >
                  <i class="bi bi-suit-heart<?php echo $wcwl_count?'-fill':'';?>"></i>
                </a>
                <a href="<?php echo site_wc_fb_share_url($product);?>" class="fs-3 text-primary" target="_blank">
                  <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="fs-3 text-primary btn-copy">
                  <i class="bi bi-link-45deg"></i>
                </a>
              </div> <?php */?> 
            </div>
            <?php ?><input class="data-copy" value="<?php echo esc_url( $product->get_permalink() ); ?>" style="opacity: 0; visibility: hidden; display: none;" /><?php ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>