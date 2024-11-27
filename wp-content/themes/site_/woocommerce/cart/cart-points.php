<?php

$user_id = get_current_user_ID();
if( $user_id == 0 ) return;

//global $cart_point_reward;
$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
if( $get_points == 0 ) return;

//var_dump($cart_point_reward);

?>
<div class="mb-3 section-bg"> <!-- bg-light border border-0 rounded p-2 -->
  <div class="d-flex justify-content-between align-items-center py-2">
    <span><b>Điểm tích lũy</b>: <span class="number"><?php echo $get_points;?></span></span> <span class="note">(1 điểm = 1.000đ)</span>
    
    <?php //echo do_shortcode('[WPS_CART_PAGE_SECTION]');
          //echo do_shortcode('[WPS_CHECKOUT_PAGE_SECTION]'); ?>
  </div>
  <div class="point-applying">
      <div id="result"></div> 
      <div id="point-remaining"></div>
    </div>
  <div class="d-flex mb-3">
    <div class="wps_wpr_apply_custom_points input-group me-2">
      <input type="number" min="0" max="<?php echo $get_points;?>" name="wps_cart_points" id="wps_cart_points" class="form-control control-points" placeholder="Nhập số điểm" aria-label="Nhập số điểm" aria-describedby="basic-addon1">
      <button type="submit" class="btn btn-primary rounded" name="wps_cart_points_apply" id="wps_cart_points_apply" value="Apply Points" style="margin-left: 5px;" data-point="<?php echo $get_points;?>" data-id="1" data-order-limit="0">Áp dụng</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  const formElement = document.getElementById('cart-form');
  const inputElement = document.getElementById('wps_cart_points');
  const resultElement = document.getElementById('result');
  const remainPointElement = document.getElementById('point-remaining');
  const maxPoints = <?php echo $get_points; ?>;

  // Check if a value was previously stored in localStorage and display it
  const wps_cart_points = localStorage.getItem('wps_cart_points');
  const wps_cart_remain_points = localStorage.getItem('wps_cart_remain_points');
  if (wps_cart_points) {
    resultElement.textContent = `Điểm sử dụng: ${wps_cart_points}`;
    remainPointElement.textContent = `Điểm còn lại: ${wps_cart_remain_points}`;
  }

  formElement.addEventListener('submit', function (event) {
      //event.preventDefault(); // Prevent form submission

      // Get the value of the input field
      const inputValue = inputElement.value;
      const remainPoint = maxPoints - inputValue;

      // Display the value
      //resultElement.textContent = `Điểm sử dụng: ${inputValue}`;

      // Store the value in localStorage
      localStorage.setItem('wps_cart_points', inputValue);
      localStorage.setItem('wps_cart_remain_points', remainPoint);
  });

  

</script>