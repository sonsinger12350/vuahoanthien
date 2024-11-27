<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="needs-validation change-pass-form" id="changePassword" action="" method="post" novalidate <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
  <!-- disablednext -->
  <h2 class="mb-4 section-header border-0 mg-top-0"><span>Đổi mật khẩu</span></h2>
  <div class="card card-body">
    <div class="row justify-content-centers py-3">
      <div class="col-12 col-lg-6">
        <div class="d-flex flex-column">
          <div class="mb-3">
            <label class="form-label">Mật khẩu hiện tại</label>
            <div class="input-password">
              <input type="password" class="form-control" name="password_current" autocomplete="off" required>
              <label show-password>
                <i class="bi bi-eye"></i>
              </label>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <div class="input-password">
              <input type="password" class="form-control" name="password_1" id="newPassword" autocomplete="off"
                minlength="6" maxlength="70"
                <?php /*/ ?>pattern="(?=.{8,})((?=.*\d)(?=.*[a-z])(?=.*[A-Z])|(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_])|(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])).*"<?php /*/ ?>
                required>
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
              </ul>
            </div>    
          </div>
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <div class="input-password">
              <input type="password" class="form-control" name="password_2" id="confirmPassword"
                autocomplete="off" minlength="6" maxlength="70"
                <?php /*/ ?>pattern="(?=.{8,})((?=.*\d)(?=.*[a-z])(?=.*[A-Z])|(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_])|(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])).*"<?php /*/ ?>
                required>
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
              </ul>
            </div> 
          </div>
          <?php wp_nonce_field( 'save_account_password', 'save-account-password-nonce' ); ?>
          <button type="submit" class="btn btn-primary px-4" name="save_account_password" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
          <input type="hidden" name="action" value="save_account_password" />
        </div>
      </div>
    </div>
  </div>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
