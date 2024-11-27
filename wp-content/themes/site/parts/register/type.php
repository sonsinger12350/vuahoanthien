<div class="row justify-content-center py-3 user-type-choose">
    <img class="mb-3 user-form-logo" src="<?php site_the_assets();?>images/logo/VieHomeDepot.png" />
    <h2 class="text-center">Tạo tài khoản</h2>
    <p class="text-center fs-5">Chọn loại tài khoản phù hợp nhất với nhu cầu của bạn.</p>
    <div class="row mx-0">
      <div class="col-12 col-md-10 col-xl-8 col-xxl-6">
        <a href="<?php echo home_url();?>" class="btn btn-link px-0 mb-3">
          <i class="bi bi-chevron-left"></i> Quay lại
        </a>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-5 col-xl-6">
      <label class="user-account-type">
        <h3 class="text-center">Tài khoản cá nhân</h3>
        <ul>
          <li>Mua sắm dễ dàng và nhanh chóng.</li>
          <li>Nhiều chương trình ưu đãi hấp dẫn.</li>
          <li>Dễ dàng theo dõi tình trạng đơn hàng.</li>
        </ul>
        <span class="btn btn-primary w-100">Tạo tài khoản</span>
        <input type="radio" name="customer_type" value="1" style="opacity: 0;"/>
      </label>
    </div>
    <div class="col-12 col-md-6 col-lg-5 col-xl-6">
      <label class="user-account-type">
        <h3 class="text-center">Tài khoản nhà thầu</h3>
        <ul>
          <li>Lên đơn hàng dễ dàng và nhanh chóng.</li>
          <li>Nhiều chương trình ưu đãi hấp dẫn.</li>
          <li>Dễ dàng theo dõi tình trạng đơn hàng.</li>
        </ul>
        <span class="btn btn-primary w-100">Tạo tài khoản</span>
        <input type="radio" name="customer_type" value="2" style="opacity: 0;"/>
      </label>
    </div>
  </div>