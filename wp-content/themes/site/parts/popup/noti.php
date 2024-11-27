<div class="modal fade" id="modal-noti" style="z-index: 99999" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thông báo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <br />
        <p>Vui lòng đăng nhập để thêm vào danh sách yêu thích</p>
      </div>
      <div class="modal-footer">
        <a href="<?php echo site_login_url();?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Đăng nhập</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-message" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thông báo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <br />
        <p class="message">Đã thêm vào danh sách yêu thích</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded" data-bs-dismiss="modal">Tiếp tục mua hàng</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-loading" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Đang xử lý ...</h5>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="show-popup-ask-to-call" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 class="modal-title">
          <img class="mb-3" src="<?php site_the_assets();?>images/icons/icon-lock-popup-header.png" loading="lazy"/>
          <span>Vui lòng đăng nhập để sử dụng tính năng tạo danh sách mới</span>
        </h5>
        <br />
        <p><a href="<?php echo site_login_url("https://vuahoanthien.com/tai-khoan/san-pham-yeu-thich-cua-ban/?wishlist-action=manage"); ?>" class="btn btn-primary w-100">Đăng nhập</a>
</p>
        <p class="text-center form-divider">
            Bạn chưa có tài khoản?
        </p>
        <p><a href="https://vuahoanthien.com/dang-ky/?redirect_link=https%3A%2F%2Fvuahoanthien.com%2Ftai-khoan%2Fsan-pham-yeu-thich-cua-ban%2F%3Fwishlist-action%3Dmanage" class="btn btn-outline-primary w-100 mb-3">Đăng ký tài khoản mới</a></p>
      </div>
    </div>
  </div>
</div>