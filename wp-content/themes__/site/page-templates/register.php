<?php
/**
 * Template Name: Register
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

// $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url();
$redirect_link = isset( $_REQUEST['redirect_link'] ) ? $_REQUEST['redirect_link'] : home_url();

$user_id = (int) get_current_user_id();
if( $user_id > 0 ) {
  wp_redirect( $redirect_link );
  exit();
}

global $no_nav, $no_coupon;

$no_nav = 1;
$no_coupon = 1;

get_header();

?>
<form class="needs-validation" novalidate method="post">
<div class="container user-form d-flex align-items-center justify-content-center">
  <?php // get_template_part( 'parts/register/type' ); ?>
  <div class="row justify-content-center py-3 register-form">
    <img class="mb-3 user-form-logo" src="<?php site_the_assets();?>images/logo/VHT-new-logo.svg"/>
    <h2 class="text-center">Tạo tài khoản</h2>
    
    <div class="col-12 col-md-8 col-xl-6 col-xxl-6">
      <!-- <a class="btn text-primary px-0 mb-3 btn-back">
        <i class="bi bi-chevron-left"></i> Quay lại
      </a> -->
      <a href="<?php echo home_url("/dang-nhap");?>" class="btn text-primary px-0 mb-3">
        <i class="bi bi-chevron-left"></i> Quay lại
      </a>
      <div class="mb-3" style="max-width: 600px;">
        <?php do_action( 'woocommerce_before_customer_login_form' ); ?>
        
        <div class="mb-3">
          <label for="phoneInput" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
          <input type="tel" name="username" minlength="10" maxlength="10" 
            pattern="[0]{1}[0-9]{9}"
            class="form-control control-for-email phoneInput" id="phoneInput" required 
            value="<?php // echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
            />
        </div>
        
        <div class="mb-3" style="display: none;">
          <label for="billing_email" class="form-label">Email</label>
          <input type="email" name="billing_email" class="form-control billing_email" id="billing_email"
            pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)"
            value="<?php echo ( ! empty( $_POST['billing_email'] ) ) ? esc_attr( wp_unslash( $_POST['billing_email'] ) ) : ''; ?>"
            />
        </div>

        <div class="mb-3">
          <label for="display_name" class="form-label">Tên</label>
          <input type="text" name="display_name" class="form-control control-for-fullname" id="display_name"
            value="<?php echo ( ! empty( $_POST['display_name'] ) ) ? esc_attr( wp_unslash( $_POST['display_name'] ) ) : ''; ?>" />
        </div>
        
        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
        <div class="mb-3">
          <label for="passwordInput" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
          <div class="input-password">
            <input type="password" name="password" class="form-control" id="passwordInput"
              minlength="6"
              maxlength="70"
              <?php /*/ ?>pattern="(?=^.{6,}$)(?=.*\d)(?=.*[!@#$%^&*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"<?php /*/ ?>
              required >
            <label show-password>
              <i class="bi bi-eye"></i>
            </label>
          </div>

          <div id="passwordHint" class="collapse">
            <ul class="list-unstyled">
              <li class="py-2"><b>Mật khẩu cần có:</b>
                <ul class="list-unstyled">
                  <li class="validate invalid" data-validate="min-length">Có tối thiểu 6 ký tự</li>
                </ul>
              </li>
              <?php /*/ ?>
              <li class="py-2"><b>Và 4 yêu cầu sau:</b>
                <ul class="list-unstyled">
                  <li class="validate invalid" data-validate="uppercase">Có tối thiểu 1 ký tự viết hoa</li>
                  <li class="validate invalid" data-validate="lowercase">Có tối thiểu 1 ký tự viết thường</li>
                  <li class="validate invalid" data-validate="number">Có tối thiểu 1 chữ số</li>
                  <li class="validate invalid" data-validate="special-chars">Có tối thiểu 1 ký tự đặc biệt (!,@,*#...)</li>
                </ul>
              </li>
              <?php /*/ ?>
            </ul>

            <?php /*/ ?>
            <b class="d-block pb-1">Độ mạnh: <span class="strength-status"><!-- Yếu/Vừa/Mạnh --></span></b>
            <div class="progress rounded-pill">
              <div class="progress-bar" role="progressbar"></div>
            </div>

            <p class="m-0 temp-hint">
              Tránh lặp lại mật khẩu từ trang web hoặc cụm từ khác có thể dễ đoán.
            </p>
            <?php /*/ ?>

          </div>
        </div>
        <?php else : ?>
        <p><?php esc_html_e( 'Mật khẩu đã được gửi đến Email của bạn', 'woocommerce' ); ?></p>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary w-100" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_attr_e( 'Register', 'woocommerce' ); ?></button>
        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
        <input type="hidden" name="email" class="emailInput" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/>
        <input type="hidden" name="redirect_link" value="<?php echo $redirect_link;?>">
        <input type="hidden" name="billing_phone" class="billingPhoneInput" value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>"/>
        <?php do_action( 'woocommerce_after_customer_login_form' ); ?>
        <p class="text-center">
          Bạn đã có tài khoản rồi? <a class="text-primary" href="<?php echo site_login_url()?>">Đăng nhập</a>
        </p>
        <p class="text-center">
          <small>
            Bằng việc chọn "Tạo tài khoản", bạn đã đồng ý với <a class="text-primary" href="https://vuahoanthien.com/dieu-khoan-su-dung/" target="_blank">Chính sách, quy định và yêu cầu của chúng tôi</a>.
          </small>
        </p>
      </div>
    </div>

  </div>
</div>
</form>
<?php wp_footer(); ?>
<script>
(function($){
  $('.user-account-type').on('click', function(){
    $('.user-type-choose').hide();
    $('.register-form').show();
  });
  $('.register-form .btn-back').on('click', function(e){
    e.preventDefault();

    $('.register-form').hide();
    $('.user-type-choose').show();
  });
  
  $('.phoneInput').on('change', function(){
    var tel = $(this);
    
    $('.billingPhoneInput').val( tel.val() );
    $('.emailInput').val( tel.val() + '@<?php echo site_domain_email();?>' );    
  });
})(jQuery);
</script>
</body>
</html>