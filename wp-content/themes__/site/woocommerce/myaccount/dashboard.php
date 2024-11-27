<?php
/** 
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

$user = wp_get_current_user();

$email = get_user_meta( $user->ID, 'billing_email', true);
// $email = $user->user_email;
// if( site_check_phone_email($user->user_email) ) {
//   $email = '';
// }

// the_title('<h4 class="mb-4">','</h4>');

function site_dashboard_auto_fill_email()
{
?>
<script>
(function($){
  $('#emailInput').on('change',function(){
    $('#accountEmail').val( $(this).val() );
  });
})(jQuery);
</script>
<?php
}
add_action('wp_footer', 'site_dashboard_auto_fill_email', 99 );

?>
<div class="card card-body">
  <div class="row">
    <div class="col-12 col-lg-7">
      <h2 class="mb-4 section-header border-0 mg-top-0"><span>Thông tin cá nhân</span></h2>
      <form class="mb-5 pr-1" action="<?php echo wc_get_endpoint_url('');?>" method="post" novalidate disablednext>
        <div class="mb-3 row">
          <label class="col-12 col-md-3 col-lg-3 col-form-label">Họ *</label>
          <div class="col-12 col-md-9 col-lg-9">
            <input type="text" name="account_last_name" class="form-control" value="<?php echo esc_attr( $user->last_name ); ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-12 col-md-3 col-lg-3 col-form-label">Tên *</label>
          <div class="col-12 col-md-9 col-lg-9">
            <input type="text" name="account_first_name" class="form-control" value="<?php echo esc_attr( $user->first_name ); ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-12 col-md-3 col-lg-3 col-form-label">Tên hiển thị</label>
          <div class="col-12 col-md-9 col-lg-9">
            <p><input type="text" name="account_display_name" class="form-control" value="<?php echo esc_attr( $user->display_name ); ?>"></p>
            <p><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></p>
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-12 col-md-3 col-lg-3 col-form-label">Số điện thoại</label>
          <div class="col-12 col-md-9 col-lg-9">
            <p><input type="text" class="form-control" value="<?php echo esc_attr( $user->user_login ); ?>"></p>
          </div>
        </div>
        <!-- <div class="mb-3 row">
          <label class="col-12 col-md-3 col-lg-4 col-form-label">Địa chỉ email</label>
          <div class="col-12 col-md-9 col-lg-8">
            <input type="email" name="billing_email" class="form-control" value="<?php //echo esc_attr( $email ); ?>"
              pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)" />
          </div>
        </div> -->
        <div class="row">
          <div class="col-12 col-md-9 offset-md-3 col-lg-8 offset-lg-4">
            <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
            <button type="submit" class="btn rounded btn-primary px-4" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
            <input type="hidden" name="action" value="save_account_details" />
            <input type="hidden" name="account_email" id="accountEmail" value="<?php echo esc_attr( $user->user_email ); ?>" />
          </div>
        </div>
      </form>
    </div>
    <div class="col-12 col-lg-5 border-start pl-1">
      <?php /*/?><h5 class="mb-3">Số điện thoại và email</h5>
      <div class="d-flex mb-3">
        <i class="bi bi-telephone me-3 fs-5 text-grey"></i>
        <div class="flex-grow-1 align-self-center">
          Số điện thoại<br>
          0987678876
        </div>
        <a class="btn btn-sm btn-outline-primary align-self-center" href="/change-mobile">Cập nhật</a>
      </div>
      <div class="d-flex mb-5">
        <i class="bi bi-envelope me-3 fs-5 text-grey"></i>
        <div class="flex-grow-1 align-self-center">
          Địa chỉ email<br>
          minhnhut@gmail.com
        </div>
        <a class="btn btn-sm btn-outline-primary align-self-center" href="/change-email">Cập nhật</a>
      </div><?php /*/?>
      <h2 class="mb-4 section-header border-0 mg-top-0"><span>Bảo mật</span></h2>
      <div class="d-flex mb-5">
        <i class="bi bi-lock me-3 fs-5 color-primary"></i>
        <div class="flex-grow-1 align-self-center">
          <a class="btn btn-sm btn-outline-primary align-self-center rounded" href="<?php echo wc_get_endpoint_url( 'edit-account' );?>">Đổi mật khẩu</a>
        </div>
        <!-- <a class="btn btn-sm btn-outline-primary align-self-center" href="<?php //echo wc_get_endpoint_url( 'edit-account' );?>">Cập nhật</a> -->
      </div>
      <?php /*/?><h5 class="mb-3">Liên kết mạng xã hội</h5>
      <div class="d-flex mb-3">
        <img src="assets/images/logo/facebook.svg" width="24" class="me-3" />
        <div class="flex-grow-1 align-self-center">
          Facebook
        </div>
        <a class="btn btn-sm btn-outline-primary align-self-center" href="">Liên kết</a>
      </div>
      <div class="d-flex">
        <img src="assets/images/logo/google.svg" width="24" class="me-3" />
        <div class="flex-grow-1 align-self-center">
          Google
        </div>
        <a class="btn btn-sm btn-light align-self-center">Đã liên kết</a>
      </div><?php /*/?>
    </div>
  </div>
</div>